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
        
        public function doLogin()
        {
            $customers = Customer::all();
            return View::make('CustomerTemplate',['customers' => $customers]);
        }
        
        public function loginPost()
        {
            return Redirect::to('/show_read');
        }        
        
	public function showWelcome()
	{
            return View::make('hello');
	}                          
        
        public function get_var_read()
        {
            $idCustomer = Input::get('idCustomer');
            if(Variable::where('customer_id',$idCustomer)
                    ->where('name','read')->count() > 0)
            {
                $read = Variable::where('customer_id',$idCustomer)
                        ->where('name','read')->get();
                $read = $read[0]->value;
            }
            return $read;          
        }
        
        public function set_no_read()
        {
            $idCustomer = Input::get('idCustomer');
            if(Variable::where('customer_id',$idCustomer)
                    ->where('name','read')->count() > 0)
            {
                $read = Variable::where('customer_id',$idCustomer)
                        ->where('name','read')->get();
                $read = $read[0];
                $read->value = "0";
                $read->save();
            }
        }             
                        
        public function test()
        {            
            $folio = OrdenEsD::UPCFolioNotEnd();
            return Response::json($folio);
        }          
        
}
