<?php

/*
|--------------------------------------------------------------------------
| Warehouse
|--------------------------------------------------------------------------

*/



Route::group(['prefix' => 'ending'], function() {    
    Route::get('/', 'EndingController@index')->name("ending.index");
    Route::get('add', 'EndingController@create')->name("ending.create");
    Route::post('add', 'EndingController@store')->name("ending.store");
    Route::get('edit/{id}', 'EndingController@edit')->name("ending.edit");    
    Route::post('edit/{id}', 'EndingController@update')->name("ending.update"); 
    Route::get('delete/{id}', 'EndingController@destroy')->name("ending.delete");  

    Route::get('print/{id}', 'EndingController@print')->name("ending.print"); 
    Route::get('post/{id}', 'EndingController@post')->name("ending.post");    
});


Route::group(['prefix' => 'inventory'], function() {    
    Route::get('/', 'InventoryController@index')->name("inventory.index");
    Route::get('add', 'InventoryController@create')->name("inventory.create");
    Route::post('add', 'InventoryController@store')->name("inventory.store");
    Route::get('edit/{id}', 'InventoryController@edit')->name("inventory.edit");    
    Route::post('edit/{id}', 'InventoryController@update')->name("inventory.update"); 
    Route::get('delete/{id}', 'InventoryController@destroy')->name("inventory.delete");   

    Route::post('iteminfo', 'InventoryController@iteminfo')->name("inventory.iteminfo");
    Route::get('show/{id}', 'InventoryController@show')->name("inventory.show");
    Route::post('upload_image', 'InventoryController@upload_image')->name("inventory.upload_image");
    Route::get('print', 'InventoryController@print')->name("inventory.print"); 
    Route::get('print-inventory', 'InventoryController@print_inventory')->name("inventory.print-inventory"); 
});


Route::group(['prefix' => 'transfer'], function() {    
    Route::get('/', 'StockTransferController@index')->name("transfer.index");
    Route::get('add', 'StockTransferController@create')->name("transfer.create");
    Route::post('add', 'StockTransferController@store')->name("transfer.store");
    Route::get('edit/{id}', 'StockTransferController@edit')->name("transfer.edit");    
    Route::post('edit/{id}', 'StockTransferController@update')->name("transfer.update"); 
    Route::get('delete/{id}', 'StockTransferController@destroy')->name("transfer.delete");  

    Route::post('source', 'StockTransferController@sourcedataTable')->name("transfer.source");   
});


Route::group(['prefix' => 'returns'], function() {    
    Route::get('/', 'ReturnsController@index')->name("returns.index");
    Route::get('add', 'ReturnsController@create')->name("returns.create");
    Route::post('add', 'ReturnsController@store')->name("returns.store");
    Route::get('edit/{id}', 'ReturnsController@edit')->name("returns.edit");    
    Route::post('edit/{id}', 'ReturnsController@update')->name("returns.update"); 
    Route::get('delete/{id}', 'ReturnsController@destroy')->name("returns.delete");    
});


Route::group(['prefix' => 'consumables'], function() {    
    Route::get('/', 'ConsumablesController@index')->name("consumables.index");
    Route::get('add', 'ConsumablesController@create')->name("consumables.create");
    Route::post('add', 'ConsumablesController@store')->name("consumables.store");
    Route::get('edit/{id}', 'ConsumablesController@edit')->name("consumables.edit");    
    Route::post('edit/{id}', 'ConsumablesController@update')->name("consumables.update"); 
    Route::get('delete/{id}', 'ConsumablesController@destroy')->name("consumables.delete"); 

    Route::get('show/{id}', 'ConsumablesController@show')->name("consumables.show");
    Route::get('print', 'ConsumablesController@print')->name("consumables.print");
});

Route::group(['prefix' => 'condemn'], function() {    
    Route::get('/', 'CondemnItemsController@index')->name("condemn.index");
    Route::get('add', 'CondemnItemsController@create')->name("condemn.create");
    Route::post('add', 'CondemnItemsController@store')->name("condemn.store");
    Route::get('edit/{id}', 'CondemnItemsController@edit')->name("condemn.edit");    
    Route::post('edit/{id}', 'CondemnItemsController@update')->name("condemn.update"); 
    Route::get('delete/{id}', 'CondemnItemsController@destroy')->name("condemn.delete");    
});