<?php


namespace App\Http\Controllers;


use http\Env\Request;

class UploadImage
{
    function trim($imageURL){
        
        $parameters['image'] = substr($imageURL, 7);
        $result['imageURL'] = asset('storage/' . $parameters['image']);

        return $result['imageURL'];
    }

}
