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
            @set_time_limit(0);
            // Process regular products
            $file = "hola";
            if (Input::has('warehouses')) {
                DB::transaction(function() {
                    $invs = Input::get('warehouses');
                    if (!is_array($invs)) {
                        $invs = array($invs);
                    }

                    foreach ($invs as $inv) {
                        //$event_id = isset($inv['customer_id']) ? (int) $inv['customer_id'] : 0;
                        $customer_id = @$inv['customer_id'];
                        //$type = isset($inv['type']) ? (int) $inv['type'] : 1;
                        $type = @$inv['type'];
                        //$type = isset($inv['date_time']) ? (int) $inv['date_time'] : '2014-10-20 16:30:12';
                        $date_time = @$inv['date_time'];             
                        //$type = isset($inv['folio']) ? (int) $inv['folio'] : '0';
                        $folio = @$inv['folio'];   

                        $file = $inv['epcs'];
                        if (!is_array($file)) {
                            $file = array($file);
                        }
                        foreach ($file as $epc) {
                            if (!in_array($epc, array_keys($epcs))) {
                                $e = new Epc($epc);
                                $epcs[$epc] = $e->getUpc();
                            }
                        }
                    }

                });   
            }
            return Response::json($file);
        }          
        
}
