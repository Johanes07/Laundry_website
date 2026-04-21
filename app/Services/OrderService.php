<?php
namespace App\Services;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use App\Models\Service;

class OrderService
{
    public function createOrder(array $data): Order
    {
        $service = Service::findOrFail($data['service_id']);

        $data['total_price']      = $service->price_per_unit * $data['quantity'];
        $data['payment_status']   = $data['payment_method'] === 'cod' ? 'cod' : 'unpaid';
        $data['store_account_id'] = $data['payment_method'] === 'cod' ? null : ($data['store_account_id'] ?? null);
        $data['status']           = 'pending';

        $order = Order::create($data);

        // Catat status history awal
        // $order->statusHistories()->create([
        //     'status' => 'pending',
        //     'note'   => 'Pesanan baru masuk.',
        // ]);

        // Fire event broadcast (untuk real-time di check-status page)
        broadcast(new OrderStatusUpdated($order));

        return $order;
    }

    // Gunakan method ini saat admin update status
    public function updateStatus(Order $order, string $newStatus, ?string $note = null): Order
    {
        $order->_statusNote = $note; // temporary property
        $order->update(['status' => $newStatus]);

        broadcast(new OrderStatusUpdated($order));

        return $order;
    }

}