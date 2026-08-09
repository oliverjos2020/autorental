# Setup Instructions for New Features

## Quick Start Guide

Follow these steps to set up and test all the new features.

---

## Step 1: Environment Configuration

### 1.1 Update `.env` file

Add the following configuration:

```env
# Paystack Configuration
PAYSTACK_TEST_KEY=your_paystack_test_key_here
PAYSTACK_LIVE_KEY=your_paystack_live_key_here

# Firebase Configuration
FIREBASE_PROJECT_ID=your_firebase_project_id
FIREBASE_CREDENTIALS_PATH=firebase-credentials.json
```

### 1.2 Firebase Service Account Setup

1. Go to Firebase Console: https://console.firebase.google.com/
2. Select your project
3. Go to Settings → Service Accounts
4. Click "Generate New Private Key"
5. Save the JSON file as `firebase-credentials.json`
6. Move the file to `storage/app/firebase-credentials.json`

```bash
# Create the file in the correct location
mkdir -p storage/app
# Copy your firebase-credentials.json to storage/app/
```

---

## Step 2: Database Setup

### 2.1 Run Migrations

```bash
# Run all pending migrations
php artisan migrate

# Or run specific migrations
php artisan migrate --path=database/migrations/2026_08_06_000000_add_status_column_to_vehicles_table.php
php artisan migrate --path=database/migrations/2026_08_06_000001_add_fcm_token_to_users_table.php
```

### 2.2 Verify Database Changes

Check that the following columns were added:

**vehicles table**:
- `publication_status` (enum: 'pending', 'published')

**users table**:
- `fcm_token` (text, nullable)

---

## Step 3: Update Existing Vehicles

If you have existing vehicles, set their publication status:

```bash
# Via Laravel Tinker
php artisan tinker

# In tinker console:
>>> App\Models\Vehicle::query()->update(['publication_status' => 'published']);
>>> exit
```

Or via SQL:
```sql
UPDATE vehicles SET publication_status = 'published' WHERE publication_status IS NULL;
```

---

## Step 4: Test the Features

### 4.1 Test Vehicle Registration with Status

1. Go to `/register-vehicle`
2. Fill in vehicle details
3. You should see a "Vehicle Status" dropdown with:
   - Pending
   - Published
4. Select "Published" and submit
5. Vehicle should appear in API with `publication_status = 'published'`

### 4.2 Test Category Display

1. Go to `/myVehicles`
2. Verify that the "Category" column displays the category name
3. Should show category from the category relationship

### 4.3 Test API Vehicles Endpoint

```bash
# Get published vehicles with pagination
curl -X GET "http://localhost:8000/api/v1/vehicles?limit=10&page=1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"

# Response should include pagination metadata
```

### 4.4 Test Booking with Payment

```bash
# Create a booking
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

# Response should include:
# - booking_order
# - transaction
# - payment (with authorization_url, access_code, reference)
```

### 4.5 Test Transaction Update

```bash
# Update transaction after payment
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

# Should update booking status and send notification
```

### 4.6 Test Booking Report

1. Go to `/booking-report`
2. Verify that the table displays:
   - Customer Name
   - Customer Email
   - Customer Phone
   - Vehicle Details
   - All other booking information

---

## Step 5: Firebase Cloud Messaging Setup

### 5.1 Update User FCM Tokens

Users need to send their FCM tokens to the backend. Create an endpoint or update user profile:

```php
// In UserAPIController or similar
Route::post('/api/v1/user/update-fcm-token', function (Request $request) {
    $request->validate(['fcm_token' => 'required|string']);
    
    auth()->user()->update(['fcm_token' => $request->fcm_token]);
    
    return response()->json(['message' => 'FCM token updated']);
});
```

### 5.2 Test FCM Notifications

1. Set FCM token for a user
2. Create a booking and complete payment
3. User should receive notification: "Payment Successful - Your ride is on the way!"

### 5.3 Test Station Admin Notifications

1. Set FCM token for station admin user
2. Create a booking
3. Station admin should receive notification with vehicle details

---

## Step 6: Verify All Components

### 6.1 Check Migrations

```bash
php artisan migrate:status
```

You should see:
- `2026_08_06_000000_add_status_column_to_vehicles_table` - Migrated
- `2026_08_06_000001_add_fcm_token_to_users_table` - Migrated

### 6.2 Check Service Registration

Verify that `FirebaseNotificationService` is properly loaded:

```bash
php artisan tinker

>>> app(App\Services\FirebaseNotificationService::class)
# Should return service instance
```

### 6.3 Check Routes

