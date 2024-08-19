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
    public $category;
    public $moreInfo;
    
 
    public function submit()
    {

        // dd($this->moreInfo);
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
            'category' => 'required'
        ]);

        $vehicle = Vehicle::create([
            'user_id' => Auth()->User()->id,
            'vehicleMake' => $this->vehicleMake,
            'vehicleModel' => $this->vehicleModel,
            'seats' => $this->seats,
            'transmission' => $this->transmission,
            'airCondition' => $this->airCondition,
            'doors' => $this->doors,
            'vehicleYear' => $this->vehicleYear,
            'status' => 1,
            'price_setup_id' => $this->category,
            'moreInfo' => $this->moreInfo
        ]);
        $this->reset(['vehicleMake', 'vehicleModel', 'seats', 'transmission', 'airCondition', 'doors', 'vehicleYear', 'category', 'moreInfo']);
            

            if (!empty($this->vehImage)):
                Photo::where('vehicle_id', $this->vehID)->delete();

                foreach ($this->vehImage as $image):
                    $filename = 'vehImage-' . Str::random(10) . '.' . $image->extension();
                    $path = $image->storeAs('uploads/vehicle', $filename, 'public');
                    $storedImages = Storage::url($path);
                    Photo::create([
                        'vehicle_id' => $vehicle,
                        'image_path' => $storedImages
                    ]);
                endforeach;
            endif;
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Registration completed Successfully',
            ]);
            // return redirect()->to('/dashboard2');

        // }catch(Exception $e){
        //     $this->dispatchBrowserEvent('notify', [
        //         'type' => 'error',
        //         'message' => $e->getMessage(),
        //     ]);
        //     return;
        // }
       
    }

    public function render()
    {
        return view('livewire.registration-type', ['brands' => CarBrand::all(), 'priceCategory' => PriceSetup::all()])->layout('components.dashboard.dashboard-master');

    }
}
