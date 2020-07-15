<?php

namespace App\Http\Controllers;

use App\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\uploadImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerController extends BaseController
{
    function store(Request $request)
    {
        date_default_timezone_set('Asia/Taipei');

        $request->validate([
            'name' => 'required',
            'account' => ['required', 'unique:owners'],
            'password' => ['required', 'between:6,12'],
            'phone' => ['required', 'digits:10'],
        ]);

        $create = Owner::create([
            'name' => $request->name,
            'account' => $request->account,
            'password' => Hash::make($request->password),
            'api_token' => Str::random(20),
            'phone' => $request->phone,

        ]);

        if ($create) {
            return response()->json($create, 200);
        } else {
            return $this->sendError('register failed', 400);
        }

    }

    function login(Request $request)
    {
        if (count(Owner::where('account', $request->account)->get()->toArray()) > 0) {
            $owner = Owner::where('account', $request->account)->first();
            if (Hash::check($request->password, $owner->password)) {
                $owner->update([
                    'api_token' => Str::random(20),
                ]);
                return response()->json($owner, 200);
            } else {
                return $this->sendError('wrong password', 400);
            }
        } else {
            return $this->sendError('account not found', 400);
        }
    }

    function all(){
        return response()->json(Owner::all());
    }

}
