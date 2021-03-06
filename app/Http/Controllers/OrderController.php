<?php

namespace App\Http\Controllers;

use App\DeprivateList;
use App\Events\NewOrder;
use App\Food;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    function store(Request $request)
    {

        try {
            DB::beginTransaction();
            if(Food::where('id', $request->food_id)->count() > 0){
                $food = Food::find($request->food_id);
                $request->validate([
                    'food_id' => ['required', 'exists:foods,id'],
                    'food_number' => ['required', 'lte:' . $food->remaining],
                    'phone' => ['required','digits:10']
                ]);

            }else{
                return $this->sendError('the food id not found', 400);
            }

            if(count(DeprivateList::where('user_id', Auth::user()->id)->get()) < 3 || DeprivateList::find($request->user_id)->is_free){
                $order = Order::create([
                    'user_id' => Auth::user()->id,
                    'phone' => $request->phone,
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
                event(new NewOrder($order));
                return response()->json($order);
            }else{
                return $this->sendError('the user has been banned since he/she has more than three bad records.', 400);
            }

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json($e, 400);
        }

    }

    function complete($id)
    {
        if (count(Order::where('id', $id)->get()) > 0) {
            $order = Order::find($id);
        } else {
            return $this->sendError('order not found', 400);
        }
        if ($order->send) {
            $order->update([
                'complete' => 1,
            ]);
            return response()->json($order);
        } else {
            return $this->sendError('the order has been canceled', 400);
        }

    }

    function cancel($id)
    {
        if (count(Order::where('id', $id)->get()) > 0) {
            $order = Order::find($id);
            if ($order->send) {
                $order->update([
                    'send' => 0,
                ]);
                $food = Food::find($order->food_id);
                $food->update([
                    'remaining' => $food->remaining + $order->food_number,
                ]);
            } else {
                return response()->json('the order has been canceled', 400);
            }

            return response()->json($order);
        } else {
            return response()->json('the given order id not found', 400);
        }
    }

    function look($id)
    {
        if (count(Order::where('id', $id)->get()) > 0) {
            $order = Order::with('food', 'user')->find($id);
            return response()->json($order);
        } else {
            return response()->json('the given order not found', 400);
        }

    }
    function index()
    {
        return response()->json(Order::all());
    }


}
