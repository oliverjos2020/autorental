<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Exception;
use App\Models\MapVehicleDriver;

class DriverVehicleManagement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $limit = '10';
    protected $queryString = ['limit', 'search'];

    public function updatingLimit()
    {
        $this->resetPage();
    }
    public function render()
    {
        $records = MapVehicleDriver::all();
        return view('livewire.driver-vehicle-management', ['records' => $records])->layout('components.dashboard.dashboard-master');
    }
}
