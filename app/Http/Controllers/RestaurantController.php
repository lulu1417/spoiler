<?php

namespace App\Http\Controllers;

use App\BusinessHour;
use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use function MongoDB\BSON\toJSON;

class RestaurantController extends Controller
{
    function store(Request $request){

        try {
            DB::beginTransaction();

            $request->validate([
                'owner_id' => ['required', 'exists:owners,id'],
                'name' => ['required', 'unique:restaurants'],
                'coordinate' => 'required',
                'business_hours' => ['required'],
                'link' => 'required',
                'image' => ['sometimes', 'mimes:png, jpg, jpeg, bmp'],
                'phone' => ['required', 'digits:9'],
            ]);

            if (request()->hasFile('image')) {
                $upload = new UploadImage();
                $parameters['image'] = $upload->trim($request->all());

            } else {
                $parameters['image'] = null;
            }

            $data['restaurant'] = Restaurant::create([
                'name' => $request->name,
                'coordinate' => $request->coordinate,
                'link' => $request->link,
                'address' => $request->address,
                'image' => $parameters['image'],
                'assessment' => 0,
                'phone' => $request->phone,
                'owner_id' => $request->owner_id,
            ]);

            $data['distance'] = 'calculated_distance';

            foreach ($request->business_hours as $business_hour) {
                 $data['business_hour'] = BusinessHour::create([
                    'restaurant_id' => $data['restaurant']->id,
                    'week_day' => $business_hour['week_day'],
                    'period' => $business_hour['period'],
                    'start_time' => $business_hour['start_time'],
                    'end_time' => $business_hour['end_time'],
                ]);
            }
            DB::commit();
            return response()->json($data, 200);

        }catch (Exception $error) {

            DB::rollback();
            return response()->json("create restaurant failed", 400);
        }

    }

    function search(Request $request)
    {

        if ($request->search) {
            $result['restaurant'] = Restaurant::like('name', $request->restaurant)->get();
            $result['food'] = Food::like('name', $request->food)->get();
        }

        //distance
        //$user_coordinate = getGoogleMapCoordinate;
//        $restaurants = Restaurant::all();
//        foreach($restaurants as $restaurant){
//            //calculate distance
//            if($restaurant['distance'] < 1){
//                $result[] = $restaurant;
//            }
//        }

        return response()->json($result);

    }

    function index(){
        return response()->json(Restaurant::all());
    }

    function look($id){
        return response()->json(Restaurant::find($id)->food()->get());
    }
}
