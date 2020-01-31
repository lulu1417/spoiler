<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FBController extends Controller
{
    function getFacebookResource(){
        require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

        $fb = new \Facebook\Facebook([
            'app_id' => env('FB_CLIENT_ID'),
            'app_secret' => env('FB_CLIENT_SECRET'),
            'default_graph_version' => 'v5.7',
        ]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $fb->get('/me', '{access-token}');
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();
        echo 'Logged in as ' . $me->getName();
    }
}
