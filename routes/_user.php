<?php

/*
|--------------------------------------------------------------------------
| User Management
|--------------------------------------------------------------------------
*/

// USER
Route::group(['prefix' => 'user'], function() {    
    Route::get('/', 'UserController@index')->name("user.index");
    Route::get('datatable', 'UserController@dataTable')->name("user.datatable");
    Route::get('add', 'UserController@add')->name("user.create");
    Route::post('add', 'UserController@doAdd')->name("user.store");    
    Route::group(['middleware' => 'access_level'], function() { 
        Route::get('edit/{id}', 'UserController@edit')->name("user.edit");   
        Route::post('edit/{id}', 'UserController@doEdit')->name("user.update");    
        Route::delete('/delete/{id}', 'UserController@doDelete')->name("user.delete");
    });    
});

// ROLE
Route::group(['prefix' => 'role'], function() {    
    Route::get('/', 'RoleController@index')->name("role.index");
    Route::get('datatable', 'RoleController@dataTable')->name("role.datatable");  
    Route::get('add', 'RoleController@add')->name("role.create");
    Route::post('add', 'RoleController@doAdd')->name("role.store");
    Route::get('edit/{id}', 'RoleController@edit')->name("role.edit");    
    Route::post('edit/{id}', 'RoleController@doEdit')->name("role.update");
});
