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
            $clients = Pclient::all();
            $useModes = UseMode::all();            
            return View::make('CustomerTemplate',['clients' => $clients,
                'useModes' => $useModes]);
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
            if(Variable::where('pclient_id',Input::get('client_id'))
                    ->where('name','read')->count() > 0)
            {
                //return "1";
                $read = Variable::where('pclient_id',Input::get('client_id'))
                        ->where('name','read')->get();
                $read = $read[0]->value;
            }
            //else
            //    return "2";
            return $read;          
        }        

        public static function set_no_read_web()
        {
            $idClient = $idClient = Auth::user()->pclient->id;
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
            $idOrderM = OrdenEsM::idPendingClient(6);
            $folio = OrdenEsD::UPCsFolio($idOrderM);

            $order = new OrdenEsM();
            $json_string = $order->contentFile();
            $folioUpcs = json_decode($json_string);   
            $folioUpcs = $folioUpcs->products;        

            foreach ($folio as $orderD)
            {
                $prod = self::ProductFolio($orderD->upc,$folioUpcs);
                if($prod != NULL){
                    $orderD->quantityf = $prod->quantity;
                    if ($orderD->quantity == $prod->quantity){              
                        $orderD->ok = true;
                    }
                    else {                                   
                        $orderD->ok = false;
                    }          
                }
            }
            return $folio;
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
        
        public function test_get_folio()
        {
            $order = new OrdenEsM();
            $res = $order->contentFile();
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
        
        public static function reset_read()
        {
            $idClient = Auth::user()->pclient->id;
            $var = Variable::where('name','read')->where('pclient_id',$idClient)->get();
            $var[0]->value = '0';
            $var[0]->save();
            
            $ordenesm = OrdenEsM::where('pending',1)->where('pclient_id',$idClient)->get();
            
            foreach ($ordenesm as $ordenm){
                $ordenm->pending = 0;
                $ordenm->save();
            }
            return Redirect::to('/showread'); 
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
