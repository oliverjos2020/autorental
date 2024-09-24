<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
use App\Models\User;
use App\Models\MapVehicleDriver;
use Livewire\WithPagination;

class DriverVehicleAssignment extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $limit = '10';
    public $search;
    protected $queryString = ['limit', 'search'];
    public $vehicle = [];
    public $driver;

    public function submit()
    {
        // dd($this->vehicle);
        $this->validate([
            'vehicle' => 'required|array|min:1',
            'driver' => 'required',
        ]);
         foreach($this->vehicle as $i):
            MapVehicleDriver::create(['user_id' => $this->driver, 'vehicle_id' => $i]);
         endforeach;
         $this->reset(['vehicle', 'driver']);
         $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Mapping Created Successfully',
        ]);
    }
    public function delete($id)
    {
        try{
            MapVehicleDriver::findOrfail($id)->delete();
            session()->flash('delete', 'Deleted Successfully');
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Deleted Successfully',
            ]);

        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }

    }
    public function render()
    {
        $mappedVehicleIds = MapVehicleDriver::pluck('vehicle_id');
        $vehicle = Vehicle::whereNotIn('id', $mappedVehicleIds)->get();
        $mappedDriversIds = MapVehicleDriver::pluck('user_id');
        $user = User::where('role_id', 3)->whereNotIn('id', $mappedDriversIds)->get();
        $records = MapVehicleDriver::latest()->paginate($this->limit);
        return view('livewire.driver-vehicle-assignment', ['users' => $user, 'vehicles' => $vehicle, 'records' => $records])->layout('components.dashboard.dashboard-master');
    }
}
