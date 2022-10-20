<?php

use Illuminate\Support\Facades\Route;

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




Route::get("login", "AuthController@login")->name("login");
Route::post("login", "AuthController@auth");

Route::group([
    "middleware"    => "auth"
], function(){
    // -------------------------------------------------------
    // User
    // -------------------------------------------------------
    Route::get("users", "UserController@index");
    Route::get("get_users", "UserController@get_users");
    Route::get("logout", "AuthController@logout");
    Route::post("create_user", "UserController@create_user")->name("create_user");
    Route::get("delete_user/{id}", "UserController@delete")->name("delete_user");
    Route::get("restore_user/{id}", "UserController@restore")->name("restore_user");
    // -------------------------------------------------------

    // -------------------------------------------------------
    // Tour
    // -------------------------------------------------------
    Route::get("tour/create", "TourController@form")->name("create_tour");
    Route::get("tour/{id}/delete", "TourController@delete")->name("delete_tour");
    Route::get("tour/{id}/edit", "TourController@form")->name("edit_tour");
    Route::get("tours", "TourController@index")->name("tours");
    Route::post("tour", "TourController@store")->name("store_tour");
    Route::post("tour/{id}/update", "TourController@store")->name("update_tour");
    Route::get("categories", "TourController@index_category")->name("index_category");
    Route::get("get_categories", "TourController@get_categories")->name("get_categories");
    Route::POST("store_category", "TourController@store_category")->name("store_category");
    Route::get("delete_category/{id}", "TourController@delete_category")->name("delete_category");
    Route::get("logs", "TourController@logs")->name("logs");
    Route::get("get_logs", "TourController@get_logs")->name("get_logs");
    // -------------------------------------------------------

    // -------------------------------------------------------
    // Content
    // -------------------------------------------------------
    Route::get("contents", "ContentController@index")->name("contents");
    Route::post("content", "ContentController@store")->name("store_content");
    Route::get("content/{id}/delete", "ContentController@destroy")->name("destroy_content");
    // -------------------------------------------------------

    // -------------------------------------------------------
    // Transactions
    // -------------------------------------------------------
    Route::get("transactions", "TransactionController@index")->name("index");
    Route::get("get_transactions", "TransactionController@get_transactions")->name("get_transactions");

    // Quick search dummy route to display html elements in search dropdown (header search)
    Route::get('/quick-search', 'PagesController@quickSearch')->name('quick-search');
});
