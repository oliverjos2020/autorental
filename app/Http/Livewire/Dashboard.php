<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Station;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Transaction;
use App\Models\BookingOrder;
use App\Models\MapVehicleDriver;
use Illuminate\Support\Facades\DB;


class Dashboard extends Component
{
    public function render()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $isAdmin = auth()->user()->role_id == 1;
        $stationId = auth()->user()->station_id;

        // ── Existing Data (Station Admin - role 2) ──
        $dd = User::where('station_id', $stationId)->whereIn('role_id', [3])->pluck('id');
        $drivers = User::whereIn('role_id', [3])->where('station_id', $stationId)->get();
        $vehicles = Vehicle::where('station_id', $stationId)->get();
        $mappedVehicleIds = MapVehicleDriver::pluck('vehicle_id');
        $MappedVehicle = Vehicle::whereIn('id', $mappedVehicleIds)->where('station_id', $stationId)->get();
        $mappedDriver = MapVehicleDriver::whereIn('user_id', $dd)->get();
        $data2 = DB::table('users')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->where('station_id', $stationId)->where('role_id', 3)
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month')->toArray();

        $driverChart = array_fill(1, 12, 0);
        foreach ($data2 as $month2 => $count2) {
            $driverChart[$month2] = $count2;
        }

        // ── Existing Data (Admin - role 1) ──
        $stationAdmin = User::where('role_id', 2)->get();
        $stationAdminGraph = User::where('role_id', 2)->count();
        $totalDrivers = User::where('role_id', 3)->get();
        $totalDriversGraph = User::where('role_id', 3)->count();
        $mobileUsers = User::where('role_id', 5)->get();
        $mobileUsersGraph = User::where('role_id', 5)->count();
        $totalVehicles = Vehicle::all();
        $totalVehiclesGraph = Vehicle::count();

        // ══════════════════════════════════════════════
        // ══ NEW: Enhanced Dashboard Data            ══
        // ══════════════════════════════════════════════

