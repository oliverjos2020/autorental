<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Photo;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\CarBrand;
use App\Models\Category;
use App\Models\Location;
use App\Models\PriceSetup;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
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
    public $owner;

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
            'owner' => 'required'
        ]);

        $vehicle = Vehicle::create([
            'user_id' => $this->owner,
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
            // 'owner' => $this->owner
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
        $carOwners = User::where('id', 6)->get();
        return view('livewire.registration-type', ['brands' => CarBrand::all(), 'categories' => Category::all(), 'carOwners' => $carOwners])->layout('components.dashboard.dashboard-master');

    }
}
