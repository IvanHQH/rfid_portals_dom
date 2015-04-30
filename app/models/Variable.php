<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Variable
 *
 * @author Arellano
 */
class Variable extends BaseModel{
    //put your code here
    public static function varReadTrue($idClient) {                
        if(Variable::where('pclient_id',$idClient)
                ->where('name','read')->count() > 0)
        {
            $read = Variable::where('pclient_id',$idClient)
                    ->where('name','read')->get();
            $read = $read[0];
            $read->value = "1";
            $read->save();
        }             
        
    }
}
