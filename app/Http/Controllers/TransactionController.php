<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingOrder;
use App\Models\Transaction;
use App\Models\Vehicle;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        try {
            $transaction = $request->validate([
                'booking_order_id' => 'required',
                'transaction_id' => ['required', 'unique:transactions'],
                'transaction_desc' => 'required',
                'user_id' => 'required',
                'amount' => 'required',
                'response_code' => 'required',
                'response_message' => 'required',
                'raw_json' => 'required',
                'status' => 'required'
            ]);
            $create = Transaction::create($transaction);
            if ($create):
                return response()->json([
                    'responseCode' => 201,
                    'responseMessage' => 'Transaction created successfully',
                ], 201);
            else:
                return response()->json(['responseMessage' => 'An error occurred initializing transaction', 'responseCode' => 400], 400);
            endif;

        } catch (ValidationException $e) {
            return response()->json([
                'responseMessage' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }
    public function update(Request $request)
    {
        try {
            $request->validate([
                'transaction_id' => 'required'
            ]);

            // Find the transaction
            $transaction = Transaction::where('transaction_id', $request->transaction_id)->first();
            if (!$transaction):
                return response()->json(['responseMessage' => 'Transaction ID not found', 'responseCode' => 404], 404);
            endif;

            // Get booking order
            $bookingOrder = BookingOrder::find($transaction->booking_order_id);
            if (!$bookingOrder):
                return response()->json(['responseMessage' => 'Booking order not found', 'responseCode' => 404], 404);
            endif;

            $paystackResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PAYSTACK_TEST_KEY'),
            ])->get('https://api.paystack.co/transaction/verify/' . $request->transaction_id);
            $paystackData = $paystackResponse->json();
            // print_r($paystackData);
            // die;
            if ($paystackData['status'] === true) {
                // Update transaction
                $transaction->update([
                    'response_code' => $paystackData['data']['response_code'] ?? 99,
                    'response_message' => $paystackData['data']['gateway_response'],
                    'raw_json' => json_encode($paystackData),
                    'status' => $paystackData['data']['status']
                ]);

                // Payment successful
                $bookingOrder->update([
                    'payment_status' => 1, // 1 = paid
                    // 'status' => 1 // 1 = approved
                ]);

              
                // Send Firebase notification to user about successful payment
                $user = User::find($bookingOrder->user_id);
                if ($user && $user->fcm_token) {
                    $firebaseService = new FirebaseNotificationService();
                    $firebaseService->sendNotification(
                        $user->fcm_token,
                        'Payment Successful',
                        'Your payment has been confirmed. Your ride is on the way!',
                        [
                            'booking_order_id' => (string) $bookingOrder->id,
                            'transaction_id' => $transaction->transaction_id,
                            'type' => 'payment_success'
                        ]
                    );
                }

                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Payment verified successfully. Booking confirmed.',
                    'data' => [
                        'transaction' => $transaction,
                        'booking_order' => $bookingOrder
                    ]
                ], 200);

            }elseif ($paystackData['status'] === false) {
                // Payment failed
                $bookingOrder->update([
                    'payment_status' => 0, // 0 = unpaid
                    'status' => 0, // 0 = pending
                    'response_code' => 99
                ]);

                return response()->json([
                    'responseCode' => 400,
                    'responseMessage' => 'Payment failed. Booking cancelled.',
                    'data' => [
                        'transaction' => $transaction,
                        'booking_order' => $bookingOrder
                    ]
                ], 400);
            }else{
                 // Still pending
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Transaction status updated',
                    'data' => [
                        'transaction' => $transaction,
                        'booking_order' => $bookingOrder
                    ]
                ], 200);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'responseMessage' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        }
    }
}
