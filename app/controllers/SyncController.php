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
            //return "isset";
            $result['products'] = Product::select('id','pclient_id', 'name', 
                    'description','upc','warehouse_id')->where('pclient_id',
                    $input['pclient_id'])->orderBy("id")->get();
            $result['warehouses'] = Warehouse::select('id', 'name', 
                    'description')->where('pclient_id',$input['pclient_id'])->get();
        }
        //return "!isset";
        return Response::json($result);
    }          
    
}
