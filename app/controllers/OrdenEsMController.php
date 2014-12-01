<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OrdenEsMs
 *
 * @author Arellano
 */
class OrdenEsMController extends BaseController{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
    
	public function index()
	{
            $ordern_es_ms = array();
            $ordern_es_ms = OrdenEsM::all();
            return Response::json($ordern_es_ms);
	}
        
	/**
	 *fet
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            echo "hola";
	}       
        
        public function showread()
        {   
            $step = 'start';
            return View::make('ReadTemplate',['step' => $step]);
        }        
        
        public function writeJsonFolio()
        {
            $ordenM = OrdenEsD::UPCFolioNotEnd();       
            $data_to_file_json = json_encode($ordenM);
            $fp = fopen("/home/developer/Projects/RFID/laravel/public/readsupcs.json","w+"); 
            if($fp == false) { 
               die("No se ha podido crear el archivo."); 
            }else{
                fwrite($fp, $data_to_file_json);
            }            
        }
        
        public function writeJsonTags()
        {       
            $ordenM = json_encode(Input::get('orden_es_ds'));       
            $fp = fopen("/home/developer/Projects/RFID/laravel/public/readsupcs.json","w+"); 
            if($fp == false) { 
               die("No se ha podido crear el archivo."); 
            }else{
                fwrite($fp, $ordenM);
            }
            /*$json_string = file_get_contents("/home/developer/Projects/RFID/laravel/public/readsupcs.json");
            $ordersD = json_decode($json_string);
            //$folioUpcs = OrdenEsD::jsonTagsToUpcs($ordersD);
            return Response::json($ordersD);
            /*$data_to_file_json = json_encode($folioUpcs);
            $fp = fopen("/home/developer/Projects/RFID/laravel/public/readsupcs.json","w+"); 
            if($fp == false) { 
               die("No se ha podido crear el archivo."); 
            }else{
                fwrite($fp, $data_to_file_json);
            }       */
        }       
        
	/**
	*post
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */        
	public function store()
	{         
            $idPending = OrdenEsM::idPending();           
            if($idPending == -1){
                $input = Input::All();
                $ordenM = new OrdenEsM();                        
                $ordenM->customer_id = $input['customer_id'];
                $ordenM->folio = $input['folio'];
                $ordenM->type = $input['type'];
                $ordenM->pending = $input['pending'];
                $ordenM->created_at = $input['created_at'];
                $ordenM->updated_at = $input['updated_at'];
                $ordenM->save();                
                return "yes save";
            }
            return "no save";
            /*$idPending = OrdenEsM::idPending();           
            if($idPending == -1){
                $input = Input::All();
                $ordenM = new OrdenEsM();                        
                $ordenM->customer_id = $input['customer_id'];
                $ordenM->folio = $input['folio'];
                $ordenM->type = $input['type'];
                $ordenM->pending = $input['pending'];
                $ordenM->created_at = $input['created_at'];
                $ordenM->updated_at = $input['updated_at'];
                $ordenM->save();                
                return "yes insert";
            }
            elseif($idPending > 0)
            {
                $input = Input::All();
                $ordenM = new OrdenEsM(); 
                $ordenM->id = $idPending;
                $ordenM->created_at = $input['created_at'];
                $ordenM->updated_at = $input['updated_at'];
                $ordenM->save();                
                return "yes update";
            }
            return "no";  */         
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            $orden_es_ds = OrdenEsM::where('orden_es_m_id',$id)->get();
            return Response::Json($orden_es_ds);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{		

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

	}
}
