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
        $id = OrdenEsM::idLastFolio(Input::get('folio'),Input::get('created_at'));   
        if($id != 0){
            $log = new EventsLog();
            $log->event_id = $id;
            $log->user_id = Input::get('user_id');
            $log->type = Input::get('type');
            $log->description = Input::get('description');
            $log->created_at = Input::get('created_at');
            $log->updated_at = Input::get('updated_at');
            $log->customer_id = Input::get('customer_id');
            $log->save(); 
        }
    }    
    
    public function rows_data()
    {
        $logs = array();
        $i = 0;
        foreach (EventsLog::with('User')->where('customer_id',Auth::user()->customer_id)
                ->orderBy('id', 'desc')->get() as $log)
        {
            $newlog = new EventsLog();
            $newlog->name = $log->user->name;
            $newlog->description = $log->description;
            $newlog->created_at = $log->created_at;  
            $logs[$i] = $newlog;
            $i = $i + 1;
        }
        return $logs;
    }       
    
    public function comparison_rows($id)
    {
        $logs = EventsLog::where('event_id',$id)->get();
        $log = $logs[0];
        $comps = explode(',',$log->description);
        $rescomps = array();
        $i = 0;
        foreach($comps as $comp)
        {
            $rescomps[$i] = $comp;
            $i = $i + 1;
        }
        return View::make('EventsLogTemplate',['rescomps' => $rescomps]);
    }    
}
