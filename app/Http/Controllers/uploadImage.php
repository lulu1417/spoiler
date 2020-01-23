<?php


namespace App\Http\Controllers;


use http\Env\Request;

class uploadImage
{
    function trim(Request $request){

        $imageURL = request()->file('image')->store('public');
        $parameters['image'] = substr($imageURL, 7);
        $result['imageURL'] = asset('storage/' . $parameters['image']);

        return $result['imageURL'];
    }

}
