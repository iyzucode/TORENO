<?php

namespace App\Livewire\Cashier;

use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public $playAudio = false;
    public $latestOrderId = null;

    public function mount()
    {
        $latestOrder = Order::latest()->first();
        if ($latestOrder) {
            $this->latestOrderId = $latestOrder->id;
        }
    }

    public function confirmPayment($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'pending') {
            $order->status = 'cooking';
            $order->save();
            session()->flash('message', 'Pembayaran untuk pesanan #' . substr($order->id, 0, 8) . ' dikonfirmasi.');
        }
    }

    public function render()
    {
        $this->playAudio = false;
        
        $pendingOrders = Order::with(['table', 'items.menu'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $cookingOrders = Order::with(['table'])
            ->where('status', 'cooking')
            ->orderBy('created_at', 'desc')
            ->get();

        // Check for new orders
        $latestOrder = Order::latest()->first();
        if ($latestOrder && $latestOrder->id !== $this->latestOrderId) {
            $this->playAudio = true;
            $this->latestOrderId = $latestOrder->id;
        }

        return view('livewire.cashier.dashboard', [
            'pendingOrders' => $pendingOrders,
            'cookingOrders' => $cookingOrders,
        ])->layout('layouts.app');
    }
}
