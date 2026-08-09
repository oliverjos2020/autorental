<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\BookingOrder;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Check for new booking messages for station admin
     */
    public function checkMessage()
    {
        $stationVehiclesArray = Vehicle::where('station_id', auth()->user()->station_id)->pluck('id')->toArray();
        $booking = BookingOrder::whereIn('vehicle_id', $stationVehiclesArray)
            ->where('payment_status', 1)
            ->where('status', 0)
            ->with(['user', 'vehicle'])
            ->latest()
            ->get();

        if ($booking->count() > 0) {
            return response()->json([
                'responseCode' => 200,
                'message' => 'You have ' . $booking->count() . ' new ride request(s)!',
                'count' => $booking->count(),
                'bookings' => $booking
            ], 200);
        } else {
            return response()->json([
                'responseCode' => 404,
                'message' => 'No new requests'
            ], 404);
        }
    }

    /**
     * Send notification to station admin when a vehicle is booked
     */
    public function notifyStationAdmin(Request $request)
    {
        try {
            $request->validate([
                'booking_order_id' => 'required|exists:booking_orders,id',
            ]);

            $bookingOrder = BookingOrder::with(['user', 'vehicle', 'vehicle.station'])->find($request->booking_order_id);

            if (!$bookingOrder) {
                return response()->json([
                    'responseCode' => 404,
                    'message' => 'Booking order not found'
                ], 404);
            }

            // Get station admin/manager
            $stationAdmin = User::where('station_id', $bookingOrder->vehicle->station_id)
                ->whereIn('role_id', [1, 2]) // Admin or Station Manager
                ->first();

            if ($stationAdmin && $stationAdmin->fcm_token) {
                $firebaseService = new FirebaseNotificationService();
                $firebaseService->sendNotification(
                    $stationAdmin->fcm_token,
                    'New Vehicle Order',
                    'A customer has ordered ' . $bookingOrder->vehicle->vehicleMake . ' ' . $bookingOrder->vehicle->vehicleModel,
                    [
                        'booking_order_id' => (string) $bookingOrder->id,
                        'vehicle_id' => (string) $bookingOrder->vehicle_id,
                        'user_name' => $bookingOrder->user->name,
                        'type' => 'vehicle_ordered'
                    ]
                );
            }

            return response()->json([
                'responseCode' => 200,
                'message' => 'Notification sent to station admin'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 422,
                'message' => 'Error sending notification',
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Get unread notifications count for current user
     */
    public function getUnreadCount()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'responseCode' => 401,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // For station admins/managers - count pending bookings
            if (in_array($user->role_id, [1, 2])) {
                $stationVehiclesArray = Vehicle::where('station_id', $user->station_id)->pluck('id')->toArray();
                $unreadCount = BookingOrder::whereIn('vehicle_id', $stationVehiclesArray)
                    ->where('payment_status', 1)
                    ->where('status', 0)
                    ->count();

                return response()->json([
                    'responseCode' => 200,
                    'unread_count' => $unreadCount,
                    'type' => 'station_admin'
                ], 200);
            }

            return response()->json([
                'responseCode' => 200,
                'unread_count' => 0
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 422,
                'message' => 'Error fetching unread count',
                'error' => $e->getMessage()
            ], 422);
        }
    }
}
