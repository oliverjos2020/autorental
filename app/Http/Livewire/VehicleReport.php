<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Station;
use Livewire\Component;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class VehicleReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $limit = '10';
    public $statusFilter = '';
    public $tripFilter = '';
    public $sortBy = 'total_bookings';
    public $sortDirection = 'desc';
    protected $queryString = ['limit', 'search', 'statusFilter', 'tripFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTripFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $isAdmin = auth()->user()->role_id == 1;
        $stationId = auth()->user()->station_id;

        // Fleet overview stats
        $vehicleQuery = Vehicle::query();
        if (!$isAdmin) {
            $vehicleQuery->where('station_id', $stationId);
        }
        $totalVehicles = (clone $vehicleQuery)->count();
        $vehiclesOnTrip = (clone $vehicleQuery)->where('on_trip', 1)->count();
        $vehiclesAvailable = (clone $vehicleQuery)->where('on_trip', 0)->where('status', 2)->count();
        $vehiclesInactive = (clone $vehicleQuery)->where('status', '!=', 2)->count();
        $utilizationRate = $totalVehicles > 0 ? round(($vehiclesOnTrip / $totalVehicles) * 100, 1) : 0;

        // Vehicle list with booking count
        $vehiclesQuery = Vehicle::select('vehicles.*')
            ->selectSub(function ($query) {
                $query->selectRaw('COUNT(*)')
                    ->from('booking_orders')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id');
            }, 'total_bookings')
            ->selectSub(function ($query) {
                $query->selectRaw('COALESCE(SUM(booking_orders.amount), 0)')
                    ->from('booking_orders')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id')
                    ->where('booking_orders.payment_status', 1);
            }, 'total_revenue')
            ->with(['photos', 'station:id,stationName', 'user:id,name']);

        if (!$isAdmin) {
            $vehiclesQuery->where('vehicles.station_id', $stationId);
        }

        if ($this->search) {
            $vehiclesQuery->where(function ($q) {
                $q->where('vehicleMake', 'like', '%' . $this->search . '%')
                  ->orWhere('vehicleModel', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== '') {
            $vehiclesQuery->where('status', $this->statusFilter);
        }

        if ($this->tripFilter !== '') {
            $vehiclesQuery->where('on_trip', $this->tripFilter);
        }

        $vehiclesQuery->orderBy($this->sortBy, $this->sortDirection);

        $vehicles = $vehiclesQuery->paginate($this->limit);

        // Top 5 most booked vehicles
        $topVehiclesQuery = Vehicle::select('vehicles.id', 'vehicles.vehicleMake', 'vehicles.vehicleModel', 'vehicles.vehicleYear')
            ->selectRaw('COUNT(booking_orders.id) as booking_count')
            ->leftJoin('booking_orders', 'vehicles.id', '=', 'booking_orders.vehicle_id')
            ->groupBy('vehicles.id', 'vehicles.vehicleMake', 'vehicles.vehicleModel', 'vehicles.vehicleYear');

        if (!$isAdmin) {
            $topVehiclesQuery->where('vehicles.station_id', $stationId);
        }

        $topVehicles = $topVehiclesQuery->orderBy('booking_count', 'desc')->limit(5)->get();

        // Bookings by vehicle category (via price_setup -> category)
        $categoryStats = DB::table('vehicles')
            ->join('price_setups', 'vehicles.price_setup_id', '=', 'price_setups.id')
            ->join('categories', 'price_setups.category_id', '=', 'categories.id')
            ->select('categories.category', DB::raw('COUNT(vehicles.id) as vehicle_count'))
            ->when(!$isAdmin, function ($q) use ($stationId) {
                $q->where('vehicles.station_id', $stationId);
            })
            ->groupBy('categories.category')
            ->get();

        return view('livewire.vehicle-report', [
            'vehicles' => $vehicles,
            'totalVehicles' => $totalVehicles,
            'vehiclesOnTrip' => $vehiclesOnTrip,
            'vehiclesAvailable' => $vehiclesAvailable,
            'vehiclesInactive' => $vehiclesInactive,
            'utilizationRate' => $utilizationRate,
            'topVehicles' => $topVehicles,
            'categoryStats' => $categoryStats,
        ])->layout('components.dashboard.dashboard-master');
    }
}
