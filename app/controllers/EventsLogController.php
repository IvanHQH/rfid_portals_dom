<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LogController
 *
 * @author Arellano
 */
class EventsLogController extends BaseController {
    //put your code here
    
    public function index()
    {
        
    }    
    
    /**
    *post
     * save log handheld
     */
    public function store()
    {    
        $idUseMode = Pclient::find(Input::get('client_id'))->useMode->id;
        switch($idUseMode){
            case 1://folio comparison   
                $id = OrdenEsM::idLastFolio(Input::get('folio'),
                        Input::get('created_at'));   
                if($id != 0){
                    $log = new EventsLog();
                    $log->event_id = $id;
                    $log->user_id = Input::get('user_id');
                    $log->type = Input::get('type');
                    $log->description = Input::get('description');
                    $log->created_at = Input::get('created_at');
                    $log->updated_at = Input::get('updated_at');
                    $log->pclient_id = Input::get('client_id');
                    $log->save(); 
                }                
                return "ok";
                break;
            case 2://inventory place
            case 3://inventory
                $id = OrdenEsM::idLastClient(Input::get('client_id'),
                        Input::get('created_at'));   
                if($id != 0){
                    $log = new EventsLog();
                    $log->event_id = $id;
                    $log->user_id = Input::get('user_id');
                    $log->description = Input::get('description');
                    $log->created_at = Input::get('created_at');
                    $log->updated_at = Input::get('updated_at');
                    $log->pclient_id = Input::get('client_id');
                    $log->save(); 
                }               
                return "ok";
                break;        
        }        
    }    
    
    /*
     * there is two returns
     * - rescomps when there are not information of accessing or Missing
     * - $rescompsExce and $rescompsMiss when there are information of 
     *      accessing or Missing
     */
    
    public function excess_missing_order($id)
    {
        $rescomps = array();
        $rescompsExce = array();
        $rescompsMiss = array();
        $nextExce = false;
        $nextMiss = false;
        $name = "";
        if(EventsLog::where('event_id',$id)->count() >  0){            
            $logs = EventsLog::where('event_id',$id)->get();
            $log = $logs[0];
            $comps = explode(',',$log->description);            
            foreach($comps as $comp){
                if( strlen($comp) > 0 ){
                    if($comp == "**Excedentes:"){
                        $nextMiss = false;
                        $nextExce = true;    
                    }
                    if($comp == "**Faltantes:"){
                        $nextMiss = true;
                        $nextExce = false;
                    }
                    if($nextExce){
                        if($comp != "**Excedentes:")
                            array_push($rescompsExce,$comp);
                    }
                    if($nextMiss){
                        if($comp != "**Faltantes:")
                            array_push($rescompsMiss,$comp);
                    }
                    if($nextExce == false && $nextMiss == false){
                        array_push($rescomps,$comp);
                    }
                }
            }
            $orderid = OrdenEsM::find($id);            
            if($orderid != NULL){
                $name = $orderid->warehouse->name;   
            }
        }
        if(count($rescomps) > 0){
            return View::make('EventsLogTemplate',['rescomps' => $rescomps,
                'description' => $name]); 
        }else{
            return View::make('EventsLogTemplate',['rescompsExce' => $rescompsExce,
                'rescompsMiss' => $rescompsMiss,'description' => $name]);             
        }
    }    
}
