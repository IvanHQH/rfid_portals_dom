<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/login', 'HomeController@doLogin');
Route::post('/login', 'HomeController@loginPost');
Route::post('/variables/get_var_read', 'HomeController@get_var_read');
Route::post('/variables/set_no_read', 'HomeController@set_no_read');

Route::get('/log_show_read', 'OrdenEsDController@log_show_read');
Route::get('/upc/data_pending', 'OrdenEsDController@row_data_pending');
Route::get('/upc/data/{idOrderM?}', 'OrdenEsDController@row_data');
Route::resource('ordenesd', 'OrdenEsDController');
Route::post('/read/start_read', 'OrdenEsDController@start_read');
Route::post('/read/show_read', 'OrdenEsDController@show_read');
Route::post('/checkfolio', 'OrdenEsDController@checkfolio');

Route::resource('ordenesm', 'OrdenEsMController');
Route::get('/ordenesm',  'OrdenEsMController@getIndex');
Route::get('/getIndexData',  'OrdenEsMController@getIndexData');
Route::get('/showread', 'OrdenEsMController@showread');
Route::get('/dates/lastfolio', 'OrdenEsMController@dateslastfolio');
Route::post('/writeJsonFolio', 'OrdenEsMController@writeJsonFolio');
Route::post('/writeJsonTags', 'OrdenEsMController@writeJsonTags');

Route::get('/sync', 'SyncController@index');
Route::post('/sync', 'SyncController@postInventory');
Route::post('/sync/desktop', 'SyncController@postDesktop');
Route::post('/sync/postRead', 'SyncController@postRead');

Route::resource('logs', 'EventsLogController');
Route::get('/events_logs/rows_data', 'EventsLogController@rows_data');

Route::resource('customer', 'CustomerController');

Route::resource('product', 'ProductController');
