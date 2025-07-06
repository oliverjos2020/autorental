<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Validation\ValidationException;
use Exception;

class VehicleController extends Controller
{
    public function vehicles(Request $request)
    {
        try {
            // Default limit
            $limit = $request->input('limit', 50);
            $query = Vehicle::with([
                'photos',
                'user:id,bank_code,account_number,percentage_charge,account_code',
                'priceSetup.related',
                'priceSetup.duration:slug,duration', // 👈 include duration here
                'station:id,stationName,location_id',
                'station.location:id,location,longitude,latitude'
            ]);


            // Apply filters
            if ($request->has('vehicleMake')) {
                $query->where('vehicleMake', $request->input('vehicleMake'));
            }
            if ($request->has('vehicleModel')) {
                $query->where('vehicleModel', $request->input('vehicleModel'));
            }
            if ($request->has('vehicleYear')) {
                $query->where('vehicleYear', $request->input('vehicleYear'));
            }
            if ($request->has('station_id')) {
                $query->where('station_id', $request->input('station_id'));
            }

            $query->where('on_trip', 0)->inRandomOrder();

            // Get the results with the limit
            $data = $query->limit($limit)->get();

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'success',
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'responseCode' => 422,
            ], 422);
        }
    }
    public function station(Request $request)
    {
        try {
            // Default limit
            $limit = $request->input('limit', 20);

            $query = Vehicle::with([
                'station:id,slug,location_id:id',
                'station.location:id,longitude,latitude',
                'photos',
                'user:id,bank_code,account_number,percentage_charge,account_code',
                'priceSetup.related'
            ]);


            // Apply filters
            if ($request->has('vehicleMake')) {
                $query->where('vehicleMake', $request->input('vehicleMake'));
            }
            if ($request->has('vehicleModel')) {
                $query->where('vehicleModel', $request->input('vehicleModel'));
            }
            if ($request->has('vehicleYear')) {
                $query->where('vehicleYear', $request->input('vehicleYear'));
            }
            if ($request->has('station_id')) {
                $query->where('station_id', $request->input('station_id'));
            }

            $query->where('on_trip', 0)->orderBy('created_at', 'desc');

            // Get the results with the limit
            $data = $query->limit($limit)->get();

            return response()->json([
                'responseCode' => 200,
                'responseMessage' => 'success',
                'data' => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'responseCode' => 422,
            ], 422);
        }
    }

    public function singleVehicle($vehID)
    {
        // dd($request);
        try {
            if (empty($vehID)) {
                return response()->json([
                    'errors' => 'Vehicle ID is required.',
                    'responseCode' => 400,
                ], 400);
            }
            $data = Vehicle::with('photos')->find($vehID);
            if (!$data) {
                return response()->json(['responseCode' =>404, 'responseMessage' => 'Vehicle not found'], 404);
            }

            return response()->json(['data' => $data], 200);
        } catch (Exception $e) {
            return response()->json([
                'errors' => $e->getMessage(),
                'responseCode' => 422, // Adding the response code
            ], 422);
        }
    }


}
