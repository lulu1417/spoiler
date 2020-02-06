<?php

namespace App\Http\Controllers;

use App\User;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FBController extends Controller
{
    public static function store(Request $request)
    {

        try {
            $fb = new facebookResource();
            $resource = $fb->getFacebookResource($request->token);
            $create = User::create([
                'name' => $resource['name'],
                'account' => $resource['id'],
                'password' => 'facebook',
                'api_token' => 'logout',
                'point' => 0,
            ]);
            return response()->json($create);
        } catch (FacebookSDKException $e) {
            return response()->json(' Failed to Get Facebook Resources',400);
        }
    }

    public static function login(Request $request)
    {
        try {
            $fb = new facebookResource($request->token);
            $resource = $fb->getFacebookResource($request->token);
            $user = User::where('account', $resource['id'])->first();
            $user->update([
                    'api_token' => Str::random(20),
            ]);
                return response()->json($user,200);

        } catch (FacebookSDKException $e) {
            return response()->json(' Failed to Get Facebook Resources',400);
        }
    }


}
