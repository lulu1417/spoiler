<?php

namespace App\Http\Controllers;

use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RestaurantController extends Controller
{

    function index(){

//        $response = DB::table('restaurants')
//            ->leftJoin('foods', 'restaurants.id', '=', 'foods.restaurant_id')
//            ->select( 'restaurants.*', 'foods.name as food_name', 'foods.original_price')->get();
//
//        return $response;

        $restaurants = Restaurant::with('foods')->withCount('foods')->get();
        return response()->json($restaurants);

    }
    function getSubscriptUsers(){
        $subUser = Restaurant::with('subscriptUser')->get();
        return response()->json($subUser);
    }

    function store(Request $request){

        try {
            DB::beginTransaction();
            $request->validate([
                'name' => ['required', 'unique:restaurants'],
                'class' => 'required',
                'east_longitude' => ['required','numeric'],
                'north_latitude' => ['required','numeric'],
                'start_time' => ['required', 'digits:6'],
                'end_time' => ['required', 'digits:6', 'gte:'.$request->start_time],
                'link' => ['required','unique:restaurants'],
                'address' => ['required','unique:restaurants'],
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

        }catch (Exception $error) {

            DB::rollback();
            return response()->json("create restaurant failed", 400);
        }

    }

    function distanceCalculate($user_north_latitude, $user_east_longitude, $search_range, $filted)
    {
        $calculateDistance = new calculateDistance();
        $result = array();
        foreach($filted as $restaurant){
            $restaurant['distance'] = $calculateDistance->getDistance($user_north_latitude, $user_east_longitude, $restaurant->north_latitude, $restaurant->east_longitude);
            if($restaurant['distance'] < $search_range ){
                $result[] = $restaurant;
            }
        }
        if($result){
            return $result;
        }else{
            return false;
        }

    }

    function calculateOverlappedTime($user_start_time, $user_end_time, $filted){
        $result = array();
        foreach($filted as $restaurant){
            if($user_start_time < $restaurant->start_time){
                if($user_end_time > $restaurant->start_time){
                    $result[] = $restaurant;
                }
            }elseif($user_start_time < $restaurant->end_time){
                $result[] = $restaurant;
            }
        }
        if($result){
            return $result;
        }else{
            return false;
//            return response()->json('no restaurant found within the specified time', 400);
        }

    }

    function filtClass($arrClass, $filted){
        if($arrClass){
            $result = array();
            foreach($filted as $restaurant){
                foreach ($arrClass as $class){
                    if($restaurant->class == $class){
                        $result[] = $restaurant;
                    }
                }
            }
            if($result){
                return $result;
            }else{
                return false;
//            return response()->json('no restaurant found within the specified class', 400);
            }
        }else{
            return $filted;
        }


    }

    function filter(Request $request){
        $request->validate([
            'start_time' => 'digits:6',
            'end_time' => ['digits:6', 'gte:'.$request->start_time],
            'only_remianing' => 'boolean',
            'user_north_latitude' => 'numeric',
            'user_east_longitude' => 'numeric',
            'search_range' => 'numeric',
        ]);
        $filted = Restaurant::withCount('foods')->get();
        if($request->user_north_latitude){
            $distance = $this->distanceCalculate($request->user_north_latitude, $request->user_east_longitude, $request->search_range, $filted);
        }else{
            $distance = $filted;
        }
        if($request->start_time && $distance){
            $time = $this->calculateOverlappedTime($request->start_time, $request->end_time, $distance);
        }else{
            $time = $distance;
        }
        if($request->class && $time){
            $filtClass = $this->filtClass($request->class, $time);
        }else{
            $filtClass = $time;
        }

        if($request->only_remaining && $filtClass){
            $only_remaining = array();
            foreach ($filtClass as $restaurant){
                if($restaurant->foods_count > 0) {
                    $only_remaining = $restaurant;
                }
            }
        }else{
            $only_remaining = $filtClass;
        }

        if($only_remaining){
            return $only_remaining;
        }else{
            return response()->json('no restaurant found within the specified conditions', 400);
        }


    }

    function look($id){
        return response()->json(Restaurant::with('foods')->find($id)->get());
    }


}
