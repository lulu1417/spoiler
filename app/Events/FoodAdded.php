<?php

namespace App\Events;

use App\Food;
use App\Restaurant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FoodAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $restaurant;
    public $food;
    public $class = 'new food';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Food $food)
    {
        $this->food = $food;
        $this->restaurant = Restaurant::find($food->restaurant_id);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('food-channel'.$this->restaurant);
        return ['food-channel'];
    }
    public function broadcastAs()
    {
        return 'food-event';
    }
}
