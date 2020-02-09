<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{

    public function sendError($error, $number ,$code)
    {
        $response = [
            'success' => false,
            'message' => $error,
            'code' => $number
        ];
        return response()->json($response, $code);
    }

}
