<?php

namespace App\Http\Livewire;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use App\Http\Livewire\Concerns\FiltersVehicles;

class MyVehicles extends Component
{
    use WithPagination, FiltersVehicles;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Newest first by default; the shared default (most booked) suits the report better
        if (!request()->has('sortBy')) {
            $this->sortBy = 'created_at';
        }
    }

    public function delete($id)
    {
        try {
            // Scoped so a station admin can't delete another station's vehicle
            $this->fleetQuery()->findOrFail($id)->delete();
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Deleted Successfully',
            ]);
        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $stats = [
            'total' => $this->fleetQuery()->count(),
            'owners' => $this->fleetQuery()->distinct()->count('vehicles.user_id'),
            'onTrip' => $this->fleetQuery()->where('on_trip', 1)->count(),
            'available' => $this->fleetQuery()->where('on_trip', 0)->where('status', 2)->count(),
            'unpublished' => $this->fleetQuery()->where('publication_status', '!=', 'published')->count(),
        ];

        return view('livewire.my-vehicles', [
            'vehicles' => $this->reportQuery()->paginate((int) $this->limit),
            'stats' => $stats,
        ] + $this->filterOptions())->layout('components.dashboard.dashboard-master');
    }
}
