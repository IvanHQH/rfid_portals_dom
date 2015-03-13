<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UPCRowView
 *
 * @author Arellano
 */
class UPCRowView {
    //put your code here
    var $upc;
    var $quantity;
    var $name;
    var $ok;
    var $quantityf;
    
    function UPCRowView($upc,$name,$count)
    {
        $this->upc = $upc;
        $this->quantity = $count;
        $this->name = $name;
    }     
      
}
