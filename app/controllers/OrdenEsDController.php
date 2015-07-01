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
        
        /*public function init_timer()
        {
            $base = new EventBase();
            $n = 2;
            $e = Event::timer($base, function($n) use (&$e) {
                echo "$n seconds elapsed\n";
                $e->delTimer();
            }, $n);
            $e->addTimer($n);
            $base->loop();            
        }*/
        
        public function row_data_pending()
        {
            $idClient = Auth::user()->pclient->id;
            $ordenM = OrdenEsD::UPCsPending($idClient); 
            return Response::json($ordenM);
        }        

        public function row_data($idOrdenM)
        {
            $ordenM = OrdenEsD::UPCsFolio($idOrdenM); 
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
            $input = Input::all();
            if(Product::where('pclient_id',$input['client_id'])->
                    where('upc',$input['upc'])->count() > 0)
            {
                $nextId = OrdenEsM::nextId($input['client_id']);
                $ordenD = new OrdenEsD();
                $ordenD->upc = $input['upc'];
                $ordenD->epc = $input['epc'];
                $ordenD->quantity = $input['quantity'];
                $ordenD->created_at = $input['created_at'];
                $ordenD->updated_at = $input['updated_at'];
                $ordenD->orden_es_m_id = $nextId;
                $ordenD->save();
                return "yes save";
            }
            return "no save";
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{                        

	}

	public function showUseMode($id)
	{                        
            $idUseMode = Auth::user()->pclient->use_mode_id;
            $ordenm = OrdenEsM::find($id);
            switch($idUseMode){
                case 1://folio comparison
                case 3://inventory                      
                case 4://folio comparison 
                    $ordenesd = OrdenEsD::UPCsFolio($id); 
                    //return $ordenesd;
                    return View::make('OrdenDTemplate',['ordenesd' => $ordenesd,
                        'description' => $ordenm->folio]);                    
                    break;
                case 2://inventory place
                case 5:
                    $ordenesd = OrdenEsD::UPCsInventoryPlace($id);
                    return View::make('OrdenDTemplate',['ordenesd' => $ordenesd,
                        'description' => $ordenm->warehouse->name]);                   
                    break;   
            }
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
                
        public function start_read_v1()
        {            
            $idClient = Auth::user()->pclient->id;
            Variable::varReadTrue($idClient);
            $idUseMode = Auth::user()->pclient->use_mode_id;
            switch($idUseMode){           
                case 1://folio comparison                  
                    return View::make('PortalTemplate',['step' => 'show_read']);
                    break;          
                case 4://folio comparison axa                         
                    return View::make('PortalTemplate',['step' => 'refresh_read']);
                    break;
            }   
        }
               
        public function store_orderm()
        {
            $order = new OrdenEsM();
            $dateTime = date("Y-m-d H:i:s");
            $order->folio = Input::get('folio');
            $order->created_at = $dateTime;
            $order->updated_at = $dateTime;            
        }
        
        public function refresh_read()
        {            
            //self::init_timer();
            return View::make('PortalTemplate',['step' => 'refresh_read']);
        }
        
        public function show_read()
        {
            $idClient = Auth::user()->pclient->id;
            $idPending = OrdenEsM::idPendingClientWarehouse($idClient,0);
            if($idPending > 0)
                return View::make('PortalTemplate',['step' => 'check']);
            else
                return View::make('PortalTemplate',['step' => 'show_read']);                         
        }           
        
        public function checkfolio()
        {
            $messages = array();
            $idOrderm = OrdenEsM::idPendingClientWarehouse(Auth::user()->pclient->id, 0);
            $orderm = OrdenEsM::find($idOrderm);
            if(Auth::user()->pclient->use_mode_id == 1){
                $folio = Input::get('folio');
            }
            if(Auth::user()->pclient->use_mode_id == 4)
                $folio = $orderm->folio;            
            $type = Input::get('type');
            //echo $folio;
            //die();
            if($folio != null){
                $redsUpcs = array();
                $redsUpcs = OrdenEsD::UPCsPending(Auth::user()->pclient->id);
                if (count($redsUpcs) > 0) { 
                    $order = new OrdenEsM();
                    $json_string = $order->contentFile();
                    $folioUpcs = json_decode($json_string);            
                    $messages = $this->comparedMessage($redsUpcs,$folioUpcs->products);
                    $idUser = "";
                    $idUser = User::idUPCUser($redsUpcs,Auth::user()->pclient->id);
                    EventsLog::saveLog($messages,$folio,$idUser,Auth::user()->pclient->id);
                    OrdenEsM::updateOrderFolio($folio,$type);  
                    HomeController::set_no_read_web();
                }
                else{
                    $messages[0] = "no hay lecturas de tags";
                }                
                return View::make('PortalTemplate',['messages' => $messages]);  
            }
            else{
                HomeController::set_no_read_web();
                return View::make('PortalTemplate',['step' => "check"]);
            }          
        }               
        
        public function update_ordenesd()
        {            
            //return Input::get('idClient');
            $idPending = OrdenEsM::idPendingClientWarehouse(
                    Input::get('client_id'),Input::get('warehouse_id'));
            if($idPending > 0)  
            {
                $orderm = OrdenEsM::find($idPending);
                $orderm->warehouse_id = Input::get('warehouse_id');
                $orderm->save();
                DB::table('orden_es_ds')->where('orden_es_m_id',
                        $idPending )->delete();
                return 1;
            }
            return 2;
        }   
        
        public function update_ordenesd_v4()
        {            
            //return Input::get('idClient');
            $idPending = OrdenEsM::idPendingClientWarehouse(
                    Input::get('client_id'),Input::get('warehouse_id'));
            if($idPending > 0)  
            {
                $orderm = OrdenEsM::find($idPending);
                $orderm->warehouse_id = Input::get('warehouse_id');
                $orderm->save();
                DB::table('orden_es_ds')->where('orden_es_m_id',
                        $idPending )->delete();
                $ordenesd = Input::get('orden_es_ds');
                if (isset($ordenesd)) { //if(Input::has('epcs')) {                    
                    if (!is_array($ordenesd)) {
                        $ordenesd = array($ordenesd);
                    }                    
                    foreach ($ordenesd as $ordend) {
                        if(Product::where('pclient_id',Input::get('client_id'))->
                            where('upc',@$ordend['upc'])->count() > 0)
                        //if(Product::where('pclient_id',Input::get('client_id'))->count() > 0 )
                            {
                                $ordenD = new OrdenEsD();                                                
                                $ordenD->epc = @$ordend['epc'];                         
                                $ordenD->upc = @$ordend['upc']; 
                                $ordenD->quantity = 1;
                                $ordenD->created_at = Input::get('created_at');
                                $ordenD->updated_at = Input::get('updated_at');
                                $ordenD->orden_es_m_id = $idPending;
                                $ordenD->save();      
                            }                        
                    }
                    return 1;
                }
            }
            return 0;
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
                        if($upcRead->quantityf != $upcfolio->quantity){
                            $message = $upcRead->upc." ".$upcRead->name;
                            if($upcRead->quantityf < $upcfolio->quantity){                                
                                $message = $message." ". $upcRead->quantityf.
                                    " esperados ".$upcfolio->quantity.",";
                            }elseif($upcRead->quantityf > $upcfolio->quantity){
                                $message =$message. " ". $upcRead->quantity.
                                    " esperados ".$upcfolio->quantity.",";                          
                            }
                            $messages[$index] = $message;
                            $index = $index + 1;
                        }
                        break;                
                        foreach ($redsUpcs as $upcRead){
                            if($upcRead->upc == $upcfolio->upc){                        
                                $find = true;
                                if($upcRead->quantityf != $upcfolio->quantity){
                                    $message = $upcRead->upc." ".$upcRead->name;
                                    if($upcRead->quantityf < $upcfolio->quantity){                                
                                        $message = $message." ". $upcRead->quantityf.
                                            " esperados ".$upcfolio->quantity.",";
                                    }elseif($upcRead->quantityf > $upcfolio->quantity){
                                        $message =$message. " ". $upcRead->quantity.
                                            " esperados ".$upcfolio->quantity.",";                          
                                    }
                                    $messages[$index] = $message;
                                    $index = $index + 1;
                                }
                                break;
                            }
                        }
                    }
                }
                if($find == false){
                    $message = $upcfolio->upc." ".$upcRead->name. " Inexistente,";
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
                    $message = $upcfolio->upc." ".$upcRead->name." Excedente,";
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
