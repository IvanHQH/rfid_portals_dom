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
    public function index() {            
        $input = Input::All();        
        $result = array();
        if(isset($input['pclient_id'])){       
            return ":)";
            $result['products'] = Product::select('id','pclient_id', 'name', 
                    'description','upc','warehouse_id')->where('pclient_id',
                    $input['pclient_id'])->orderBy("id")->get();
            $result['warehouses'] = Warehouse::select('id', 'name', 
                    'description')->get();
        }
        return Response::json($result);
    }
    
    public function index_data() {            
        $input = Input::All();        
        $result = array();        
        if(isset($input['pclient_id'])){   
            //return ":)";
            $result['products'] = Product::select('id','pclient_id', 'name', 
                    'description','upc','warehouse_id')->where('pclient_id',
                    $input['pclient_id'])->orderBy("id")->get();
            $result['warehouses'] = Warehouse::select('id', 'name', 
                    'description')->get();
        }
        return Response::json($result);
    }    
    
    /*public function postRead(){
        //@set_time_limit(0);
        // Process regular products
        return Response::json(Input::all());
        if (Input::has('inventories')) {
            
            DB::transaction(function() {
                $invs = Input::get('inventories');
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
            return Response::json($epcs);
        }
    }*/       
    
}
