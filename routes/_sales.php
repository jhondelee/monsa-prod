<?php

/*
|--------------------------------------------------------------------------
| Sales Management
|--------------------------------------------------------------------------

*/
// Sales
Route::group(['prefix' => 'sales'], function() {  

    Route::get('/', 'SalesController@index')->name("salesorder.index");
    
    Route::get('add', 'SalesController@create')->name("salesorder.create");

    Route::post('add', 'SalesController@store')->name("salesorder.store");

    Route::get('edit/{id}', 'SalesController@edit')->name("salesorder.edit");    

    Route::post('edit/{id}', 'SalesController@update')->name("salesorder.update"); 

    Route::get('delete/{id}', 'SalesController@destroy')->name("salesorder.delete");    

    //Route::get('printDraft/{id}', 'SalesController@printDraft')->name("salesorder.printDraft"); 
    Route::get('printDraft/{id}', 'SalesController@printDraft_test')->name("salesorder.printDraft"); 

    Route::get('print/{id}', 'SalesController@printSO')->name("salesorder.print"); 

    Route::get('cancel/{id}', 'SalesController@cancel')->name("salesorder.cancel");

    Route::get('post/{id}', 'SalesController@post')->name("salesorder.post"); 

    Route::get('deduct/{id}', 'SalesController@deduct')->name("salesorder.deduct");   

    Route::post('getcustomeritems', 'SalesController@getcustomeritems')->name("salesorder.getcustomeritems");

    Route::post('getforsoitems', 'SalesController@getForSOitems')->name("salesorder.getforsoitems");

    Route::post('additem', 'SalesController@getInventoryItems')->name("salesorder.getInventoryItems");

});

// Sales Payment
Route::group(['prefix' => 'sales-payment'], function() {  

    Route::get('/', 'SalesPaymentController@index')->name("sales_payment.index");
    
    Route::get('add', 'SalesPaymentController@create')->name("sales_payment.create");

    Route::post('add', 'SalesPaymentController@store')->name("sales_payment.store");

    Route::get('update/{id}', 'SalesPaymentController@update')->name("sales_payment.update"); 

    Route::post('addterm', 'SalesPaymentController@storeitems')->name("sales_payment.storeitems");   

    Route::get('edit/{id}', 'SalesPaymentController@edit')->name("sales_payment.edit");

    Route::get('remove/{id}', 'SalesPaymentController@remove')->name("sales_payment.remove");  

    Route::get('delete/{id}', 'SalesPaymentController@destroy')->name("sales_payment.delete");    

    Route::get('print/{id}', 'SalesPaymentController@print')->name("sales_payment.print"); 

    Route::post('datatable', 'SalesPaymentController@datatable')->name("sales_payment.datatable");

    Route::post('getsoinifo', 'SalesPaymentController@getSOinfo')->name("sales_payment.getsoinifo");

    Route::post('showpayments', 'SalesPaymentController@showpayments')->name("sales_payment.showpayments");

    Route::post('details', 'SalesPaymentController@details')->name("sales_payment.details");

});
    

