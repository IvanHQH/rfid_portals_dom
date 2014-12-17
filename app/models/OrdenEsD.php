<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdenEsD
 *
 * @author Arellano
 */
class OrdenEsD extends BaseModel{
    //put your code here
    /*public function OrdenEsM()
    {
        return $this->hasMany('SubtypeService');
    }*/
    

    public static function UPCFolioNotEnd(){
        $idOrderM = OrdenEsM::idPending();
        $folio = OrdenEsD::UPCFolio($idOrderM);
        return $folio;
    }    
    
    public static function UPCFolio($idOrderM)
    {
        $ordersD = OrdenEsD::where('orden_es_m_id',$idOrderM)->get();
        $i = 0;
        $folio = array();
        foreach ($ordersD as $orderD)
        {
            $count = Product::where('upc',$orderD->upc)->count();
            if($count > 0){

                $pos = -1;
                $j = 0;
                foreach ($folio as $upc){
                    $pos = -1;
                    if($upc->upc == $orderD->upc){
                        $pos = $j;
                        break;
                    }                            
                    $j = $j + 1;
                }                   
                if($pos == -1){
                    $name = Product::nameProduct($orderD->upc);
                    $pf = new UPCFolio($orderD->upc,$name,1);
                    $folio[$i] = $pf;
                    $i = $i + 1;
                }else{
                   $folio[$pos]->quantity = $folio[$pos]->quantity+1;
                }                    
            }
        }
        return $folio;        
    }
    
    public static function jsonTagsToUpcs($ordersD){
        //$json_string = file_get_contents($this->pathPublic.'/readstags.json');
        $folio = array();
        //$ordersD = json_decode($json_string); 
        $i = 0;
        foreach ($ordersD as $orderD)
        {
            $count = Product::where('upc',$orderD->upc)->count();
            if($count > 0){

                $pos = -1;
                $j = 0;
                foreach ($folio as $upc){
                    $pos = -1;
                    if($upc->upc == $orderD->upc){
                        $pos = $j;
                        break;
                    }                            
                    $j = $j + 1;
                }                   
                if($pos == -1){
                    $name = Product::nameProduct($orderD->upc);
                    $pf = new UPCFolio($orderD->upc,$name,1);
                    $folio[$i] = $pf;
                    $i = $i + 1;
                }else{
                   $folio[$pos]->quantity = $folio[$pos]->quantity;
                }                    
            }
        }
        return $folio;        
    }    
}
