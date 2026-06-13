<?php

namespace App\Livewire\Owner;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $startDate;
    public $endDate;

    public function mount()
    {
        // Default to today
        $this->startDate = Carbon::today()->startOfDay()->format('Y-m-d\TH:i');
        $this->endDate = Carbon::today()->endOfDay()->format('Y-m-d\TH:i');
    }

    public function filter()
    {
        // Triggers re-render with updated dates
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);
    }

    public function render()
    {
        // Strictly use 'completed' orders
        $ordersQuery = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                            ->where('status', 'completed');

        $totalRevenue = $ordersQuery->sum('total_amount');
        $totalOrdersCount = $ordersQuery->count();

        // Additional Analytics
        $averageOrderValue = $totalOrdersCount > 0 ? $totalRevenue / $totalOrdersCount : 0;
        
        $totalItemsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->where('orders.status', 'completed')
            ->sum('order_items.quantity');
            
        $averageItemsPerOrder = $totalOrdersCount > 0 ? $totalItemsSold / $totalOrdersCount : 0;

        // Sales History Table (Detailed Orders)
        $ordersList = Order::with(['table', 'items'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->get();

        // Get Top Selling Items within the time range
        $topItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select('order_items.menu_name', DB::raw('SUM(order_items.quantity) as total_sold'), DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->whereBetween('orders.created_at', [$this->startDate, $this->endDate])
            ->where('orders.status', 'completed')
            ->groupBy('order_items.menu_name')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('livewire.owner.dashboard', [
            'totalRevenue' => $totalRevenue,
            'totalOrdersCount' => $totalOrdersCount,
            'averageOrderValue' => $averageOrderValue,
            'totalItemsSold' => $totalItemsSold,
            'averageItemsPerOrder' => $averageItemsPerOrder,
            'ordersList' => $ordersList,
            'topItems' => $topItems,
        ])->layout('layouts.app', ['header' => 'Dashboard Owner']);
    }
}
