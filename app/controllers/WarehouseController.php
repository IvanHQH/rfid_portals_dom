<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WarehouseController
 *
 * @author Arellano
 */
class WarehouseController extends BaseController{
    
    //put your code here
    public function index() {        
        $warehouses = Warehouse::where('pclient_id',Auth::user()->pclient->id)->get();
        return View::make('WarehouseTemplate',['warehouses' => $warehouses]);                 
    }
    
    /**
    *post
     * Store a newly created resource in storage.
     *
     * @return Response
     */    
    public function store($id = 0)
    {
        $input = Input::All();
        if ($id == 0)
                $warehouse = new Warehouse();
        else {
                $warehouse = Warehouse::find($id);
                if (!$warehouse) 
                        return App::abort(403, 'Item not found');
        }
        $warehouse->name = $input['warehouse_name'];
        $warehouse->description = $input['warehouse_description'];
        $warehouse->pclient_id =  Auth::user()->pclient->id;  
        $warehouse -> save();
        
        return Response::json($warehouse);
    }    
    
    public function getWarehouse($id) {
            $wh = Warehouse::find($id);
            if ($wh !== null) {
                return Response::json($wh);                
            }
    }         
    
    public function postDelete($id)
    {
        $w = Warehouse::find($id);
        if ($w) 
            $w -> delete();
        return Response::json(array('ok' => 'ok'));
    }    
}
