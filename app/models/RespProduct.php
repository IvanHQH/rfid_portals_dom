<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RespProducts
 *
 * @author Arellano
 */
class RespProduct {
    var $name;
    var $quantity;
    
    function RespProduct($name,$quantity)
    {
        $this->quantity = $quantity;
        $this->name = $name;
    }    
}
