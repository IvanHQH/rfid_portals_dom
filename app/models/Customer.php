<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Customer
 *
 * @author Arellano
 */
class Customer extends BaseModel{
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];    
}
