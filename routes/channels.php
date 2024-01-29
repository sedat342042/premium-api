<?php

use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('presence-user', function ($user){
  \Log::debug(["h"]);
  return $user;
});
/*
Broadcast::channel('user.{userId}', function ($user, $userId) {
  if ($user->id === $userId) {
    return ['id' => $user->id, 'first_name' => $user->first_name]; 
  }
});
*/

Broadcast::channel('user.{userId}', function ($user, $userId) {
  //\Log::debug([$user->id, $userId]);
  if ( (int) $user->id === (int) $userId) {
    //\Log::debug(['id' => $user->id, 'first_name' => $user->first_name]);
    return ['id' => $user->id, 'first_name' => $user->first_name]; 
  }
});

    Broadcast::channel('updates', function ($user) {
      if (auth()->check()) {
        return $user->toArray();
    }
    });
    
    Broadcast::channel('online', function ($user) {
        if (auth()->check()) {
            return $user->toArray();
        }
    });
