<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($errorMessage, $code = 400)
    {
        $response['message'] = $errorMessage;
        return response()->json($response, $code);
    }
}
