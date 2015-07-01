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
            $client = new Pclient;          
            if(User::where('name',Input::get('user_name'))->where('password',
                    Input::get('user_password'))->where('type',1)->count() > 0 ){                
                    if(Pclient::where('name',Input::get('pclient_name'))->count() > 0)
                    {
                        return Response::json(array(
                                'success' => false,
                                'errors'  => "ya existe el cliente"                    
                        ));                
                    }                                                             
                    $hour = new DateTime('now');                                 
                    $client->name = Input::get('pclient_name');
                    $client->created_at = $hour;
                    $client->updated_at = $hour;                     

                    $userMode = UseMode::where('name',Input::get('pclient_use_mode'))->get();
                                                            
                    $client->use_mode_id = $userMode[0]->id;                
                    $client->save();
                                          
                    //create and save new variable of client
                    $var = new Variable();
                    $client = Pclient::where('name',Input::get('pclient_name'))->first();
                    if($client != null){
                        $var->pclient_id = $client->id;
                        $var->name = 'read';
                        $var->value = "0";
                        $var->created_at = $hour;
                        $var->updated_at = $hour;
                        $var->save();
                        //end create variable
                        //return Response::json($client);
                        return Response::json(array(
                                'success' => true
                        ));
                    }
            }  
            return Response::json(array(
                    'success' => false,
                    'errors' => 'Usuario ó contraseña de Super Usuario Invalida'
            ));
            //return Response::json($client);
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
