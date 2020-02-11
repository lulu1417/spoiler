<?php

namespace App\Http\Controllers;

use App\Order;
use App\Restaurant;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    function store(Request $request){

        $request->validate([
            'user_id' => ['required', 'exists:users'],
            'food_id' => ['required', 'exists:foods'],
        ]);

        $create = Order::create([
            'user_id' => $request->user_id,
            'food_id' => $request->food_id,
            'order_number' => rand(1000000000000, 9999999999999),
            'complete' => false,
            'send' => false,
        ]);

        return response()->json($create);

    }

    function search(Request $request){

        $subscription = Order::where('user_id', $request->user_id)->where('food_id', $request->food_id);
        return response()->json($subscription);

    }

    function complete($id){
        $order = Order::find($id)->first();
        $order = $order->update([
           'complete' => true,
        ]);
        return response()->json($order);

    }

    function cancel($id){
        $order = Order::find($id)->first();
        return response()->json($order->delete());
    }

    function index($id){
        $data['order'] = Order::find($id)->first();
//        $data['food'] =
//        $data['restaurant'] = ;

        return response()->json();
    }
}
