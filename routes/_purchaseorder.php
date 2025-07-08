<?php

/*
|--------------------------------------------------------------------------
| Purchase Order
|--------------------------------------------------------------------------

*/



Route::group(['prefix' => 'order'], function() {    
    Route::get('/', 'OrderController@index')->name("order.index");
    Route::get('add', 'OrderController@create')->name("order.create");
    Route::post('add', 'OrderController@store')->name("order.store");
    Route::get('edit/{id}', 'OrderController@edit')->name("order.edit");    
    Route::post('edit/{id}', 'OrderController@update')->name("order.update"); 
    Route::get('delete/{id}', 'OrderController@destroy')->name("order.delete");    

    Route::get('print/{id}', 'OrderController@print')->name("order.print"); 
    Route::get('cancel/{id}', 'OrderController@cancel')->name("order.cancel");
    Route::get('post/{id}', 'OrderController@post')->name("order.post"); 

    Route::post('getitems', 'OrderController@getItems')->name("order.getitems");
    Route::post('getpoitems', 'OrderController@getPOitems')->name("order.getpoitems");
    Route::post('supplieritems', 'OrderController@supplierItems')->name("order.supplieritems");

});


Route::group(['prefix' => 'incoming'], function() {    
    Route::get('/', 'IncomingController@index')->name("incoming.index");
    Route::get('add', 'IncomingController@create')->name("incoming.create");
    Route::post('add', 'IncomingController@store')->name("incoming.store");
    Route::get('edit/{id}', 'IncomingController@edit')->name("incoming.edit");    
    Route::post('edit/{id}', 'IncomingController@update')->name("incoming.update"); 
    Route::get('delete/{id}', 'IncomingController@destroy')->name("incoming.delete");    

    Route::post('receiving', 'IncomingController@receiving')->name("incoming.receiving");
    Route::get('post/{id}', 'IncomingController@post')->name("incoming.post");
    Route::get('print/{id}', 'IncomingController@print')->name("incoming.print"); 
});




    
