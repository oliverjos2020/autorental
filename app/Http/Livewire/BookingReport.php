<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use App\Models\Station;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\BookingOrder;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseNotificationService;

class BookingReport extends Component
{
    use WithPagination;

    // Booking status codes (same meaning as on the dashboard)
    const PENDING = 0;
    const CONFIRMED = 1;
    const ONGOING = 2;
    const COMPLETED = 3;
    const DECLINED = 4;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $limit = '10';
    public $statusFilter = '';
    public $paymentFilter = '';
    public $typeFilter = '';
    public $stationFilter = '';
    public $ownerFilter = '';
    public $startDate = '';
    public $endDate = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Decline dialog
    public $decliningId = null;
    public $declineReason = '';

    protected $sortable = ['created_at', 'pickupDate', 'dropoffDate', 'amount'];
    protected $filterFields = ['search', 'limit', 'statusFilter', 'paymentFilter', 'typeFilter', 'stationFilter', 'ownerFilter', 'startDate', 'endDate'];

    protected function queryString()
    {
        return [
            'limit',
            'search' => ['except' => ''],
            'statusFilter' => ['except' => ''],
            'paymentFilter' => ['except' => ''],
            'typeFilter' => ['except' => ''],
            'stationFilter' => ['except' => ''],
            'ownerFilter' => ['except' => ''],
            'startDate' => ['except' => ''],
            'endDate' => ['except' => ''],
            'sortBy' => ['except' => 'created_at'],
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
        if (!in_array($field, $this->sortable, true)) {
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
        $this->reset(['search', 'statusFilter', 'paymentFilter', 'typeFilter', 'stationFilter', 'ownerFilter', 'startDate', 'endDate']);
        $this->resetPage();
    }

    private function isAdmin(): bool
    {
        return auth()->user()->role_id == 1;
    }

    /**
     * Bookings this user may see: everything for admins, otherwise their station's vehicles.
     */
    private function scopedQuery()
    {
        $query = BookingOrder::query();

        if (!$this->isAdmin()) {
            $query->whereIn('vehicle_id', Vehicle::where('station_id', auth()->user()->station_id)->select('id'));
        }

        return $query;
    }

    private function filteredQuery()
    {
        $query = $this->scopedQuery()->with(['user', 'vehicle.user', 'vehicle.station']);

        if (trim($this->search) !== '') {
            $term = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($term) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone_no', 'like', $term))
                    ->orWhereHas('vehicle', fn ($v) => $v->where('vehicleMake', 'like', $term)->orWhere('vehicleModel', 'like', $term)->orWhere('vehicleID', 'like', $term))
                    ->orWhereHas('vehicle.user', fn ($o) => $o->where('name', 'like', $term))
                    ->orWhere('booking_orders.id', ltrim(trim($this->search), '#'));
            });
        }
        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }
        if ($this->paymentFilter !== '') {
            $query->where('payment_status', $this->paymentFilter);
        }
        if ($this->typeFilter !== '') {
            $query->where('type', $this->typeFilter);
        }
        if ($this->stationFilter !== '' && $this->isAdmin()) {
            $query->whereIn('vehicle_id', Vehicle::where('station_id', $this->stationFilter)->select('id'));
        }
        if ($this->ownerFilter !== '') {
            $query->whereIn('vehicle_id', Vehicle::where('user_id', $this->ownerFilter)->select('id'));
        }
        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $field = in_array($this->sortBy, $this->sortable, true) ? $this->sortBy : 'created_at';

