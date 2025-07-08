<?php

/*
|--------------------------------------------------------------------------
| References
|--------------------------------------------------------------------------

*/
// UNIT OF MEASURES
Route::group(['prefix' => 'unit_of_measure'], function() {    
    Route::get('/', 'UnitOfMeasureController@index')->name("unit_of_measure.index");
    Route::get('add', 'UnitOfMeasureController@create')->name("unit_of_measure.create");
    Route::post('add', 'UnitOfMeasureController@store')->name("unit_of_measure.store");
    Route::get('edit/{id}', 'UnitOfMeasureController@edit')->name("unit_of_measure.edit");    
    Route::post('edit', 'UnitOfMeasureController@update')->name("unit_of_measure.update"); 
    Route::get('delete/{id}', 'UnitOfMeasureController@destroy')->name("unit_of_measure.delete");    
});



// AREA
Route::group(['prefix' => 'area'], function() {    
    Route::get('/', 'AreaController@index')->name("area.index");
    Route::get('add', 'AreaController@create')->name("area.create");
    Route::post('add', 'AreaController@store')->name("area.store");
    Route::get('edit/{id}', 'AreaController@edit')->name("area.edit");    
    Route::post('edit', 'AreaController@update')->name("area.update"); 
    Route::get('delete/{id}', 'AreaController@destroy')->name("area.delete");    
});


// WAREHOUSE LOCATION
Route::group(['prefix' => 'location'], function() {    
    Route::get('/', 'WarehouseLocationController@index')->name("location.index");
    Route::get('add', 'WarehouseLocationController@create')->name("location.create");
    Route::post('add', 'WarehouseLocationController@store')->name("location.store");
    Route::get('edit/{id}', 'WarehouseLocationController@edit')->name("location.edit");    
    Route::post('edit', 'WarehouseLocationController@update')->name("location.update"); 
    Route::get('delete/{id}', 'WarehouseLocationController@destroy')->name("location.delete");  
});