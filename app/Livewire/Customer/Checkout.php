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

    public $payment_method = 'qris';

    public function processCheckout($cartData)
    {
        $this->validate([
            'customer_name' => 'required|min:3',
            'customer_phone' => 'required|min:9|max:15',
            'payment_method' => 'required|in:cash,qris',
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
        $orderCode = $phonePart . $datePart . rand(100, 999); // added random to ensure unique if multiple orders very quickly

        $order = Order::create([
            'order_code' => $orderCode,
            'table_id' => session('table_id'),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $this->payment_method,
        ]);

        foreach ($orderItemsData as $itemData) {
            $itemData['order_id'] = $order->id;
            OrderItem::create($itemData);
        }

        // Generate Midtrans QRIS using Core API to get raw QR Code
        if ($this->payment_method === 'qris') {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'payment_type' => 'gopay', // Using gopay type will return raw QRIS image URL
                'transaction_details' => [
                    'order_id' => $order->id, // Use UUID to ensure exact match with DB
                    'gross_amount' => $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                    'phone' => $order->customer_phone,
                ],
            ];

            try {
                $response = \Midtrans\CoreApi::charge($params);
                
                // For gopay, the QR code image URL is in actions array
                $qrUrl = null;
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $qrUrl = $action->url;
                            break;
                        }
                    }
                }
                
                if ($qrUrl) {
                    $order->update(['snap_token' => $qrUrl]); // we reuse snap_token column to store the QR image URL
                }
            } catch (\Exception $e) {
                \Log::error('Midtrans CoreApi Error: ' . $e->getMessage());
            }
        }

        $this->dispatch('clearCart');

        return $this->redirect('/order/' . $order->id, navigate: false);
    }

    public function render()
    {
        return view('livewire.customer.checkout')->layout('layouts.customer');
    }
}
