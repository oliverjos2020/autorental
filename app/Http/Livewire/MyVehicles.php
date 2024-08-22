<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

class MyVehicles extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $limit = '10';
    protected $queryString = ['limit', 'search'];

    public function updatingSearch()
   {
       $this->resetPage();
   }

   public function updatingLimit()
   {
       $this->resetPage();
   }
   public function delete($id)
    {
        try{
            Vehicle::findOrfail($id)->delete();
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
        $vehicleManagement = Vehicle::query()->where('vehicleMake', 'like', '%' . $this->search . '%')->where('station_id', Auth()->user()->station_id)->latest()->paginate($this->limit);
        return view('livewire.my-vehicles', [
            'vehicles' => $vehicleManagement,
        ])->layout('components.dashboard.dashboard-master');
    }
}
