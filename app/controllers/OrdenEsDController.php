<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdenEsDController
 *
 * @author Arellano
 */
class OrdenEsDController extends BaseController {
        var $pathPublic = "/home/developer/Projects/RFID/laravel/public";
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */        
                
        public function index()
	{
            $ordern_es_ds = array();
            $ordern_es_ds['orden_es_ds'] = OrdenEsD::all();
            return Response::json($ordern_es_ds);
	}   
        
        public function row_data()
        {
            $ordenM = OrdenEsD::UPCFolioNotEnd(); 
            return Response::json($ordenM);
        }
        
	/**
	 *fet
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

	}
            
	/**
	*post
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{              
            $nextId = OrdenEsM::nextId();
            $input = Input::all();
            $ordenD = new OrdenEsD();
            $ordenD->upc = $input['upc'];
            $ordenD->epc = $input['epc'];
            $ordenD->quantity = $input['quantity'];
            $ordenD->created_at = $input['created_at'];
            $ordenD->updated_at = $input['updated_at'];
            $ordenD->orden_es_m_id = $nextId;
            $ordenD->save();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            $tags = OrdenEsD::where('orden_es_m_id',$id)->get();
            return Response::json($tags);
	}
        
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{		

	}

        
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}
        
        public function log_show_read()
        {   
            if(User::where('name',Input::get('name'))->
                where('password',Input::get('password'))->count() == 0 )
            {
                $customers = array();
                $customers = Customer::all();
                return View::make('CustomerTemplate',['customers' => $customers]);
            }
            else
            {
                if(Customer::where('name',Input::get('nameCustomer'))->count() > 0)
                {
                    $customer = Customer::where('name',
                        Input::get('nameCustomer'))->get()[0];                    
                    return View::make('ReadTemplate',
                        ['customer' => $customer,'step' => "start"]);
                }                    
            }                
        }       
        
        public function start_read()
        {
            $idCustomer = 1;
            if(Variable::where('customer_id',$idCustomer)
                    ->where('name','read')->count() > 0)
            {
                $read = Variable::where('customer_id',$idCustomer)
                        ->where('name','read')->get();
                $read = $read[0];
                $read->value = "1";
                $read->save();
            }
            return View::make('ReadTemplate',['step' => 'show_read']);
        }
        
        public function show_read()
        {
            $idPending = OrdenEsM::idPending();
            if($idPending > 0)
                return View::make('ReadTemplate',['step' => 'check']);
            else
                return View::make('ReadTemplate',['step' => 'show_read']);
        }           
        
        public function checkfolio()
        {
            $messages = array();
            $folio = Input::get('folio');
            if($folio != null)
            {
                $redsUpcs = array();
                $redsUpcs = OrdenEsD::UPCFolioNotEnd();
                if (count($redsUpcs) > 0) {                    
                    $json_string = file_get_contents($this->pathPublic.'/responseupcs.json');
                    $folioUpcs = json_decode($json_string);            
                    $messages = $this->comparedMessage($redsUpcs,$folioUpcs);
                    $idUser = "";
                    $idUser = User::idUPCUser($redsUpcs);
                    EventsLog::saveLog($messages,$folio,$idUser);
                    OrdenEsM::updateOrderFolio($folio);                                               
                }
                else{
                    $messages[0] = "no hay lecturas de tags";
                }
                return View::make('ReadTemplate',['messages' => $messages]);  
            }
            else{
                return View::make('ReadTemplate',['step' => "check"]);
            }            
        }       
        
        public function comparedMessage($redsUpcs,$folioUpcs)
        {
            $messages = array();
            $message = "";
            $find = null;
            $index = 0;
            //serch for upcs of the folio in the reads upcs
            foreach ($folioUpcs as $upcfolio){
                $message = "";
                $find = false;
                foreach ($redsUpcs as $upcRead){
                    if($upcRead->upc == $upcfolio->upc){                        
                        $find = true;
                        if($upcRead->quantity != $upcfolio->quantity){
                            $message = "[".$upcRead->upc."]"."[".$upcRead->name."]";
                            if($upcRead->quantity < $upcfolio->quantity){                                
                                $message = $message." hay solo ". $upcRead->quantity.
                                    " de tags leeidas, se esperaban ".$upcfolio->quantity;
                            }elseif($upcRead->quantity > $upcfolio->quantity){
                                $message =$message. " hay ". $upcRead->quantity.
                                    " en tagas leeidas,solo se esperaban ".$upcfolio->quantity;                          
                            }
                            $messages[$index] = $message;
                            $index = $index + 1;
                        }
                        break;
                    }
                }
                if($find == false){
                    $message = "[".$upcfolio->upc."]"."[".$upcRead->name."]"
                        . " no se encuentra en las tags leeidas";
                    $messages[$index] = $message;
                    $index = $index + 1;
                }
            }            
            //serch for upcs in the folio
            foreach ($redsUpcs as $upcfolio){
                $message = "";
                $find = false;
                foreach ($folioUpcs  as $upcRead){
                    if($upcRead->upc == $upcfolio->upc){
                        $find = true;
                        break;
                    }
                }
                if($find == false){
                    $message = "[".$upcfolio->upc."]"."[".$upcRead->name."]".
                        " no se esperaba en el folio";
                    $messages[$index] = $message;
                    $index = $index + 1;                    
                }
            }                                    
            if(count($messages) == 0){
                $message = "Orden exitosa";
                $messages[$index] = $message;
            }                
            return $messages;
        }        
}
