<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get("contents", "ContentController@contents");
Route::get("recommends", "TourController@recommends");
Route::get("tours", "TourController@tours");
Route::post("tour/{tour_id}/transaction", "TourController@create_transaction");
Route::post("notification", "TourController@handleNotification");


