<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Warehouses
 *
 * @author Arellano
 */
class Warehouse extends BaseModel{
    //put your code here
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];          
}
