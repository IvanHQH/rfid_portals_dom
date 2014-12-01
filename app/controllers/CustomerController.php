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
class CustomerController extends BaseController{
    	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
        public  function index()
        {
            $customers = array();
            $customers = Customer::all();
            return $customers;
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
            $customer = new Customer();
            $customer->name = Input::get('customerName');
            $customer->logo = Input::get('Logo');
            $customer->created_at = $hour;
            $customer->updated_at = $hour;
            $customer->save();
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
