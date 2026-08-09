<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Station;
use App\Models\Vehicle;
use App\Models\BookingOrder;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Models\Location;
use Exception;

class BookingAPIController extends Controller
{
    public function booking(Request $request)
    {

        try {

            if ($request->type == 'booking') {
                // Validate the incoming request
                $request->validate([
                    'user_id' => ['required'],
                    'address' => ['required'],
                    'wth_driver' => ['required'],
                    'duration' => ['required', 'sometimes'],
                    // 'identity_card' => 'required|file|mimes:jpg,png,jpeg|max:300',
                    'vehicle_id' => ['required'],
                    'pickup_location' => ['required'],
                    'dropoff_location' => ['sometimes'],
                    'pickupDate' => ['required', 'date'],
                    'dropoffDate' => ['required', 'date'],
                    'amount' => ['required'],
                    'type' => ['required']
                ]);

                $filePath = "";
                $filePath2 = "";

                $request->validate([
                    'driverLicense' => 'sometimes|file|mimes:jpg,png,jpeg|max:300'
                ]);
                $request->validate([
                    'identity_card' => 'sometimes|file|mimes:jpg,png,jpeg|max:300'
                ]);


                // Handle identity card upload
                if ($request->hasFile('identity_card')) {
                    $file = $request->file('identity_card');
                    $fileName = time() . '_ID_' . $file->getClientOriginalName();
                    $filePath = $file->storeAs('uploads', $fileName, 'public');

                    // return response()->json(['file_path' => asset('storage/uploads/' . $fileName)]);
                }


                // Handle driver license upload
                if ($request->hasFile('driverLicense')) {
                    $fileName2 = time() . 'DL_' . $request->file('driverLicense')->getClientOriginalName();
                    $filePath2 = $request->file('driverLicense')->storeAs('uploads', $fileName2, 'public');
                }
                // dd('/storage/' . $filePath);

                // Update user details
                User::where('id', $request->user_id)->update([
                    'address' => $request->address,
                    'identity_card' => '/storage/' . $filePath,
                    'driverLicense' => '/storage/' . $filePath2,
                ]);

                // Create booking order with PENDING status
                $bookingOrder = BookingOrder::create([
                    'user_id' => $request->user_id,
                    'vehicle_id' => $request->vehicle_id,
                    'pickup_location' => $request->pickup_location,
                    'dropoff_location' => $request->dropoff_location,
                    'pickupDate' => $request->pickupDate,
                    'dropoffDate' => $request->dropoffDate,
                    'amount' => $request->amount,
                    'duration' => $request->duration,
                    'wth_driver' => $request->wth_driver,
                    'payment_status' => 0, // 0 = pending payment
                    'status' => 0, // 0 = pending booking
                    'type' => $request->type
                ]);

                // Create transaction record with PENDING status
                $transaction = Transaction::create([
                    'booking_order_id' => $bookingOrder->id,
                    'transaction_id' => 'TXN_' . $bookingOrder->id . '_' . time(),
                    'transaction_desc' => 'Vehicle Booking Payment - ' . $bookingOrder->vehicle->vehicleMake . ' ' . $bookingOrder->vehicle->vehicleModel,
                    'user_id' => $request->user_id,
                    'amount' => $request->amount,
                    'response_code' => '99',
                    'response_message' => 'Pending',
                    'raw_json' => json_encode(['status' => 'pending']),
                    'status' => 'pending'
                ]);

                // Initialize Paystack payment
                try {
                    $paystackResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('PAYSTACK_TEST_KEY'),
                    ])->post('https://api.paystack.co/transaction/initialize', [
                                'email' => $request->user()->email ?? User::find($request->user_id)->email,
                                'amount' => (int) ($request->amount * 100), // Paystack expects amount in kobo
                                'reference' => $transaction->transaction_id,
                                'metadata' => [
                                    'booking_order_id' => $bookingOrder->id,
                                    'transaction_id' => $transaction->id,
                                    'user_id' => $request->user_id,
                                    'vehicle_id' => $request->vehicle_id
                                ]
                            ]);

                    $paystackData = $paystackResponse->json();

                    if ($paystackData['status'] === true) {
                        return response()->json([
                            'responseCode' => 201,
                            'responseMessage' => 'Booking created. Proceed to payment.',
                            'data' => [
                                'booking_order' => $bookingOrder,
                                'transaction' => $transaction,
                                'payment' => [
                                    'authorization_url' => $paystackData['data']['authorization_url'],
                                    'access_code' => $paystackData['data']['access_code'],
                                    'reference' => $paystackData['data']['reference']
                                ]
                            ]
                        ], 201);
                    } else {
                        return response()->json([
                            'responseCode' => 400,
                            'responseMessage' => 'Failed to initialize payment',
                            'error' => $paystackData['message'] ?? 'Unknown error'
                        ], 400);
                    }
                } catch (Exception $e) {
                    return response()->json([
                        'responseCode' => 422,
                        'responseMessage' => 'Error initializing payment',
                        'error' => $e->getMessage()
                    ], 422);
                }
            } else if ($request->type == 'ehailing') {

                $request->validate([
                    'user_id' => ['required'],
                    'pickup_location' => ['required'],
                    'dropoff_location' => ['required'],
                    'vehicle_id' => ['required'],
                    'amount' => ['required'],
                    'type' => ['required']
                ]);

                // Create booking order with PENDING status
                $bookingOrder = BookingOrder::create([
                    'user_id' => $request->user_id,
                    'vehicle_id' => $request->vehicle_id,
                    'pickup_location' => $request->pickup_location,
                    'dropoff_location' => $request->dropoff_location,
                    'amount' => $request->amount,
                    'wth_driver' => $request->wth_driver,
                    'payment_status' => 0,
                    'status' => 0,
                    'type' => $request->type
                ]);

                // Create transaction record with PENDING status
                $transaction = Transaction::create([
                    'booking_order_id' => $bookingOrder->id,
                    'transaction_id' => 'TXN_' . $bookingOrder->id . '_' . time(),
                    'transaction_desc' => 'E-Hailing Payment',
                    'user_id' => $request->user_id,
                    'amount' => $request->amount,
                    'response_code' => '000',
                    'response_message' => 'Pending',
                    'raw_json' => json_encode(['status' => 'pending']),
                    'status' => 'pending'
                ]);

                // Initialize Paystack payment
                try {
                    $user = User::find($request->user_id);
                    $paystackResponse = Http::withHeaders([
                        'Authorization' => 'Bearer ' . env('PAYSTACK_TEST_KEY'),
                    ])->post('https://api.paystack.co/transaction/initialize', [
                                'email' => $user->email,
                                'amount' => (int) ($request->amount * 100),
                                'reference' => $transaction->transaction_id,
                                'metadata' => [
                                    'booking_order_id' => $bookingOrder->id,
                                    'transaction_id' => $transaction->id,
                                    'user_id' => $request->user_id,
                                    'vehicle_id' => $request->vehicle_id
                                ]
                            ]);

                    $paystackData = $paystackResponse->json();

                    if ($paystackData['status'] === true) {
                        $getVehicleRecord = Vehicle::where('id', $request->vehicle_id)->first();
                        $getStation = Station::where('id', $getVehicleRecord->station_id)->first();
                        $getCoordinates = Location::where('id', $getStation->location_id)->first();
                        $getUser = User::where('id', $getVehicleRecord->user_id)->first();

                        return response()->json([
                            'responseCode' => 201,
                            'responseMessage' => 'Booking created. Proceed to payment.',
                            'data' => [
                                'booking_order' => $bookingOrder,
                                'transaction' => $transaction,
                                'coordinates' => $getCoordinates,
                                'driver' => $getUser,
                                'payment' => [
                                    'authorization_url' => $paystackData['data']['authorization_url'],
                                    'access_code' => $paystackData['data']['access_code'],
                                    'reference' => $paystackData['data']['reference']
                                ]
                            ]
                        ], 201);
                    } else {
                        return response()->json([
                            'responseCode' => 400,
                            'responseMessage' => 'Failed to initialize payment',
                            'error' => $paystackData['message'] ?? 'Unknown error'
                        ], 400);
                    }
                } catch (Exception $e) {
                    return response()->json([
                        'responseCode' => 422,
                        'responseMessage' => 'Error initializing payment',
                        'error' => $e->getMessage()
                    ], 422);
                }

            }

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        }
    }

    public function getMyBookings(Request $request)
    {
        try {
            $request->validate([
                'user_id' => ['required']
            ]);

            $myBookings = BookingOrder::with(['vehicle', 'vehicle.firstPhoto'])->where('user_id', $request->user_id)->orderBy('created_at', 'desc')->get();

            if ($myBookings->isEmpty()) {
                // If no bookings found for the user
                return response()->json([
                    'responseCode' => 404,
                    'responseMessage' => 'No bookings found'
                ], 404);
            } else {
                // If bookings exist
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Success',
                    'data' => $myBookings
                ], 201);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        }


    }

    public function updateTrip(Request $request)
    {
        $request->validate([
            'trip_id' => ['required'],
            'vehicle_id' => ['required'],
            'reason' => ['sometimes']
        ]);

        BookingOrder::where('id', $request->trip_id)->update(['reason' => $request->reason]);
        Vehicle::where('id', $request->vehicle_id)->update(['on_trip' => 0]);
        return response()->json([
            'responseCode' => 200,
            'responseMessage' => 'Trip updated successfully'
        ], 200);


    }
}
