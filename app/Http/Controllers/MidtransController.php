<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Notification;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid notification signature key'], 403);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id; // This is the Order UUID
        $fraud = $notification->fraud_status;

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transaction == 'capture') {
            // For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $order->payment_status = 'pending';
                } else {
                    $order->payment_status = 'paid';
                    if ($order->status == 'pending') {
                        $order->status = 'cooking';
                    }
                }
            }
        } else if ($transaction == 'settlement') {
            $order->payment_status = 'paid';
            // Automatically move to cooking since it's paid
            if ($order->status == 'pending') {
                $order->status = 'cooking';
            }
        } else if ($transaction == 'pending') {
            $order->payment_status = 'unpaid';
        } else if ($transaction == 'deny') {
            $order->payment_status = 'failed';
        } else if ($transaction == 'expire') {
            $order->payment_status = 'failed';
            if ($order->status == 'pending') {
                $order->status = 'cancelled';
            }
        } else if ($transaction == 'cancel') {
            $order->payment_status = 'failed';
            if ($order->status == 'pending') {
                $order->status = 'cancelled';
            }
        }

        $order->save();

        return response()->json(['message' => 'Success']);
    }
}
