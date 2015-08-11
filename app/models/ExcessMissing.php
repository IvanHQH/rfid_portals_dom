<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExcessMissing
 *
 * @author Arellano
 */
class ExcessMissing {
    /*
     * Return array of assets excess of one client between one range of dates
     */
    public function getExcessMissing($id,$dateInit,$dateEnd,$excess,$missing)
    {
        $orders = OrdenEsM::where('created_at','>=',$dateInit)->
                where('created_at','<=',$dateEnd)->where('pclient_id',$id)->get();
        $assets = array();
        foreach($orders as $order)
        {
            $msgs = EventsLog::where('event_id',$order->id)->get();
            if(count($msgs) > 0){
                $msg = $msgs[0]->description;
                //message is similar to 
                //'**Excedentes:Article 06 660010110024,**Faltantes:,Prod 01,Asset 02'
                $elements = explode(',', $msg);
                $type = ' ';
                foreach($elements as $element){                          
                    if($element == '**Excedentes:')
                        $type = 'e';
                    elseif($element == '**Faltantes:')
                        $type = 'm';
                    else{
                        if(($type == 'e' && $excess == true) || 
                                ($type == 'm' && $missing == true)){  
                        //if(($type == 'e' && $excess == true)){
                            $asset = new AssetRowView();  
                            //element is similar to 'Article 006 660010110016'
                            $words = explode(' ', $element);
                            //get only UPC
                            $upc = $words[count($words)-1];
                            $prod = Product::getProduct($upc, Auth::user()->pclient->id);                             
                            if($prod != ""){
                                //Start Create assset
                                $asset->upc = $upc;
                                $asset->name = $prod->name;
                                $asset->origin = $prod->warehouse->name;
                                if($type == 'e')
                                   $asset->actual = $order->warehouse->name;
                                else $asset->actual = "";
                                //$asset->dateTime = $order->created_at;
                                $date=date_create($order->created_at);                                                                     
                                $asset->dateTime = date_format($date,'d-m-Y H:i');
                                //End Create asset
                                array_push ($assets, $asset);                 
                            }
                        }
                    }
                }                    
            }                
        }
        return $assets;
    }            
}