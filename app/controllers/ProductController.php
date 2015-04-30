<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProductController
 *
 * @author Arellano
 */
class ProductController extends BaseController{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            $products = Product::where('pclient_id',Auth::user()->pclient->id)->get();
            $upc = new EPC();
            $epcObj = new EPC();            
            $upcSuggest = $epcObj->suggestUPC();         
            //echo $upcSuggest;die();
            $warehouses = Warehouse::where('pclient_id',Auth::user()->pclient->id)->get();
            return View::make('ProductTemplate',['products' => $products,
                'warehouses' => $warehouses,'upcSuggest' => $upcSuggest]);         
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
	public function store($id = 0)
	{
            if(Product::where('name',Input::get('product_name'))->count() > 0){
                return Response::json(array(
                        'success' => false,
                        'errors'  => "ya existe el nombre"                    
                )); 
            }                    
            
            if(Product::where('upc',Input::get('product_upc'))->count() > 0){
                return Response::json(array(
                        'success' => false,
                        'errors'  => "ya existe el UPC"                    
                )); 
            }             
            
            $input = Input::All();
            if ($id == 0)
                $product = new Product();
            else {
                $product = Product::find($id);
                if (!$product) 
                        return App::abort(403, 'Item not found');
            }
            $product->name = $input['product_name'];
            $product->upc = $input['product_upc'];
            $product->description = $input['product_description'];
            $product->pclient_id =  Auth::user()->pclient->id;  
            if(isset($input['product_warehouse']))
                $product->warehouse_id = $input['product_warehouse'];
            $product -> save();
            
            $epcObj = new EPC();
            $refSerial = $epcObj->nextReferenceSerial();
            //if($refSerial == $product->upc){
                $pclient = Pclient::find(Auth::user()->pclient->id);
                $pclient->reference_serial = $refSerial;
                $pclient->save();                
            //}
            //echo "successful";die();
            return Response::json(array(
                    'success' => true,                   
            ));                         
	}

        /*public function saveSerialUPC($refSerial){
            $pclient = Pclient::find(Auth::user()->pclient->id);
            $pclient->reference_serial = $refSerial;
            $pclient->save();
        } */

        public function getProduct($id) {
                $p = Product::find($id);
                if ($p !== null) {
                    return Response::json($p);
                }
        }        
        
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
            $ordern_es_ms = array();
            $ordern_es_ms['orden_es_ms'] = OrdenEsM::all();
            return Response::json($ordern_es_ms);
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
            $p = Product::find($id);
            if ($p) {
                    $p -> delete();
            }
            return Response::json(array('ok' => 'ok'));
	}
        
}
