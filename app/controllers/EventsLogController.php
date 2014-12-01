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
        return View::make('EventsLogTemplate');
    }    
    
    /**
    *post
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {     
        $hour = new DateTime('now');
        $customer = new Customer();
        $customer->name = Input::get('customerName');
        $customer->logo = Input::get('Logo');
        $customer->created_at = $hour;
        $customer->updated_at = $hour;
        $customer->save();
        return Redirect::to('/login');
    }    
    
    public function rows_data()
    {
        $logs = array();
        $i = 0;
        foreach (EventsLog::with('User')->orderBy('id', 'desc')->get() as $log)
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
    
}
