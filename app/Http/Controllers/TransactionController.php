<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Vehicle;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        try{
            $transaction = $request->validate([
                'booking_id' => 'required',
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
            if($create):
                return response()->json([
                    'responseCode' => 201,
                    'responseMessage' => 'Transaction created successfully',
                ], 201);
            else:
                return response()->json(['responseMessage' => 'An error occurred initializing transaction', 'responseCode' => 400], 400);
            endif;

        } catch (ValidationException $e){
            return response()->json([
                'responseMessage' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }
    public function update(Request $request)
    {
        try{
            $request->validate([
                'transaction_id' => 'required',
                'response_code' => 'required',
                'response_message' => 'required',
                'raw_json' => 'required',
                'status' => 'required'
            ]);
            $update = Transaction::where('transaction_id', $request->transaction_id)->update([
                'response_code' => $request->response_code,
                'response_message' => $request->response_message,
                'raw_json' => $request->raw_json,
                'status' => $request->status
            ]);

            if(!$update):
                return response()->json(['responseMessage' => 'Transaction ID not found', 'responseCode' => 404], 404);
            endif;

            $getVehicleID = Transaction::where('transaction_id', $request->transaction_id)->first();
            Vehicle::where('id', $getVehicleID->vehicle_id)->update([
                'on_trip' => 1
            ]);

            if($update):
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Transaction updated successfully',
                ], 200);
            else:
                return response()->json(['responseMessage' => 'An error occurred updating transaction', 'responseCode' => 400], 400);
            endif;

        } catch (ValidationException $e){
            return response()->json([
                'responseMessage' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }
}
