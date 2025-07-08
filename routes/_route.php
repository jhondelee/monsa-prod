<?php

/*
|--------------------------------------------------------------------------
| Route Management
|--------------------------------------------------------------------------
*/

// PERMISSION
Route::group(['prefix' => 'permission'], function() {    
    Route::get('/', 'PermissionController@index')->name("permission.index");
    Route::get('datatable', 'PermissionController@dataTable')->name("permission.datatable");
    
    Route::get('add', 'PermissionController@add')->name("permission.create");
    Route::post('add', 'PermissionController@doAdd')->name("permission.store");
    
    Route::get('edit/{id}', 'PermissionController@edit')->name("permission.edit");    
    Route::post('edit/{id}', 'PermissionController@doEdit')->name("permission.update");    
});

// PERMISSION GROUP
Route::group(['prefix' => 'permission-group'], function() {    
    Route::get('/', 'PermissionGroupController@index')->name("pgroup.index");
    Route::get('datatable', 'PermissionGroupController@dataTable')->name("pgroup.datatable");
    
    Route::get('create', 'PermissionGroupController@add')->name("pgroup.create");
    Route::post('create', 'PermissionGroupController@doAdd')->name("pgroup.store");
    
    Route::get('edit/{id}', 'PermissionGroupController@edit')->name("pgroup.edit");    
    Route::post('edit/{id}', 'PermissionGroupController@doEdit')->name("pgroup.update");    
    
    Route::match(['get', 'delete'], 'delete/{id}', 'PermissionGroupController@doDelete')->name("pgroup.delete");
});
