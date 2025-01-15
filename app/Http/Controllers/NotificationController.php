<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\BookingOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function checkMessage()
    {
        // Logic to check for a new message
        // $hasMessage = true; // Replace with actual logic

        // if ($hasMessage) {
        //     return response()->json(['message' => 'You have a new message!'], 200);
        // }

        // return response()->json(['message' => 'No new messages.'], 204);
        // DB::enableQueryLog();
        $stationVehiclesArray = Vehicle::where('station_id', Auth()->user()->station_id)->pluck('id')->toArray();
        $booking = BookingOrder::whereIn('vehicle_id', $stationVehiclesArray)->where('payment_status', 1)->where('status', 0);
        // print_r(DB::getQueryLog());
        if($booking->count() > 0)
        {
            return response()->json(['responseCode' => 200, 'message' => 'You have '.$booking->count().' new ride request message!'], 200);
        }else{
            return response()->json(['responseCode' => 404, 'message' => 'No new request'], 404);
        }

        // return $booking->count();
    }
}
