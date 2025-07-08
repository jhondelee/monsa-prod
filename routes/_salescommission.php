<?php

/*
|--------------------------------------------------------------------------
| Sales Commission
|--------------------------------------------------------------------------

*/
// Assign Area
Route::group(['prefix' => 'assign-area'], function() {  

    Route::get('/', 'AssignedAreaController@index')->name("assign_area.index");
    
    Route::get('add', 'AssignedAreaController@create')->name("assign_area.create");

    Route::post('add', 'AssignedAreaController@store')->name("assign_area.store");

    Route::get('edit/{id}', 'AssignedAreaController@edit')->name("assign_area.edit");    

    Route::post('edit', 'AssignedAreaController@update')->name("assign_area.update"); 

    Route::get('delete/{id}', 'AssignedAreaController@destroy')->name("assign_area.delete");    

    Route::get('print/{id}', 'AssignedAreaController@printSO')->name("assign_area.print"); 

});


// Rate
Route::group(['prefix' => 'commission-rate'], function() {    

    Route::get('/', 'RateController@index')->name("commission_rate.index");

    Route::get('add', 'RateController@create')->name("commission_rate.create");

    Route::post('add', 'RateController@store')->name("commission_rate.store");

    Route::get('edit/{id}', 'RateController@edit')->name("commission_rate.edit");    

    Route::post('edit', 'RateController@update')->name("commission_rate.update"); 

    Route::get('delete/{id}', 'RateController@destroy')->name("commission_rate.delete");    
});


// Agent Commission
Route::group(['prefix' => 'agent-commission'], function() {  

    Route::get('/', 'AgentCommissionController@index')->name("commission.index");
    
    Route::get('add', 'AgentCommissionController@create')->name("commission.create");

    Route::post('add', 'AgentCommissionController@store')->name("commission.store");

    Route::get('edit/{id}', 'AgentCommissionController@edit')->name("commission.edit");    

    Route::post('edit/{id}', 'AgentCommissionController@update')->name("commission.update"); 

    Route::get('delete/{id}', 'AgentCommissionController@destroy')->name("commission.delete");    

    Route::get('print/{id}', 'AgentCommissionController@printSO')->name("commission.print"); 

    Route::post('generate', 'AgentCommissionController@getsalesCom')->name("commission.generate"); 

    Route::post('agentEarned', 'AgentCommissionController@agentEarned')->name("commission.agentearned"); 

});



// Agent Team
Route::group(['prefix' => 'agent-team'], function() {  

    Route::get('/', 'AgentTeamController@index')->name("team.index");
    
    Route::get('add', 'AgentTeamController@create')->name("team.create");

    Route::post('add', 'AgentTeamController@store')->name("team.store");

    Route::get('edit/{id}', 'AgentTeamController@edit')->name("team.edit");    

    Route::post('edit/{id}', 'AgentTeamController@update')->name("team.update"); 

    Route::get('delete/{id}', 'AgentTeamController@destroy')->name("team.delete");  

    Route::post('subagents', 'AgentTeamController@showSubAgents')->name("team.subagents");   

});


