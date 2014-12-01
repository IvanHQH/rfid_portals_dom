<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Product
 *
 * @author Arellano
 */
class Product extends BaseModel{
    
    public static function nameProduct($upc)
    {                
        $count = Product::where('upc',$upc)->count();
        if($count > 0){
            $products = Product::where('upc',$upc)->get();
            return $products[0]->name;
        }        
        return "";                
    }
    
}
