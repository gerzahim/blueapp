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

Route::get('test', function () {
    $name = request('name');
    return $name;
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('/', 'HomeController');
Route::resource('post', 'PostController');
Route::get('/getsample', 'HomeController@getSample');
Route::get('/borrow', 'HomeController@getUnderConstruction');
Route::get('/lend', 'HomeController@getUnderConstruction');
Route::get('/refurbishment', 'HomeController@getUnderConstruction');


Route::resource('category', 'CategoryController');
Route::resource('product_dimensions', 'ProductDimensionsController');
Route::resource('product', 'ProductController');
Route::resource('client', 'ClientController');
Route::resource('vendor', 'VendorController');
Route::resource('rma', 'RMAController');
Route::resource('refurbishes', 'RefurbishesController');


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
Route::get('/get_products', 'ResponseController@getProductsbyAjax');
Route::get('/get_vendors', 'ResponseController@getVendorsbyAjax');
Route::get('/get_clients', 'ResponseController@getClientsbyAjax');
Route::get('/get_clientsDT', 'ResponseController@getClientsDT');
Route::get('/get_couriers', 'ResponseController@getCouriersbyAjax');
Route::get('/get_purchases_items', 'ResponseController@getPurchasesItemsbyAjax');
Route::get('/get_orders_by_customer_id/{id}', 'ResponseController@getOrderByCustomerID');
Route::get('/get_purchases_by_vendor_id/{id}', 'ResponseController@getPurchasesByVendorID');
Route::get('/get_rmas', 'ResponseController@getRMAItemsbyAjax');




Route::resource('order', 'OrderController');
Route::resource('courier', 'CourierController');
Route::resource('stock', 'StockController');






