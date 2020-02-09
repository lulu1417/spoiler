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
    public static function login(Request $request)
    {
        try {
            $base = new BaseController();
            $fb = new FacebookResource();
            if(!$request->token){
              return $base->sendError("You must provide an access token", 1, 400);
            }
            $resource = $fb->getFacebookResource($request->token);
            if(count(User::where('account', $resource['id'])->get()->toArray()) == 0){
                if($resource){
                    $create = User::create([
                        'name' => $resource['name'],
                        'account' => $resource['id'],
                        'password' => 'facebook',
                        'api_token' => Str::random(20),
                        'image' => $resource['picture']['url'],
                        'point' => 0,
                    ]);
                    return response()->json($create);
                }else{
                    return $base->sendError("wrong token", 2, 400);
                }

            }else{
                $user = User::where('account', $resource['id'])->first();
                $user->update([
                    'api_token' => Str::random(20),
                ]);
                return response()->json($user,200);
            }

            return response()->json($resource);
        } catch (FacebookSDKException $e) {
            return response()->json(' Failed to Get Facebook Resources',400);
        }
    }

}
