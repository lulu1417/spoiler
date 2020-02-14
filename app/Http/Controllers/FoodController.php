<?php

namespace App\Http\Controllers;

use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FoodController extends Controller
{
    function index(){

        $response = DB::table('restaurants')
            ->rightJoin('foods', 'restaurants.id', '=', 'foods.restaurant_id')
            ->select( 'foods.id as food_id','foods.name as food_name', 'foods.discounted_price', 'foods.remaining',
                'restaurants.id as restaurant_id', 'restaurants.name as restaurant_name', 'restaurants.address')->get();

        return $response;
    }

    function store(Request $request){
        $request->validate([
            'name' => Rule::unique('foods')->where(function ($query) use ($request) {
                return $query->where('restaurant_id', $request->restaurant_id);
            }),
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

        $food = Food::create([
            'name' => $request->name,
            'remaining' => $request->remaining,
            'original_price' => $request->original_price,
            'discounted_price' => $request->discounted_price,
            'image' => $parameters['image'],
            'restaurant_id' => $request->restaurant_id,
        ]);

        return response()->json($food, 200);

    }
    function search(Request $request)
    {
        if ($request->search) {
            $result['restaurant'] = Restaurant::where('name', "like", "%".$request->search."%")->get();
            $result['food'] = Food::where('name', "like", "%".$request->search."%")->get();
            return response()->json($result);
        }else{
            return response()->json(["message" =>'You must provide an keyword for searching'], 400);
        }

    }
}
