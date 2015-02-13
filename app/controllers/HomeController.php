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
            $orders = OrdenEsM::where('folio','7001')->where('created_at','2015-01-31 13:26:47')->get();
            if(count($orders) > 0)
            {
                return $orders[0]->id;
            }            
        }        
        
        public function test_get_folio()
        {
            /*
            $prods = array();
            $prods[0] = new RespProduct("Textil Brillante", 2);
            $prods[1] = new RespProduct("Madera Brillante Monterreal", 2);
            $res['products'] = $prods;
             * */
            //OrdenEsDController order = new OrdenEsDController();
            //return ":)";
            $order = new OrdenEsM();
            $res = $order->contentFile();
            //$res = json_decode($res);
            return $res;
        }
        
        public function test_get_product()
        {
            $prod = array();
            $prod['id'] = "1";
            $prod['upc'] = "123456";
            $prod['name'] = "Product A";
            $prod['description'] = "Description product A";
            return Response::json($prod);
        }
        
        public function add_product()
        {
            $dateTime = date("Y-m-d H:i:s");
            $input = Input::all();
            $product = new Product();
            $product->name = $input['name'];
            $product->description = $input['description'];
            $product->upc = $input['upc'];
            $product->created_at = date("Y-m-d H:i:s");
            $product->updated_at = date("Y-m-d H:i:s");
            $product->save();
            return "yes save";
        }         
        
        public function reset_read()
        {
            $var = Variable::where('name','read')->where('customer_id',1)->get();
            $var[0]->value = '0';
            $var[0]->save();
            
            $ordenesm = OrdenEsM::where('pending',1)->where('customer_id',1)->get();
            
            foreach ($ordenesm as $ordenm){
                $ordenm->pending = 0;
                $ordenm->save();
            }
            return Redirect::to('/showread'); 
            //"/showread"
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
