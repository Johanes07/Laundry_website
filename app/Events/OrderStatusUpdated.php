<?php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ← ganti ini
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow // ← dan ini
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('order.' . $this->order->order_code),
        ];
    }

    public function broadcastWith(): array
    {
            return [
            'status'            => $this->order->status,
            'status_label'      => $this->order->status_label,
            'payment_status'    => $this->order->payment_status,
            'payment_label'     => $this->order->payment_status_label,
            'payment_method'    => $this->order->payment_method_label,
            'updated_at'        => now()->format('d M, H:i'),
            'note'              => $this->order->statusHistories()->latest()->first()?->note,
        ];
    }

    public function broadcastAs(): string
    {
        return 'status.updated';
    }
}