        // ── Booking Statistics ──
        $bookingQuery = BookingOrder::query();
        if (!$isAdmin) {
            $stationVehicleIds = Vehicle::where('station_id', $stationId)->pluck('id')->toArray();
            $bookingQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $totalBookings = (clone $bookingQuery)->count();
        $pendingBookings = (clone $bookingQuery)->where('status', 0)->count();
        $ongoingBookings = (clone $bookingQuery)->where('status', 2)->count();
        $completedBookings = (clone $bookingQuery)->where('status', 3)->count();
        $paidBookings = (clone $bookingQuery)->where('payment_status', 1)->count();
        $unpaidBookings = (clone $bookingQuery)->where('payment_status', 0)->count();

        // ── Revenue Statistics ──
        $revenueQuery = BookingOrder::query()->where('payment_status', 1);
        if (!$isAdmin) {
            $stationVehicleIds = $stationVehicleIds ?? Vehicle::where('station_id', $stationId)->pluck('id')->toArray();
            $revenueQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $totalRevenue = (clone $revenueQuery)->sum('amount');
        $todayRevenue = (clone $revenueQuery)->whereDate('created_at', Carbon::today())->sum('amount');
        $monthRevenue = (clone $revenueQuery)->whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->sum('amount');

        // ── Vehicle Trip Status ──
        $vehicleTripQuery = Vehicle::query();
        if (!$isAdmin) {
            $vehicleTripQuery->where('station_id', $stationId);
        }
        $vehiclesOnTrip = (clone $vehicleTripQuery)->where('on_trip', 1)->count();
        $vehiclesAvailable = (clone $vehicleTripQuery)->where('on_trip', 0)->where('status', 2)->count();

        // ── Monthly Booking Chart (current year) ──
        $monthlyBookingsQuery = DB::table('booking_orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->whereYear('created_at', $currentYear);
        if (!$isAdmin) {
            $stationVehicleIds = $stationVehicleIds ?? Vehicle::where('station_id', $stationId)->pluck('id')->toArray();
            $monthlyBookingsQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $monthlyBookingsData = $monthlyBookingsQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('count', 'month')->toArray();

        $bookingChart = array_fill(1, 12, 0);
        foreach ($monthlyBookingsData as $month => $count) {
            $bookingChart[$month] = $count;
        }

        // ── Monthly Revenue Chart (current year) ──
        $monthlyRevenueQuery = DB::table('booking_orders')
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(amount) as total'))
            ->where('payment_status', 1)
            ->whereYear('created_at', $currentYear);
        if (!$isAdmin) {
            $monthlyRevenueQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $monthlyRevenueData = $monthlyRevenueQuery->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month')->toArray();

        $revenueChart = array_fill(1, 12, 0);
        foreach ($monthlyRevenueData as $month => $total) {
            $revenueChart[$month] = (float) $total;
        }

        // ── Recent Bookings (latest 5) ──
        $recentBookingsQuery = BookingOrder::with(['user:id,name', 'vehicle:id,vehicleMake,vehicleModel,vehicleYear']);
        if (!$isAdmin) {
            $recentBookingsQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $recentBookings = $recentBookingsQuery->latest()->limit(5)->get();

        // ── Booking Type Distribution (booking vs ehailing) ──
        $bookingTypeQuery = BookingOrder::query();
        if (!$isAdmin) {
            $bookingTypeQuery->whereIn('vehicle_id', $stationVehicleIds);
        }
        $bookingTypeBooking = (clone $bookingTypeQuery)->where('type', 'booking')->count();
        $bookingTypeEhailing = (clone $bookingTypeQuery)->where('type', 'ehailing')->count();

        // ── Station Performance (Admin only) ──
        $stationPerformance = collect();
        if ($isAdmin) {
            $stationPerformance = Station::select('stations.id', 'stations.stationName')
                ->withCount(['vehicles'])
                ->get()
                ->map(function ($station) {
                    $vehicleIds = Vehicle::where('station_id', $station->id)->pluck('id')->toArray();
                    $station->total_bookings = BookingOrder::whereIn('vehicle_id', $vehicleIds)->count();
                    $station->total_revenue = BookingOrder::whereIn('vehicle_id', $vehicleIds)->where('payment_status', 1)->sum('amount');
                    $station->on_trip_count = Vehicle::where('station_id', $station->id)->where('on_trip', 1)->count();
                    return $station;
                })
                ->sortByDesc('total_revenue')
                ->values();
        }

        return view('dashboard.dashboard2', [
            // Existing
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'MappedVehicle' => $MappedVehicle,
            'mappedDriver' => $mappedDriver,
            'driverChart' => $driverChart,
            'stationAdmin' => $stationAdmin,
            'stationAdminGraph' => $stationAdminGraph,
            'totalDrivers' => $totalDrivers,
            'mobileUsers' => $mobileUsers,
            'totalVehicles' => $totalVehicles,
            'totalDriversGraph' => $totalDriversGraph,
            'mobileUsersGraph' => $mobileUsersGraph,
            'totalVehiclesGraph' => $totalVehiclesGraph,
            // New
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'ongoingBookings' => $ongoingBookings,
            'completedBookings' => $completedBookings,
            'paidBookings' => $paidBookings,
            'unpaidBookings' => $unpaidBookings,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'vehiclesOnTrip' => $vehiclesOnTrip,
            'vehiclesAvailable' => $vehiclesAvailable,
            'bookingChart' => $bookingChart,
            'revenueChart' => $revenueChart,
            'recentBookings' => $recentBookings,
            'bookingTypeBooking' => $bookingTypeBooking,
            'bookingTypeEhailing' => $bookingTypeEhailing,
            'stationPerformance' => $stationPerformance,
        ])->layout('components.dashboard.dashboard-master');
    }
}
