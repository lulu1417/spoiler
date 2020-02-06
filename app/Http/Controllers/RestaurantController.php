<?php

namespace App\Http\Controllers;

use App\BusinessHour;
use App\OwnerRestaurant;
use App\Restaurant;
use App\Type;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    function store(Request $request){
        $request->validate([
            'owner_id' => ['required', 'exists:owners'],
            'name' => ['required', 'unique:restaurants'],
            'class' => 'required',
            'coodinate' => 'required',
            'business_hours' => 'required',
            'link' => 'required',
            'image' => ['sometimes', 'mimes:png, jpg, jpeg, bmp'],
        ]);

        if (request()->hasFile('image')) {
            $upload = new UploadImage();
            $parameters['image'] = $upload->trim($request->all());

        } else {
            $parameters['image'] = null;
        }

        $create = Restaurant::create([
            'name' => $request->name,
            'coodinate' => $request->coordinate,
            'link' => $request->link,
            'image' => $parameters['image'],
            'assessment' => 0,
        ]);

        OwnerRestaurant::create([
            'owner_id' => $request->owner_id,
            'restaurant_id' => $create->id,
        ]);

        foreach ($request->business_hours as $business_hour){
            BusinessHour::create([
                'restaurant_id' => $create->id,
                'week_day' => $business_hour['week_day'],
                'period' => $business_hour['period'],
                'start_time' => $business_hour['start_time'],
                'end_time' => $business_hour['end_time'],
            ]);
        }

        if($request->type){
            Type::create([
                'name' => $request->type,
                'restaurant_id' => $create->id,
            ]);

        }
        return response()->json($create, 200);

    }

    function index(){
        return response()->json(Restaurant::all());
    }
}
