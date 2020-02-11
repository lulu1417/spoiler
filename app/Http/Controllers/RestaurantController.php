<?php

namespace App\Http\Controllers;

use App\BusinessHour;
use App\Food;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantController extends Controller
{
    function store(Request $request){

        try {
            DB::beginTransaction();
            $request->validate([
                'name' => ['required', 'unique:restaurants'],
                'class' => ['required','unique:restaurants'],
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

    function search(Request $request)
    {

        if ($request->search) {
            $result['restaurant'] = Restaurant::where('name', "like", "%".$request->search."%")->get();
            $result['food'] = Food::where('name', "like", "%".$request->search."%")->get();
            return response()->json($result);
        }else{
            return response()->json(["message" =>'You must provide an keyword for searching'], 400);
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



    }

    function index(){
        return response()->json(Restaurant::all());
    }

    function look($id){
        return response()->json(Restaurant::find($id)->food()->get());
    }
}
