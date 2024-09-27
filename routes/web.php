<?php

use App\Http\Livewire\Index;
// use App\Http\Livewire\VendorViewDetails;
// use App\Http\Livewire\VendorManagement;
use App\Http\Livewire\Review;
use App\Http\Livewire\Listing;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Checkout;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\StartRide;
use App\Http\Livewire\MyVehicles;
use App\Http\Livewire\EditVehicle;
use App\Http\Livewire\RideBooking;
use App\Http\Livewire\RideResults;
use App\Http\Livewire\PayPalPayment;
use App\Http\Livewire\RoleManagement;
use App\Http\Livewire\UserManagement;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\MyBookingOrders;
use App\Http\Livewire\RegistrationType;
use Illuminate\Support\Facades\Artisan;
use App\Http\Livewire\StationManagement;
use App\Http\Livewire\CarBrandManagement;
use App\Http\Livewire\CategoryManagement;
use App\Http\Livewire\LocationManagement;
use App\Http\Livewire\DriverVehicleAssignment;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserAPIController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BookingAPIController;
use App\Http\Controllers\TransactionController;


//FOR API
use App\Http\Livewire\PriceSetupManagement;
use App\Http\Livewire\BookingOrderManagement;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/processPaypal', [PaymentController::class, 'processPaypal'])->name('processPaypal');
Route::get('/processSuccess', [PaymentController::class, 'processSuccess'])->name('processSuccess');
Route::get('/processCancel', [PaymentController::class, 'processCancel'])->name('processCancel');



Route::get('/', function () {
    return view('auth.login');
});
Route::get('/link', function(){
    try{
        Artisan::call('storage:link');
         echo "linked successfully!";
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
});
Route::get('/unlink', function () {
    try {
        $link = public_path('storage');  // Path to the symbolic link
        if (file_exists($link)) {
            unlink($link);  // Remove the symbolic link
            echo "Symbolic link unlinked successfully!";
        } else {
            echo "Symbolic link does not exist.";
        }
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
});

Route::get('/clear/cache', function () {

    try {
        // Clear various caches
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        echo "Caches cleared successfully!";
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
});
// Route::get('/', Index::class)->name('index');
Route::get('/listing', Listing::class)->name('listing');
Route::get('/review/{reviewId}', Review::class)->name('review');

Route::get('/ridebooking', RideBooking::class)->name('ridebooking');
Route::get('/ride-results', RideResults::class)->name('ride.results');

Route::middleware(['auth'])->group(function () {
    Route::get('/mybooking-orders', MyBookingOrders::class)->name('MyBookingOrders');
    Route::get('/assign-driver-vehicle', DriverVehicleAssignment::class)->name('assignDriverVehicle');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/dashboard2', Dashboard::class)->name('dashboard2');
    Route::get('/role', RoleManagement::class)->name('role');
    Route::get('/station', StationManagement::class)->name('station');
    Route::get('/category', CategoryManagement::class)->name('category');
    Route::get('/location', LocationManagement::class)->name('location');
    Route::get('/brand', CarBrandManagement::class)->name('carbrand');
    Route::get('/priceSetup', PriceSetupManagement::class)->name('priceSetup');
    Route::get('/myVehicles', MyVehicles::class)->name('myVehicles');
    Route::get('/editVehicles/{vehID}', EditVehicle::class)->name('myVehicles');
    Route::get('/register-vehicle', RegistrationType::class)->name('registerVehicle');
    Route::get('/bookingOrder/{status}', BookingOrderManagement::class)->name('bookingOrder');
    Route::get('/start-ride', StartRide::class)->name('startRide');
    Route::get('/users', UserManagement::class)->name('userSetup');
    Route::middleware('can:admin-only')->group(function () {

        // Route::get('/vendorManagement/{type}', VendorManagement::class)->name('vendorSetup');
        // Route::get('/vendor/{vehID}', VendorViewDetails::class)->name('viewVendor');
        Route::get('/profile/{userID}', Profile::class)->name('profile');

    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//API V1

Route::middleware('api')->group(function () {
    Route::get('/api/v1/refresh', [UserAPIController::class, 'refresh']);
    Route::post('/api/v1/user/register', [UserAPIController::class, 'register']);
    Route::post('/api/v1/user/confirmOTP', [UserAPIController::class, 'confirmOtp']);
    Route::post('/api/v1/user/login', [UserAPIController::class, 'login']);
    Route::post('/api/v1/resendOTP', [UserAPIController::class, 'resendOTP']);
    Route::post('/api/v1/sendOTP', [UserAPIController::class, 'sendOTP']);
    Route::post('/api/v1/changePassword', [UserAPIController::class, 'changePassword']);
    Route::post('/api/v1/ConfirmJustOTP', [UserAPIController::class, 'confirmJustOTP']);
    Route::group(['middleware' => ['auth.jwt']], function() {
        Route::post('/api/v1/booking', [BookingAPIController::class, 'booking']);
        Route::get('/api/v1/vehicles', [VehicleController::class, 'vehicles']);
        Route::get('/api/v1/brands', [BrandController::class, 'brands']);
        Route::get('/api/v1/singleVehicle/{vehID}', [VehicleController::class, 'singleVehicle']);
        Route::post('/api/v1/transaction/create', [TransactionController::class, 'create']);
        Route::post('/api/v1/logout', [UserAPIController::class, 'logout']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('/user-profile', [UserAPIController::class, 'userProfile']);
    });

});


require __DIR__.'/auth.php';
