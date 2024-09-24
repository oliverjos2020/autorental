<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarBrand;

class BrandController extends Controller
{
    public function brands()
    {
        try {

            $data = CarBrand::all();
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
}
