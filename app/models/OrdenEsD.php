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
    
    public static function UPCsPending($idClient){
        $idOrderM = OrdenEsM::idPendingClient($idClient);
        $folio = OrdenEsD::UPCsFolio($idOrderM);

        $order = new OrdenEsM();
        $json_string = $order->contentFile();
        $folioUpcs = json_decode($json_string);   
        $folioUpcs = $folioUpcs->products;        

        foreach ($folio as $orderD)
        {
            $prod = self::ProductFolio($orderD->upc,$folioUpcs);
            if($prod != NULL){
                $orderD->quantityf = $prod->quantity;
                if ($orderD->quantity == $prod->quantity)          
                    $orderD->ok = true;
                else                                 
                    $orderD->ok = false;    
            }
        }
        $found = false;
        if(Auth::user()->pclient->useMode->id == 4){            
            foreach ($folioUpcs as $upcfolio){
                $found = false;
                foreach ($folio as $upcRead){
                    if ($upcfolio->upc == $upcRead->upc){
                        $found = true;
                        break;
                    }
                }
                if($found == false){
                    $prod = new UPCRowView($upcfolio->upc,$upcfolio->name,$upcfolio->quantity);             
                    $prod->ok = false;
                    $prod->quantityf = 0;
                    array_push($folio,$prod);
                }
            }
        }
        sort($folio);
        return $folio;
    }    
    
    public static function UPCsFolio($idOrderM)
    {
        $ordersD = OrdenEsD::where('orden_es_m_id',$idOrderM)->get();
        //$i = 0;
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
                    $pf = new UPCRowView($orderD->upc,$name,1);
                    array_push($folio,$pf);
                    //$folio[$i] = $pf;
                    //$i = $i + 1;
                }else
                   $folio[$pos]->quantity = $folio[$pos]->quantity+1;              
            }
        }
        return $folio;        
    }
    
    public static function ProductFolio($upc,$folioUpcs)
    {
        foreach ($folioUpcs as $upcfolio){
            if($upc == $upcfolio->upc){                
                return $upcfolio;
            }
        }
        return NULL;        
    }

    public static function jsonTagsToUpcs($ordersD){
        $folio = array();
        //$i = 0;
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
                    $pf = new UPCRowView($orderD->upc,$name,1);
                    array_push($folio,$pf);
                    //$folio[$i] = $pf;
                    //$i = $i + 1;
                }else
                   $folio[$pos]->quantity = $folio[$pos]->quantity;                  
            }
        }        
        return $folio;        
    }    
    
    public static function UPCsInventoryPlace($idOrderM)
    {
        $ordersD = OrdenEsD::where('orden_es_m_id',$idOrderM)->get();
        //$i = 0;
        $folio = array();
        foreach ($ordersD as $orderD)
        {
            $name = Product::nameProduct($orderD->upc);
            $pf = new UPCRowView($orderD->upc,$name,1);
            array_push($folio,$pf);
            //$folio[$i] = $pf;
            //$i = $i + 1;
        }
        return $folio;     
    }
}
