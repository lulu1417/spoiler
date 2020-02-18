<?php

namespace App\Http\Controllers;

use App\Events\TestEvent;
use App\Order;
use App\Subscription;
use Illuminate\Http\Request;

class SubscriptController extends Controller
{
    function store(Request $request){

        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'restaurant_id' => ['required', 'exists:restaurants,id'],
        ]);

        $create = Subscription::create([
            'user_id' => $request->user_id,
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json($create);

    }

    function search(Request $request){

        $subscription = Order::where('user_id', $request->user_id)->where('restaurant_id', $request->restaurant_id);
        return response()->json($subscription);

    }

    function cancel($id){
        $subscription = Subscription::find($id);
        $subscription->delete();
        return response()->json($subscription);
    }

    public function handle(TestEvent $event)
    {

    }
}
