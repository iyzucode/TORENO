<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use Livewire\Component;

class OrderTracking extends Component
{
    public $order;

    public function mount($order_id)
    {
        $this->order = Order::with(['items.menu', 'table'])->findOrFail($order_id);
    }

    public function render()
    {
        // Refresh the order to get the latest status when polling
        $this->order->refresh();
        return view('livewire.customer.order-tracking')->layout('layouts.customer');
    }
}
