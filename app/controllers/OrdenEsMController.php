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

    public function getIndex()
    {
        $ordern_es_ms = array();
        $ordern_es_ms = $tasks = DB::select(
                'call get_orden_es_ms('.Auth::user()->pclient->id.')');  
        return View::make('OrdenesMTemplate',['ordern_es_ms' => $ordern_es_ms,
            'idUseMode' => Auth::user()->pclient->use_mode_id]);
    }

    public function getIndexData()
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

    }       

    public function folio_capture()
    {   
        $step = 'start';
        return View::make('PortalTemplate',['step' => $step]);
    }               

    /**
    *post
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {                     
        $input = Input::All();
        $idPending = OrdenEsM::idPendingClientWarehouse($input['client_id'],0);           
        if($idPending == 0){ 
            $ordenM = new OrdenEsM();
            $ordenM->pclient_id = $input['client_id'];
            $ordenM->warehouse_id = $input['warehouse_id'];
            $ordenM->folio = $input['folio'];
            $ordenM->type = $input['type'];
            $ordenM->pending = $input['pending'];
            $ordenM->created_at = $input['created_at'];
            $ordenM->updated_at = $input['updated_at'];                
            $ordenM->save();
            return "yes save";
        }
        return "no save";       
    }

    /*
     * Start read for version 4
     */
    public function start_read_v4()
    {            
        $input = Input::All();
        $dateTime = date("Y-m-d H:i:s");            
        if (isset($input['warehouse_id'])){ 
            $idPending = OrdenEsM::idPendingClientWarehouse(
                    Auth::user()->pclient->id,$input['warehouse_id']);                
        }else{
            $idPending = OrdenEsM::idPendingClientWarehouse(
                    Auth::user()->pclient->id,0);                  
        }
        if($idPending == 0 and isset($input['folio'])){                         
            //DB::transaction(function($input){                                                            
                $idClient = Auth::user()->pclient->id;
                Variable::setReadTrue($idClient);                
                $ordenM = new OrdenEsM();
                $ordenM->folio = $input['folio'];
                $ordenM->pclient_id = Auth::user()->pclient->id;
                if (isset($input['warehouse_id']))
                    $ordenM->warehouse_id = $input['warehouse_id'];
                if (isset($input['type'])){
                    if($input['type'] == 'Entrada')
                        $ordenM->type = 1;
                    else
                        $ordenM->type = 0;    
                }
                $ordenM->pending = 1;
                $ordenM->created_at = $dateTime;
                $ordenM->updated_at = $dateTime;                
                $ordenM->save();
                $idUseMode = Auth::user()->pclient->use_mode_id;                      
                return View::make('PortalTemplate',['step' => 'refresh_read']);
            //});
        }
        return View::make('PortalTemplate',['step' => 'start']);
    }        

    public function order_pending()
    {
        $idClient = Input::get('client_id');
        $id = OrdenEsM::idPendingClientWarehouse($idClient,0);
        if($id > 0)
            return "1";
        return "0";
    }    

    public function createReadsXls($id){
        $order = new OrdenEsM();
        $order->createReadsXls($id);
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

    public function postDelete($id){
        //echo $id;die();
        DB::table('events_logs')->where('event_id',$id)->delete();
        DB::table('orden_es_ds')->where('orden_es_m_id',$id)->delete();
        DB::table('orden_es_ms')->where('id',$id)->delete();
        return Response::json(array('ok' => 'ok'));
    }
}
