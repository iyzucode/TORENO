<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Livewire\Component;

class Checkout extends Component
{
    public $customer_name;
    public $customer_phone;

    public function processCheckout($cartData)
    {
        $this->validate([
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required|min:9|max:15',
        ]);

        if (!session()->has('table_id')) {
            abort(403, 'Sesi meja tidak ditemukan. Silakan scan ulang QR Code Anda.');
        }

        if (empty($cartData)) {
            $this->addError('cart', 'Keranjang belanja Anda kosong.');
            return;
        }

        $total = 0;
        $orderItemsData = [];
        
        foreach ($cartData as $item) {
            $menu = Menu::find($item['id']);
            if (!$menu || !$menu->is_available) {
                continue; 
            }

            $quantity = (int)$item['quantity'];
            if ($quantity <= 0) continue;

            $subtotal = $menu->price * $quantity;
            $total += $subtotal;

            $orderItemsData[] = [
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'quantity' => $quantity,
                'price' => $menu->price,
                'subtotal' => $subtotal,
                'notes' => $item['notes'] ?? '',
            ];
        }

        if (empty($orderItemsData)) {
            $this->addError('cart', 'Item pesanan tidak valid.');
            return;
        }

        $phonePart = substr($this->customer_phone, -4);
        $datePart = now()->format('YmdHi');
        $orderCode = $phonePart . $datePart;

        $order = Order::create([
            'order_code' => $orderCode,
            'table_id' => session('table_id'),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);

        foreach ($orderItemsData as $itemData) {
            $itemData['order_id'] = $order->id;
            OrderItem::create($itemData);
        }

        $this->dispatch('clearCart');

        return $this->redirect('/order/' . $order->id, navigate: false);
    }

    public function render()
    {
        return view('livewire.customer.checkout')->layout('layouts.customer');
    }
}
