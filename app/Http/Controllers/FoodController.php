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
            return response()->json($food, 200);
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    function like(Request $request)
    {
        if ($request->search) {
            $result['restaurant'] = Restaurant::where('name', "like", "%" . $request->search . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -2, 2, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -1, 1, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 2, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 1, "utf-8") . "%")
                ->get();
            $result['food'] = Food::where('name', "like", "%" . $request->search . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -2, 2, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, -1, 1, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 2, "utf-8") . "%")
                ->orwhere('name', "like", "%" . mb_substr($request->search, 0, 1, "utf-8") . "%")
                ->with('restaurant')->get();
            return response()->json($result);
        } else {
            return response()->json(["message" => 'You must provide an keyword for searching'], 400);
        }

    }

    function search(Request $request)
    {
        $result['restaurant'] = Restaurant::all()->toArray();
        $result['food'] = Food::all()->toArray();

        if ($request->search) {
            $result['restaurant'] = array_filter($result['restaurant'], function ($item) use ($request) {

//                var_dump(strtolower($item['name']).' '.similar_text(strtolower($item['name']), $request->search) / strlen($request->search) );
                return (similar_text(strtolower($item['name']), $request->search) / strlen($request->search)) > 0.5;
            });
            $result['food'] = array_filter($result['food'], function ($item) use ($request) {
//                var_dump(strtolower($item['name']).' '.similar_text(strtolower($item['name']), $request->search)/ strlen($request->search) );
                return (similar_text(strtolower($item['name']), $request->search) / strlen($request->search)) > 0.5;
            });
        }
        $result['restaurant'] = array_values($result['restaurant']);
        $result['food'] = array_values($result['food']);
        return response()->json($result);
    }

}
