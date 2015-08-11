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
        //echo count($products);die();
        $upc = new EPC();
        $epcObj = new EPC();            
        $upcSuggest = $epcObj->suggestUPC();                 
        $warehouses = Warehouse::where('pclient_id',Auth::user()->pclient->id)->get();
        $users = User::where('pclient_id',Auth::user()->pclient->id)->get();
        return View::make('ProductTemplate',[
            'products' => $products,
            'warehouses' => $warehouses,
            'upcSuggest' => $upcSuggest,
            'users' => $users
            ]);         
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
        $input = Input::All();
        $validarName = false;
        $validarUpc = false;
        if ($id == 0)
            $product = new Product();                    
        else {
            $product = Product::find($id);
            if($product->name == $input['product_name'])
                $validarName = false;
            if($product->upc = $input['product_upc'])
                $validarUpc = false;
            if (!$product) 
                    return App::abort(403, 'Item not found');
        }
        if($validarName == true)
        {
            if(Product::where('name',Input::get('product_name'))->count() > 0){
                return Response::json(array(
                        'success' => false,
                        'errors'  => "ya existe el nombre"                    
                )); 
            }                    
        }    
        if($validarUpc == true)
        {            
            if(Product::where('upc',Input::get('product_upc'))->count() > 0){
                return Response::json(array(
                        'success' => false,
                        'errors'  => "ya existe el UPC"                    
                )); 
            }    
        }
        $product->name = $input['product_name'];
        $product->upc = $input['product_upc'];
        $product->description = $input['product_description'];
        $product->pclient_id =  Auth::user()->pclient->id;  
        if(isset($input['product_warehouse']))
            $product->warehouse_id = $input['product_warehouse'];
        $product -> save();

        ///version traceability
        if(Auth::user()->pclient->use_mode_id == 5){
            $trace = new TraceabilityM();
            $p = Product::where('upc',$product->upc)->
                    where('pclient_id',Auth::user()->pclient->id)->first();
            $trace->product_id = $p->id;
            if(isset($input['user_name'])){
                $trace->user_id = 1;
            }                    
            if(isset($input['created_at']))  { 
                 $trace->created_at = $input['created_at'];
                 if ($id == 0)
                    $trace->updated_at = $input['created_at'];
            }
            $trace->save();
        }
        ///            

        $epcObj = new EPC();
        $refSerial = $epcObj->nextReferenceSerial();
        $pclient = Pclient::find(Auth::user()->pclient->id);
        $pclient->reference_serial = $refSerial;
        $pclient->save();                
        return Response::json(array(
                'success' => true,                   
        ));                         
    }

    public function getProduct($id) {
        $p = Product::find($id);
        if ($p !== null) {
            if(Auth::user()->pclient->use_mode_id == 5)//trazabilidad                                    
            {
                $tm = TraceabilityM::where('product_id',$id)->first();
                return Response::json(
                        array(                                                                
                            'name' => $p->name,
                            'upc' => $p->upc,
                            'description' => $p->description,                              
                            'created_at' => (string)$tm->created_at,
                            'user_name' => $tm->user->name
                            ));
            }
            else    
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
    public function postDelete($id)
    {
        //echo "ok";die();
        $p = Product::find($id);
        if ($p) {
            $p -> delete();
        }
        return Response::json(array('ok' => 'ok'));
    }
 
    public function add_product()
    {
        $dateTime = date("Y-m-d H:i:s");
        $input = Input::all();
        $product = new Product();
        $product->name = $input['name'];
        $product->description = $input['description'];
        $product->upc = $input['upc'];
        $product->created_at = date("Y-m-d H:i:s");
        $product->updated_at = date("Y-m-d H:i:s");
        $product->save();
        return "yes save";
    }        
    
}
