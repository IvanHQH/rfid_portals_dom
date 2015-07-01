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
        $idOrderM = OrdenEsM::idPendingClientWarehouse($idClient,0);
        $folioRead = OrdenEsD::UPCsFolio($idOrderM);

        $order = new OrdenEsM();
        $json_string = $order->contentFile();
        $folioFile = json_decode($json_string);   
        $folioFile = $folioFile->products;        

        //puts ok or error and indicates surplus product
        foreach ($folioRead as $prodRead)
        {
            $prod = self::ProductFolio($prodRead->upc,$folioFile);
            $prodRead->quantityf = $prodRead->quantity;
            if($prod != NULL){
                $prodRead->quantity = $prod->quantity;                
            }
            else
               $prodRead->quantity = 0;
        }
        $found = false;
        if(Auth::user()->pclient->use_mode_id == 4){            
            foreach ($folioFile as $upcfile){
                $found = false;
                $prod = self::ProductFolio($upcfile->upc,$folioRead);
                if($prod != NULL)
                    $found = true;
                if($found == false){
                    $prod = new UPCRowView($upcfile->upc,$upcfile->name,$upcfile->quantity);             
                    $prod->ok = false;
                    $prod->quantityf = 0;
                    array_push($folioRead,$prod);
                }
            }
        }
        foreach ($folioRead as $prodRead)
        {              
            if ($prodRead->quantity == $prodRead->quantityf)          
                $prodRead->ok = true;
            else 
                $prodRead->ok = false;    
        }        
        sort($folioRead);
        return $folioRead;
    }    
    
    public static function UPCsFolio($idOrderM)
    {
        $ordersD = OrdenEsD::where('orden_es_m_id',$idOrderM)->get();
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
                }else
                   $folio[$pos]->quantity = $folio[$pos]->quantity+1;              
            }
        }
        return $folio;        
    }
    
    public static function ProductFolio($upc,$Upcs)
    {
        foreach ($Upcs as $upcfolio){
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
