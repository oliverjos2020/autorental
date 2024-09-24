<?php

namespace App\Http\Controllers;
use App\Mail\SendOtpMail;
use App\Mail\ResetPasswordOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAPIController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_no' => ['required', 'max:15', 'unique:users'],
                'category_id' => ['required'],
                'workplace' => ['required'],
                'state' => ['required'],
                'gender' => ['required'],
                'expertise' => ['required'],
                'yrs_of_exp' => ['required'],
                'photo' => ['required'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $roleId = 2;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'password' => Hash::make($request->password),
                'role_id' => $roleId,
                'category_id' => $request->category_id,
                'workplace' => $request->workplace,
                'state' => $request->state,
                'gender' => $request->gender,
                'expertise' => $request->expertise,
                'yrs_of_exp' => $request->yrs_of_exp,
                'photo' => $request->photo
            ]);

            $otp = $this->generateOTP();

            // Save OTP to the user's record (assuming you have a column in the users table for this)
            $user->otp = $otp;
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail($otp, $request->name));

            return response()->json([
                'responseCode' => 201,
                'responseMessage' => 'Account Created successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'responseCode' => 422,
                'responseMessage' => 'The given data was invalid.',
                'errors' => $e->errors(),
            ], 422);
        }


    }

    public function resendOTP(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'max:255']
        ]);
        $user = User::where('email', $request->email)->first();
        if($user):
            $otp = $this->generateOTP();
            $user->otp = $otp;
            $user->save();
            Mail::to($user->email)->send(new SendOtpMail($otp, $request->name));

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'success',
                'data' => $user,
            ], 200);
        else:
            return response()->json(['responseCode' => 404, 'responseMessage' => 'Email address not found'], 404);
        endif;
    }

    public function login(Request $request)
    {
        if ($request->method() !== 'POST') {
            return response()->json(['error' => 'The request method is invalid. Please use POST.'], 405);
        }
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials', 'responseCode' => 401], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['error' => 'Account not verified. Please verify your account with the OTP sent to your email.', 'responseCode' => 403], 403);
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials', 'responseCode' => 401], 401);
        }

        return response()->json(['responseCode' => 200, 'responseMessage' => 'success', 'token' => $token, 'user' => $user->only(['id', 'name', 'email', 'phone_no', 'passport'])]);
    }

    public function refresh()
{
    try {
        // Parse the expired token and refresh it
        $newToken = JWTAuth::parseToken()->refresh();

        return response()->json([
            'token' => $newToken,
        ]);
    } catch (JWTException $e) {
        return response()->json([
            'error' => 'Token is missing or invalid',
        ], 401);
    }
}

    public function generateOTP($length = 6)
    {
        return mt_rand(100000, 999999); // Generates a 6-digit OTP
    }

    public function confirmOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string'
            ]);

            $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

            if ($user) {
                // OTP is correct, you can now mark the user as verified or proceed further
                $user->otp = null; // Clear the OTP
                $user->email_verified_at = now();
                $user->save();
                $token = JWTAuth::fromUser($user);
                return response()->json(['message' => 'OTP confirmed successfully.', 'token' => $token, 'responseCode' => 200], 200);
            }

            return response()->json(['error' => 'Invalid Email or OTP.', 'responseCode' => 400], 400);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json([
            'responseCode' => 200,
            'responseMessage' => 'User logged out successfully'
        ], 200);
    }

    public function sendOTP(Request $request)
    {
        try{
            $request->validate([
                'email' => ['required', 'email', 'max:255']
            ]);

            $user = User::where('email', $request->email)->first();
            if($user):
                $otp = $this->generateOTP();
                $user->otp = $otp;
                $user->save();
                Mail::to($user->email)->send(new ResetPasswordOtpMail($otp, $request->name));
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'OTP sent successfully'
                ], 200);
            else:
                return response()->json([
                    'responseCode' => 404,
                    'responseMessage' => 'User not found',
                    'data' => [],
                ], 404);
            endif;
        } catch (ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

    public function confirmJustOTP(Request $request)
    {
        try{
            $request->validate([
                'otp' => ['required']
                // 'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);
            $user = User::where('otp', $request->otp)->first();
            if($user):
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Success. OTP Valid'
                ], 200);
            else:
                return response()->json([
                    'responseCode' => 404,
                    'responseMessage' => 'Invalid OTP Passed'
                ], 404);
            endif;
        } catch (ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

    public function changePassword(Request $request)
    {
        try{
            $request->validate([
                'email' => ['required', 'email', 'max:255'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            $user = User::where('email', $request->email)->first();
            if($user):
                $user->otp = null;
                $user->password = Hash::make($request->password);
                $user->save();
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Password changed successfully'
                ], 200);
            else:
                return response()->json([
                    'responseCode' => 404,
                    'responseMessage' => 'User not found',
                    'data' => [],
                ], 404);
            endif;
        } catch (ValidationException $e){
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

}
