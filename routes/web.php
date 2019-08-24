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

Route::get('/', function () {
    return view('auth.login');
});

Route::resource('purchase', 'PurchaseOrderController');
Route::get('/purchase/order', 'PurchaseOrderController@purchase')->name('purchase.po');

Route::get('/home', 'HomeController@index')->name('home');
//sales
Route::middleware('role:sales')->group(function(){
  Route::resource('sales','SalesController');  
  Route::match(['put', 'patch'], 'sales/accept/{id}', 'SalesController@accept')->name('sales.accept');
  Route::match(['put', 'patch'], 'sales/reject/{id}', 'SalesController@reject')->name('sales.reject');

});
//operation
Route::middleware('role:operation')->group(function(){
    Route::resource('operation', 'OperationController');
    Route::match(['put', 'patch'], 'operation/send/{id}', 'OperationController@send')->name('operation.send');
    Route::get('operation/invoice/{id}', 'OperationController@invoice')->name('operation.invoice');
});

Route::middleware('role:complain')->group(function(){
    Route::resource('complain', 'ComplainController');
});