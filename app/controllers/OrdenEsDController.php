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
            $nextId = OrdenEsM::nextId($input['client_id']);
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

	}

	public function showUseMode($id)
	{                        
            $idUseMode = Auth::user()->pclient->useMode->id;
            $ordenm = OrdenEsM::find($id);
            switch($idUseMode){
                case 1://folio comparison      
                case 4://folio comparison 
                    $ordenesd = OrdenEsD::UPCsFolio($id); 
                    return View::make('OrdenDTemplate',['ordenesd' => $ordenesd,
                        'description' => $ordenm->folio]);                    
                    break;
                case 2://inventory place
                case 3://inventory                     
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
                
        public function start_read()
        {
            $idCustomer = Auth::user()->pclient->id;
            if(Variable::where('pclient_id',$idCustomer)
                    ->where('name','read')->count() > 0)
            {
                $read = Variable::where('pclient_id',$idCustomer)
                        ->where('name','read')->get();
                $read = $read[0];
                $read->value = "1";
                $read->save();
            }            
            $idUseMode = Auth::user()->pclient->useMode->id;
            switch($idUseMode){           
                case 1://folio comparison                  
                    return View::make('PortalTemplate',['step' => 'show_read']);
                    break;          
                case 4://folio comparison axa     
                    return View::make('PortalTemplate',['step' => 'refresh_read']);
                    break;
            }   
        }
               
        public function refresh_read()
        {            
            return View::make('PortalTemplate',['step' => 'refresh_read']);
        }
        
        public function show_read()
        {
            $idClient = Auth::user()->pclient->id;
            $idPending = OrdenEsM::idPendingClient($idClient);
            if($idPending > 0)
                return View::make('PortalTemplate',['step' => 'check']);
            else
                return View::make('PortalTemplate',['step' => 'show_read']);                         
        }           
        
        public function checkfolio()
        {
            $messages = array();
            $folio = Input::get('folio');
            $type = Input::get('type');
            if($folio != null)
            {
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
            $idPending = OrdenEsM::idPendingClient(Input::get('client_id'));
            if($idPending > 0)  
            {
                DB::table('orden_es_ds')->where('orden_es_m_id',
                        $idPending )->delete();
                return 1;
            }
            return 2;
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
                                    " de tags leeidas se esperaban ".$upcfolio->quantity.",";
                            }elseif($upcRead->quantity > $upcfolio->quantity){
                                $message =$message. " hay ". $upcRead->quantity.
                                    " en tags leeidas solo se esperaban ".$upcfolio->quantity.",";                          
                            }
                            $messages[$index] = $message;
                            $index = $index + 1;
                        }
                        break;
                    }
                }
                if($find == false){
                    $message = "[".$upcfolio->upc."]"."[".$upcRead->name."]"
                        . " no se encuentra en las tags leeidas,";
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
                        " no se esperaba en el folio,";
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
