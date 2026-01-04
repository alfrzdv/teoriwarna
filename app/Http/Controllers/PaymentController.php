<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(Order $order)
    {
        try {
            // Check if order belongs to authenticated user
            if ($order->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if payment is already completed
            if ($order->payment->status === 'paid' || $order->payment->status === 'success') {
                return response()->json(['error' => 'Payment already completed'], 400);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone ?? $order->shipping_phone,
                ],
                'item_details' => $this->getItemDetails($order),
                'callbacks' => [
                    'finish' => route('payment.finish', $order),
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            // Update payment with snap token
            $order->payment->update(['snap_token' => $snapToken]);

            return response()->json([
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create payment token'], 500);
        }
    }

    private function getItemDetails(Order $order)
    {
        $items = [];

        foreach ($order->order_items as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int) $item->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }

        // Add shipping cost as item
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Shipping Cost - ' . ucfirst($order->shipping_method),
            ];
        }

        return $items;
    }

    public function notification(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderNumber = $notification->order_id;

            Log::info('Midtrans Notification Received', [
                'order_number' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
            ]);

            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('Order not found: ' . $orderNumber);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = $order->payment;

            DB::beginTransaction();

            try {
                if ($transactionStatus == 'capture') {
                    if ($fraudStatus == 'accept') {
                        $payment->update([
                            'status' => 'paid',
                            'payment_date' => now(),
                        ]);
                        $order->update(['status' => 'processing']);
                    }
                } elseif ($transactionStatus == 'settlement') {
                    $payment->update([
                        'status' => 'paid',
                        'payment_date' => now(),
                    ]);
                    $order->update(['status' => 'processing']);
                } elseif ($transactionStatus == 'pending') {
                    $payment->update(['status' => 'pending']);
                } elseif ($transactionStatus == 'deny') {
                    $payment->update(['status' => 'failed']);
                } elseif ($transactionStatus == 'expire') {
                    $payment->update(['status' => 'failed']);
                    $order->update(['status' => 'cancelled']);
                } elseif ($transactionStatus == 'cancel') {
                    $payment->update(['status' => 'cancelled']);
                    $order->update(['status' => 'cancelled']);
                }

                DB::commit();

                return response()->json(['message' => 'Notification processed']);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Database Update Error: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }

    public function finish(Order $order, Request $request)
    {
        // Redirect user back to order detail page after payment
        $message = 'Pembayaran sedang diproses. Silakan tunggu konfirmasi.';

        if ($request->has('transaction_status')) {
            $status = $request->query('transaction_status');

            if ($status === 'settlement' || $status === 'capture') {
                $message = 'Pembayaran berhasil! Terima kasih atas pesanan Anda.';
            } elseif ($status === 'pending') {
                $message = 'Pembayaran pending. Silakan selesaikan pembayaran Anda.';
            } elseif ($status === 'deny' || $status === 'cancel' || $status === 'expire') {
                $message = 'Pembayaran gagal atau dibatalkan.';
            }
        }

        return redirect()->route('orders.show', $order)->with('success', $message);
    }

    public function checkStatus(Order $order)
    {
        try {
            if ($order->user_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return response()->json([
                'payment_status' => $order->payment->status,
                'order_status' => $order->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Status Check Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to check status'], 500);
        }
    }
}
