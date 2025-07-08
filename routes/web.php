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

        Route::get('/', 'HomeController@index')->name('main');
        
        Route::post('refresh','HomeController@index')->name("main.refresh"); 

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
});
 
