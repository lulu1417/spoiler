<?php

namespace App\Http\Controllers;

use App\Food;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    function store(Request $request){

        try{
            DB::beginTransaction();
            $food = Food::find($request->food_id);
            $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'food_id' => ['required', 'exists:foods,id'],
                'food_number' => ['required','lte:'.$food->remaining],
            ]);

            $create = Order::create([
                'user_id' => $request->user_id,
                'food_id' => $request->food_id,
                'order_number' => strval(rand(1000000000000, 9999999999999)),
                'food_number' => $request->food_number,
                'complete' => false,
                'send' => true,
            ]);
            $food->update([
                'remaining' => $food->remaining - $request->food_number,
            ]);

            DB::commit();
            return response()->json($create);
        }catch (Exception $e){
            DB::rollBack();
            return response()->json($e, 400);
        }

    }

    function complete($id){
        if($order = Order::find($id)){

        }else{
            return response()->json('order not found',400);
        }
        $order->update([
           'complete' => true,
        ]);
        return response()->json($order);
    }

    function cancel($id){

        if($order = Order::find($id)){

        }else{
            return response()->json('order not found',400);
        }
        if($order->send){
            $order->update([
                'send' => false,
            ]);
            $food = Food::find($order->food_id);
            $food->update([
                'remaining' => $food->remaining + $order->food_number,
            ]);
        }

        return response()->json($order);
    }

    function look($id){
        $order = Order::with('food', 'user')->find($id);
        return response()->json($order);
    }

    function index(){
        return response()->json(Order::all());
    }
}
