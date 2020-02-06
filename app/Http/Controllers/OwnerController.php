<?php

namespace App\Http\Controllers;

use App\Food;
use App\Owner;
use App\Restaurant;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\uploadImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerController extends Controller
{
    function store(Request $request){
        date_default_timezone_set('Asia/Taipei');
        $request->validate([
            'name' => 'required',
            'account' => ['required', 'unique:users'],
            'password' => ['required', 'min:6', 'max:12'],
        ]);
        $create = Owner::create([
            'name' => $request->name,
            'account' => $request->account,
            'password' => Hash::make($request->password),
            'api_token' => 'logout',
        ]);

        if($create){
            return response()->json($create,200);
        }else{
            return response()->json('register failed',400);
        }

    }
    function login(Request $request){
        $user = Owner::where('account', $request->account)->first();
        if(Hash::check($request->password, $user->password)){
            $user = $user->update([
                'api_token' => Str::random(10),
            ]);
            return response()->json($user,200);
        }else{
            return response()->json('wrong password',400);
        }
    }
}
