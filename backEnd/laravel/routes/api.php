<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Auth', 'prefix' => 'v1', 'as' => 'auth.'], function () {
    Route::post('oauth/token', [AuthController::class, 'login'])->name('auth');
    Route::get('oauth/{driver}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirectToProvider']);
    Route::get('oauth/{driver}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'handleProviderCallback']);
});
