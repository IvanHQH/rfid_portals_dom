<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UseModeController
 *
 * @author Arellano
 */
class UseModeController extends BaseController {
    //put your code here
    	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
        public  function index()
        {
            $useModes = array();
            $useModes = UseMode::all();
            return $useModes;
        }    
}
