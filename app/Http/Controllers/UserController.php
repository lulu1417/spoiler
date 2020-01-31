<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Validator; //驗證器
use Mail; //寄信

class UserController extends Controller
{
    //Facebook登入


    function store(Request $request){
        date_default_timezone_set('Asia/Taipei');
        $request->validate([
                'name' => 'required',
                'email' => ['required', 'unique:users'],
                'password' => ['required', 'min:6', 'max:12'],
            ]);
        $create = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => 'logout',
        ]);

        if($create){
            return response()->json($create,200);
        }else{
            return response()->json('register failed',400);
        }

    }
    function login(Request $request){
        $user = User::where('email', $request->email)->first();
        if(Hash::check($request->password, $user->password)){
            $user = $user->update([
               'api_token' => Str::random(10),
            ]);
            return response()->json($user,200);
        }else{
            return response()->json('wrong password',400);
        }
    }

    function test(){

        //row

        $layer = 8;
        $length = 9;

        $star = 5;
        $row = 0;
        while($row < 3) {

            //middle
            if($row == 1){
                $arr[$row][$length/2] = '*';
            }else{
                $arr[$row][$length/2] = ' ';
            }

            //col
            for ($add = 1; $add < $star/2; $add++) {
                $arr[$row][4 + $add] = '*';
                $arr[$row][4 - $add] = '*';
            }

            while($add < $length/2){
                $arr[$row][$length/2+$add] = ' ';
                $arr[$row][$length/2-$add] = ' ';
                $add++;
            }

            $row ++;
            $star += 2;

        }
        $star = 9;
        while($row < $layer) {

            //middle
            $arr[$row][$length/2] = '*';

            //col
            for ($add = 1; $add < $star/2; $add++) {
                $arr[$row][4 + $add] = '*';
                $arr[$row][4 - $add] = '*';
            }

            while($add < $length/2){
                $arr[$row][$length/2+$add] = ' ';
                $arr[$row][$length/2-$add] = ' ';
                $add++;
            }

            $row ++;
            $star -= 2;

        }

        return response()->json($arr);

    }
}
