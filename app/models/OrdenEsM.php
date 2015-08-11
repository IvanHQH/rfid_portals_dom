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
    
    public static function idLastFolio($folio,$dateTime)
    {
        $orders = OrdenEsM::where('folio',$folio)->where('created_at',$dateTime)->get();
        if(count($orders) > 0)
        {
            return $orders[0]->id;
        }
        return 0;
    }
    
    public static function getProduct($idOrder,$upc)
    {
        $ordenesd = OrdenEsD::UPCsFolio($idOrder);
        foreach ($ordenesd as $product) {
            if($product->upc == $upc)
                return $product;
        }
        return null;
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
    
    public static function idOrderM($created_at)
    {
        $date = new DateTime((string)$created_at);
        $created_at_f = $date->format('Y-m-d H:i:s');         
        $order_m = OrdenEsM::where('created_at',$created_at_f)->take(1)->get();
        if($order_m != null)
            return $order_m[0]->id;
        else
            return -1;
    }    
    
    public function createReadsXls($idOrderM)
    {        
        Excel::create('FirstExcel', function($excel) use($idOrderM) {

            $excel->sheet('Sheetname', function($sheet) use($idOrderM) {

            $ref = Variable::where('pclient_id',1)->where('name','Ref')->get();
            $ref = $ref[0];
            $ref = $ref->value;
            $ship= Variable::where('pclient_id',1)->where('name','ShipCarrier')->get();
            $ship = $ship[0];
            $ship = $ship->value;
            $lot= Variable::where('pclient_id',1)->where('name','Lot')->get();
            $lot = $lot[0];  
            $lot = $lot->value;
            $serial= Variable::where('pclient_id',1)->where('name','Serial')->get();
            $serial = $serial[0];  
            $serial = $serial->value;
            $locat= Variable::where('pclient_id',1)->where('name','LocationField1')->get();
            $locat = $locat[0];        
            $locat = $locat->value;                

            $sheet->appendRow(2, array(
                ''
            ));              

            $sheet->appendRow(2, array(
                'Ref #', 'PO #','ShipCarrier','Notes','SKU','QTY','Lot#','Serial#',
                'Expiration Date','LocationField1','LocationField2','LocationField3',
                'LocationField4','Cost','VarUoMavg'
            ));          
            $redsUpcs = OrdenEsD::where('orden_es_m_id',$idOrderM)->get();
            $i = 3;
            foreach ($redsUpcs as $upcRead){                      
                $sheet->appendRow($i, array(
                    $ref,'', $ship,'',$upcRead->upc,$upcRead->quantityf,$lot,$serial,'',$locat                        
                ));
                $i = $i + 1;
            }                   
            });         
        })->download('xls');   
        /*Excel::create('FirstExcel', function($excel) {

            // Call writer methods here

        })->export('xls');*/
    }    
    
}
