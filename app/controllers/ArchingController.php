<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomerController
 *
 * @author Arellano
 */
class ArchingController extends BaseController{
    	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
        var $path = '/home/developer/Projects/RFID/laravel/public/';
    
        public  function index()
        {
            $ordern_es_ms = array();
            $ordern_es_ms = OrdenEsM::indexAllForViewLayout();     
            $step = 'inv_init';        
            return View::make('ArchingTemplate',['ordern_es_ms' => $ordern_es_ms,
                'idUseMode' => Auth::user()->pclient->use_mode_id,
                    'step' => $step]);
        }
        
        public  function Inventory_Initial()
        {
            $ordern_es_ms = array();
            $ordern_es_ms = OrdenEsM::indexAllForViewLayout();     
            $step = 'inv_init';        
            return View::make('ArchingTemplate',['ordern_es_ms' => $ordern_es_ms,
                'idUseMode' => Auth::user()->pclient->use_mode_id,
                    'step' => $step]);
        }        
        
        public  function Inventory_End($id)
        {
            $ordern_es_ms = array();
            $ordern_es_ms = OrdenEsM::indexAllForViewLayout(); 
            $order = OrdenEsM::find($id);
            //echo $order->created_at;
            //die();
            $inv_init = (string)$order->created_at;
            $step = 'inv_end';        
            return View::make('ArchingTemplate',['ordern_es_ms' => $ordern_es_ms,
                'idUseMode' => Auth::user()->pclient->use_mode_id,
                    'step' => $step,'inv_init' => $inv_init,'id_inv_init' => $id]);
        }        
        
        public function Up_File($inventories)
        {  
            $step = 'up_file';        
            $elements = explode("+", $inventories);
            if(count($elements) == 2){
                $order = OrdenEsM::find($elements[1]);
                $inv_end = (string)$order->created_at;
                $order = OrdenEsM::find($elements[0]);
                $inv_init = (string)$order->created_at;
                return View::make('ArchingTemplate',
                ['step' => $step,'inv_init'=>$inv_init,'inv_end'=>$inv_end,
                        'inv_init_id'=>$elements[0],
                        'inv_end_id'=>$elements[1]]);                
            }
            else
                return Redirect::to('/arching_inv_init');            
        }            
        
        public function upload_file($inventories)
        {
            $step = 'up_file'; 
            $elements = explode("+", $inventories);
            if(Input::hasFile('archivo')) {
                if (Input::file('archivo')->isValid())
                { 
                   $file = Input::file('archivo')->getClientOriginalName(); 
                   Input::file('archivo')->move($this->path,$file);
                   if(count($elements) == 2){                    
                       $order = OrdenEsM::find($elements[1]);
                       $inv_end = (string)$order->created_at;
                       $order = OrdenEsM::find($elements[0]);
                       $inv_init = (string)$order->created_at;
                       return View::make('ArchingTemplate',
                       ['step' => $step,
                               'inv_init'=>$inv_init,
                               'inv_end'=>$inv_end,
                               'inv_init_id'=>$elements[0],
                               'inv_end_id'=>$elements[1],
                               'file'=>$file]);
                   }
                   else
                       return Redirect::to('/arching_inv_init');                
                }             
            }       
            else
            {      
               if(count($elements) == 2){
                   $order = OrdenEsM::find($elements[1]);
                   $inv_end = (string)$order->created_at;
                   $order = OrdenEsM::find($elements[0]);
                   $inv_init = (string)$order->created_at;
                   return View::make('ArchingTemplate',
                   ['step' => $step,
                           'inv_init'=>$inv_init,
                           'inv_end'=>$inv_end,
                           'inv_init_id'=>$elements[0],
                           'inv_end_id'=>$elements[1]]);                
               }
               else
                   return Redirect::to('/arching_inv_init');                   
            }
        }        
        
	public function Arching_Do($params)
	{                                         
            $step = 'show_arching';   
            $elements = explode("+", $params);
            if(count($elements) > 1){
                $inv_init_dt = $elements[0];
                $inv_end_dt = $elements[1];                  
            }
            $arch = new Arching();
            if(count($elements) == 2){    
                //echo "2";die();
                $archings = $arch->Calculating_Arching($inv_init_dt,$inv_end_dt,null);
                return View::make('ArchingTemplate',
                    ['step' => $step,'inv_init'=>$inv_init_dt,
                        'inv_init_id'=>$elements[0],
                        'inv_end_id'=>$elements[1],    
                        'inv_end'=>$inv_end_dt,'archings'=>$archings]);                
            }
            elseif(count($elements) == 3){
                $fileName = $elements[2];    
                $pathFile = $this->path.$fileName;                            
                try{
                    Excel::load($pathFile, function($archivo)
                    {   
                     $result=$archivo->get();
                     foreach($result as $key => $value){};
                    })->get();                
                }
                catch (Exception $e)
                {
                    return Redirect::back();  
                }
                $archings = $arch->Calculating_Arching($inv_init_dt,$inv_end_dt,$pathFile);
                return View::make('ArchingTemplate',
                    ['step' => $step,'inv_init'=>$inv_init_dt,
                        'inv_init_id'=>$elements[0],
                        'inv_end_id'=>$elements[1],                        
                        'file'=>$elements[2],
                        'inv_end'=>$inv_end_dt,'file_path'=>$pathFile,
                        'archings'=>$archings]);                  
            }
            else
                return Redirect::to('/arching_up_file');                       
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
        
	}

}
