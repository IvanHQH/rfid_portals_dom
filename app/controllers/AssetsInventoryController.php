<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AssetsInventoryController
 *
 * @author Arellano
 */
class AssetsInventoryController extends BaseController{
    //put your code here
    public function rpt_excess($params)
    {
        $type = 'excess';              
        $elements = explode("+", $params);
        //echo $params[0];die();
        if(count($elements) > 1){
            
            $dateInit = $elements[0];
            $dateEnd = $elements[1];
            $exms = new ExcessMissing();
            $assets = $exms->getExcessMissing(Auth::user()->pclient->id,
                    $dateInit,$dateEnd,true,false);
            return View::make('ExcessMissingTemplate',['step' => 'show_assets',
                'type' => $type,'assets' => $assets,'date_init' => $dateInit,
                'date_end' => $dateEnd]);            
        }        
        else{
            return View::make('ExcessMissingTemplate',['step' => 'select_date',
                'type' => $type]);
        }
    }
    
    
    
    public function rpt_missing($params)
    {  
        $type = 'missing';
        $elements = explode("+", $params);
        if(count($elements) > 1){       
            $dateInit = $elements[0];
            $dateEnd = $elements[1];
            $exms = new ExcessMissing();
            $assets = $exms->getExcessMissing(Auth::user()->pclient->id,
                    $dateInit,$dateEnd,false,true);            
            return View::make('ExcessMissingTemplate',['step' => 'show_assets',
                'type' => $type,'assets' => $assets,'date_init' => $dateInit,
                'date_end' => $dateEnd]);               
        }      
        else{
            return View::make('ExcessMissingTemplate',['step' => 'select_date',
                'type' => $type]);
        }
    }    
}
