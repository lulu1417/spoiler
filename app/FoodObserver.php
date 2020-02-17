<?php


namespace App;

use App\Food;
use Illuminate\Support\Facades\Log;

class FoodObserver
{
    public function creating(Food $food) {
        info('creating');
        info($food);
    }
    public function created(Food $food) {
        info('created');
        info($food);
    }

    public function saving(Food $food) {
        info('saving');
        info($food);
    }
    public function saved(Food $food) {
        info('saved');
        info($food);
    }

}