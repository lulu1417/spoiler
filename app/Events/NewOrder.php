<?php

namespace App\Events;


use App\Food;
use App\Order;
use App\Restaurant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrder implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $class = 'new order';
    public $restaurant;

    public function __construct($order)
    {
        $this->order = $order;
        $this->restaurant = Restaurant::find($order->food->restaurant_id);
    }

    public function broadcastOn()
    {
        return ['order-channel'.$this->restaurant->id];
    }

    public function broadcastAs()
    {
        return 'order-event';
    }
}
