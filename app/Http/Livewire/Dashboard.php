<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\BookingOrder;


class Dashboard extends Component
{
    public function render()
    {
        $user = User::whereIn('role_id', [2, 3, 4])->get();
        $approved = Vehicle::where('status', 2)->get();
        $pending = Vehicle::where('status', 1)->get();
        $pendingRequest = Vehicle::where('user_id', Auth()->user()->id)->where('status', 2)->where('on_trip', 1)->get();
        $approvedRequest = BookingOrder::where('status', 2)->get();
        $completedTrips = BookingOrder::where('status', 3)->get(); 
        $partnerVehicles = Vehicle::where('user_id', Auth()->user()->id)->where('status', 2)->get();
        $partnerVehiclesArray = Vehicle::where('user_id', Auth()->user()->id)->where('status', 2)->pluck('id')->toArray();
        $activeOrders = BookingOrder::whereIn('vehicle_id', $partnerVehiclesArray)->where('status', 1)->get();
        // dd($activeOrders);
        return view('dashboard.dashboard2', ['users' => $user, 'approved' => $approved, 'pending' => $pending, 'pendingRequest' => $pendingRequest, 'approvedRequest' => $approvedRequest, 'partnerVehicles' => $partnerVehicles, 'orders' => $activeOrders, 'completedTrips' => $completedTrips])->layout('components.dashboard.dashboard-master');;
    }
}
