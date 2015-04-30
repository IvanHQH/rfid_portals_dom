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
    return Redirect::to('login');
});

Route::post('login', function(){
    if(User::where('name',Input::get('name'))->count() == 0 )   
        return Redirect::to('login');
    else
    {
        $user = User::where('name',Input::get('name'))->take(1)->get();
        /*if(Auth::id() == $user[0]->id)
        {}
        else{*/
            if($user[0]->password == Input::get('password')){
                Auth::loginUsingId($user[0]->id);
                User::setCustomerSelect($user[0]->id, Input::get('nameClient'));
                return Redirect::to('ordenesm');
            }
            else
                return Redirect::to('login');                    
        //}
    }
});

Route::post('login_forced', function(){
    $user = User::find(Input::get('id'));
    if($user != null){
       if($user->password == Input::get('pwd'))
       {
           Auth::loginUsingId($user->id);
           $user->pclient_id = Input::get('client_id');           
           $user->save();
           return "ok";
       }
       else
           return 'pwd fail';        
    }
    else return "user not found";  
});

Route::get('/login', 'HomeController@doLogin');
Route::post('/test_get_folio', 'HomeController@test_get_folio');
Route::post('/ordenesmhd', 'OrdenEsMController@storeHandheld');
Route::resource('ordenesd', 'OrdenEsDController');
Route::post('/variables/get_var_read', 'HomeController@get_var_read');
Route::post('/variables/set_no_read', 'HomeController@set_no_read');
Route::get('/test', 'HomeController@test');
Route::resource('ordenesm', 'OrdenEsMController');
Route::resource('logs', 'EventsLogController');
Route::get('/reset_read','HomeController@reset_read');
Route::resource('customer', 'CustomerController');
Route::get('/test_conection','HomeController@test_conection');
Route::resource('pclient', 'PclientController');

Route::resource('usemode', 'UseModeController');
Route::get('/logout','HomeController@logout');
Route::post('/order_pending', 'OrdenEsMController@order_pending');
Route::post('/update_ordenesd', 'OrdenEsDController@update_ordenesd');
Route::resource('sync', 'SyncController');
Route::post('/sync_data', 'SyncController@index_data');

//Route::resource('user', 'UserController');

Route::group(array('before' => 'auth'), function()
{    
    Route::post('/test_get_product', 'HomeController@test_get_product');    
    Route::post('/add_product', 'HomeController@add_product');
    Route::get('/variables/set_no_read_portal','HomeController@set_no_read_web');

    Route::get('/upc/data_pending', 'OrdenEsDController@row_data_pending');    
    
    Route::get('/upc/data/{id?}', 'OrdenEsDController@row_data');    
    Route::post('/read/start_read_v1', 'OrdenEsDController@start_read_v1');
    Route::post('/read/show_read', 'OrdenEsDController@show_read');
    Route::post('/read/checkfolio', 'OrdenEsDController@checkfolio');
    Route::post('/read/refresh_read', 'OrdenEsDController@refresh_read');

    Route::get('/ordenesm',  'OrdenEsMController@getIndex');
    Route::get('/getIndexData',  'OrdenEsMController@getIndexData');
    Route::get('/showread', 'OrdenEsMController@showread');        
    Route::get('/dates/lastfolio', 'OrdenEsMController@dateslastfolio');
    Route::post('/writeJsonFolio', 'OrdenEsMController@writeJsonFolio');
    Route::post('/writeJsonTags', 'OrdenEsMController@writeJsonTags');
    Route::post('/read/start_read_v4', 'OrdenEsMController@start_read_v4');
    Route::post('/ordenesm/delete/{id}', 'OrdenEsMController@delete');
        
    Route::get('/events_logs/rows_data', 'EventsLogController@rows_data');
    Route::get('comparison/{id?}', 'EventsLogController@comparison_rows');    
    //showUseMode
    Route::get('showUseMode/{id?}', 'OrdenEsDController@showUseMode');
    Route::resource('product', 'ProductController');
    Route::post('/product/index', 'ProductController@index');
    Route::post('/product/{id?}', 'ProductController@store');
    Route::get('/product/get/{id}', 'ProductController@getProduct');    
    
    Route::resource('warehouse', 'WarehouseController');
    Route::post('/warehouse/{id?}', 'WarehouseController@store');
    Route::get('/warehouse/get/{id}', 'WarehouseController@getWarehouse');    

});
