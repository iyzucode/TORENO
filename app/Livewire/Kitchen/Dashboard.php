<?php

namespace App\Livewire\Kitchen;

use App\Models\Order;
use Livewire\Component;

class Dashboard extends Component
{
    public function markAsCompleted($orderId)
    {
        $order = Order::find($orderId);
        if ($order && $order->status === 'cooking') {
            $order->status = 'completed';
            $order->save();
            session()->flash('message', 'Pesanan #' . substr($order->id, 0, 8) . ' ditandai selesai.');
        }
    }

    public function render()
    {
        $cookingOrders = Order::with(['table', 'items.menu'])
            ->where('status', 'cooking')
            ->orderBy('updated_at', 'asc') // Oldest first (FIFO)
            ->get();

        $completedOrders = Order::with(['table'])
            ->where('status', 'completed')
            ->whereDate('updated_at', today())
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return view('livewire.kitchen.dashboard', [
            'cookingOrders' => $cookingOrders,
            'completedOrders' => $completedOrders,
        ])->layout('layouts.app');
    }
}
