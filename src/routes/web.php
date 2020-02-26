<?php

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





Route::resource('post', 'PostController');

Route::resource('category', 'CategoryController');
Route::resource('product_dimensions', 'ProductDimensionsController');
Route::resource('product', 'ProductController');
Route::resource('client', 'ClientController');

Route::resource('po', 'PurchaseController');
Route::resource('courier', 'CourierController'); # /routes/web.php



/*
Route::get('welcome', function () {
    return view('layouts.home');
});
Route::get('/', function () {
    return view('welcome');
});
Route::post('/postcreate', [
	'uses' => 'PurchaseController@getqtyProducts',
	'as' => 'postcreate'
]);
*/