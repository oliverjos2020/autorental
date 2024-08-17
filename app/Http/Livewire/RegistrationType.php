<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\CarBrand;
use App\Models\Location;
use App\Models\Photo;
use App\Models\Vehicle;
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
    public $existingvehImage = [];
    public $category = null;
    public $moreInfo;
    public function mount($type)
    {
        $vehicle = Vehicle::where('id', $this->vehID)->first();
        $this->type = $type;
        $this->phone_no = Auth()->user()->phone_no ?? '';
        $this->address = Auth()->user()->address ?? '';
        $this->vehicleMake = $vehicle->vehicleMake ?? '';
        $this->vehicleModel = $vehicle->vehicleModel ?? '';
        $this->vehicleYear = $vehicle->vehicleYear ?? '';
        $this->seats = $vehicle->seats ?? '';
        $this->transmission = $vehicle->transmission ?? '';
        $this->passengers = $vehicle->passengers ?? '';
        $this->airCondition = $vehicle->airCondition ?? '';
        $this->driverLicense = $vehicle->driverLicense ?? '';
        $this->passport = Auth()->user()->passport ?? '';
        $this->meansOfIdentification = Auth()->user()->meansOfIdentification ?? '';
        $this->identificationDocument = Auth()->user()->identificationDocument ?? '';
        $this->bank = Auth()->user()->bank ?? '';
        $this->accountNumber = Auth()->user()->accountNumber ?? '';
        $this->accountName = Auth()->user()->accountName ?? '';
        $this->accountType = Auth()->user()->accountType ?? '';
        $this->doors = $vehicle->doors ?? '';
        $user = Auth()->user();
        $this->existingDocument = $user->identificationDocument ?? null;
        $this->existingPassport = $user->passport ?? null;
        $this->location = $vehicle->location ?? null;
        $this->existingvehImage = Photo::where('vehicle_id', $this->vehID)->get();

    }
    
    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit()
    {
        $rules = [
            'type' => 'required',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
            'address' => 'required',
            'vehicleMake' => 'required',
            'vehicleModel' => 'required',
            'seats' => 'required',
            'transmission' => 'required',
            'passengers' => 'required',
            'airCondition' => 'required',
            'doors' => 'required',
            'meansOfIdentification' => 'required',
            'bank' => 'required',
            'accountNumber' => 'required',
            'accountName' => 'required',
            'accountType' => 'required',
            'vehImage.*' => 'required|image|max:300',
            'location' => 'required',
            'vehicleYear' => 'required'
        ];
        // try{
            $slugBrand = Str::of(Str::lower($this->vehicleMake))->slug('-');
            $existingBrand = CarBrand::where('slug', $slugBrand)->first();
            if(!$existingBrand):
                CarBrand::create([
                    'brand' => $this->vehicleMake,
                    'slug' => $slugBrand,
                ]);
            endif;
            
            if ($this->type == 'rental'):
            elseif ($this->type == 'booking'):
                $rules['driverLicense'] = 'required';
            endif;
            $data = $this->validate($rules);

            if ($this->type == 'rental' || $this->type == 'booking' || $this->type == 'entertainment'):
                $data['role_id'] = 2;
            endif;
            if ($this->type == 'rental'):
                $this->category = 1;
            elseif ($this->type == 'booking'):
                $this->category = 2;
            elseif ($this->type == 'entertainment'):
                $this->category = 3;
            endif;

            if (empty($this->existingPassport)):
                $rules['passport'] = 'required|image|max:300';
                if ($this->passport):
                    $filename = 'passport-' . Str::random(10) . '.' . $this->passport->extension();
                    $path = $this->passport->storeAs('uploads/passport', $filename, 'public');
                    $url = Storage::url($path);
                    $data['passport'] = $url;
                endif;
            else:
                if ($this->passport && $this->passport instanceof \Livewire\TemporaryUploadedFile) {
                    $filename = 'passport-' . Str::random(10) . '.' . $this->passport->extension();
                    $path = $this->passport->storeAs('uploads/passport', $filename, 'public');
                    $url = Storage::url($path);
                    $data['passport'] = $url;
                }
            endif;

            if (empty($this->existingDocument)):
                $rules['identificationDocument'] = 'required|image|max:300';
                if ($this->identificationDocument):
                    $filenameI = 'identification-' . Str::random(10) . '.' . $this->identificationDocument->extension();
                    $pathI = $this->identificationDocument->storeAs('uploads/identification', $filenameI, 'public');
                    $urlI = Storage::url($pathI);
                    $data['identificationDocument'] = $urlI;
                endif;
            else:
                if ($this->identificationDocument && $this->identificationDocument instanceof \Livewire\TemporaryUploadedFile):
                    $filenameI = 'identification-' . Str::random(10) . '.' . $this->identificationDocument->extension();
                    $pathI = $this->identificationDocument->storeAs('uploads/identification', $filenameI, 'public');
                    $urlI = Storage::url($pathI);
                    $data['identificationDocument'] = $urlI;
                endif;
            endif;

            unset($data['type']);
            unset($data['vehImage']);
            unset($data['vehicleMake']);
            unset($data['vehicleModel']);
            unset($data['vehicleYear']);
            unset($data['seats']);
            unset($data['transmission']);
            unset($data['passengers']);
            unset($data['airCondition']);
            unset($data['doors']);
            unset($data['location']);
            unset($data['driverLicense']);
            User::find(Auth()->User()->id)->update($data);
            if($this->vehID !=='new'):
                $vehicle = Vehicle::find($this->vehID)->update([
                    'user_id' => Auth()->User()->id,
                    'vehicleMake' => $this->vehicleMake,
                    'vehicleModel' => $this->vehicleModel,
                    'seats' => $this->seats,
                    'transmission' => $this->transmission,
                    'passengers' => $this->passengers,
                    'airCondition' => $this->airCondition,
                    'doors' => $this->doors,
                    'location' => $this->location,
                    'driverLicense' => $this->driverLicense,
                    'vehicleYear' => $this->vehicleYear,
                    'status' => 1
                ]);
            else:
                $vehicle = Vehicle::create([
                    'user_id' => Auth()->User()->id,
                    'vehicleMake' => $this->vehicleMake,
                    'vehicleModel' => $this->vehicleModel,
                    'seats' => $this->seats,
                    'transmission' => $this->transmission,
                    'passengers' => $this->passengers,
                    'airCondition' => $this->airCondition,
                    'doors' => $this->doors,
                    'location' => $this->location,
                    'driverLicense' => $this->driverLicense,
                    'vehicleYear' => $this->vehicleYear,
                    'status' => 1,
                    'category_id' => $this->category
                ]);
            endif;
            
            if(is_bool($vehicle)):
                $vehicle = $this->vehID;
            else:
                $vehicle = $vehicle->id;
            endif;

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
            return redirect()->to('/dashboard2');

        // }catch(Exception $e){
        //     $this->dispatchBrowserEvent('notify', [
        //         'type' => 'error',
        //         'message' => $e->getMessage(),
        //     ]);
        //     return;
        // }
       
    }

    protected function validateCurrentStep()
    {
        $rules = [];
        if ($this->step == 1) {
            $rules = [
                'type' => 'required',
                'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:15',
                'address' => 'required'
            ];
        } elseif ($this->step == 2) {
            $rules = [
                'vehicleMake' => 'required',
                'vehicleModel' => 'required',
                'vehicleYear' => 'required',
                'seats' => 'required',
                'transmission' => 'required',
                'passengers' => 'required',
                'airCondition' => 'required',
                'doors' => 'required',
                'location' => 'required'
            ];
        } elseif ($this->step == 3) {
            $rules = [
                'meansOfIdentification' => 'required',
            ];
            if (empty($this->existingPassport)):
                $rules['passport'] = 'required|image|max:300';
            endif;
            if (empty($this->existingDocument)):
                $rules['identificationDocument'] = 'required|image|max:300';
            endif;

        } elseif ($this->step == 4) {
            $rules = [
                'bank' => 'required',
                'accountNumber' => 'required',
                'accountName' => 'required',
                'accountType' => 'required'
            ];
        } elseif($this->step5 == 5){
            $rules = [
                'vehImage.*' => 'required|image|max:300', // 1MB Max for each image
            ];
        }
        $this->validate($rules);
    }

    public function render()
    {
        return view('livewire.registration-type', ['brands' => CarBrand::all(), 'locations' => Location::all()])->layout('components.dashboard.dashboard-master');

    }
}
