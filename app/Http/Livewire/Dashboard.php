<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\BookingOrder;
use App\Models\MapVehicleDriver;
use Illuminate\Support\Facades\DB;


class Dashboard extends Component
{
    public function render()
    {

        $drivers = User::whereIn('role_id', [3])->where('station_id', auth()->user()->station_id)->get();
        $vehicles = Vehicle::where('station_id', auth()->user()->station_id)->get();
        $mappedVehicleIds = MapVehicleDriver::pluck('vehicle_id');
        $MappedVehicle = Vehicle::whereIn('id', $mappedVehicleIds)->get();
        $mappedDriver = MapVehicleDriver::pluck('user_id')->unique();
        $currentYear = Carbon::now()->year;
        $data2 = DB::table('users')
                ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
                ->where('station_id', auth()->user()->station_id)->where('role_id', 3)
                ->whereYear('created_at', $currentYear)
                ->groupBy(DB::raw('MONTH(created_at)'))
                ->pluck('count', 'month')->toArray();

            // Ensure all months are represented
            $driverChart = array_fill(1, 12, 0); // Fill with 0 for all 12 months
            foreach ($data2 as $month2 => $count2) {
                $driverChart[$month2] = $count2;
            }
        //station admin
        $stationAdmin = User::where('role_id', 2)->get();
        $stationAdminGraph = User::where('role_id', 2)->count();
        //drivers
        $totalDrivers = User::where('role_id', 3)->get();
        $totalDriversGraph = User::where('role_id', 3)->count();
        //Mobile Users
        $mobileUsers = User::where('role_id', 5)->get();
        $mobileUsersGraph = User::where('role_id', 5)->count();
        //Total Vehicles
        $totalVehicles = Vehicle::all();
        $totalVehiclesGraph = Vehicle::count();
        // dd($stationAdminGraph);

        return view('dashboard.dashboard2', ['drivers' => $drivers, 'vehicles' => $vehicles, 'MappedVehicle' => $MappedVehicle, 'mappedDriver' => $mappedDriver, 'driverChart' => $driverChart, 'stationAdmin' => $stationAdmin, 'stationAdminGraph' => $stationAdminGraph, 'totalDrivers' => $totalDrivers, 'mobileUsers' => $mobileUsers, 'totalVehicles' => $totalVehicles, 'totalDriversGraph' => $totalDriversGraph, 'mobileUsersGraph' => $mobileUsersGraph, 'totalVehiclesGraph' => $totalVehiclesGraph])->layout('components.dashboard.dashboard-master');
    }
}
