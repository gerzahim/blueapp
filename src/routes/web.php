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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/', 'HomeController');
Route::resource('post', 'PostController');

Route::resource('category', 'CategoryController');
Route::resource('product_dimensions', 'ProductDimensionsController');
Route::resource('product', 'ProductController');
Route::resource('client', 'ClientController');
Route::resource('vendor', 'VendorController');

/** Purchases  **/
Route::resource('purchases', 'PurchaseController');
Route::get('/editPurchase/{id}', [
    'uses' => 'PurchaseController@editPurchase',
    'as' => 'purchases.editPurchase'
]);

/** Sorted  **/
Route::get('/clients_sorted', 'ClientController@sortedByName');
Route::get('/vendors_sorted', 'VendorController@sortedByName');
Route::get('/products_sorted', 'ProductController@sortedByName');


/** JSON RESPONSE  **/
Route::get('/get_products', 'PurchaseController@getProductsbyAjax');
Route::get('/get_vendors', 'PurchaseController@getVendorsbyAjax');
Route::get('/get_clients', 'PurchaseController@getClientsbyAjax');
Route::get('/get_clientsDT', 'PurchaseController@getClientsDT');
Route::get('/get_couriers', 'PurchaseController@getCouriersbyAjax');

/** Orders  **/
Route::resource('order', 'OrderController');
Route::get('/get_purchases_items', 'OrderController@getPurchasesItemsbyAjax');

Route::resource('courier', 'CourierController');
Route::resource('stock', 'StockController');


Route::get('/rma', 'HomeController@getUnderConstruction');
Route::get('/borrow', 'HomeController@getUnderConstruction');
Route::get('/lend', 'HomeController@getUnderConstruction');
Route::get('/refurbishment', 'HomeController@getUnderConstruction');


Route::get('/getsample', 'HomeController@getSample');



