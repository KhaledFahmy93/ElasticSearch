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

Route::prefix('elasticsearch')->group(function (){
    Route::get('test' ,  'ClientController@elasticsearchTest');
    Route::get('data' ,  'ClientController@elasticsearchData');
    Route::get('queries'  , 'ClientController@elasticsearchQueries');
}); 


Route::prefix('elastica')->group(function (){
    Route::get('test' , 'ClientController@elasticaTest');
    Route::get('data'  , 'ClientController@elasticaData');
    Route::get('queries'  , 'ClientController@elasticQueries');
    Route::get('advanced' , 'ClientController@elasticaAdvanced');
});

Route::prefix('duck')->group(function (){
    Route::get('search' , 'DuckController@searchForDuck')->name('duck_search');
});


