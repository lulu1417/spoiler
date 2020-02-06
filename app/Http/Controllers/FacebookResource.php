<?php


namespace App\Http\Controllers;


use App\User;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

class FacebookResource
{
    function getFacebookResource($token){

        date_default_timezone_set('Asia/Taipei');
        $fb = new Facebook([
            'app_id'                => env('FB_CLIENT_ID'),
            'app_secret'            => env('FB_CLIENT_SECRET'),
            'default_graph_version' => 'v3.2',
        ]);
        $endpoint = env('FBEndpoint');
        try {
            $response = $fb->get($endpoint, $token);

        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            return false;
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        try {

            return $response->getGraphUser();
        } catch (FacebookSDKException $e) {
            return response()->json(' Failed to Get Facebook Resources',400);
        }

    }

}
