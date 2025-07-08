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

Route::post('login', 'Auth\LoginController@login')->middleware('web_throttle:5,1');

Route::get('/logout',function(){ Auth::logout(); return redirect('/'); });

Route::group(['middleware'=>'auth'],function(){

        Route::get('home', 'HomeController@index')->name('main');

        Route::get('/', 'HomeController@index')->name('main');
        
        Route::post('refresh','HomeController@index')->name("main.refresh"); 

        Route::post('getinventorystatus','HomeController@getinventorystatus')->name("main.getinventorystatus");

        Route::post('getsalesmonthly','HomeController@getsalesmonthly')->name("main.getsalesmonthly");

        Route::group(['namespace' => 'UserManagement'], function() {        
            require('_user.php');
        });  

        Route::group(['namespace' => 'RouteManagement'], function() {           
            require('_route.php');
        }); 

        Route::group(['namespace' => 'References'], function() {
            require('_reference.php');
        });

        Route::group(['namespace' => 'ItemManagement'], function() {        
            require('_itemmanagement.php');
        }); 

        Route::group(['namespace' => 'PurchaseOrder'], function() {        
            require('_purchaseorder.php');
        });

        Route::group(['namespace' => 'Warehouse'], function() {        
            require('_warehouse.php');
        });

        Route::group(['namespace' => 'CustomerManagement'], function(){
            require('_customermanagement.php');
        });

        Route::group(['namespace' => 'Sales'], function(){
            require('_sales.php');
        });

        Route::group(['namespace' => 'SalesCommission'], function(){
            require('_salescommission.php');
        });

        Route::group(['namespace' => 'SalesMobileTools'], function(){
            require('_mobiletools.php');
        });

});
 
