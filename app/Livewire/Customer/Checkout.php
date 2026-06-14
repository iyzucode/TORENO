<?php

namespace App\Livewire\Customer;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\PromoCode;
use Livewire\Component;

class Checkout extends Component
{
    public $customer_name;
    public $customer_phone;

    public $payment_method = 'qris';
    public $promoCodeInput = '';
    public $appliedPromoCode = null;
    public $discountAmount = 0;
    public $appliedPromoId = null;

    public function applyPromoCode($subtotalAmount)
    {
        $this->resetErrorBag('promo_code');
        session()->forget('promo_success');

        if (empty($this->promoCodeInput)) {
            $this->addError('promo_code', 'Silakan masukkan kode promo.');
            return;
        }

        $promo = PromoCode::where('code', strtoupper($this->promoCodeInput))->first();

        if (!$promo) {
            $this->addError('promo_code', 'Kode promo tidak ditemukan.');
            return;
        }

        if (!$promo->is_active) {
            $this->addError('promo_code', 'Kode promo sudah tidak aktif.');
            return;
        }

        if ($promo->start_date && now()->lt($promo->start_date)) {
            $this->addError('promo_code', 'Kode promo belum bisa digunakan.');
            return;
        }

        if ($promo->end_date && now()->gt($promo->end_date)) {
            $this->addError('promo_code', 'Masa berlaku kode promo sudah habis.');
            return;
        }

        if ($promo->usage_limit && $promo->used_count >= $promo->usage_limit) {
            $this->addError('promo_code', 'Batas penggunaan kupon sudah habis.');
            return;
        }

        if ($subtotalAmount < $promo->min_purchase) {
            $this->addError('promo_code', 'Minimum belanja untuk kupon ini adalah Rp ' . number_format($promo->min_purchase, 0, ',', '.'));
            return;
        }

        // Hitung diskon
        $discount = 0;
        if ($promo->type === 'percentage') {
            $discount = $subtotalAmount * ($promo->value / 100);
            if ($promo->max_discount && $discount > $promo->max_discount) {
                $discount = $promo->max_discount;
            }
        } else {
            $discount = $promo->value;
        }

        // Diskon tidak boleh melebihi subtotal
        if ($discount > $subtotalAmount) {
            $discount = $subtotalAmount;
        }

        $this->appliedPromoCode = $promo->code;
        $this->appliedPromoId = $promo->id;
        $this->discountAmount = $discount;
        
        session()->flash('promo_success', 'Kupon diskon berhasil diterapkan!');
    }

    public function removePromoCode()
    {
        $this->promoCodeInput = '';
        $this->appliedPromoCode = null;
        $this->appliedPromoId = null;
        $this->discountAmount = 0;
        $this->resetErrorBag('promo_code');
        session()->forget('promo_success');
    }

    public function processCheckout($cartData, $frontendSubtotal = 0, $frontendTaxAmount = 0, $frontendServiceChargeAmount = 0, $frontendTotalAmount = 0, $frontendDiscountAmount = 0)
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
        
        // Cek promo lagi di backend untuk validasi akhir
        $backendDiscountAmount = 0;
        $backendPromoId = null;
        
        if ($this->appliedPromoCode) {
            $promo = PromoCode::where('code', $this->appliedPromoCode)->first();
            if ($promo && $promo->is_active && $backendSubtotal >= $promo->min_purchase) {
                $backendPromoId = $promo->id;
                if ($promo->type === 'percentage') {
                    $backendDiscountAmount = $backendSubtotal * ($promo->value / 100);
                    if ($promo->max_discount && $backendDiscountAmount > $promo->max_discount) {
                        $backendDiscountAmount = $promo->max_discount;
                    }
                } else {
                    $backendDiscountAmount = $promo->value;
                }
                
                if ($backendDiscountAmount > $backendSubtotal) {
                    $backendDiscountAmount = $backendSubtotal;
                }
                
                // Increase used_count
                $promo->increment('used_count');
            }
        }

        $netSubtotal = max(0, $backendSubtotal - $backendDiscountAmount);

        $backendTaxAmount = $netSubtotal * ($taxRate / 100);
        
        $backendServiceChargeAmount = $serviceChargeType === 'fixed' 
            ? $serviceChargeRate 
            : $netSubtotal * ($serviceChargeRate / 100);
            
        $backendGrandTotal = $netSubtotal + $backendTaxAmount + $backendServiceChargeAmount;

        // Validasi strict: cocokkan hitungan backend dengan frontend
        if (
            abs($backendSubtotal - (float)$frontendSubtotal) > 0.1 ||
            abs($backendTaxAmount - (float)$frontendTaxAmount) > 0.1 ||
            abs($backendServiceChargeAmount - (float)$frontendServiceChargeAmount) > 0.1 ||
            abs($backendGrandTotal - (float)$frontendTotalAmount) > 0.1 ||
            abs($backendDiscountAmount - (float)$frontendDiscountAmount) > 0.1
        ) {
            $this->addError('cart', 'Terjadi perubahan harga atau kupon tidak valid. Silakan refresh keranjang Anda.');
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
            'promo_code_id' => $backendPromoId,
            'discount_amount' => $backendDiscountAmount,
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
