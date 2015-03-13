<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PclientController
 *
 * @author Arellano
 */
class PclientController  extends BaseController{
    	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
        public  function index()
        {
            $clients = array();
            $clients = Pclient::all();
            return $clients;
        }
        /**
	 *fet
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{            
            
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
            $client = new Pclient();
            $client->name = Input::get('clientName');
            $client->logo = Input::get('Logo');
            $client->created_at = $hour;
            $client->updated_at = $hour;
            $client->use_mode_id = Pclient::idUseMode(Input::get('useMode'));
            $client->save();
            return Redirect::to('/login');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
            $custom = Customer::find($id);
            $custom->delete();            
	}
}
