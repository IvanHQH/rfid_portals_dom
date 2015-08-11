<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Used for report de excess and missing
 *
 * @author Arellano
 */
class AssetRowView {
    //put your code here
    var $upc;
    var $name;
    var $origin;
    var $actual;
    var $dateTime;//quantity found
    
    function AssetRowView()
    {
    }      
    
    function setAssetRowView($upc,$name,$origin,$actual,$dateTime)
    {
        $this->upc = $upc;
        $this->name = $name;
        $this->origin = $origin;
        $this->actual = $actual;
        $this->dateTime = $dateTime;
    }        
}
