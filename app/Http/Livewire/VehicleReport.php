<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Vehicle;
use App\Models\Station;
use Livewire\Component;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use App\Http\Livewire\Concerns\FiltersVehicles;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VehicleReport extends Component
{
    use WithPagination, FiltersVehicles;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $isAdmin = $this->isAdmin();
        $stationId = auth()->user()->station_id;

        // Fleet overview stats (respect owner/station filters)
        $totalVehicles = $this->fleetQuery()->count();
        $vehiclesOnTrip = $this->fleetQuery()->where('on_trip', 1)->count();
        $vehiclesAvailable = $this->fleetQuery()->where('on_trip', 0)->where('status', 2)->count();
        $vehiclesInactive = $this->fleetQuery()->where('status', '!=', 2)->count();
        $totalOwners = $this->fleetQuery()->distinct()->count('vehicles.user_id');
        $utilizationRate = $totalVehicles > 0 ? round(($vehiclesOnTrip / $totalVehicles) * 100, 1) : 0;

        $vehicles = $this->reportQuery()->paginate((int) $this->limit);

        // Top 5 most booked vehicles
        $topVehiclesQuery = Vehicle::select('vehicles.id', 'vehicles.vehicleMake', 'vehicles.vehicleModel', 'vehicles.vehicleYear')
            ->selectRaw('COUNT(booking_orders.id) as booking_count')
            ->leftJoin('booking_orders', 'vehicles.id', '=', 'booking_orders.vehicle_id')
            ->groupBy('vehicles.id', 'vehicles.vehicleMake', 'vehicles.vehicleModel', 'vehicles.vehicleYear');

        if (!$isAdmin) {
            $topVehiclesQuery->where('vehicles.station_id', $stationId);
        }

        $topVehicles = $topVehiclesQuery->orderBy('booking_count', 'desc')->limit(5)->get();

        // Fleet by vehicle category (via price_setup -> category)
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
            'totalOwners' => $totalOwners,
            'utilizationRate' => $utilizationRate,
            'topVehicles' => $topVehicles,
            'categoryStats' => $categoryStats,
        ] + $this->filterOptions())->layout('components.dashboard.dashboard-master');
    }
}
