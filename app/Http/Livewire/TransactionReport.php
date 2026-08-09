<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class TransactionReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $startDate;
    public $endDate;
    public $status;
    public $limit = '10';
    protected $queryString = ['limit', 'search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingLimit()
    {
        $this->resetPage();
    }

    public function render()
    {
        $isAdmin = auth()->user()->role_id == 1;
        $stationId = auth()->user()->station_id;

        $query = Transaction::with([
            'user:id,name,email,phone_no',
        ]);

        // Station scope
        if (!$isAdmin) {
            $stationVehicleIds = Vehicle::where('station_id', $stationId)->pluck('id')->toArray();
            $bookingIds = BookingOrder::whereIn('vehicle_id', $stationVehicleIds)->pluck('id')->toArray();
            $query->whereIn('booking_order_id', $bookingIds);
        }

        // Search by transaction ID or user
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('transaction_id', 'like', '%' . $this->search . '%')
                  ->orWhere('transaction_desc', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                         ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Date range
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);
        }

        // Status filter
        if (!is_null($this->status) && $this->status !== '') {
            $query->where('status', $this->status);
        }

        // Summary stats
        $totalCount = (clone $query)->count();
        $totalAmount = (clone $query)->sum('amount');
        $successCount = (clone $query)->where('status', '1')->count();
        $successAmount = (clone $query)->where('status', '1')->sum('amount');
        $failedCount = (clone $query)->where('status', '!=', '1')->count();

        $transactions = $query->latest()->paginate($this->limit);

        return view('livewire.transaction-report', [
            'transactions' => $transactions,
            'totalCount' => $totalCount,
            'totalAmount' => $totalAmount,
            'successCount' => $successCount,
            'successAmount' => $successAmount,
            'failedCount' => $failedCount,
        ])->layout('components.dashboard.dashboard-master');
    }
}
