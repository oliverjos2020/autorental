<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Photo;
use Carbon\Carbon;
use App\Models\PriceSetup;
use App\Models\Vehicle;

class VendorViewDetails extends Component
{

    public $vehID;
    public $category;
    public $showTextarea = false;
    public $showApprove = false;
    public $reason = '';

    public function deny()
    {
        $this->showTextarea = true;
    }
    public function showApprove()
    {
        $this->showApprove = true;
    }

    public function submitDeny()
    {
        $this->validate([
            'reason' => ['required']
        ]);
        Vehicle::where('id', $this->vehID)->update([
            'reason' => $this->reason,
            'status' => 3
        ]);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Application Declined Successfully',
        ]);

        return redirect()->to('/vendorManagement/pending');
    }
    public function approve()
    {
        
        Vehicle::where('id', $this->vehID)->update([
            'dateApproved' => Carbon::now(),
            'status' => 2,
            'price_setup_id' => $this->category
        ]);
        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Application Approved Successfully',
        ]);

        return redirect()->to('/vendorManagement/pending');

    }
    public $step = 1;
    public function nextStep()
    {
        // $this->validateCurrentStep();
        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function mount($vehID)
    {
        $this->vehID = $vehID;
    }
    public function render()
    {
        // dd($this->email->user);
        $vehicle = Vehicle::where('id', $this->vehID)->first();
        $user = User::where('id', $vehicle->user_id)->first();
        $photo = Photo::where('vehicle_id', $this->vehID)->get();
        // dd($vehicle);

        return view('livewire.vendor-view-details', ['user' => $user, 'vehicle' => $vehicle, 'photos' => $photo, 'priceCategory' => PriceSetup::all()])->layout('components.dashboard.dashboard-master');

    }
}
