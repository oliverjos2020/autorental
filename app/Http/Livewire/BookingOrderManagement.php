<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use App\Models\Vehicle;
use Exception;

class BookingOrderManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    public $status;
    public $limit = '10';
    protected $queryString = ['limit', 'search'];

    public function mount($status)
    {
        $this->status = $status;
    }


    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }

    public function approve($id, $vehicleId)
    {
        try{
            BookingOrder::find($id)->update([
                'status' => 2
            ]);
            Vehicle::find($vehicleId)->update([
                'on_trip' => 1
            ]);
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Order Request approved successfully'
            ]);
            $this->resetPage();
        }catch(Exception $e){
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }
    }
    public function end($id, $vehicleId)
    {
        try{
            BookingOrder::find($id)->update([
                'status' => 3
            ]);
            Vehicle::find($vehicleId)->update([
                'on_trip' => 0
            ]);
            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Trip ended successfully'
            ]);
            $this->resetPage();
        }catch(Exception $e){
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }
    }
    
    public function render()
    {
        if ($this->status == 'pending'):
            $status = 1;
        elseif ($this->status == 'ongoing'):
            $status = 2;
        elseif ($this->status == 'completed'):
            $status = 3;
        endif; 
        // dd($status);
        if(Auth()->user()->role_id == 1):
            $orders = BookingOrder::where('user_id', 'like', '%' . $this->search . '%')->where('status', $status)->latest()->paginate($this->limit);
        elseif (Auth()->user()->role_id == 2):
            $partnerVehiclesArray = Vehicle::where('user_id', Auth()->user()->id)->where('status', 2)->pluck('id')->toArray();
            $orders = BookingOrder::whereIn('vehicle_id', $partnerVehiclesArray)->where('status', $status)->latest()->paginate($this->limit);
        endif;

        return view('livewire.booking-order', [
            'orders' => $orders,
        ])->layout('components.dashboard.dashboard-master');
    }
}
