<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class RevenueReport extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $status;
    public $limit = '10';
    public $period = 'all'; // all, today, week, month, year
    protected $queryString = ['limit', 'period'];

    public function mount()
    {
        $this->startDate = null;
        $this->endDate = null;
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

    public function updatingPeriod()
    {
        $this->resetPage();
        $this->startDate = null;
        $this->endDate = null;
    }

    private function getBaseQuery()
    {
        $query = Transaction::query();

        // Scope by station for non-admin
        if (auth()->user()->role_id != 1) {
            $stationVehicleIds = Vehicle::where('station_id', auth()->user()->station_id)->pluck('id')->toArray();
            $bookingIds = BookingOrder::whereIn('vehicle_id', $stationVehicleIds)->pluck('id')->toArray();
            $query->whereIn('booking_order_id', $bookingIds);
        }

        // Date filters
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);
        } elseif ($this->period !== 'all') {
            switch ($this->period) {
                case 'today':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }

        // Status filter
        if (!is_null($this->status) && $this->status !== '') {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function render()
    {
        $baseQuery = $this->getBaseQuery();

        // Summary stats
        $totalRevenue = (clone $baseQuery)->where('status', '1')->sum('amount');
        $totalTransactions = (clone $baseQuery)->count();
        $successfulTransactions = (clone $baseQuery)->where('status', '1')->count();
        $failedTransactions = (clone $baseQuery)->where('status', '!=', '1')->count();
        $averageOrderValue = $successfulTransactions > 0 ? $totalRevenue / $successfulTransactions : 0;

        // Monthly revenue chart data (current year)
        $monthlyRevenue = Transaction::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(CAST(amount AS DECIMAL(10,2))) as total')
            )
            ->where('status', '1')
            ->whereYear('created_at', Carbon::now()->year);

        if (auth()->user()->role_id != 1) {
            $stationVehicleIds = Vehicle::where('station_id', auth()->user()->station_id)->pluck('id')->toArray();
            $bookingIds = BookingOrder::whereIn('vehicle_id', $stationVehicleIds)->pluck('id')->toArray();
            $monthlyRevenue->whereIn('booking_order_id', $bookingIds);
        }

        $monthlyRevenue = $monthlyRevenue->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')
            ->toArray();

        $revenueChart = array_fill(1, 12, 0);
        foreach ($monthlyRevenue as $month => $total) {
            $revenueChart[$month] = (float) $total;
        }

        // Paginated transactions
        $transactions = (clone $baseQuery)->with(['user:id,name,email'])->latest()->paginate($this->limit);

        return view('livewire.revenue-report', [
            'transactions' => $transactions,
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'successfulTransactions' => $successfulTransactions,
            'failedTransactions' => $failedTransactions,
            'averageOrderValue' => $averageOrderValue,
            'revenueChart' => $revenueChart,
        ])->layout('components.dashboard.dashboard-master');
    }
}
