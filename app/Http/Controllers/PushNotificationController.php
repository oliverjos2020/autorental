<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseNotificationService;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class PushNotificationController extends Controller
{
    /**
     * Send push notification to a specific device
     * 
     * POST /api/v1/push-notification/send
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'data' => 'sometimes|array'
            ]);

            $result = FirebaseNotificationService::push(
                $request->device_token,
                $request->title,
                $request->message,
                $request->data ?? []
            );

            if ($result['success']) {
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Notification sent successfully',
                    'data' => $result
                ], 200);
            } else {
                return response()->json([
                    'responseCode' => 400,
                    'responseMessage' => 'Failed to send notification',
                    'error' => $result['message']
                ], 400);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => 422,
                'responseMessage' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send push notification to a user by user_id
     * 
     * POST /api/v1/push-notification/send-to-user
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToUser(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'data' => 'sometimes|array'
            ]);

            $user = User::find($request->user_id);

            if (!$user->fcm_token) {
                return response()->json([
                    'responseCode' => 400,
                    'responseMessage' => 'User does not have a registered device token'
                ], 400);
            }

            $result = FirebaseNotificationService::push(
                $user->fcm_token,
                $request->title,
                $request->message,
                $request->data ?? []
            );

            if ($result['success']) {
                return response()->json([
                    'responseCode' => 200,
                    'responseMessage' => 'Notification sent to user successfully',
                    'data' => [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        ...$result
                    ]
                ], 200);
            } else {
                return response()->json([
                    'responseCode' => 400,
                    'responseMessage' => 'Failed to send notification to user',
                    'error' => $result['message']
                ], 400);
            }

        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => 422,
                'responseMessage' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send push notification to multiple devices
     * 
     * POST /api/v1/push-notification/send-to-many
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToMany(Request $request)
    {
        try {
            $request->validate([
                'device_tokens' => 'required|array|min:1',
                'device_tokens.*' => 'required|string',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'data' => 'sometimes|array'
            ]);

            $result = FirebaseNotificationService::pushToMany(
                $request->device_tokens,
                $request->title,
                $request->message,
                $request->data ?? []
            );

            return response()->json([
                'responseCode' => $result['success'] ? 200 : 400,
                'responseMessage' => $result['message'],
                'data' => [
                    'total_devices' => count($request->device_tokens),
                    'sent' => $result['sent'],
                    'failed' => $result['failed']
                ]
            ], $result['success'] ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => 422,
                'responseMessage' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send push notification to all users with a specific role
     * 
     * POST /api/v1/push-notification/send-to-role
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendToRole(Request $request)
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'data' => 'sometimes|array'
            ]);

            $users = User::where('role_id', $request->role_id)
                ->whereNotNull('fcm_token')
                ->pluck('fcm_token')
                ->toArray();

            if (empty($users)) {
                return response()->json([
                    'responseCode' => 400,
                    'responseMessage' => 'No users with registered devices found for this role'
                ], 400);
            }

            $result = FirebaseNotificationService::pushToMany(
                $users,
                $request->title,
                $request->message,
                $request->data ?? []
            );

            return response()->json([
                'responseCode' => $result['success'] ? 200 : 400,
                'responseMessage' => $result['message'],
                'data' => [
                    'total_users' => count($users),
                    'sent' => $result['sent'],
                    'failed' => $result['failed']
                ]
            ], $result['success'] ? 200 : 400);

        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => 422,
                'responseMessage' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register/Update device FCM token for a user
     * 
     * POST /api/v1/push-notification/register-device
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerDevice(Request $request)
    {
        try {
            $request->validate([
                'device_token' => 'required|string'
            ]);

            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'responseCode' => 401,
                    'responseMessage' => 'Unauthorized'
                ], 401);
            }

            $user->update(['fcm_token' => $request->device_token]);

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Device token registered successfully',
                'data' => [
                    'user_id' => $user->id,
                    'device_registered' => true
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => 422,
                'responseMessage' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove device FCM token for a user (logout/unregister)
     * 
     * POST /api/v1/push-notification/unregister-device
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unregisterDevice(Request $request)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'responseCode' => 401,
                    'responseMessage' => 'Unauthorized'
                ], 401);
            }

            $user->update(['fcm_token' => null]);

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'Device token removed successfully',
                'data' => [
                    'user_id' => $user->id,
                    'device_registered' => false
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 500,
                'responseMessage' => 'Server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
