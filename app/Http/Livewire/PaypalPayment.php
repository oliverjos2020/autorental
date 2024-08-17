<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PaypalPayment extends Component
{
    public function render()
    {
        return view('livewire.paypal-payment')->layout('components.dashboard.dashboard-master');
    }
}
