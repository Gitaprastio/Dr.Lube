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

Route::get('/po', function () {
    return view('dashboard.order.index');
});

Route::get('/sls', function () {
    return view('dashboard.sales.index');
});

Route::get('/op', function () {
    return view('dashboard.operation.index');
});

Route::get('/cmp', function () {
    return view('dashboard.complain.index');
});

Route::get('user/complaint', 'ComplainController@create')->name('user.complain');
Route::post('user/add-complaint', 'ComplainController@store')->name('user.store');
Route::post('user/reply-complaint/{id}', 'ComplainController@reply')->name('user.reply');

Route::resource('purchase', 'PurchaseOrderController');

Route::get('/home', 'HomeController@index')->name('home');
//sales
Route::middleware('role:sales')->group(function(){
    Route::resource('sales','SalesController');
});
//operation
Route::middleware('role:operation')->group(function(){
    Route::resource('operation', 'OperationController');
});

Route::middleware('role:complain')->group(function(){
    Route::resource('complain', 'ComplainController');
});