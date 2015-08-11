<?php

class HomeController extends BaseController {
    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */    
    var $rows;           

    public function doLogin()
    {                                                            
        $clients = Pclient::all();
        $useModes = UseMode::all();            
        return View::make('CustomerTemplate',['clients' => $clients,
            'useModes' => $useModes]);
    }

    public function loginPost()
    {
        return Redirect::to('/show_read');
    }                               

    public function get_var_read()
    {            
        if(Variable::where('pclient_id',Input::get('client_id'))
                ->where('name','read')->count() > 0)
        {
            $read = Variable::where('pclient_id',Input::get('client_id'))
                    ->where('name','read')->get();
            $read = $read[0]->value;
        }
        return $read;          
    }        

    public function set_no_read()
    {    
        $idClient = Input::get('client_id');
        if(Variable::where('pclient_id',$idClient)
                ->where('name','read')->count() > 0)
        {
            $read = Variable::where('pclient_id',$idClient)
                    ->where('name','read')->get();
            $read = $read[0];
            $read->value = "0";
            $read->save();
        }
    }             
                        
    public function test()
    {            

    }            
        
    public static function ProductFolio($upc,$folioUpcs)
    {        
        foreach ($folioUpcs as $upcfolio){
            if($upc == $upcfolio->upc){       
                return $upcfolio;
            }
        }
        return NULL;        
    }                    

    public static function reset_read()
    {
        $idClient = Auth::user()->pclient->id;
        Variable::setReadFalse($idClient);
        $ordenesm = OrdenEsM::where('pending',1)->where('pclient_id',$idClient)->get();

        foreach ($ordenesm as $ordenm){
            $ordenm->pending = 0;
            $ordenm->save();
        }
        return Redirect::to('/folio_capture'); 
    }

    public function logout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen            
    }

    public function test_conection()
    {
        return "ok";
    }
        

}
