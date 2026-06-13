<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class OrderHistory extends Component
{
    public $phone_number;
    public $orders = [];
    public $hasSearched = false;

    public function search()
    {
        $this->validate([
            'phone_number' => 'required|min:9|max:15'
        ]);

        $this->hasSearched = true;

        $this->orders = Order::where('customer_phone', $this->phone_number)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->latest()
            ->get();
    }

    public function viewOrder($id)
    {
        return $this->redirect('/order/' . $id, navigate: false);
    }

    public function render()
    {
        return view('livewire.customer.order-history')->layout('layouts.customer');
    }
}
