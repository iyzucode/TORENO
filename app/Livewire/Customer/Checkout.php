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

    public function processCheckout($cartData, $frontendSubtotal = 0, $frontendTaxAmount = 0, $frontendServiceChargeAmount = 0, $frontendTotalAmount = 0)
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

        $backendSubtotal = 0;
        $orderItemsData = [];
        
        foreach ($cartData as $item) {
            $menu = Menu::find($item['id']);
            if (!$menu || !$menu->is_available) {
                continue; 
            }

            $quantity = (int)$item['quantity'];
            if ($quantity <= 0) continue;

            $itemSubtotal = $menu->price * $quantity;
            $backendSubtotal += $itemSubtotal;

            $orderItemsData[] = [
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'quantity' => $quantity,
                'price' => $menu->price, // Harga asli dari DB
                'subtotal' => $itemSubtotal,
                'notes' => $item['notes'] ?? '',
            ];
        }

        if (empty($orderItemsData)) {
            $this->addError('cart', 'Item pesanan tidak valid atau sudah tidak tersedia.');
            return;
        }

        $taxRate = (float)\App\Models\Setting::getVal('tax_rate', 0);
        $serviceChargeType = \App\Models\Setting::getVal('service_charge_type', 'percentage');
        $serviceChargeRate = (float)\App\Models\Setting::getVal('service_charge_rate', 0);
        
        $backendTaxAmount = $backendSubtotal * ($taxRate / 100);
        
        $backendServiceChargeAmount = $serviceChargeType === 'fixed' 
            ? $serviceChargeRate 
            : $backendSubtotal * ($serviceChargeRate / 100);
            
        $backendGrandTotal = $backendSubtotal + $backendTaxAmount + $backendServiceChargeAmount;

        // Validasi strict: cocokkan hitungan backend dengan frontend
        if (
            abs($backendSubtotal - (float)$frontendSubtotal) > 0.1 ||
            abs($backendTaxAmount - (float)$frontendTaxAmount) > 0.1 ||
            abs($backendServiceChargeAmount - (float)$frontendServiceChargeAmount) > 0.1 ||
            abs($backendGrandTotal - (float)$frontendTotalAmount) > 0.1
        ) {
            $this->addError('cart', 'Terjadi perubahan harga menu atau biaya pada sistem. Silakan refresh halaman keranjang Anda.');
            return;
        }

        $phonePart = substr($this->customer_phone, -4);
        $datePart = now()->format('YmdHi');
        $orderCode = $phonePart . $datePart . rand(100, 999);

        $order = Order::create([
            'order_code' => $orderCode,
            'table_id' => session('table_id'),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'subtotal' => $backendSubtotal,
            'tax_rate' => $taxRate,
            'tax_amount' => $backendTaxAmount,
            'service_charge_type' => $serviceChargeType,
            'service_charge_rate' => $serviceChargeRate,
            'service_charge_amount' => $backendServiceChargeAmount,
            'total_amount' => $backendGrandTotal,
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
