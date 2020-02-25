<?php

namespace App\Http\Controllers;

use App\User;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Illuminate\Support\Str;

class FBController extends Controller
{

    public function fbCallback()
    {
        session_start(); //to deal with CSRF
        $fb = new Facebook([
            'app_id' => env('FB_CLIENT_ID'),
            'app_secret' => env('FB_CLIENT_SECRET'),
            'default_graph_version' => 'v3.2',
        ]);

        $helper = $fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            return response()->json('Graph returned an error: ' . $e->getMessage(), 400);
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            return response()->json('Facebook SDK returned an error: ' . $e->getMessage(), 400);
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                return response()->json(
                    "Error: " . $helper->getError() . "\n".
                    "Error Code: " . $helper->getErrorCode() . "\n".
                    "Error Reason: " . $helper->getErrorReason() . "\n".
                    "Error Description: " . $helper->getErrorDescription() . "\n"
                    , 401);
            } else {
                return response()->json('Bad request', 400);
            }
        }
        $login = $this->login($accessToken);
        return response()->json($login);

    }

    public static function login($token)
    {
        date_default_timezone_set('Asia/Taipei');
        $fb = new Facebook([
            'app_id' => env('FB_CLIENT_ID'),
            'app_secret' => env('FB_CLIENT_SECRET'),
            'default_graph_version' => 'v3.2',
        ]);

        $endpoint = env('FBEndpoint');

        $response = $fb->get($endpoint, $token);

        $resource = $response->getGraphUser();
        if (count(User::where('account', $resource['id'])->get()->toArray()) == 0) {
            $create = User::create([
                'name' => $resource['name'],
                'access_token' => $token,
                'account' => $resource['id'],
                'password' => 'facebook',
                'api_token' => Str::random(20),
                'image' => $resource['picture']['url'],
                'point' => 0,
                'bad_record' => 0,
            ]);
            return $create;

        } else {
            $user = User::where('account', $resource['id'])->first();
            $user->update([
                'api_token' => Str::random(20),
            ]);
            return $user;
        }

    }
}
