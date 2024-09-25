<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\CarBrand;
use App\Models\Location;
use App\Models\Photo;
use App\Models\Vehicle;
use App\Models\PriceSetup;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class RegistrationType extends Component
{
    use WithFileUploads;

    public $type;
    public $vehID;
    public $addtionalMake = false;
    public function addMake()
    {
        $this->addtionalMake = true;
    }

    public $step = 1;
    public $vehicleMake;
    public $vehicleModel;
    public $vehicleYear;
    public $seats;
    public $doors;
    public $transmission;
    public $airCondition;
    public $vehImage = [];
    public $existingvehImage = [];
    public $category;
    public $moreInfo;
    public $fuelCapacity;
    public $maxSpeed;
    public $maxPower;
    public $motor;
    public $keylessEntry;
    public $musicPlayer;
    public $airBags;

    public function submit()
    {

        // dd(($this->airCondition)?$this->airCondition: 'no');
        $this->validate([
            'vehicleMake' => 'required',
            'vehicleModel' => 'required',
            'seats' => 'required',
            'transmission' => 'required',
            'airCondition' => 'required',
            'doors' => 'required',
            'vehImage' => 'required|array|min:1',
            'vehImage.*' => 'required|image|max:300',
            'vehicleYear' => 'required',
            'category' => 'required',
            'motor' => 'required',
            'maxPower' => 'required',
            'maxSpeed' => 'required',
            'fuelCapacity' => 'required',
        ]);

        $vehicle = Vehicle::create([
            'user_id' => Auth()->User()->id,
            'station_id' => Auth()->User()->station_id,
            'vehicleMake' => $this->vehicleMake,
            'vehicleModel' => $this->vehicleModel,
            'seats' => $this->seats,
            'transmission' => $this->transmission,
            'airCondition' => ($this->airCondition)?$this->airCondition: 'no',
            'doors' => $this->doors,
            'vehicleYear' => $this->vehicleYear,
            'status' => 1,
            'price_setup_id' => $this->category,
            'moreInfo' => $this->moreInfo,
            'keylessEntry' => ($this->keylessEntry)?$this->keylessEntry: 'no',
            'musicPlayer' => ($this->musicPlayer)?$this->musicPlayer: 'no',
            'airBags' => ($this->airBags)?$this->airBags: 'no',
            'fuelCapacity' => $this->fuelCapacity,
            'maxSpeed' => $this->maxSpeed,
            'maxPower' => $this->maxPower,
            'motor' => $this->motor
        ]);


        $destinationPath = public_path('uploads/vehicle');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        foreach ($this->vehImage as $image):
            $filename = 'vehImage-' . Str::random(10) . '.' . $image->extension();
            $path = $image->storeAs('uploads/vehicle', $filename, 'public');
            $storedImages = Storage::url($path);
            Photo::create([
                'vehicle_id' => $vehicle->id,
                'image_path' => $storedImages
            ]);
        endforeach;
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Registration completed Successfully',
            ]);
            $this->reset(['vehicleMake', 'vehicleModel', 'seats', 'transmission', 'airCondition', 'doors', 'vehicleYear', 'category', 'moreInfo']);


    }

    public function render()
    {
        return view('livewire.registration-type', ['brands' => CarBrand::all(), 'priceCategory' => PriceSetup::all()])->layout('components.dashboard.dashboard-master');

    }
}
