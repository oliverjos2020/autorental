# API Changes Documentation

## Updated Endpoints

### 1. GET /api/v1/vehicles
**Changes**: Added publication_status filter and pagination

**Query Parameters**:
- `limit` (optional, default: 50) - Number of results per page
- `page` (optional, default: 1) - Page number
- `vehicleMake` (optional) - Filter by vehicle make
- `vehicleModel` (optional) - Filter by vehicle model
- `vehicleYear` (optional) - Filter by vehicle year
- `station_id` (optional) - Filter by station

**Response**:
```json
{
  "responseCode": 200,
  "responseMessage": "success",
  "data": [
    {
      "id": 1,
      "vehicleMake": "Toyota",
      "vehicleModel": "Corolla",
      "vehicleYear": "2022",
      "publication_status": "published",
      "photos": [...],
      "priceSetup": {...},
      "station": {...}
    }
  ],
  "pagination": {
    "total": 150,
    "per_page": 50,
    "current_page": 1,
    "last_page": 3,
    "from": 1,
    "to": 50
  }
}
```

**Note**: Only returns vehicles with `publication_status = 'published'`

---

### 2. POST /api/v1/booking
**Changes**: Now creates transaction record and initializes Paystack payment

**Request Body**:
```json
{
  "user_id": 1,
  "vehicle_id": 5,
  "pickup_location": "Lekki",
  "dropoff_location": "VI",
  "pickupDate": "2026-08-10",
  "dropoffDate": "2026-08-12",
  "amount": 50000,
  "duration": 2,
  "wth_driver": 1,
  "type": "booking",
  "address": "123 Main St",
  "driverLicense": "file",
  "identity_card": "file"
}
```

**Response** (Success):
```json
{
  "responseCode": 201,
  "responseMessage": "Booking created. Proceed to payment.",
  "data": {
    "booking_order": {
      "id": 10,
      "user_id": 1,
      "vehicle_id": 5,
      "amount": 50000,
      "payment_status": 0,
      "status": 0,
      "created_at": "2026-08-06T10:30:00Z"
    },
    "transaction": {
      "id": 15,
      "booking_order_id": 10,
      "transaction_id": "TXN_10_1722950400",
      "amount": 50000,
      "status": "pending",
      "created_at": "2026-08-06T10:30:00Z"
    },
    "payment": {
      "authorization_url": "https://checkout.paystack.com/...",
      "access_code": "...",
      "reference": "TXN_10_1722950400"
    }
  }
}
```

**Flow**:
1. Creates BookingOrder with status=0 (pending), payment_status=0 (unpaid)
2. Creates Transaction with status='pending'
3. Initializes Paystack payment
4. Returns payment authorization URL for client to redirect user

---

### 3. POST /api/v1/transaction/update
**Changes**: Enhanced to handle payment verification and update booking status

**Request Body**:
```json
{
  "transaction_id": "TXN_10_1722950400",
  "response_code": "00",
  "response_message": "Approved",
  "raw_json": "{\"status\": true, \"message\": \"Authorization URL created\"}",
  "status": "completed"
}
```

**Status Values**:
- `completed` - Payment successful
- `failed` - Payment failed
- `pending` - Still pending

**Response** (Payment Successful):
```json
{
  "responseCode": 200,
  "responseMessage": "Payment verified successfully. Booking confirmed.",
  "data": {
    "transaction": {
      "id": 15,
      "transaction_id": "TXN_10_1722950400",
      "status": "completed",
      "response_code": "00",
      "response_message": "Approved"
    },
    "booking_order": {
      "id": 10,
      "payment_status": 1,
      "status": 1
    }
  }
}
```

**Automatic Actions**:
- Updates transaction with payment response
- Sets booking payment_status = 1 (paid)
- Sets booking status = 1 (approved)
- Sets vehicle on_trip = 1
- Sends Firebase notification to user: "Payment Successful - Your ride is on the way!"

**Response** (Payment Failed):
```json
{
  "responseCode": 400,
  "responseMessage": "Payment failed. Booking cancelled.",
  "data": {
    "transaction": {...},
    "booking_order": {...}
  }
}
```

---

### 4. POST /api/v1/notify-station-admin (NEW)
**Purpose**: Send notification to station admin when vehicle is booked

**Request Body**:
```json
{
  "booking_order_id": 10
}
```

**Response**:
```json
{
  "responseCode": 200,
  "message": "Notification sent to station admin"
}
```

**Automatic Actions**:
- Finds station admin/manager for the vehicle's station
- Sends FCM notification with vehicle details and customer name
- Includes sound alert on mobile devices

---

### 5. GET /api/v1/notifications/unread-count (NEW)
**Purpose**: Get unread notification count for current user

**Response** (Station Admin):
```json
{
  "responseCode": 200,
  "unread_count": 5,
  "type": "station_admin"
}
```

**Response** (Other Users):
```json
{
  "responseCode": 200,
  "unread_count": 0
}
```

---

## New Database Columns

### vehicles table
- `publication_status` (enum: 'pending', 'published') - Default: 'pending'

### users table
- `fcm_token` (text, nullable) - Firebase Cloud Messaging token

---

## Payment Flow Diagram

```
1. Client calls POST /api/v1/booking
   ↓
2. Server creates:
   - BookingOrder (status=0, payment_status=0)
   - Transaction (status='pending')
   ↓
3. Server initializes Paystack payment
   ↓
4. Server returns authorization_url to client
   ↓
5. Client redirects user to Paystack checkout
   ↓
6. User completes payment on Paystack
   ↓
7. Client receives payment response from Paystack
   ↓
8. Client calls POST /api/v1/transaction/update with payment details
   ↓
9. Server verifies payment and updates:
   - Transaction (status='completed')
   - BookingOrder (payment_status=1, status=1)
   - Vehicle (on_trip=1)
   ↓
10. Server sends FCM notification to user
    ↓
11. Server sends FCM notification to station admin
```

---

## Error Handling

### Validation Errors
```json
{
  "responseCode": 422,
  "errors": {
    "user_id": ["The user id field is required."],
    "vehicle_id": ["The vehicle id field is required."]
  }
}
```

### Not Found Errors
```json
{
  "responseCode": 404,
  "responseMessage": "Booking order not found"
}
```

### Payment Initialization Errors
```json
{
  "responseCode": 400,
  "responseMessage": "Failed to initialize payment",
  "error": "Paystack error message"
}
```

---

## Testing with cURL

### Get Published Vehicles with Pagination
```bash
curl -X GET "http://localhost:8000/api/v1/vehicles?limit=10&page=1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Create Booking
```bash
curl -X POST "http://localhost:8000/api/v1/booking" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "vehicle_id": 5,
    "pickup_location": "Lekki",
    "dropoff_location": "VI",
    "pickupDate": "2026-08-10",
    "dropoffDate": "2026-08-12",
    "amount": 50000,
    "duration": 2,
    "wth_driver": 1,
    "type": "booking",
    "address": "123 Main St"
  }'
```

### Update Transaction
```bash
curl -X POST "http://localhost:8000/api/v1/transaction/update" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_id": "TXN_10_1722950400",
    "response_code": "00",
    "response_message": "Approved",
    "raw_json": "{\"status\": true}",
    "status": "completed"
  }'
```

### Get Unread Count
```bash
curl -X GET "http://localhost:8000/api/v1/notifications/unread-count" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```
