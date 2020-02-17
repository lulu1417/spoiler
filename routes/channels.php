<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Food;
use App\Restaurant;
use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('App.User.{id}', function ($user, $id) {
//    return (int) $user->id === (int) $id;
//});

Broadcast::channel('food', function () {
    return true;
});

Broadcast::channel('subscript.{restaurantId}', function ($user, $restaurantId) {
    return $user->id === Restaurant::with('subscriptUser')->findOrNew($restaurantId)->user_id;
});
