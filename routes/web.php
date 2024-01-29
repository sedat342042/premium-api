<?php

use Pusher\Pusher;
use App\Events\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController; 
use App\Http\Controllers\RolesController; 
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\PermissionsController; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['auth']], function(){
    
    Route::get('/test', function (Request $request) {
        broadcast(new UserStatus(auth()->user()))->toOthers();
        //event(new UserStatus(auth()->user()));
        return auth()->user();
    });
    // Create a new Pusher instance.
    $pusher = new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options')
    );

    // Get the list of users in the presence channel.
    $channels = $pusher->getChannels();

    return $channels;
   
});


Route::get('/', function () {
    return view('welcome');
});



Auth::routes(['verify' => true]);

Route::group(['middleware' => ['auth', 'verified']], function() {       
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    /**
     * User Routes
     */
    Route::group(['middleware' => ['permission']], function() {    
        Route::group(['prefix' => 'users', 'as'=>'users.'], function() {        
            Route::resource('/roles', RolesController::class);
            Route::resource('/permissions', PermissionsController::class);
        });
        Route::resource('users', UsersController::class)->except('show');
    });
    Route::get('users/show/{id}', [UsersController::class, 'show'])->name('users.show');
    Route::get('users/profile', [UsersController::class, 'show'])->name('users.profile');
});