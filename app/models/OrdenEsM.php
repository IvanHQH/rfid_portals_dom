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
    var $pathPublic = "/home/developer/Projects/RFID/laravel/public";
    
    public function contentFile()
    {
        $json = file_get_contents($this->pathPublic.'/responseupcs.json');
        return $json;
    }      
    
    public function OrdenEsD()
    {        
        return $this->hasMany('OrdenEsD');
    }
    
    public static function nextId($idClient)
    {
        $id = 1;
        $count = DB::table('orden_es_ms')->where('pclient_id',
                $idClient)->orderBy('id','desc')->take(1)->count();            
        if($count > 0){
            $lastOrderM = DB::table('orden_es_ms')->where('pclient_id',
                $idClient)->orderBy('id', 'desc')
                ->take(1)->get();
            $id = $lastOrderM[0]->id;           
        }
        return $id;        
    }
    
    public static function idPendingClientWarehouse($idClient,$idWarehouse)
    {
        //return $idClient;
        $count = OrdenEsM::where('pending',1)->where('pclient_id',
                $idClient)->count();        
        $id = 0;
        if($count > 0){
            $orderM = OrdenEsM::where('pending',1)->where('pclient_id',
                $idClient)->get();
            $id = $orderM[0]->id;
        }
        return $id;
    }            
    
    public static function indexAllForViewLayout()
    {
        $id = Auth::user()->pclient->useMode->id;
        $ordersM = array();
        switch($id)
        {
            case 1://folio comparison
            case 4://folio comparison axa
                $ordersM = self::foliocomparisonrows();
                break;
            case 2://inventory place
            case 3://inventory
                $ordersM = OrdenEsM::where('pclient_id',
                        Auth::user()->pclient->id)->orderBy('created_at',
                                'desc')->get();
                break;
        }
        return $ordersM;
    }    
    
    public static function foliocomparisonrows()
    {        
        $ordersM = OrdenEsM::where('pclient_id',
                Auth::user()->pclient_id)->orderBy('created_at', 'desc')->get();
        //$ordersM = OrdenEsM::all();
        $ordersMView = array();
        //$i = 0;        
        foreach ($ordersM as $order)
        {
            if($order->type == 1)
                $order->type = "Entrada";
            else
                $order->type = "Salida";
            array_push($ordersMView,$order);
            //$ordersMView[$i] = $order;
            //$i = $i + 1;
        }
        return $ordersMView;
    }

    /*
     * update the number folio and put as no pending 
     */        
    public static function updateOrderFolio($folio,$type)
    {
        $count = OrdenEsM::where('pending',1)->count();
        if($count > 0)
        {
            $ordersM = OrdenEsM::where('pending',1)->get();                
            $orderM = OrdenEsM::where('id',$ordersM[0]->id)->get();    
            $orderM = $orderM[0];
            $orderM->folio = $folio;
            $orderM->pending = 0;
            if($type == 'Entrada')
                $orderM->type = 1;
            else
                $orderM->type = 0;
            $orderM->save();
        }
        else{}
    }    
    
    public static function idLastFolio($folio,$dateTime)
    {
        $orders = OrdenEsM::where('folio',$folio)->where('created_at',$dateTime)->get();
        if(count($orders) > 0)
        {
            return $orders[0]->id;
        }
        return 0;
    }
    
    public static function idLastClient($idClient,$dateTime)
    {
        $orders = OrdenEsM::where('pclient_id',$idClient)->where('created_at',
                $dateTime)->get();
        if(count($orders) > 0)
        {
            return $orders[0]->id;
        }
        return 0;
    }    
    
    public function customer()
    {
        return $this->belongsTo('Customer');
    }
    
    public function warehouse()
    {
        return $this->belongsTo('Warehouse');    
    }
    
}
