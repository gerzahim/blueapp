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

/** Purchases  **/
Route::resource('purchases', 'PurchaseController');
Route::get('/editPurchase/{id}', [
    'uses' => 'PurchaseController@editPurchase',
    'as' => 'purchases.editPurchase'
]);
Route::get('/get_products', 'PurchaseController@getProductsbyAjax');
Route::get('/get_vendors', 'PurchaseController@getVendorsbyAjax');
Route::get('/get_clients', 'PurchaseController@getClientsbyAjax');
Route::get('/get_couriers', 'PurchaseController@getCouriersbyAjax');

/** Orders  **/
Route::resource('order', 'OrderController');

Route::resource('courier', 'CourierController');
Route::resource('stock', 'StockController');





