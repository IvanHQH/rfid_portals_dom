<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserController
 *
 * @author Arellano
 */
class UserController extends BaseController {
	/**
	*post
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $user = new User();
            if(User::where('name',Input::get('user_name_aut'))->where('password',
                    Input::get('user_password_aut'))->where('type',1)->count() > 0 ){
                
                if(User::where('name',Input::get('user_name_new'))->count() > 0)
                {
                    return Response::json(array(
                            'success' => false,
                            'errors'  => "ya existe el usuario"                    
                    ));                
                }                 
                
                $hour = new DateTime('now');                
                $user->name = Input::get('user_name_new');
                $user->password = Input::get('user_password_new');
                $user->created_at = $hour;
                $user->updated_at = $hour;
                if(isset($input['upc'])){
                    $user->upc = $input['upc'];//return 2;
                }                
                $pclient = Pclient::where('name',Input::get('client_name'))->first();
                //return Response::json($pclient);
                if($pclient != null){
                    $user->pclient_id = $pclient->id;
                    $user->save();     
                    return Response::json(array(
                            'success' => true
                    ));  
                }
            }   
            return Response::json(array(
                    'success' => false
            ));
            //return Response::json($client);
	}    
}
