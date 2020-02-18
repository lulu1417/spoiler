<?php

namespace App\Http\Controllers;

use App\Food;
use App\Order;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    function store(Request $request){

        try{
            DB::beginTransaction();
            $remaining  = Food::find($request->food_id)->remaining;
            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'food_id' => ['required', 'exists:foods,id'],
                'food_number' => ['required','lte:'.$remaining],
            ]);
            
            $create = Order::create([
                'user_id' => $request->user_id,
                'food_id' => $request->food_id,
                'order_number' => strval(rand(1000000000000, 9999999999999)),
                'food_number' => $request->food_number,
                'complete' => false,
                'send' => true,
            ]);
            DB::commit();
            return response()->json($create);
        }catch (Exception $e){
            DB::rollBack();
            return response()->json($e, 400);
        }

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
        return response()->json($order->update([
            'send' => false,
        ]));
    }

    function look($id){
        $order = Order::find($id);
        $data = $order->with('food', 'user')->first();
        return response()->json($data);
    }
}
