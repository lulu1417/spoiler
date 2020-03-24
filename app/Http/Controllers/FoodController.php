<?php

namespace App\Http\Controllers;

use App\Events\FoodAdded;
use App\Food;
use App\Restaurant;
use ArrayObject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FoodController extends Controller
{
    function index()
    {
        $foods = Food::with('restaurant')->get();
        return $foods;
    }

    function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => Rule::unique('foods')->where(function ($query) use ($request) {
                    return $query->where('restaurant_id', $request->restaurant_id);
                }),
                'remaining' => ['required', 'integer'],
                'original_price' => ['required', 'integer'],
                'discounted_price' => ['required', 'integer'],
                'image' => ['sometimes', 'mimes:jpg,jpeg,png,bmp'],
                'restaurant_id' => ['required', 'exists:restaurants,id'],
            ]);

            if (request()->hasFile('image')) {
                $upload = new UploadImage();
                $imageURL = request()->file('image')->store('public');
                $parameters['image'] = $upload->trim($imageURL);

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
            Log::info(event(new FoodAdded($food)));

            DB::commit();
            return response()->json($food, 200);
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    function update(Request $request, Food $id)
    {
        try {
            $food = $id;
            DB::beginTransaction();
            $request->validate([
                'name' => Rule::unique('foods')->ignore($food->id)->where(function ($query) use ($food, $request) {
                    return ($query->where('restaurant_id', $food->restaurant_id));
                }),
                'remaining' => ['integer'],
                'original_price' => ['integer'],
                'discounted_price' => ['integer'],
                'image' => ['sometimes', 'mimes:png,jpg,jpeg,bmp'],
                'restaurant_id' => ['exists:restaurants,id'],
            ]);

            if (request()->hasFile('image')) {
                $upload = new UploadImage();
                $imageURL = request()->file('image')->store('public');
                $parameter['image'] = $upload->trim($imageURL);
                $food->update([
                    'image' => $parameter['image']
                ]);

            }
            $originalNumber = $food->remaining;

            $food->update($request->except(['image']));

            if ($food->remaining > $originalNumber) {
                Log::info(event(new FoodAdded($food)));
            }

            DB::commit();
            return response()->json(Food::find(2), 200);
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    function search(Request $request)
    {
        if ($request->search) {
            $result['restaurant'] = Restaurant::where('name', "like", "%" . $request->search . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -2, 2, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -1, 1, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 2, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 1, "utf-8"). "%")
                ->get();
            $result['food'] = Food::where('name', "like", "%" . $request->search . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -2, 2, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -1, 1, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 2, "utf-8"). "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 1, "utf-8"). "%")
                ->with('restaurant')->get();
//            $result = Restaurant::where('name', "like", "%" . mb_substr($request->search, 0, 2, "utf-8") . "%")->get();
            return response()->json($result);
        } else {
            return response()->json(["message" => 'You must provide an keyword for searching'], 400);
        }

    }

}
