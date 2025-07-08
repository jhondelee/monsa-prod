<?php

/*
|--------------------------------------------------------------------------
| Item Management
|--------------------------------------------------------------------------

*/



Route::group(['prefix' => 'item'], function() {    
    Route::get('/', 'ItemController@index')->name("item.index");
    Route::get('add', 'ItemController@create')->name("item.create");
    Route::post('add', 'ItemController@store')->name("item.store");
    Route::get('edit/{id}', 'ItemController@edit')->name("item.edit");    
    Route::post('edit/{id}', 'ItemController@update')->name("item.update"); 
    Route::get('delete/{id}', 'ItemController@destroy')->name("item.delete");    
});


Route::group(['prefix' => 'supplier'], function() {    
    Route::get('/', 'SupplierController@index')->name("supplier.index");
    Route::get('add', 'SupplierController@create')->name("supplier.create");
    Route::post('add', 'SupplierController@store')->name("supplier.store");
    Route::get('edit/{id}', 'SupplierController@edit')->name("supplier.edit");    
    Route::post('edit/{id}', 'SupplierController@update')->name("supplier.update"); 
    Route::get('delete/{id}', 'SupplierController@destroy')->name("supplier.delete");    

    Route::get('items/{id}', 'SupplierController@items')->name("supplier.items");
    Route::post('add-items/{id}', 'SupplierController@add_items')->name("supplier.add_items");

    Route::post('supplied', 'SupplierController@supplied')->name("supplier.supplied"); 
    Route::post('storeitems/{id}', 'SupplierController@storeitems')->name("supplier.storeitems"); 
    Route::post('showitems', 'SupplierController@showitems')->name("supplier.showitems");
});

    
