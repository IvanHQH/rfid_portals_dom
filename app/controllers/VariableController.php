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
class VariableController extends BaseController{

    public function setReadFalse()
    {
        $input = Input::all();
        $idClient = @$input['client_id'];
        if(Variable::where('pclient_id',$idClient)
                ->where('name','read')->count() > 0)
        {
            $read = Variable::where('pclient_id',$idClient)
                    ->where('name','read')->get();
            $read = $read[0];
            $read->value = "0";
            $read->save();
            return "ok";
        }            
    }       
    
}
