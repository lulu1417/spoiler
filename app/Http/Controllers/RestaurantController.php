<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    function store(Request $request){
        $request->validate([
            'name' => ['required', 'unique:restaurants'],
            'class' => 'required',
            'coodinate' => 'required',
            'operating_time' => 'required',
            'link' => 'required',
            'image' => ['sometimes', 'mimes:png, jpg, jpeg, bmp'],
        ]);

        if (request()->hasFile('image')) {
            $upload = new uploadImage();
            $parameters['image'] = $upload->trim($request->all());

        } else {
            $parameters['image'] = null;
        }

        $create = Restaurant::create([
            'name' => $request->name,
            'coodinate' => $request->coordinate,
            'operating_time' => $request->operating_time,
            'link' => $request->link,
            'image' => $parameters['image'],
            'assessment' => 0,
        ]);
        return response()->json($create, 200);

    }

    function index(){
        return response()->json(Restaurant::all());
    }
}
