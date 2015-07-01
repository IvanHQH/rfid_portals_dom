<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProdArchView
 *
 * @author Arellano
 */
class ProdArchView {
    
    var $name;    
    var $upc;
    var $qtyInvInit;    //Quantity Inventory Initial
    var $qtyOutPOS;     //Quantity Out POS
    var $qtyInitLessPOS;//Quantity Inventory Initial Lees Out POS
    var $qtyInvEnd;     //Quantity Inventory End    
    var $qtyDiff;       //Quantity Out POS
    
    function ProdArchView($upc,$name)
    {
        $this->upc = $upc;
        $this->name = $name;
    }   
}
