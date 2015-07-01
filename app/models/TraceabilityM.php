<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TraceabilityM
 *
 * @author Arellano
 */
class TraceabilityM  extends BaseModel{
    //put your code here
    public function user()
    {
        return $this->belongsTo('User');
    }        
}
