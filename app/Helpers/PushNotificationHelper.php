<?php

use App\Services\FirebaseNotificationService;
use App\Models\User;

/**
 * Send push notification to a device
 * 
 * @param string $deviceToken - FCM device token
 * @param string $title - Notification title
 * @param string $message - Notification body/message
 * @param array $data - Optional additional data payload
 * @return array
 */
if (!function_exists('sendPushNotification')) {
    function sendPushNotification($deviceToken, $title, $message, $data = [])
    {
        return FirebaseNotificationService::push($deviceToken, $title, $message, $data);
    }
}

/**
 * Send push notification to a user by user ID
 * 
 * @param int $userId - User ID
 * @param string $title - Notification title
 * @param string $message - Notification body/message
 * @param array $data - Optional additional data payload
 * @return array
 */
if (!function_exists('sendPushToUser')) {
    function sendPushToUser($userId, $title, $message, $data = [])
    {
        $user = User::find($userId);
        
        if (!$user || !$user->fcm_token) {
            return [
                'success' => false,
                'message' => 'User not found or no device token registered'
            ];
        }
        
        return FirebaseNotificationService::push($user->fcm_token, $title, $message, $data);
    }
}

/**
 * Send push notification to multiple devices
 * 
 * @param array $deviceTokens - Array of FCM device tokens
 * @param string $title - Notification title
 * @param string $message - Notification body/message
 * @param array $data - Optional additional data payload
 * @return array
 */
if (!function_exists('sendPushToMany')) {
    function sendPushToMany($deviceTokens, $title, $message, $data = [])
    {
        return FirebaseNotificationService::pushToMany($deviceTokens, $title, $message, $data);
    }
}

/**
 * Send push notification to all users with a specific role
 * 
 * @param int $roleId - Role ID
 * @param string $title - Notification title
 * @param string $message - Notification body/message
 * @param array $data - Optional additional data payload
 * @return array
 */
if (!function_exists('sendPushToRole')) {
    function sendPushToRole($roleId, $title, $message, $data = [])
    {
        $tokens = User::where('role_id', $roleId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();
        
        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No users with registered devices found for this role'
            ];
        }
        
        return FirebaseNotificationService::pushToMany($tokens, $title, $message, $data);
    }
}

/**
 * Send push notification to all users in a station
 * 
 * @param int $stationId - Station ID
 * @param string $title - Notification title
 * @param string $message - Notification body/message
 * @param array $data - Optional additional data payload
 * @return array
 */
if (!function_exists('sendPushToStation')) {
    function sendPushToStation($stationId, $title, $message, $data = [])
    {
        $tokens = User::where('station_id', $stationId)
            ->whereNotNull('fcm_token')
            ->pluck('fcm_token')
            ->toArray();
        
        if (empty($tokens)) {
            return [
                'success' => false,
                'message' => 'No users with registered devices found for this station'
            ];
        }
        
        return FirebaseNotificationService::pushToMany($tokens, $title, $message, $data);
    }
}
