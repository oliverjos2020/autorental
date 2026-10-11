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
use App\Services\PaystackService;
use Illuminate\Support\Facades\Log;
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

                $user = User::find($request->user_id);
                if(!$user){
                    return response()->json([
                        'responseCode' => 400,
                        'responseMessage' => 'User not found',
                        'error' => 'User not found'
                    ], 400);
                }
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

                $vehicle = Vehicle::find($request->vehicle_id);
                if(!$vehicle){
                    return response()->json([
                        'responseCode' => 400,
                        'responseMessage' => 'Vehicle not found',
                        'error' => 'Vehicle not found'
                    ], 400);
                }

                $getCarOwner = User::find($vehicle->user_id);

                if(!$getCarOwner){
                    return response()->json([
                        'responseCode' => 400,
                        'responseMessage' => 'Car Owner not found',
                        'error' => 'User not found'
                    ], 400);
                }
                if (empty($getCarOwner->account_code)) {
                    return response()->json([
                        'responseCode' => 400,
                        'responseMessage' => 'Car owner has no payout account set up',
                        'error' => 'Car owner has no Paystack subaccount'
                    ], 400);
                }

                // Work out VAT and the 3-way split before creating any records
                $paystack = new PaystackService();
                try {
                    $split = $paystack->buildSplit((float) $request->amount, $getCarOwner->account_code);
                } catch (Exception $e) {
                    Log::error('Booking split configuration error', ['error' => $e->getMessage()]);
                    return response()->json([
                        'responseCode' => 500,
                        'responseMessage' => 'Payment split is not configured',
                        'error' => $e->getMessage()
                    ], 500);
                }

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
                    'amount' => $split['total'] / 100, // booking amount + VAT
                    'response_code' => '99',
                    'response_message' => 'Pending',
                    'raw_json' => json_encode(['status' => 'pending']),
                    'status' => 'pending'
                ]);
 
                // Initialize Paystack payment with split
                try {
                    $paystackData = $paystack->initializeSplitTransaction(
                        $request->user()->email ?? $user->email,
                        $split,
                        $transaction->transaction_id,
                        [
                            'booking_order_id' => $bookingOrder->id,
                            'transaction_id' => $transaction->id,
                            'user_id' => $request->user_id,
                            'vehicle_id' => $request->vehicle_id
                        ]
                    );

                    // Paystack rejects the split when it doesn't know one of the subaccounts.
                    // Find out which one, repair the car owner's if we can, otherwise say what to fix.
                    if (($paystackData['status'] ?? false) !== true && stripos($paystackData['message'] ?? '', 'subaccount') !== false) {
                        $missing = $paystack->missingSubaccounts($split);

                        if (isset($missing['car_owner']) && $getCarOwner->bank_code && $getCarOwner->account_number) {
                            try {
                                $newCode = $paystack->createSubaccount(
                                    $getCarOwner->business_name ?: $getCarOwner->name,
                                    $getCarOwner->bank_code,
                                    // account_number is stored as an integer, which drops leading zeros; NUBANs are always 10 digits
                                    str_pad((string) $getCarOwner->account_number, 10, '0', STR_PAD_LEFT)
                                );
                                Log::warning('Re-created missing Paystack subaccount for car owner', [
                                    'user_id' => $getCarOwner->id, 'old' => $missing['car_owner'], 'new' => $newCode,
                                ]);
                                $getCarOwner->update(['account_code' => $newCode]);
                                unset($missing['car_owner']);

                                if (!$missing) {
                                    $split = $paystack->buildSplit((float) $request->amount, $newCode);
                                    $paystackData = $paystack->initializeSplitTransaction(
                                        $request->user()->email ?? $user->email,
                                        $split,
                                        $transaction->transaction_id,
                                        [
                                            'booking_order_id' => $bookingOrder->id,
                                            'transaction_id' => $transaction->id,
                                            'user_id' => $request->user_id,
                                            'vehicle_id' => $request->vehicle_id
                                        ]
                                    );
                                }
                            } catch (Exception $e) {
                                Log::error('Could not re-create car owner subaccount', ['user_id' => $getCarOwner->id, 'error' => $e->getMessage()]);
                            }
                        }

                        if ($missing) {
                            $labels = [
                                'association' => 'ASSOCIATION_SUBACCOUNT_CODE',
                                'app' => 'APP_SUBACCOUNT_CODE',
                                'car_owner' => "car owner #{$getCarOwner->id}'s account_code",
                            ];
                            $details = [];
                            foreach ($missing as $name => $code) {
                                $details[] = "{$labels[$name]} ({$code})";
                            }
                            Log::error('Paystack subaccounts not found', ['missing' => $missing]);
                            $transaction->update(['status' => 'failed', 'response_message' => 'Subaccount not found']);

                            return response()->json([
                                'responseCode' => 400,
                                'responseMessage' => 'Failed to initialize payment',
                                'error' => 'Paystack does not recognise: ' . implode(', ', $details)
                                    . '. Check these codes exist under the same Paystack key (test/live) the app is using.'
                            ], 400);
                        }
                    }

                    if (($paystackData['status'] ?? false) === true) {
                        return response()->json([
                            'responseCode' => 201,
                            'responseMessage' => 'Booking created. Proceed to payment.',
                            'data' => [
                                'booking_order' => $bookingOrder,
                                'transaction' => $transaction,
                                'payment' => [
                                    'amount' => $split['base'] / 100,
                                    'vat' => $split['vat'] / 100,
                                    'total' => $split['total'] / 100,
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
                ], 200);
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
