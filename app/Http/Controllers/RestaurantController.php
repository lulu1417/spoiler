<?php

namespace App\Http\Controllers;

use App\Food;
use App\Like;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Fluent;


class RestaurantController extends Controller
{

    function index()
    {

        $restaurants = Restaurant::with('foods')->withCount('foods')->withCount('likes')->get();
        return response()->json($restaurants);

    }

    function getSubscriptUsers()
    {
        $subUser = Restaurant::with('subscriptUser')->get();
        return response()->json($subUser);
    }



    function store(Request $request)
    {

        try {
            DB::beginTransaction();

            $validator = Validator::make($request->toArray(),
                [
                    'name' => ['required', 'string', 'unique:restaurants'],
                    'class' => ['required', 'string'],
                    'east_longitude' => ['required', 'numeric'],
                    'north_latitude' => ['required', 'numeric'],
                    'start_time' => ['required', 'digits:6'],
                    'end_time' => ['required', 'digits:6', 'gt:' . $request->start_time],
                    'link' => ['required', 'unique:restaurants'],
                    'address' => ['required', 'unique:restaurants'],
                    'image' => ['sometimes', 'mimes:png,jpg,jpeg,bmp'],
                    'phone' => ['required', 'digits:9', 'unique:restaurants'],
                ]);
            $validator->validate();
            if (request()->hasFile('image')) {
                $upload = new UploadImage();
                $imageURL = request()->file('image')->store('public');
                $parameters['image'] = $upload->trim($imageURL);

            } else {
                $parameters['image'] = null;
            }
            $create = Restaurant::create([
                'name' => $request->name,
                'class' => $request->class,
                'east_longitude' => $request->east_longitude,
                'north_latitude' => $request->north_latitude,
                'link' => $request->link,
                'address' => $request->address,
                'image' => $parameters['image'],
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'phone' => $request->phone,
                'owner_id' => Auth::user()->id,
            ]);

            DB::commit();
            return response()->json($create, 200);

        } catch (Exception $error) {

            DB::rollback();
            return response()->json("create restaurant failed", 400);
        }

    }
    function update(Restaurant $id, Request $request)
    {
        try {
            $restaurant = $id;
            DB::beginTransaction();
            $validator = Validator::make($request->toArray(),
                [
                    'name' => ['string', Rule::unique('restaurants')->ignore($restaurant->id)],
                    'class' => ['string'],
                    'east_longitude' => ['numeric'],
                    'north_latitude' => ['numeric'],
                    'start_time' => ['digits:6'],
                    'end_time' => ['digits:6'],
                    'link' => [Rule::unique('restaurants')->ignore($restaurant->id)],
                    'address' => ['string ', Rule::unique('restaurants')->ignore($restaurant->id)],
                    'image' => ['sometimes', 'mimes:png,jpg,jpeg,bmp'],
                    'phone' => ['digits:9', 'unique:restaurants'],
                ]);
            $validator->sometimes('end_time', ['digits:6','gt:' . $request->start_time], function ($request) {
                return $request->start_time;
            });
            $validator->validate();
            if (request()->hasFile('image')) {
                $upload = new UploadImage();
                $parameters['image'] = $upload->trim($request->all());

            } else {
                $parameters['image'] = null;
            }

            $restaurant->update($request->all());

            DB::commit();
            return response()->json($restaurant, 200);
        } catch (Exception $e) {
            DB::rollBack();
        }

    }

    function filter(Request $request)
    {
        $validator = Validator::make($request->toArray(),
            [
                'start_time' => 'digits:6',
                'end_time' => 'digits:6',
                'only_remaining' => 'boolean',
                'user_north_latitude' => ['numeric', 'required_with:search_range'],
                'user_east_longitude' => ['numeric', 'required_with:search_range'],
                'search_range' => ['numeric'],
                'class' => 'array',
                'class.*' => 'required_with:class',
            ]);

        $validator->sometimes('end_time', 'digits:6|gt:' . $request->start_time, function ($input) {
            return isset($input->start_time);
        });

        $validator->validate();

        if (isset($request['class'])) {
            foreach ($request['class'] as $class) {
                if ($class == null) {
                    $request['class'] = [];
                }
            }
        }

//        if(empty($request['class'][0])){
//            return response()->json('array class cannot be null', 400);
//        }

        $conditions = [
            "start_time" => 0000,
            "end_time" => 2400,
            "only_remaining" => false,
            "user_north_latitude" => 23,
            "user_east_longitude" => 120,
            "search_range" => 100000,
            "class" => []
        ];

        foreach ($conditions as $key => $value) {
            if (isset($request->{$key})) {
                $conditions[$key] = $request[$key];
            }
        }

        if ($conditions['only_remaining']) {
            $result = Restaurant::
            withCount('likes')
                ->with('foods')
                ->whereHas('foods', function ($query) use ($conditions) {
                    $minimal = $conditions['only_remaining'] ? 1 : 0;
                    $query->where('remaining', '>=', $minimal);
                })
                ->when(!empty($conditions['class']), function ($query) use ($conditions) {
                    $classes = $conditions['class'];
                    $query->whereIn('class', $classes);
                })
                ->get()
                ->toArray();
        } else {
            $result = Restaurant::
            withCount('likes')
                ->with('foods')
                ->when(!empty($conditions['class']), function ($query) use ($conditions) {
                    $classes = $conditions['class'];
                    $query->whereIn('class', $classes);
                })
                ->get()
                ->toArray();
        }

        $resultWithDistance = array_map(function ($restaurant) use ($conditions) {
            $calculateDistance = new calculateDistance();
            $restaurant['distance'] =
                $calculateDistance->getDistance(
                    $conditions['user_north_latitude'],
                    $conditions['user_east_longitude'],
                    $restaurant['north_latitude'],
                    $restaurant['east_longitude']
                );
            return $restaurant;
        }, $result);


        $filted = array_filter($resultWithDistance, function ($restaurant) use ($conditions) {
            return ($restaurant['distance'] <= $conditions['search_range']);
        });


        $filted = array_filter($filted, function ($restaurant) use ($conditions) {
            if ($conditions['start_time'] < $restaurant['start_time']) {
                return ($conditions['end_time'] > $restaurant['start_time']);
            } else {
                return ($conditions['start_time'] < $restaurant['end_time']);
            }
        });

        if (!empty($filted)) {
            return response()->json(array_values($filted));
        } else {
            return response()->json('no restaurant found within the specified conditions', 400);
        }


    }

    function look($id)
    {
        return response()->json(Restaurant::with('foods')->find($id));
    }

    function score(Request $request)
    {
        $request->validate([
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'send' => ['required', 'boolean'],
        ]);

        if (Like::where('user_id', Auth::user()->id)->where('restaurant_id', $request->restaurant_id)->count()) {

            $like = Like::where('user_id', Auth::user()->id)->where('restaurant_id', $request->restaurant_id)->first();
            $like->update([
                'send' => $request->send,
            ]);
            return response()->json($like);

        } else {
            $create = Like::create([
                'user_id' => Auth::user()->id,
                'restaurant_id' => $request->restaurant_id,
                'send' => $request->send,

            ]);
            return response()->json($create);
        }


    }


}
