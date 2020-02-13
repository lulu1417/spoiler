<?php

namespace App\Http\Controllers;

use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    function index(){

        $foods = Food::all()->toArray();
        $data = array_map(function ($food) {
            $food['restaurant'] = Food::find($food['id'])->restaurant;
            return $food;
        }, $foods);
        return response()->json($data);
    }

    function store(Request $request){
        $request->validate([
            'name' => ['required', 'unique:foods'],
            'remaining' => ['required', 'integer'],
            'original_price' => ['required', 'integer'],
            'discounted_price' => ['required', 'integer'],
            'image' => ['sometimes', 'mimes:png, jpg, jpeg, bmp'],
            'restaurant_id' => ['required', 'exists:restaurants,id'],
        ]);

        if (request()->hasFile('image')) {
            $upload = new UploadImage();
            $parameters['image'] = $upload->trim($request->all());

        } else {
            $parameters['image'] = null;
        }

        $create = Food::create([
            'name' => $request->name,
            'remaining' => $request->remaining,
            'original_price' => $request->original_price,
            'discounted_price' => $request->discounted_price,
            'image' => $parameters['image'],
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json($create, 200);

    }

    function search(Request $request){

        if($request->restaurant){
            $result = Restaurant::like('name', $request->restaurant)->get();
        }elseif ($request->food){
            $result = Food::like('name', $request->food)->get();
        }
        return response()->json($result);

    }
}
