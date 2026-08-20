# Push Notification API Documentation

## Base URL
```
https://your-domain.com
```

## Authentication
All endpoints require JWT Bearer token in the Authorization header:
```
Authorization: Bearer <your_jwt_token>
```

---

## Endpoints

### 1. Register Device Token
Register or update the FCM device token for the authenticated user. Call this after user login or when FCM token refreshes.

**Endpoint:** `POST /api/v1/push-notification/register-device`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "device_token": "fMJ8kL9x...your_fcm_token_here"
}
```

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Device token registered successfully",
  "data": {
    "user_id": 1,
    "device_registered": true
  }
}
```

**Error Responses:**
```json
// 401 - Token expired
{
  "responseCode": 401,
  "responseMessage": "Token has expired"
}

// 401 - Invalid token
{
  "responseCode": 401,
  "responseMessage": "Token is invalid"
}

// 422 - Validation error
{
  "responseCode": 422,
  "responseMessage": "Validation error",
  "errors": {
    "device_token": ["The device token field is required."]
  }
}
```

---

### 2. Unregister Device Token
Remove the FCM device token for the authenticated user. Call this on user logout.

**Endpoint:** `POST /api/v1/push-notification/unregister-device`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:** None required

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Device token removed successfully",
  "data": {
    "user_id": 1,
    "device_registered": false
  }
}
```

---

### 3. Send Push Notification to Device
Send a push notification to a specific device using its FCM token.

**Endpoint:** `POST /api/v1/push-notification/send`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "device_token": "fMJ8kL9x...fcm_token_of_target_device",
  "title": "New Booking",
  "message": "You have a new booking request",
  "data": {
    "type": "booking",
    "booking_id": "123",
    "action": "view_booking"
  }
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| device_token | string | Yes | FCM token of the target device |
| title | string | Yes | Notification title (max 255 chars) |
| message | string | Yes | Notification body/message |
| data | object | No | Additional data payload for the app |

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Notification sent successfully",
  "data": {
    "success": true,
    "message": "Notification sent successfully"
  }
}
```

**Error Response (400):**
```json
{
  "responseCode": 400,
  "responseMessage": "Failed to send notification",
  "error": "FCM token is empty"
}
```

---

### 4. Send Push Notification to User
Send a push notification to a user by their user ID.

**Endpoint:** `POST /api/v1/push-notification/send-to-user`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "user_id": 5,
  "title": "Payment Received",
  "message": "Your payment of N50,000 was successful",
  "data": {
    "type": "payment",
    "transaction_id": "TXN_12345",
    "amount": 50000
  }
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| user_id | integer | Yes | ID of the target user |
| title | string | Yes | Notification title (max 255 chars) |
| message | string | Yes | Notification body/message |
| data | object | No | Additional data payload for the app |

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Notification sent to user successfully",
  "data": {
    "user_id": 5,
    "user_name": "John Doe",
    "success": true,
    "message": "Notification sent successfully"
  }
}
```

**Error Response (400):**
```json
{
  "responseCode": 400,
  "responseMessage": "User does not have a registered device token"
}
```

---

### 5. Send Push Notification to Multiple Devices
Send a push notification to multiple devices at once.

**Endpoint:** `POST /api/v1/push-notification/send-to-many`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "device_tokens": [
    "fcm_token_1...",
    "fcm_token_2...",
    "fcm_token_3..."
  ],
  "title": "System Announcement",
  "message": "The app will be under maintenance tonight",
  "data": {
    "type": "announcement",
    "priority": "high"
  }
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| device_tokens | array | Yes | Array of FCM tokens |
| title | string | Yes | Notification title (max 255 chars) |
| message | string | Yes | Notification body/message |
| data | object | No | Additional data payload for the app |

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Sent to 3 of 3 devices",
  "data": {
    "total_devices": 3,
    "sent": 3,
    "failed": 0
  }
}
```

---

### 6. Send Push Notification to Role
Send a push notification to all users with a specific role.

**Endpoint:** `POST /api/v1/push-notification/send-to-role`

**Headers:**
```json
{
  "Authorization": "Bearer <jwt_token>",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "role_id": 2,
  "title": "Driver Alert",
  "message": "New ride requests available in your area",
  "data": {
    "type": "driver_alert",
    "action": "check_rides"
  }
}
```

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| role_id | integer | Yes | ID of the target role |
| title | string | Yes | Notification title (max 255 chars) |
| message | string | Yes | Notification body/message |
| data | object | No | Additional data payload for the app |

