<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Exception;

class RatingController extends Controller
{
    public function create(Request $request)
    {
        try {
            $data = $request->validate([
                'user_id' => 'required',
                'rating' => 'required|integer|min:1|max:5',
                'subject' => 'required',
                'details' => 'required'
            ]);
            $rating = Rating::where('user_id', $data['user_id'])->first();
            if ($rating) {
                return response()->json([
                    'responseMessage' => 'You have already rated this user',
                    'responseCode' => 400
                ], 400);
            }
            $user = User::where('id', $data['user_id'])->first();
            if (!$user) {
                return response()->json([
                    'responseMessage' => 'User with id not found',
                    'responseCode' => 404
                ], 404);
            }
            $create = Rating::create($data);
            if ($create):
                return response()->json([
                    'responseCode' => 201,
                    'responseMessage' => 'Thank you for your feedback',
                ], 201);
            else:
                return response()->json([
                    'responseMessage' => 'An error occurred recording feedback', 
                    'responseCode' => 400
                ], 400);
            endif;
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

    public function fetch($user_id)
    {
        try {
            if (empty($user_id)) {
                return response()->json([
                    'errors' => 'Vehicle ID is required.',
                    'responseCode' => 400,
                ], 400);
            }
            $ratings = Rating::where('user_id', $user_id)->get();
            return response()->json([
                'responseMessage' => 'Success', 
                'responseCode' => 200,
                'data' => $ratings
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }
}