        return $query->orderBy($field, $this->sortDirection === 'asc' ? 'asc' : 'desc')->orderByDesc('id');
    }

    /* ---------------------------------------------------------------- actions */

    private function notifyRenter(BookingOrder $booking, string $title, string $body, string $type): void
    {
        try {
            $renter = User::find($booking->user_id);
            if ($renter && $renter->fcm_token) {
                FirebaseNotificationService::push($renter->fcm_token, $title, $body, [
                    'booking_order_id' => (string) $booking->id,
                    'type' => $type,
                ]);
            }
        } catch (Exception $e) {
            Log::warning('Booking notification failed', ['booking' => $booking->id, 'error' => $e->getMessage()]);
        }
    }

    private function notify(string $type, string $message): void
    {
        $this->dispatchBrowserEvent('notify', ['type' => $type, 'message' => $message]);
    }

    public function approve($id)
    {
        try {
            $booking = $this->scopedQuery()->findOrFail($id);
            $message = null;

            DB::transaction(function () use ($booking, &$message) {
                $booking = BookingOrder::whereKey($booking->id)->lockForUpdate()->first();
                $vehicle = Vehicle::whereKey($booking->vehicle_id)->lockForUpdate()->first();

                if ((int) $booking->status !== self::PENDING) {
                    $message = 'Only pending bookings can be approved.';
                } elseif ((int) $booking->payment_status !== 1) {
                    $message = 'This booking has not been paid for yet.';
                } elseif (!$vehicle) {
                    $message = 'The vehicle for this booking no longer exists.';
                } elseif ((int) $vehicle->on_trip === 1) {
                    $message = 'This vehicle is already on a trip. End that booking first.';
                } else {
                    $booking->update(['status' => self::ONGOING]);
                    $vehicle->update(['on_trip' => 1]);
                }
            });

            if ($message) {
                return $this->notify('error', $message);
            }

            $this->notifyRenter($booking, 'Booking Approved', 'Your booking has been approved. Enjoy your ride!', 'booking_approved');
            $this->notify('success', 'Booking approved. Vehicle is now on trip.');
        } catch (Exception $e) {
            $this->notify('error', $e->getMessage());
        }
    }

    public function confirmDecline($id)
    {
        $booking = $this->scopedQuery()->findOrFail($id);
        $this->decliningId = $booking->id;
        $this->declineReason = '';
        $this->resetValidation();
    }

    public function cancelDecline()
    {
        $this->reset(['decliningId', 'declineReason']);
        $this->resetValidation();
    }

    public function decline()
    {
        $this->validate(['declineReason' => ['required', 'string', 'min:3', 'max:500']], [
            'declineReason.required' => 'Please give a reason for declining.',
        ]);

        try {
            $booking = $this->scopedQuery()->findOrFail($this->decliningId);

            if ((int) $booking->status !== self::PENDING) {
                $this->cancelDecline();
                return $this->notify('error', 'Only pending bookings can be declined.');
            }

            $booking->update(['status' => self::DECLINED, 'reason' => $this->declineReason]);

            $this->notifyRenter(
                $booking,
                'Booking Declined',
                'Your booking was declined: ' . $this->declineReason . ($booking->payment_status == 1 ? ' Please contact support about your refund.' : ''),
                'booking_declined'
            );

            $paid = $booking->payment_status == 1;
            $this->cancelDecline();
            $this->notify('success', $paid
                ? 'Booking declined. It was already paid, so remember to refund it on Paystack.'
                : 'Booking declined.');
        } catch (Exception $e) {
            $this->notify('error', $e->getMessage());
        }
    }

    public function end($id)
    {
        try {
            $booking = $this->scopedQuery()->findOrFail($id);
            $message = null;

            DB::transaction(function () use ($booking, &$message) {
                $booking = BookingOrder::whereKey($booking->id)->lockForUpdate()->first();

                if (!in_array((int) $booking->status, [self::CONFIRMED, self::ONGOING], true)) {
                    $message = 'Only approved bookings can be ended.';
                    return;
                }

                $booking->update(['status' => self::COMPLETED]);

                // Free the vehicle unless another approved booking is still out on it
                $stillOut = BookingOrder::where('vehicle_id', $booking->vehicle_id)
                    ->where('id', '!=', $booking->id)
                    ->whereIn('status', [self::CONFIRMED, self::ONGOING])
                    ->exists();

                if (!$stillOut) {
                    Vehicle::whereKey($booking->vehicle_id)->update(['on_trip' => 0]);
                }
            });

            if ($message) {
                return $this->notify('error', $message);
            }

            $this->notifyRenter($booking, 'Trip Completed', 'Your trip has ended. Thank you for riding with us!', 'trip_completed');
            $this->notify('success', 'Booking ended. Vehicle is available again.');
        } catch (Exception $e) {
            $this->notify('error', $e->getMessage());
        }
    }

    /* ----------------------------------------------------------------- export */

    public function exportCsv()
    {
        $query = $this->filteredQuery();
        $labels = [0 => 'Pending', 1 => 'Confirmed', 2 => 'Ongoing', 3 => 'Completed', 4 => 'Declined'];

        return response()->streamDownload(function () use ($query, $labels) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Booking #', 'Customer', 'Email', 'Phone', 'Vehicle', 'Plate/ID', 'Owner', 'Station', 'Type', 'Pick Up', 'Drop Off', 'Pick Up Location', 'Drop Off Location', 'Amount', 'Payment', 'Status', 'Reason', 'Created']);
            foreach ($query->cursor() as $b) {
                fputcsv($out, [
                    $b->id, $b->user->name ?? '', $b->user->email ?? '', $b->user->phone_no ?? '',
                    trim(($b->vehicle->vehicleMake ?? '') . ' ' . ($b->vehicle->vehicleModel ?? '') . ' ' . ($b->vehicle->vehicleYear ?? '')),
                    $b->vehicle->vehicleID ?? '', $b->vehicle->user->name ?? '', $b->vehicle->station->stationName ?? '',
                    $b->type, $b->pickupDate, $b->dropoffDate, $b->pickup_location, $b->dropoff_location,
                    $b->amount, $b->payment_status == 1 ? 'Paid' : 'Unpaid', $labels[(int) $b->status] ?? $b->status,
                    $b->reason, optional($b->created_at)->format('Y-m-d H:i'),
                ]);
            }
            fclose($out);
        }, 'bookings-' . now()->format('Ymd-His') . '.csv');
    }

    public function render()
    {
        $scoped = $this->scopedQuery();

        $stats = [
            'total' => (clone $scoped)->count(),
            'pending' => (clone $scoped)->where('status', self::PENDING)->count(),
            'ongoing' => (clone $scoped)->whereIn('status', [self::CONFIRMED, self::ONGOING])->count(),
            'completed' => (clone $scoped)->where('status', self::COMPLETED)->count(),
            'declined' => (clone $scoped)->where('status', self::DECLINED)->count(),
            'revenue' => (clone $scoped)->where('payment_status', 1)->whereIn('status', [self::CONFIRMED, self::ONGOING, self::COMPLETED])->sum('amount'),
        ];

        $ownerIds = Vehicle::query()
            ->when(!$this->isAdmin(), fn ($q) => $q->where('station_id', auth()->user()->station_id))
            ->distinct()->pluck('user_id');

        return view('livewire.booking-report', [
            'bookings' => $this->filteredQuery()->paginate((int) $this->limit),
            'stats' => $stats,
            'owners' => User::whereIn('id', $ownerIds)->orderBy('name')->get(['id', 'name']),
            'stations' => Station::orderBy('stationName')->get(['id', 'stationName']),
            'isAdmin' => $this->isAdmin(),
            'filtersActive' => $this->search !== '' || $this->statusFilter !== '' || $this->paymentFilter !== '' || $this->typeFilter !== ''
                || $this->stationFilter !== '' || $this->ownerFilter !== '' || $this->startDate || $this->endDate,
        ])->layout('components.dashboard.dashboard-master');
    }
}
