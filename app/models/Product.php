<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author Arellano
 */
class Product extends BaseModel{
    
    use SoftDeletingTrait;

    protected $dates = ['deleted_at'];    
    
    public static function nameProduct($upc,$idPclient)
    {                
        $count = Product::where('upc',$upc)->where('pclient_id',
                $idPclient)->count();
        if($count > 0){
            $products = Product::where('upc',$upc)->where('pclient_id',
                    $idPclient)->get();
            return $products[0]->name;
        }        
        return null;                
    }
    
    public static function upcProduct($name)
    {                
        $count = Product::where('name',$name)->where('pclient_id',
                Auth::user()->pclient_id)->count();
        if($count > 0){
            $products = Product::where('name',$name)->where('pclient_id',
                    Auth::user()->pclient_id)->get();
            return $products[0]->upc;
        }        
        return "";                
    }    
    
    public function warehouse()
    {
        return $this->belongsTo('Warehouse');
    }    
    
    public function traceabilitym()
    {
        return $this->belongsTo('TraceabilityM');
        //return $this->hasMany('TraceabilityM');
    } 
    
    public static function getProduct($upc,$idPclient)
    {
        $prods = Product::where('upc',$upc)->where('pclient_id',$idPclient)->get();
        $prod = null;
        if(count($prods)>0)
            $prod = $prods[0];
        return $prod;
    }
    
    public static function assignUPCtoAssetList($idClient,$listAssets)
    {
        $list = array();
        foreach($listAssets as $asset)
        {
            $prod = Product::where('name',$asset->name)->
                    where('pclient_id',$idClient)->get();
            if(count($prod)>0)
            {
                $asset->upc = $prod[0]->upc;
                array_push($list, $asset);
            }
        }
        return $list;
    }    
    
}
