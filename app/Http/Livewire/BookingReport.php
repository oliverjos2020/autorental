<?php

namespace App\Http\Livewire;

use App\Models\Vehicle;
use Livewire\Component;
use App\Models\BookingOrder;
use Livewire\WithPagination;

class BookingReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $status;
    public $limit = '10';
    protected $queryString = ['limit'];

    public function updatingstartDate()
    {
        $this->resetPage();
    }
    public function updatingendDate()
    {
        $this->resetPage();
    }
    public function updatingstatus()
    {
        $this->resetPage();
    }

   public function updatingLimit()
   {
       $this->resetPage();
   }

    public function render()
    {

        $stationVehiclesArray = Vehicle::where('station_id', Auth()->user()->station_id)->pluck('id')->toArray();
        // dd($stationVehiclesArray);
        $booking = BookingOrder::whereIn('vehicle_id', $stationVehiclesArray)->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })->when(!is_null($this->status), function ($query) {
                $query->where('payment_status', $this->status);
            })
            ->latest()
            ->paginate($this->limit);

        return view('livewire.booking-report', ['bookings' => $booking])->layout('components.dashboard.dashboard-master');
    }
}
