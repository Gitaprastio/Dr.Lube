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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/botman', 'BotManController@handle')->middleware('auth')->name('botman.handle');
Route::get('/botman/tinker', 'BotManController@tinker')->middleware('auth');

Route::middleware('auth')->group(function(){
    Route::resource('purchase', 'PurchaseOrderController');
    Route::get('new/purchase', 'PurchaseOrderController@purchase')->name('purchase.order');
});

Route::get('/home', 'HomeController@index')->name('home');
//sales
Route::middleware(['auth', 'role:sales'])->group(function(){
  Route::resource('sales','SalesController');  
  Route::match(['put', 'patch'], 'sales/accept/{id}', 'SalesController@accept')->name('sales.accept');
  Route::match(['put', 'patch'], 'sales/reject/{id}', 'SalesController@reject')->name('sales.reject');

});
//operation
Route::middleware(['auth', 'role:operation'])->group(function(){
    Route::resource('operation', 'OperationController');
    Route::match(['put', 'patch'], 'operation/send/{id}', 'OperationController@send')->name('operation.send');
    Route::get('operation/invoice/{id}', 'OperationController@invoice')->name('operation.invoice');
});

Route::middleware(['auth', 'role:complain'])->group(function(){
    Route::resource('complain', 'ComplainController');
});
