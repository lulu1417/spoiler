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

        $restaurants = Restaurant::all()->toArray();

        $data = array_map(function ($restaurant) {
            $restaurant['number'] = count($restaurant['food'] = Restaurant::find($restaurant['id'])->foods);
            return $restaurant;
        }, $restaurants);

        return response()->json($data);

    }

    function store(Request $request){

        try {
            DB::beginTransaction();
            $request->validate([
                'name' => ['required', 'unique:restaurants'],
                'class' => 'required',
                'coordinate' => 'required',
                'start_time' => ['required', 'digits:6'],
                'end_time' => ['required', 'digits:6'],
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

            $data['restaurant'] = Restaurant::create([
                'name' => $request->name,
                'class' => $request->class,
                'coordinate' => $request->coordinate,
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
            return response()->json($data, 200);

        }catch (Exception $error) {

            DB::rollback();
            return response()->json("create restaurant failed", 400);
        }

    }

    function distanceCalculate($userCoordinate, $distanceScope)
    {

        //distance
        $restaurants = Restaurant::all();
        foreach($restaurants as $restaurant){
            //calculate distance
            $restaurant['distance'] = calculateDistance($restaurant->coordinate, $userCoordinate);
            if($restaurant['distance'] < $distanceScope){
                $result[] = $restaurant;
            }
        }

        return response()->json($result);

    }

    function calculateOverlappedTime($userStartTime, $userEndTime){

    }


    function look($id){
        return response()->json(Restaurant::find($id)->food()->get());
    }
}
