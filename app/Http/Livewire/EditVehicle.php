<?php

namespace App\Http\Livewire;

use App\Models\Photo;
use App\Models\Vehicle;
use Livewire\WithFileUploads;
use Livewire\Component;
use App\Models\CarBrand;
use App\Models\PriceSetup;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EditVehicle extends Component
{
    use WithFileUploads;
    public $vehID;
    public $addtionalMake = false;
    public function addMake()
    {
        $this->addtionalMake = true;
    }

    public $vehicleMake;
    public $vehicleModel;
    public $seats;
    public $transmission;
    public $airCondition;
    public $doors;
    public $vehicleYear;
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
    public function mount()
    {
        $vehicle = Vehicle::where('id', $this->vehID)->first();
        $this->vehicleMake = $vehicle->vehicleMake ?? '';
        $this->vehicleModel = $vehicle->vehicleModel ?? '';
        $this->vehicleYear = $vehicle->vehicleYear ?? '';
        $this->seats = $vehicle->seats ?? '';
        $this->transmission = $vehicle->transmission ?? '';
        $this->airCondition = $vehicle->airCondition == 'no'? '':'yes';
        $this->doors = $vehicle->doors ?? '';
        $this->moreInfo = $vehicle->moreInfo ?? '';
        $this->category = $vehicle->price_setup_id ?? '';
        $this->fuelCapacity = $vehicle->fuelCapacity ?? '';
        $this->maxSpeed = $vehicle->maxSpeed ?? '';
        $this->maxPower = $vehicle->maxPower ?? '';
        $this->motor = $vehicle->motor ?? '';
        $this->keylessEntry = $vehicle->keylessEntry == 'no'? '':'yes';
        $this->musicPlayer = $vehicle->musicPlayer == 'no'? '':'yes';
        $this->airBags = $vehicle->airBags == 'no'? '':'yes';
        $this->existingvehImage = Photo::where('vehicle_id', $this->vehID)->get();
    }

    public function submit()
    {

        $this->validate([
            'vehicleMake' => 'required',
            'vehicleModel' => 'required',
            'seats' => 'required',
            'transmission' => 'required',
            'airCondition' => 'required',
            'doors' => 'required',
            // 'vehImage' => 'required|array|min:1',
            // 'vehImage.*' => 'required|image|max:300',
            'vehicleYear' => 'required',
            'category' => 'required',
            'motor' => 'required',
            'maxPower' => 'required',
            'maxSpeed' => 'required',
            'fuelCapacity' => 'required'
        ]);
        // First, retrieve the vehicle instance and then update it
        $vehicle = Vehicle::find($this->vehID);

        // Update the vehicle's attributes
        $vehicle->update([
            'vehicleMake' => $this->vehicleMake,
            'vehicleModel' => $this->vehicleModel,
            'seats' => $this->seats,
            'transmission' => $this->transmission,
            'airCondition' => ($this->airCondition)?$this->airCondition: 'no',
            'doors' => $this->doors,
            'vehicleYear' => $this->vehicleYear,
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

        // Check if there are new images
        if (!empty($this->vehImage)) {
            // Delete old photos for this vehicle
            Photo::where('vehicle_id', $this->vehID)->delete();

            // Loop through each new image and store it
            foreach ($this->vehImage as $image) {
                $filename = 'vehImage-' . Str::random(10) . '.' . $image->extension();
                $path = $image->storeAs('uploads/vehicle', $filename, 'public');
                $storedImages = Storage::url($path);

                // Create new Photo record with the vehicle's ID
                Photo::create([
                    'vehicle_id' => $vehicle->id, // Use the vehicle's actual ID
                    'image_path' => $storedImages
                ]);
            }
        }

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Vehicle Updated Successfully',
        ]);
    }
    public function render()
    {
        return view('livewire.registration-type', [
            'brands' => CarBrand::all(),
            'priceCategory' => PriceSetup::all()
        ])->layout('components.dashboard.dashboard-master');
    }
}
