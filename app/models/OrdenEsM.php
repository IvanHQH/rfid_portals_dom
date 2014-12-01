<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdenEsMs
 *
 * @author Arellano
 */
class OrdenEsM extends BaseModel{
    //put your code here
    
    var $idCutomer;
    
    public function OrdenEsD()
    {        
        return $this->hasMany('OrdenEsD');
    }
    
    public static function nextId()
    {
        $id = 1;
        $count = DB::table('orden_es_ms')->orderBy('id','desc')->take(1)->count();            
        if($count > 0){
            $lastOrderM = DB::table('orden_es_ms')->orderBy('id', 'desc')
                ->take(1)->get();
            $id = $lastOrderM[0]->id;                
        }
        return $id;
    }
    
    public static function idPending()
    {
        $count = OrdenEsM::where('pending',1)->count();
        $id = -1;
        if($count > 0){
            $orderM = OrdenEsM::where('pending',1)->get();
            $id = $orderM[0]->id;
        }            
        return $id;
    }
    
    /*
     * update the number folio and put as no pending 
     */        
    public static function updateOrderFolio($folio)
    {
        $count = OrdenEsM::where('pending',1)->count();
        if($count > 0)
        {
            $ordersM = OrdenEsM::where('pending',1)->get();                
            $orderM = OrdenEsM::where('id',$ordersM[0]->id)->get();    
            $orderM = $orderM[0];
            $orderM->folio = $folio;
            $orderM->pending = 0;
            $orderM->save();
        }
        else{}
    }    
}
