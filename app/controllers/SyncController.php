<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SyncController
 *
 * @author Arellano
 */
class SyncController extends BaseController {
    /**
     * Returns the catalog objects to sync
     *
     * @return Response
     */
    
    var $pathPublic = "/home/developer/Projects/RFID/laravel/public";    
    
    public function index() {       
        $input = Input::All();        
        $result = array();
        if(isset($input['pclient_id'])){       
            //return ":)";
            $result['products'] = Product::select('id','pclient_id', 'name', 
                    'description','upc','warehouse_id')->where('pclient_id',
                    $input['pclient_id'])->orderBy("id")->get();
            $result['warehouses'] = Warehouse::select('id', 'name', 
                    'description')->where('pclient_id',$input['pclient_id'])->get();
        }
        return Response::json($result);
    }
    
    public function index_data() {     
        $input = Input::All();        
        $result = array();              
        if($input['pclient_id']){   
            $result['products'] = Product::select('id','pclient_id', 'name', 
                    'description','upc','warehouse_id')->where('pclient_id',
                    $input['pclient_id'])->orderBy("id")->get();
            $result['warehouses'] = Warehouse::select('id', 'name', 
                    'description')->where('pclient_id',$input['pclient_id'])->get();
        }
        return Response::json($result);
    }          
    
    public function postInventory() {
        /*$file = Input::all();
        return Response::json($file);*/

        @set_time_limit(0);
        // Process regular products
        if (Input::has('inventories')) {
            DB::transaction(function() {
                $invs = Input::get('inventories');
                if (!is_array($invs)) {
                    $invs = array($invs);
                }
                foreach ($invs as $inv) {
                    $ordenM = new OrdenEsM();
                    $ordenM->pclient_id = @$inv['client_id'];
                    if(isset($inv['warehouse_id'])){
                        $ordenM->warehouse_id = @$inv['warehouse_id'];//return 2;
                    }
                    if (isset($inv['customer_id'])){
                        $ordenM->customer_id = @$inv['customer_id'];//return 3;
                    }
                    if (isset($inv['folio'])){           
                        $ordenM->folio = @$inv['folio'];//return 4;
                    }
                    if (isset($inv['type'])){  
                        $ordenM->type = @$inv['type'];//return 5;
                    }
                    if (isset($invs['pending'])){
                        $ordenM->pending = @$inv['pending'];//return 6;
                    }
                    $ordenM->pending = 1;
                    $ordenM->created_at = @$inv['created_at'];
                    $ordenM->updated_at = @$inv['updated_at']; 
                    $ordenM->save();                    

                    if (isset($inv['epcs'])) { //if(Input::has('epcs')) {
                        $file = @$inv['epcs'];
                        if (!is_array($file)) {
                            $file = array($file);
                        }
                        foreach ($file as $epc) {
                            $nextId = OrdenEsM::nextId(@$inv['client_id']);
                            $ordenD = new OrdenEsD();
                            $ordenD->epc = $epc;
                            $epcObj = new EPC();
                            $epcObj->setEpc($epc);                            
                            $ordenD->upc = $epcObj->getUpc();
                            $ordenD->quantity = 1;
                            $ordenD->created_at = @$inv['created_at'];
                            $ordenD->updated_at = @$inv['updated_at'];
                            $ordenD->orden_es_m_id = $nextId;
                            $ordenD->save();
                        }
                    }
                }
            }); # end transaction
        }else{echo "no inventories <br>";}
        return "yes save";                        
    }    
 
    public function Aux()
    {
       
        /*
          $file = Input::all();
          return Response::json($file);
         */
        //return "ptr";
        //return Response::json($data);
        @set_time_limit(0);

        DB::transaction(function() {
            
            $file = file_get_contents($this->pathPublic.'/document.json');
            $data = json_decode($file);            
            //$invs = Input::get('inventories');
            $invs = $data->inventories;
            if (!is_array($invs)) {
                $invs = array($invs);
            }
            
            foreach ($invs as $inv) {
                //return Response::json($inv);
                $ordenM = new OrdenEsM();
                $ordenM->pclient_id = $inv->client_id;
                if(isset($inv->warehouse_id)){
                    $ordenM->warehouse_id = $inv->warehouse_id;//return 2;
                }
                if (isset($inv->customer_id)){
                    $ordenM->customer_id = $inv->customer_id;//return 3;
                }
                if (isset($inv->folio)){           
                    $ordenM->folio = $inv->folio;//return 4;
                }
                if (isset($inv->type)){  
                    $ordenM->type = $inv->type;//return 5;
                }
                /*if (isset($invs['pending'])){
                    $ordenM->pending = $invs['pending'];//return 6;
                }*/
                $ordenM->pending = 1;
                $ordenM->created_at = $inv->created_at;
                $ordenM->updated_at = $inv->updated_at; 
                $ordenM->save();                    

                if (isset($inv->epcs)) { //if(Input::has('epcs')) {
                    $file = $inv->epcs;
                    if (!is_array($file)) {
                        $file = array($file);
                    }
                    foreach ($file as $epc) {
                        $nextId = OrdenEsM::nextId($inv->client_id);
                        $ordenD = new OrdenEsD();
                        $ordenD->epc = $epc;
                        $epcObj = new EPC();
                        $epcObj->setEpc($epc);                            
                        $ordenD->upc = $epcObj->getUpc();
                        $ordenD->quantity = 1;
                        $ordenD->created_at = $inv->created_at;
                        $ordenD->updated_at = $inv->created_at;
                        $ordenD->orden_es_m_id = $nextId;
                        $ordenD->save();
                    }
                }
            }
        }); # end transaction

        // return in json format
        return " save ";
    }
    
