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
                    $nextId = 0;
                    if (isset($inv['epcs'])) { 
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
                    if (isset($inv['messages'])) { 
                        $messages = @$inv['messages'];
                        if (!is_array($messages)) {
                            $messages = array($messages);
                        }
                        foreach ($messages as $msg) {                              
                            $nextId = OrdenEsM::nextId(@$inv['client_id']);
                            $log = new EventsLog();
                            $log->event_id = $nextId;
                            $log->user_id = @$inv['user_id'];
                            $log->description = $msg;
                            $log->created_at = @$inv['created_at'];
                            $log->updated_at = @$inv['updated_at'];
                            $log->pclient_id = @$inv['client_id'];
                            $log->save(); 
                        }
                    }                    
                }
            }); # end transaction
        }else{echo "no inventories";}
        return "yes save";                        
    }    
    
    public function getFolio()
    {
        $input = Input::all();
        $local_file = @$input['file_name'];
        $server_file = @$input['file_name'];
        $ftp_server = @$input['server_name'];
        $ftp_user_name = @$input['user_name'];
        $ftp_user_pass = @$input['user_password'];    
        
        $ftp = FtpPclient::where('server_name',$ftp_server)->get();  
        $ftp = $ftp[0];
        $folioFile = $ftp->ContentFileToJson($ftp_user_name,$ftp_user_pass, 
                $local_file, $local_file);  
        $folioFile = $ftp->groupContent($folioFile);
        $result = array();
        $prods = array();
        foreach ($folioFile as $upcfile){
            $prod = new Product();
            $prod->name = $upcfile->name;
            $prod->upc = $upcfile->upc;
            $prod->quantity = $upcfile->quantity; 
            array_push($prods,$prod);
        }        
        $result['products'] = $prods;
        return Response::json($result);       
    }
    
}
