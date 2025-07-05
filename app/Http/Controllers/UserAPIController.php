<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Mail\ResetPasswordOtpMail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class UserAPIController extends Controller
{
    public function deleteAccount(Request $request)
    {
        // Validate the email from the request
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        // Find and delete the user
        $user = User::where('email', $validated['email'])->first();
        
        if ($user) {
            $user->delete();
            
            // Add a success flash message
            return redirect()->back()->with('success', 'User account has been successfully deleted.');
        }
        
        // If user not found (though validation should prevent this)
        return redirect()->back()->with('error', 'User account could not be found.');
    }
    
    public function register(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'phone_no' => ['required', 'max:15', 'unique:users'],
                // 'nin' => ['required'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            $roleId = 5;
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'password' => Hash::make($request->password),
                'role_id' => $roleId,
                'nin' => $request->nin,
            ]);

            $otp = $this->generateOTP();
            // Save OTP to the user's record (assuming you have a column in the users table for this)
            $user->otp = $otp;
            $user->save();

            Mail::to($user->email)->send(new SendOtpMail($otp, $request->name));

            // Generate JWT token for the new user
            unset($user['role_id']);
            unset($user['otp']);
            unset($user['updated_at']);
            unset($user['created_at']);

            return response()->json([
                'responseCode' => 201,
                'responseMessage' => 'Registration successful. OTP has been sent to confirm account',
            ], 201);
        } catch (ValidationException $e) {
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
                'user_id' => 'required',
                'passport' => 'required|file|mimes:jpg,png,jpeg|max:300'
            ]);

            $passport = "";
            if ($request->hasFile('passport')) {
                $fileName = time() . 'Passport_' . $request->file('passport')->getClientOriginalName();
                $passport = $request->file('passport')->storeAs('uploads', $fileName, 'public');
            }

            User::where('id', $request->user_id)->update([
                'passport' => '/storage/'. $passport?? null,
            ]);
            return response()->json([
               'responseCode' => 200,
               'responseMessage' => 'Profile updated successfully',
               'data' =>[
                            'passport' => '/storage/'. $passport
                        ]
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'responseCode' => 422,
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
            return response()->json(['responseMessage' => 'The request method is invalid. Please use POST.', 'responseCode' => 405], 405);
        }
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json(['responseMessage' => 'Invalid credentials', 'responseCode' => 401], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json(['responseMessage' => 'Account not verified. Please verify your account with the OTP sent to your email.', 'responseCode' => 403], 403);
        }

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['responseMessage' => 'Invalid credentials', 'responseCode' => 401], 401);
        }

        return response()->json(['responseCode' => 200, 'responseMessage' => 'success', 'token' => $token, 'data' => $user]);
    }

    public function refresh()
    {
        try {
            // Check if a token is present in the request
            if (!$token = JWTAuth::getToken()) {
                return response()->json([
                    'responseMessage' => 'Token not provided',
                    'responseCode' => 400
                ], 400);
            }

            // Refresh the token and return it
            $newToken = JWTAuth::refresh($token);

            return response()->json([
                'token' => $newToken,
            ]);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'responseMessage' => 'Token is invalid',
                'responseCode' => 401
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'responseMessage' => 'Could not refresh token',
                'responseCode' => 500
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'responseCode' => 500
            ], 500);
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
                return response()->json(['responseMessage' => 'OTP confirmed successfully.', 'token' => $token, 'responseCode' => 200], 200);
            }

            return response()->json(['responseMessage' => 'Invalid Email or OTP.', 'responseCode' => 400], 400);
        } catch (ValidationException $e) {
            return response()->json([
                'responseMessage' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['responseMessage' => 'User logged out successfully']);
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
                'responseMessage' => $e->errors(),
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
                'responseMessage' => $e->errors(),
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
                'responseMessage' => $e->errors(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }

}
