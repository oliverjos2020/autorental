<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\BookingOrder;
use Exception;

class Checkout extends Component
{
    public $reviewId;
    protected $queryString = ['reviewId'];
    // public function mount($reviewId)
    // {
    //     $this->reviewId = $reviewId;
    // }
    public function deleteOrder($id)
    {
        try{
            BookingOrder::findOrfail($id)->delete();
            
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => 'Order deleted successfully',
            ]);
            // $this->resetPage();

        } catch (Exception $e) {
            $this->dispatchBrowserEvent('notify', [
                'type' => 'error',
                'message' => $e->getMessage(),
            ]);
            return;
        }

    }
    public function render()
    {
        
        $user = Auth()->user()->id;
        $totalAmount = BookingOrder::where('user_id', $user)
    ->where('payment_status', 0)
    ->sum('amount');
        $order = BookingOrder::where('user_id', $user)->where('payment_status', 0)->get();
        return view('livewire.home.checkout', ['orders' => $order, 'totalAmount' => $totalAmount])->layout('components.home.home-master-3');

    }
}
