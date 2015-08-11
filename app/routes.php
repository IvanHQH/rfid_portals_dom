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

Route::get('/template', 'HomeController@template');

Route::post('login', function(){
    if(User::where('name',Input::get('name'))->count() == 0 )   
        return Redirect::to('login');
    else
    {
        $user = User::where('name',Input::get('name'))->take(1)->get();
        if($user[0]->password == Input::get('password')){
            if(Pclient::where('name', Input::get('nameClient'))->count() > 0 ){
                Auth::loginUsingId($user[0]->id);
                User::setCustomerSelect($user[0]->id, Input::get('nameClient'));
                return Redirect::to('ordenesm');                    
            }else 
                return Redirect::to('login');
        }
        else
            return Redirect::to('login');                    
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
Route::resource('ordenesd', 'OrdenEsDController');
Route::post('/variables/get_var_read', 'HomeController@get_var_read');
Route::post('/variables/set_no_read', 'VariableController@setReadFalse');
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
Route::get('/download_xls/{id}', 'OrdenEsMController@createReadsXls');
Route::post('/update_ordenesd_v4', 'OrdenEsDController@update_ordenesd_v4');
Route::resource('sync', 'SyncController');
Route::post('/sync_data', 'SyncController@index_data');
Route::post('/sync', 'SyncController@postInventory');
Route::post('/getFolio', 'SyncController@getFolio');

Route::group(array('before' => 'auth'), function()
{       
    Route::post('/add_product', 'Product@add_product');
    Route::get('/upc/data_pending', 'OrdenEsDController@ordersd_of_orderm_pending');    
    
    Route::post('/read/show_read', 'OrdenEsDController@show_read');
    Route::post('/read/check_folio', 'OrdenEsDController@check_folio');
    Route::post('/read/refresh_read', 'OrdenEsDController@refresh_read');

    Route::get('/ordenesm',  'OrdenEsMController@getIndex');
    Route::get('/getIndexData',  'OrdenEsMController@getIndexData');
    Route::get('/folio_capture', 'OrdenEsMController@folio_capture');        
    Route::post('/read/start_read_v4', 'OrdenEsMController@start_read_v4');
    Route::post('/ordenesm/delete/{id}', 'OrdenEsMController@postDelete');
        
    Route::get('excess_missing_order/{id?}', 'EventsLogController@excess_missing_order');    

    Route::get('showAssetsUseMode/{id?}', 'OrdenEsDController@showAssetsUseMode');
    Route::resource('product', 'ProductController');
    Route::post('/product/index', 'ProductController@index');
    Route::post('/product/{id?}', 'ProductController@store');
    Route::get('/product/get/{id}', 'ProductController@getProduct');   
    Route::post('/product/delete/{id}', 'ProductController@postDelete'); 
    
    Route::resource('warehouse', 'WarehouseController');
    Route::post('/warehouse/{id?}', 'WarehouseController@store');
    Route::get('/warehouse/get/{id}', 'WarehouseController@getWarehouse');  
    Route::post('/warehouse/delete/{id?}', 'WarehouseController@postDelete');

    Route::get('/arching_inv_init', 'ArchingController@Inventory_Initial');
    Route::get('/arching_inv_end/{id?}', 'ArchingController@Inventory_End');
    Route::get('/arching_up_file/{inventories?}', 'ArchingController@Up_File');
    Route::get('/arching_do/{params?}', 'ArchingController@Arching_Do');
    
    Route::post('/upload/{inventories?}','ArchingController@upload_file');
    
    Route::get('/rpt_excess/{params?}','AssetsInventoryController@rpt_excess');
    Route::get('/rpt_missing/{params?}','AssetsInventoryController@rpt_missing');
});
