<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Session;

class RideBooking extends Component
{
    public $location;
    public $destination;
    public $suggestionsLocation = [];
    public $suggestionsDestination = [];

    public function updated($field)
    {
        if ($field === 'location') {
            $this->suggestionsLocation = $this->getSuggestions($this->$field);
        } elseif ($field === 'destination') {
            $this->suggestionsDestination = $this->getSuggestions($this->$field);
        }
    }

    private function getSuggestions($input)
    {
        try{
        // Call to Google Places API to get suggestions
        // Note: Replace 'YOUR_GOOGLE_API_KEY' with your actual API key.
        $apiKey = env('GOOGLE_API_KEY');
        $response = Http::get("https://maps.googleapis.com/maps/api/place/autocomplete/json", [
            'input' => $input,
            'key' => $apiKey,
        ]);

        return $response->json()['predictions'] ?? [];
    } catch (Exception $e) {
        
        // Session::flash('error', $e->getMessage());
        $response= "";
        Session::flash('error', 'Could not resolve host issues with maps.googleapis.com. kindly check your internet');
        return $response = [];
        }
    }

    public function selectSuggestion($field, $suggestion)
    {
        $this->$field = $suggestion;
        if ($field === 'location') {
            $this->suggestionsLocation = [];
        } elseif ($field === 'destination') {
            $this->suggestionsDestination = [];
        }
    }

    public function redirectToResults()
    {
        return redirect()->route('ride.results', [
            'location' => $this->location,
            'destination' => $this->destination,
        ]);
    }

    public function render()
    {
        return view('livewire.ride-booking')->layout('layouts.guest');
    }
}
