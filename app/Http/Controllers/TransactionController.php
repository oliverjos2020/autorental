<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function create(Request $request)
    {
        try{
            $transaction = $this->validate([
                'transaction_id' => 'required',
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
}
