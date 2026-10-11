<?php

namespace App\Http\Livewire\Concerns;

use App\Models\Category;
use App\Models\Station;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

/**
 * Owner / station / trip / status filtering, sorting and CSV export shared by
 * the vehicle report and the My Vehicles page.
 */
trait FiltersVehicles
{
    public $search = '';
    public $limit = '10';
    public $ownerFilter = '';
    public $stationFilter = '';
    public $categoryFilter = '';
    public $statusFilter = '';
    public $publicationFilter = '';
    public $tripFilter = '';
    public $sortBy = 'total_bookings';
    public $sortDirection = 'desc';

    protected $sortableFields = ['vehicleMake', 'total_bookings', 'total_revenue', 'last_booked_at', 'on_trip', 'created_at'];
    protected $filterFields = ['search', 'limit', 'ownerFilter', 'stationFilter', 'categoryFilter', 'statusFilter', 'publicationFilter', 'tripFilter'];

    /**
     * A method rather than a property: Livewire\Component already declares
     * $queryString, and a trait may not redeclare it differently.
     */
    protected function queryString()
    {
        return [
            'limit',
            'search' => ['except' => ''],
            'ownerFilter' => ['except' => ''],
            'stationFilter' => ['except' => ''],
            'categoryFilter' => ['except' => ''],
            'statusFilter' => ['except' => ''],
            'publicationFilter' => ['except' => ''],
            'tripFilter' => ['except' => ''],
            'sortBy' => ['except' => 'total_bookings'],
            'sortDirection' => ['except' => 'desc'],
        ];
    }

    public function updated($name)
    {
        if (in_array($name, $this->filterFields, true)) {
            $this->resetPage();
        }
    }

    public function sort($field)
    {
        if (!in_array($field, $this->sortableFields, true)) {
            return;
        }

        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'ownerFilter', 'stationFilter', 'categoryFilter', 'statusFilter', 'publicationFilter', 'tripFilter']);
        $this->resetPage();
    }

    protected function isAdmin(): bool
    {
        return auth()->user()->role_id == 1;
    }

    /**
     * Vehicles this user may see, narrowed by owner/station only (used for the summary cards).
     */
    protected function fleetQuery()
    {
        $query = Vehicle::query();

        if (!$this->isAdmin()) {
            $query->where('vehicles.station_id', auth()->user()->station_id);
        }
        if ($this->ownerFilter !== '') {
            $query->where('vehicles.user_id', $this->ownerFilter);
        }
        if ($this->stationFilter !== '' && $this->isAdmin()) {
            $query->where('vehicles.station_id', $this->stationFilter);
        }

        return $query;
    }

    protected function reportQuery()
    {
        $query = $this->fleetQuery()
            ->select('vehicles.*')
            ->selectSub(
                DB::table('booking_orders')->selectRaw('COUNT(*)')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id'),
                'total_bookings'
            )
            ->selectSub(
                DB::table('booking_orders')->selectRaw('COALESCE(SUM(booking_orders.amount), 0)')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id')
                    ->where('booking_orders.payment_status', 1),
                'total_revenue'
            )
            ->selectSub(
                DB::table('booking_orders')->selectRaw('MAX(booking_orders.created_at)')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id'),
                'last_booked_at'
            )
            // The renter and return date of the approved booking currently out
            ->selectSub(
                DB::table('booking_orders')->join('users as renters', 'renters.id', '=', 'booking_orders.user_id')
                    ->select('renters.name')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id')
                    ->whereIn('booking_orders.status', [1, 2])
                    ->orderByDesc('booking_orders.id')->limit(1),
                'current_renter'
            )
            ->selectSub(
                DB::table('booking_orders')->select('booking_orders.dropoffDate')
                    ->whereColumn('booking_orders.vehicle_id', 'vehicles.id')
                    ->whereIn('booking_orders.status', [1, 2])
                    ->orderByDesc('booking_orders.id')->limit(1),
                'current_dropoff'
            )
            ->selectSub(
                DB::table('price_setups')->join('categories', 'categories.id', '=', 'price_setups.category_id')
                    ->select('categories.category')
                    ->whereColumn('price_setups.id', 'vehicles.price_setup_id')->limit(1),
                'category_name'
            )
            ->with(['photos', 'priceSetup', 'station:id,stationName', 'user:id,name,phone_no,business_name']);

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->where('vehicles.vehicleMake', 'like', $term)
                    ->orWhere('vehicles.vehicleModel', 'like', $term)
                    ->orWhere('vehicles.vehicleID', 'like', $term)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('business_name', 'like', $term));
            });
        }
        if ($this->categoryFilter !== '') {
            $query->whereIn('vehicles.price_setup_id', function ($q) {
                $q->select('id')->from('price_setups')->where('category_id', $this->categoryFilter);
            });
        }
        if ($this->statusFilter !== '') {
            $query->where('vehicles.status', $this->statusFilter);
        }
        if ($this->publicationFilter !== '') {
            $query->where('vehicles.publication_status', $this->publicationFilter);
        }
        if ($this->tripFilter !== '') {
            $query->where('vehicles.on_trip', $this->tripFilter);
        }

        $field = in_array($this->sortBy, $this->sortableFields, true) ? $this->sortBy : 'total_bookings';
        $column = in_array($field, ['vehicleMake', 'on_trip', 'created_at'], true) ? 'vehicles.' . $field : $field;

        return $query->orderBy($column, $this->sortDirection === 'asc' ? 'asc' : 'desc')->orderBy('vehicles.id');
    }

    public function exportCsv()
    {
        $query = $this->reportQuery();

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Make', 'Model', 'Year', 'Plate/ID', 'Category', 'Owner', 'Owner Phone', 'Station', 'Status', 'Published', 'On Trip', 'Current Renter', 'Bookings', 'Revenue', 'Last Booked']);
            foreach ($query->cursor() as $v) {
                fputcsv($out, [
                    $v->id, $v->vehicleMake, $v->vehicleModel, $v->vehicleYear, $v->vehicleID, $v->category_name,
                    $v->user->name ?? '', $v->user->phone_no ?? '', $v->station->stationName ?? '',
                    [0 => 'Pending', 1 => 'Active', 2 => 'Approved', 3 => 'Declined'][(int) $v->status] ?? $v->status,
                    $v->publication_status, $v->on_trip == 1 ? 'Yes' : 'No', $v->current_renter,
                    $v->total_bookings, $v->total_revenue, $v->last_booked_at,
                ]);
            }
            fclose($out);
        }, 'vehicles-' . now()->format('Ymd-His') . '.csv');
    }

    /**
     * Options for the owner / station / category filter dropdowns, limited to what this user can see.
     */
    protected function filterOptions(): array
    {
        $ownerIds = Vehicle::query()
            ->when(!$this->isAdmin(), fn ($q) => $q->where('station_id', auth()->user()->station_id))
            ->distinct()->pluck('user_id');

        return [
            'owners' => User::whereIn('id', $ownerIds)->orderBy('name')->get(['id', 'name', 'business_name']),
            'stations' => Station::orderBy('stationName')->get(['id', 'stationName']),
            'categories' => Category::orderBy('category')->get(),
            'isAdmin' => $this->isAdmin(),
            'filtersActive' => $this->search !== '' || $this->ownerFilter !== '' || $this->stationFilter !== ''
                || $this->categoryFilter !== '' || $this->statusFilter !== '' || $this->publicationFilter !== '' || $this->tripFilter !== '',
        ];
    }
}
