<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PClient
 *
 * @author Arellano
 */
class Pclient extends BaseModel{

    public static function idUseMode($useMode)
    { 
        //OrdenEsM::where('orden_es_m_id',$id)->get();
        $userMode = UseMode::where('name',$useMode)->get();
        return $userMode[0]->id;
    }
    
    public function useMode()
    {
        return $this->belongsTo('UseMode');
    }
}
