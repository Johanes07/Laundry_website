<?php
namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

class CourierLocationUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public Order $order,
        public float $lat,
        public float $lng,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("order.{$this->order->id}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'lat'      => $this->lat,
            'lng'      => $this->lng,
            'order_id' => $this->order->id,
        ];
    }
}