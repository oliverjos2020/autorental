<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function getAllStations(Request $request)
    {
        try{
            $stations = Station::with('location:id,longitude,latitude')->get();

            // Return or process data as needed (e.g., for an API or view)
            // return response()->json($stations);
            return response()->json([
                'responseMessage' => 'Success',
                'responseCode' => 200,
                'data' => $stations
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'responseMessage' => $e->getMessage(),
                'responseCode' => 500
            ], 500);
        }
    }
}
