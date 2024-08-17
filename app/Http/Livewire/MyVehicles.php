<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Vehicle;
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
   
    public function render()
    {
        $vehicleManagement = Vehicle::query()->where('vehicleMake', 'like', '%' . $this->search . '%')->where('user_id', Auth()->user()->id)->latest()->paginate($this->limit);
        return view('livewire.my-vehicles', [
            'vehicles' => $vehicleManagement,
        ])->layout('components.dashboard.dashboard-master');
    }
}
