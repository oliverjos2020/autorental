<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\BookingOrder;

class BookingAPIController extends Controller
{
    public function booking(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'user_id' => ['required'],
                'address' => ['required', 'string'],
                'wth_driver' => ['required'],
                'identity_card' => 'required|file|mimes:jpg,png,jpeg|max:300',
                'vehicle_id' => ['required'],
                'pickup_location' => ['required'],
                'dropoff_location' => ['required'],
                'pickupDate' => ['required', 'date'],
                'dropoffDate' => ['required', 'date'],
                'amount' => ['required'],
            ]);

            $request->validate([
                'driverLicense' => 'sometimes|file|mimes:jpg,png,jpeg|max:300'
            ]);


            // Handle identity card upload
            if ($request->hasFile('identity_card')) {
                $fileName = time() . 'ID_' . $request->file('identity_card')->getClientOriginalName();
                $filePath = $request->file('identity_card')->storeAs('uploads', $fileName, 'public');
            }

            // Handle driver license upload
            if ($request->hasFile('driverLicense')) {
                $fileName2 = time() . 'DL_' . $request->file('driverLicense')->getClientOriginalName();
                $filePath2 = $request->file('driverLicense')->storeAs('uploads', $fileName2, 'public');
            }

            // Update user details
            User::where('id', $request->user_id)->update([
                'address' => $request->address,
                'identity_card' => '/storage/' . $filePath ?? null,
                'driverLicense' => '/storage/' . $filePath2 ?? null,
            ]);

            // Create booking order
            $bookingOrder = BookingOrder::create([
                'user_id' => $request->user_id,
                'vehicle_id' => $request->vehicle_id,
                'pickup_location' => $request->pickup_location,
                'dropoff_location' => $request->dropoff_location,
                'pickupDate' => $request->pickupDate,
                'dropoffDate' => $request->dropoffDate,
                'amount' => $request->amount,
                'payment_status' => 0,
                'status' => 0, // pending status for booking request
            ]);

            // Return success response
            return response()->json([
                'responseCode' => 201,
                'responseMessage' => 'Success',
                'data' => $bookingOrder
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        }
    }

}
