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




Route::resource('home', 'HomeController');
Route::resource('/', 'HomeController');
Route::resource('post', 'PostController');

Route::resource('category', 'CategoryController');
Route::resource('product_dimensions', 'ProductDimensionsController');
Route::resource('product', 'ProductController');
Route::resource('client', 'ClientController');

Route::resource('po', 'PurchaseController');
Route::get('/get_products', 'PurchaseController@getProductsbyAjax');

Route::resource('courier', 'CourierController');
Route::resource('stock', 'StockController');



