<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UPCFolio
 *
 * @author Arellano
 */
class UPCFolio {
    //put your code here
    var $upc;
    var $quantity;
    var $name;
    
    function UPCFolio($upc,$name,$folio)
    {
        $this->upc = $upc;
        $this->quantity = $folio;
        $this->name = $name;
    }
}