**Success Response (200):**
```json
{
  "responseCode": 200,
  "responseMessage": "Sent to 15 of 18 devices",
  "data": {
    "total_users": 18,
    "sent": 15,
    "failed": 3
  }
}
```

---

## Notification Data Payload Examples

### Booking Notification
```json
{
  "data": {
    "type": "booking",
    "booking_id": "123",
    "vehicle_id": "45",
    "action": "view_booking"
  }
}
```

### Payment Notification
```json
{
  "data": {
    "type": "payment",
    "transaction_id": "TXN_12345",
    "booking_id": "123",
    "amount": "50000",
    "status": "success"
  }
}
```

### Trip Update Notification
```json
{
  "data": {
    "type": "trip_update",
    "booking_id": "123",
    "status": "driver_arrived",
    "driver_name": "John Driver"
  }
}
```

### General Announcement
```json
{
  "data": {
    "type": "announcement",
    "priority": "normal",
    "action": "open_app"
  }
}
```

---

## Mobile App Integration Guide

### Flutter/Dart Example

```dart
// Register device token after login
Future<void> registerDeviceToken(String jwtToken, String fcmToken) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/v1/push-notification/register-device'),
    headers: {
      'Authorization': 'Bearer $jwtToken',
      'Content-Type': 'application/json',
    },
    body: jsonEncode({
      'device_token': fcmToken,
    }),
  );
  
  if (response.statusCode == 200) {
    print('Device registered successfully');
  }
}

// Unregister device token on logout
Future<void> unregisterDeviceToken(String jwtToken) async {
  final response = await http.post(
    Uri.parse('$baseUrl/api/v1/push-notification/unregister-device'),
    headers: {
      'Authorization': 'Bearer $jwtToken',
      'Content-Type': 'application/json',
    },
  );
  
  if (response.statusCode == 200) {
    print('Device unregistered successfully');
  }
}

// Handle incoming notification data
void handleNotificationData(Map<String, dynamic> data) {
  switch (data['type']) {
    case 'booking':
      // Navigate to booking details
      navigateToBooking(data['booking_id']);
      break;
    case 'payment':
      // Show payment confirmation
      showPaymentConfirmation(data['transaction_id'], data['amount']);
      break;
    case 'trip_update':
      // Update trip status UI
      updateTripStatus(data['booking_id'], data['status']);
      break;
    default:
      // Handle general notification
      break;
  }
}
```

### React Native Example

```javascript
// Register device token after login
const registerDeviceToken = async (jwtToken, fcmToken) => {
  try {
    const response = await fetch(`${BASE_URL}/api/v1/push-notification/register-device`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${jwtToken}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        device_token: fcmToken,
      }),
    });
    
    const data = await response.json();
    console.log('Device registered:', data);
  } catch (error) {
    console.error('Error registering device:', error);
  }
};

// Unregister device token on logout
const unregisterDeviceToken = async (jwtToken) => {
  try {
    const response = await fetch(`${BASE_URL}/api/v1/push-notification/unregister-device`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${jwtToken}`,
        'Content-Type': 'application/json',
      },
    });
    
    const data = await response.json();
    console.log('Device unregistered:', data);
  } catch (error) {
    console.error('Error unregistering device:', error);
  }
};
```

---

## Important Notes

1. **FCM Token Refresh**: The FCM token can change. Listen for token refresh events and call the register endpoint again.

2. **Token Storage**: Store the JWT token securely on the device (e.g., secure storage, keychain).

3. **Logout Flow**: Always call the unregister endpoint before logging out to stop receiving notifications.

4. **Notification Handling**: 
   - Foreground: Handle in-app notification display
   - Background: System handles notification display
   - Killed: System handles notification display

5. **Data Payload**: The `data` field is passed to your app and can be used for navigation or actions.

6. **Android Configuration**: Ensure your `google-services.json` is properly configured.

7. **iOS Configuration**: Ensure your `GoogleService-Info.plist` and APNs certificates are configured.

---

## Error Codes Summary

| Code | Message | Description |
|------|---------|-------------|
| 200 | Success | Request completed successfully |
| 400 | Bad Request | Invalid request or notification failed |
| 401 | Unauthorized | Invalid, expired, or missing JWT token |
| 422 | Validation Error | Request body validation failed |
| 500 | Server Error | Internal server error |

---

## Contact
For API issues or questions, contact the backend team.
