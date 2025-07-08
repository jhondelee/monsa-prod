<?php

/*
|--------------------------------------------------------------------------
| Customer Management
|--------------------------------------------------------------------------

*/
// Customer
Route::group(['prefix' => 'customer'], function() {    

    Route::get('/', 'CustomerController@index')->name("customer.index");

    Route::get('add', 'CustomerController@create')->name("customer.create");

    Route::post('add', 'CustomerController@store')->name("customer.store");

    Route::get('edit/{id}', 'CustomerController@edit')->name("customer.edit"); 

    Route::post('doUpdate', 'CustomerController@doUpdate')->name("customer.doupdate"); 

    Route::post('edit/{id}', 'CustomerController@update')->name("customer.update"); 

    Route::post('doDelete', 'CustomerController@doDelete')->name("customer.dodelete"); 

    Route::get('delete/{id}', 'CustomerController@destroy')->name("customer.delete"); 

    Route::post('area', 'CustomerController@getAdditionalAreaValue')->name("customer.area");
    
    Route::post('price', 'CustomerController@getCustomerItemSrp')->name("customer.price");  

    Route::post('all-items', 'CustomerController@getAddAllItems')->name("customer.all_items");  

    Route::post('selected-items', 'CustomerController@getSelectedItems')->name("customer.selected_items");  

    Route::post('cost-items', 'CustomerController@getItemCost')->name("customer.cost_items"); 

    Route::post('doDeactive', 'CustomerController@doDeactive')->name("customer.doDeactive"); 


});


//Area Prices
Route::group(['prefix' => 'area-prices'], function() {    

    Route::get('/', 'AreaPricesController@index')->name("area_prices.index");

    Route::get('add', 'AreaPricesController@create')->name("area_prices.create");

    Route::post('add', 'AreaPricesController@store')->name("area_prices.store");

    Route::get('edit/{id}', 'AreaPricesController@edit')->name("area_prices.edit"); 

    Route::post('doUpdate', 'AreaPricesController@doUpdate')->name("area_prices.update"); 

    Route::post('doDelete', 'AreaPricesController@doDelete')->name("area_prices.delete"); 

    Route::post('area', 'AreaPricesController@getAdditionalAreaValue')->name("area_prices.area");
    
    Route::post('price', 'AreaPricesController@getAreasItemSrp')->name("area_prices.price");  

    Route::post('all-items', 'AreaPricesController@getAddAllItems')->name("area_prices.all_items");  

    Route::post('selected-items', 'AreaPricesController@getSelectedItems')->name("area_prices.selected_items");  

    Route::post('cost-items', 'AreaPricesController@getItemCost')->name("area_prices.cost_items"); 

    Route::post('doDeactive', 'AreaPricesController@doDeactive')->name("area_prices.doDeactive"); 

});