```bash
php artisan route:list | grep -E "notify-station-admin|unread-count"
```

Should show:
- `POST /api/v1/notify-station-admin`
- `GET /api/v1/notifications/unread-count`

---

## Step 7: Production Deployment

### 7.1 Update Environment

Change to production keys:

```env
PAYSTACK_TEST_KEY=your_paystack_live_key_here
FIREBASE_PROJECT_ID=your_firebase_project_id
```

### 7.2 Cache Configuration

```bash
php artisan config:cache
php artisan route:cache
```

### 7.3 Run Migrations

```bash
php artisan migrate --force
```

---

## Troubleshooting

### Issue: Firebase credentials not found

**Solution**:
```bash
# Verify file exists
ls -la storage/app/firebase-credentials.json

# Check permissions
chmod 644 storage/app/firebase-credentials.json
```

### Issue: Paystack payment initialization fails

**Solution**:
1. Verify `PAYSTACK_TEST_KEY` is correct
2. Check internet connection
3. Verify Paystack API is accessible
4. Check Laravel logs: `storage/logs/laravel.log`

### Issue: FCM notifications not sending

**Solution**:
1. Verify Firebase credentials are valid
2. Check that user has `fcm_token` set
3. Check Laravel logs for errors
4. Verify Firebase project ID is correct

### Issue: Migrations fail

**Solution**:
```bash
# Check migration status
php artisan migrate:status

# Rollback if needed
php artisan migrate:rollback

# Run again
php artisan migrate
```

---

## Testing with Postman

### 1. Create Postman Collection

1. Import the following endpoints into Postman
2. Set `{{base_url}}` to `http://localhost:8000`
3. Set `{{jwt_token}}` to your JWT token

### 2. Test Endpoints

**Get Published Vehicles**
```
GET {{base_url}}/api/v1/vehicles?limit=10&page=1
Headers: Authorization: Bearer {{jwt_token}}
```

**Create Booking**
```
POST {{base_url}}/api/v1/booking
Headers: Authorization: Bearer {{jwt_token}}
Body: (see API_CHANGES.md for example)
```

**Update Transaction**
```
POST {{base_url}}/api/v1/transaction/update
Headers: Authorization: Bearer {{jwt_token}}
Body: (see API_CHANGES.md for example)
```

**Get Unread Count**
```
GET {{base_url}}/api/v1/notifications/unread-count
Headers: Authorization: Bearer {{jwt_token}}
```

---

## Performance Optimization

### 1. Database Indexing

Add indexes for better query performance:

```sql
-- Add indexes for frequently queried columns
ALTER TABLE vehicles ADD INDEX idx_publication_status (publication_status);
ALTER TABLE vehicles ADD INDEX idx_on_trip (on_trip);
ALTER TABLE booking_orders ADD INDEX idx_payment_status (payment_status);
ALTER TABLE booking_orders ADD INDEX idx_status (status);
ALTER TABLE users ADD INDEX idx_fcm_token (fcm_token);
```

### 2. Query Optimization

The API already uses eager loading:
```php
Vehicle::with(['photos', 'user', 'priceSetup', 'station'])->where(...)
```

### 3. Caching

Consider caching published vehicles:

```php
$vehicles = Cache::remember('published_vehicles_page_' . $page, 3600, function () {
    return Vehicle::where('publication_status', 'published')->paginate(50);
});
```

---

## Monitoring & Logging

### 1. Check Application Logs

```bash
tail -f storage/logs/laravel.log
```

### 2. Monitor Firebase Notifications

Firebase Console → Cloud Messaging → Sent messages

### 3. Monitor Paystack Transactions

Paystack Dashboard → Transactions

---

## Support & Documentation

- **API Documentation**: See `API_CHANGES.md`
- **Implementation Details**: See `IMPLEMENTATION_SUMMARY.md`
- **Changes Log**: See `CHANGES_LOG.md`

---

## Checklist

- [ ] Updated `.env` with Paystack and Firebase keys
- [ ] Placed Firebase credentials in `storage/app/firebase-credentials.json`
- [ ] Ran database migrations
- [ ] Updated existing vehicles with publication_status
- [ ] Tested vehicle registration with status dropdown
- [ ] Tested category display on /myVehicles
- [ ] Tested /api/v1/vehicles endpoint with pagination
- [ ] Tested booking creation with payment initialization
- [ ] Tested transaction update endpoint
- [ ] Tested Firebase notifications
- [ ] Tested booking report with email and phone
- [ ] Verified all routes are registered
- [ ] Tested in production environment

---

**Setup Date**: August 6, 2026
**Status**: Ready for Testing ✅
