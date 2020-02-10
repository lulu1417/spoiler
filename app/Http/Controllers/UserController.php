<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator; //驗證器
use Mail; //寄信
use App\Http\Controllers\ErrorResponse;

class UserController extends Controller
{

    function store(Request $request){
        date_default_timezone_set('Asia/Taipei');
        $request->validate([
                'name' => 'required',
                'account' => ['required', 'unique:users'],
                'email' => ['sometimes', 'email'],
                'password' => ['required', 'between:6,12'],
                'phone' => ['required', 'digits:10']
            ]);

        $create = User::create([
            'name' => $request->name,
            'account' => $request->account,
            'email' => $request->account,
            'password' => Hash::make($request->password),
            'api_token' => 'logout',
            'point' => 0,
            'phone' => $request->phone,
        ]);

        if($create){
            return response()->json($create,200);
        }else{
            return ErrorResponse::sendError('register failed',1 , 400);
        }

    }
    function login(Request $request){
        date_default_timezone_set('Asia/Taipei');
        if(count(User::where('account', $request->account)->get()->toArray()) > 0){
            $user = User::where('account', $request->account)->first();
            if(Hash::check($request->password, $user->password)){
                $user->update([
                    'api_token' => Str::random(20),
                ]);
                return response()->json($user,200);
            }else{
                return ErrorResponse::sendError('wrong password',3 , 400);
            }
        }else{
            return ErrorResponse::sendError('account not found',2, 400);
        }
    }

    function update(Request $request, $id){
        date_default_timezone_set('Asia/Taipei');
        $request->validate([
            'phone' => 'digits:10',
        ]);

        $user = User::find($id)->first();
        $user->update([
           $request->all(),
        ]);

        return response()->json($user);

    }
}
