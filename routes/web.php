<?php

use App\Product;

Route::get ('/','ProductController@getIndex');
Route::get ('/product/{id}','ProductController@show');
Route::get ('/checkout','CartController@checkout');
Route::post('/cart/add','CartController@postAddToCart');
Route::post('/cart/remove','CartController@removeItem');
Route::get ('/cart/delete','CartController@emptyCart');
Route::post('/pay','PaymentsController@paymentVerify');
Route::get ('/orders','OrdersController@getIndex');
Route::any ('/order/{id}','OrdersController@show');
Route::any ('/order','OrdersController@showBTC');
Route::any ('/BTCorders','OrdersController@getBTCOrders');
Route::any ('/cgcallback','PaymentsController@callback');
Route::get ('/logout', ['uses' => 'Auth\LoginController@logout'])->name('logout');
Route::get ('/home', 'HomeController@index')->name('home');
Route::get ('/login', function()
{
    return view('/auth/login');
});
Route::get ('/register', function()
{
    return view('/auth/register');
});
Auth::routes();
