<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\PriceSetup;
use App\Models\Location;
use App\Models\CarBrand;



class Index extends Component
{

    public $category;
    public $brand;
    public $transmission;
    // public $dropoffLocation;
    // public $returnDate;
     public function submitRequest()
    {
        $this->validate([
            'category' => 'required|integer',
            'brand' => 'required|string',
            'transmission' => 'required'
        ]);

        return redirect()->route('listing', [
            'category' => $this->category,
            'make' => $this->brand,
            'transmission' => $this->transmission
        ]);
    }
    public function render()
    {
        // return view('livewire.index');
        return view('livewire.home.index2', ['categories' => PriceSetup::all(), 'locations' => Location::all(), 'brands' => CarBrand::all()])->layout('components.home.home-master-2');

    }
}
