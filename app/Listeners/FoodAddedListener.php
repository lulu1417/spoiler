<?php

namespace App\Listeners;

use App\Events\FoodAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FoodAddedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FoodAdded  $event
     * @return void
     */
    public function handle(FoodAdded $event)
    {
        //
    }
}
