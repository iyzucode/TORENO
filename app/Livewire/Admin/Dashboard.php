<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Menu;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalMenus = Menu::count();

        return view('livewire.admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'totalMenus' => $totalMenus,
        ])->layout('layouts.app');
    }
}
