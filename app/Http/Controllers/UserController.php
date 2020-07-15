<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Validator;

//驗證器
use Mail;

//寄信

class UserController extends BaseController
{

    function store(Request $request)
    {
        date_default_timezone_set('Asia/Taipei');
        $request->validate([
            'name' => 'required',
            'account' => ['required', 'unique:users'],
            'email' => ['sometimes', 'email'],
            'password' => ['required', 'between:6,12'],
        ]);

        $create = User::create([
            'name' => $request->name,
            'account' => $request->account,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(20),
            'point' => 0,
            'bad_record' => 0,
            'access_token' => 0

        ]);

        if ($create) {
            return response()->json($create, 200);
        } else {
            return $this->sendError('register failed', 400);
        }

    }

    function login(Request $request)
    {
        date_default_timezone_set('Asia/Taipei');
        if (count(User::where('account', $request->account)->get()->toArray()) > 0) {
            $user = User::where('account', $request->account)->first();
            if (Hash::check($request->password, $user->password)) {
                $user->update([
                    'api_token' => Str::random(20),
                ]);
                return response()->json($user, 200);
            } else {
                return $this->sendError('wrong password', 400);
            }
        } else {
            return $this->sendError('account not found', 400);
        }
    }

    function update(Request $request, $id)
    {
        date_default_timezone_set('Asia/Taipei');

        if (count(User::where('id', $id)->get()->toArray()) > 0) {
            $request->validate([
                'account' => ['unique:users'],
                'email' => ['email'],
                'password' => ['between:6,12'],
            ]);
            $user = User::find($id);
            $user->update(
                $request->all()
            );
            return response()->json($user);
        } else {
            return $this->sendError('user id not found', 400);
        }

    }

    function logout($id)
    {
        if (count(User::where('id', $id)->get()->toArray()) > 0) {
            $user = User::find($id);
            $user->update([
                'api_token' => 'logout'
            ]);
            return response()->json($user);
        } else {
            return $this->sendError('user id not found', 400);
        }
    }

    function look($id)
    {
        if (count(User::where('id', $id)->get()->toArray()) > 0) {
            return response()->json(User::find($id)->first());
        } else {
            return $this->sendError('user id not found', 400);
        }
    }

    function getSubscription()
    {
        $subrestaurants = User::with('subscriptRestaurant')->get();
        return response()->json($subrestaurants);
    }

    function all()
    {
        return response()->json(User::all());
    }

}
