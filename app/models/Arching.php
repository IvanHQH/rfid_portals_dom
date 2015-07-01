<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Arching
 *
 * @author Arellano
 */
class Arching extends BaseModel {
        /*
         * El alogoritmo hace 2 foreach porque puede suceder que un tag no halla
         * sido leido al hacer un inventario
         */
        var $rows;
        
        public function Calculating_Arching($inv_init_dt,$inv_end_dt,$file_path)
        {            
            $idInvInit = self::idOrderM($inv_init_dt); 
            $idInvEnd = self::idOrderM($inv_end_dt);
            $archings = array();
            if($file_path != null)
            {
                $productsPOS = $this->arrayProductsPOS($file_path);            
                if(is_array($productsPOS)){
                    if(count($productsPOS)){                        
                        foreach ($this->rows as $prodPos)
                        {
                            $prodArch = new ProdArchView($prodPos->upc,$prodPos->name);
                            $prodAux = OrdenEsM::getProduct($idInvInit, $prodPos->upc);
                            if($prodAux != null)
                                $prodArch->qtyInvInit = $prodAux->quantity;    
                            else $prodArch->qtyInvInit = 0;
                            $prodAux = OrdenEsM::getProduct($idInvEnd, $prodPos->upc);
                            if($prodAux != null)
                                $prodArch->qtyInvEnd = $prodAux->quantity;                                 
                            else $prodArch->qtyInvEnd = 0;
                            $prodArch->qtyOutPOS = $prodPos->quantity;    
                            $prodArch->qtyInitLessPOS = $prodArch->qtyInvInit - $prodArch->qtyOutPOS;  
                            $prodArch->qtyDiff = $prodArch->qtyInvEnd - $prodArch->qtyInitLessPOS;                            
                            array_push($archings,$prodArch);
                        }                    
                    }
                    unlink($file_path);
                }                
            }
            
            $orden_es_ds = OrdenEsD::UPCsFolio($idInvInit);
            foreach ($orden_es_ds as $prod)
            {
                $band = false;
                foreach ($archings as $arch){
                    if($arch->upc ==  $prod->upc){
                        $band = true;
                        break;
                    }
                }
                if($band == false){
                    $p = Product::where('pclient_id',Auth::user()->pclient->id)->
                            where('upc',$prod->upc)->take(1)->get();
                    if(count($p) > 0){
                        $prodArch = new ProdArchView($prod->upc,$p[0]->name);                    
                        $prodArch->qtyInvInit = $prod->quantity;
                        $prodAux = OrdenEsM::getProduct($idInvEnd, $prod->upc);
                        if($prodAux != null)
                            $prodArch->qtyInvEnd = $prodAux->quantity;                                 
                        else $prodArch->qtyInvEnd = 0;
                        $prodArch->qtyOutPOS = 0;    
                        $prodArch->qtyInitLessPOS = $prodArch->qtyInvInit - $prodArch->qtyOutPOS;  
                        $prodArch->qtyDiff = $prodArch->qtyInvEnd - $prodArch->qtyInitLessPOS;                                        
                        array_push($archings,$prodArch);
                    }
                }
            }
            
            $orden_es_ds = OrdenEsD::UPCsFolio($idInvEnd);
            foreach ($orden_es_ds as $prod)
            {
                $band = false;
                foreach ($archings as $arch){
                    if($arch->upc ==  $prod->upc){
                        $band = true;
                        break;
                    }
                }
                if($band == false){
                    $p = Product::where('pclient_id',Auth::user()->pclient->id)->
                            where('upc',$prod->upc)->take(1)->get();  
                    if(count($p) > 0){
                        $prodArch = new ProdArchView($prod->upc,$p[0]->name);                    
                        $prodArch->qtyInvEnd = $prod->quantity;
                        $prodAux = OrdenEsM::getProduct($idInvInit, $prod->upc);
                        if($prodAux != null)
                            $prodArch->qtyInvInit = $prodAux->quantity;                                 
                        else $prodArch->qtyInvInit = 0;
                        $prodArch->qtyOutPOS = 0;    
                        $prodArch->qtyInitLessPOS = $prodArch->qtyInvInit - $prodArch->qtyOutPOS;  
                        $prodArch->qtyDiff = $prodArch->qtyInvEnd - $prodArch->qtyInitLessPOS;                                        
                        array_push($archings,$prodArch);
                    }
                }
            }                        
            return $archings;
        }        


        public function idOrderM($creted_at)
        {
            $order_m = OrdenEsM::where('created_at',$creted_at)->take(1)->get();
            if($order_m != null)
                return $order_m[0]->id;
            else
                return -1;
        }
        
        public function arrayProductsPOS($filePath)
        {
            /*$order = new OrdenEsM();
            $json_string = $order->contentFile();
            $POSFile = json_decode($json_string);            
            return $POSFile->products;  */
            $this->rows = array();
            Excel::load($filePath, function($archivo)
            {   
             $result=$archivo->get();
             foreach($result as $key => $value)
             {
                $row = new UPCRowView($value->upc,$value->nombre,$value->cantidad); 
                array_push($this->rows, $row);
             };
            })->get();
            return $this->rows;
        }
        
}
