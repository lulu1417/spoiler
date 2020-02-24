<?php

namespace App\Http\Controllers;

use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RestaurantController extends Controller
{

    function index()
    {


        $restaurants = Restaurant::with('foods')->withCount('foods')->get();
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
            $request->validate([
                'name' => ['required', 'unique:restaurants'],
                'class' => 'required',
                'east_longitude' => ['required', 'numeric'],
                'north_latitude' => ['required', 'numeric'],
                'start_time' => ['required', 'digits:6'],
                'end_time' => ['required', 'digits:6', 'gte:' . $request->start_time],
                'link' => ['required', 'unique:restaurants'],
                'address' => ['required', 'unique:restaurants'],
                'image' => ['sometimes', 'mimes:png, jpg, jpeg, bmp'],
                'phone' => ['required', 'digits:9', 'unique:restaurants'],
            ]);

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
                'assessment' => 0,
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



    function filter(Request $request)
    {
        $request->validate([
            'start_time' => 'digits:6',
            'end_time' => ['digits:6','gte:' . $request->start_time],
            'only_remaining' => 'boolean',
            'user_north_latitude' => 'numeric',
            'user_east_longitude' => 'numeric',
            'search_range' => 'numeric',
            'class' => 'array',
            //TODO check array is valid
        ]);

        if(isset($request['class'])){
            foreach ($request['class'] as $class) {
                if($class == null){
                    $request['class'] = [];
                }
            }
        }


//        if(empty($request['class'][0])){
//            return response()->json('array class cannot be null', 400);
//        }

        $conditions = [
            "start_time" => 0000000,
            "end_time" => 240000,
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


        $filted  = Restaurant::
            with('foods')
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



        $filted = array_map(function ($restaurant) use ($conditions) {
            $calculateDistance = new calculateDistance();
            $restaurant['distance'] =
                $calculateDistance->getDistance(
                    $conditions['user_north_latitude'],
                    $conditions['user_east_longitude'],
                    $restaurant['north_latitude'],
                    $restaurant['east_longitude']
                );
            if($restaurant['distance'] < $conditions['search_range'])
            return $restaurant;
        }, $filted);


        $filted = array_filter($filted, function ($restaurant) use ($conditions) {
            if ($conditions['start_time'] < $restaurant['start_time'] && $conditions['end_time'] > $restaurant['start_time']) {
                return true;
            } elseif($conditions['start_time'] < $restaurant['end_time']) {
                return true;
            }
        });

        if (!empty($filted)) {
            return response()->json($filted);
        } else {
            return response()->json('no restaurant found within the specified conditions', 400);
        }


    }

    function look($id)
    {
        return response()->json(Restaurant::with('foods')->find($id)->get());
    }


}