    public function Aux2() {
        @set_time_limit(0);
        // Process regular products
        if (Input::has('inventories')) {
            DB::transaction(function() {
                
                $invs = Input::get('inventories');
                if (!is_array($invs)) {
                    $invs = array($invs);
                }
                foreach ($invs as $inv) {
                    $ordenM = new OrdenEsM();
                    $ordenM->pclient_id = @$inv['client_id'];
                    if(isset($inv['warehouse_id']))
                       $ordenM->warehouse_id = @$inv['warehouse_id'];
                    if(isset($inv['customer_id']))
                       $ordenM->customer_id = @$inv['customer_id'];
                    if(isset($inv['folio']))
                       $ordenM->folio = @$inv['folio'];
                    if(isset($inv['type']))
                       $ordenM->type = @$inv['type'];
                    //if(isset(@$inv[pending']))
                       $ordenM->pending = 1;
                    $ordenM->created_at = @$inv['created_at'];
                    $ordenM->updated_at = @$inv['updated_at'];
                    $ordenM->save();  
                    if (isset($inv['epcs'])) {
                        $epcs = $inv['epcs'];
                        if (!is_array($epcs)) {
                            $epcs = array($epcs);
                        }
                        foreach ($epcs as $epc) {
                            $nextId = OrdenEsM::nextId(@$inv['client_id']);
                            $ordenD = new OrdenEsD();
                            $ordenD->epc = $epc;
                            $epcObj = new EPC();
                            $epcObj->setEpc($epc);                            
                            $ordenD->upc = $epcObj->getUpc();
                            $ordenD->quantity = 1;
                            $ordenD->created_at = @$inv['created_at'];
                            $ordenD->updated_at = @$inv['updated_at'];
                            $ordenD->orden_es_m_id = $nextId;
                            $ordenD->save();
                        }
                    }                                                   
                }        
                $idUseMode = Pclient::find(@$inv['client_id'])->useMode->id;                
                $messages = Input::get('messages');
                if (!is_array($messages)) {
                    $messages = array($messages);
                }                  
                foreach ($messages as $msg) {
                    switch($idUseMode){
                        case 1://folio comparison   
                            $id = OrdenEsM::idLastFolio($ordenM->folio = @$inv['folio'],
                                    @$inv['created_at']);   
                            if($id != 0){
                                $log = new EventsLog();
                                $log->event_id = $id;
                                if(isset($msg['user_id']))
                                    $log->user_id = @$msg['user_id'];
                                if(isset($msg['type']))
                                    $log->type = @$msg['type'];
                                if(isset($msg['description']))
                                    $log->description = @$msg['description'];
                                if(isset($msg['created_at']))
                                    $log->created_at = @$msg['created_at'];
                                if(isset($msg['updated_at']))
                                    $log->updated_at = @$msg['updated_at'];
                                if(isset($msg['client_at']))
                                    $log->pclient_id = @$msg['client_id'];
                                $log->save(); 
                            }                
                            break;
                        case 2://inventory place
                        case 3://inventory
                            return "ok";
                            $id = OrdenEsM::idLastClient(@$inv['client_id'],
                                    @$inv['created_at']);   
                            if($id != 0){
                                $log = new EventsLog();
                                $log->event_id = $id;
                                if(isset($msg['user_id']))
                                    $log->user_id = @$msg['user_id'];
                                if(isset($msg['description']))
                                    $log->description = @$msg['description'];
                                if(isset($msg['created_at']))
                                    $log->created_at = @$msg['created_at'];
                                if(isset($msg['updated_at']))
                                    $log->updated_at = @$msg['updated_at'];
                                if(isset($msg['client_at']))
                                    $log->pclient_id = @$msg['client_id'];
                                $log->save(); 
                            }               
                            break;        
                    }                    
                }
            }); # end transaction    
        }
    }
    
}
