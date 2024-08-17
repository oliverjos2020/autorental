<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BookingOrder;

use Livewire\WithPagination;

class MyBookingOrders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $limit = '10';
    protected $queryString = ['limit'];

    public function render()
    {
        $id = Auth()->user()->id;
        $myOrders = BookingOrder::where('user_id', $id)->where('payment_status', 1)->latest()->paginate($this->limit);
        return view('livewire.home.my-booking-orders', ['myOrders' => $myOrders])->layout('components.home.home-master-3');
    }
}
