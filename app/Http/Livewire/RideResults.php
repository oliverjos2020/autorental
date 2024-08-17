<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Exception;

class RideResults extends Component
{
    public $location;
    public $destination;
    public $distance;
    public $distanceText;

    public function mount()
    {
        $this->location = request('location');
        $this->destination = request('destination');
        $this->calculateDistance();
    }

    private function calculateDistance()
    {
        // Call to Google Distance Matrix API to get the distance
        // Note: Replace 'YOUR_GOOGLE_API_KEY' with your actual API key.
        try{
        $apiKey = env('GOOGLE_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/distancematrix/json", [
            'origins' => $this->location,
            'destinations' => $this->destination,
            'key' => $apiKey,
        ]);

        $distanceText = $response->json()['rows'][0]['elements'][0]['distance']['text'];
        $this->distanceText = $distanceText;
        $this->distance = $this->extractNumericDistance($distanceText);
    } catch (Exception $e) {
        
        // Session::flash('error', $e->getMessage());
        $response= "";
        Session::flash('error', 'Could not resolve host issues with maps.googleapis.com. kindly check your internet');
        // return $response = [];
        
    }
    }

    private function extractNumericDistance($distanceText)
    {
        // Extract the numeric part of the distance
        if (preg_match('/[\d.]+/', $distanceText, $matches)) {
            return (float) $matches[0];
        }

        return 0; // Default to 0 if no valid distance found
    }

    public function render()
    {
        $ratePerMile = 1.5; // Example rate per mile
        $charge = $this->distance * $ratePerMile;

        return view('livewire.ride-results', [
            'location' => $this->location,
            'destination' => $this->destination,
            'distance' => $this->distance,
            'charge' => $charge,
        ])->layout('layouts.guest');
    }
}
