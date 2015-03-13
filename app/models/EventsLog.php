<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Log
 *
 * @author Arellano
 */
class EventsLog extends BaseModel {
    public function User()
    {
        return $this->belongsTo('User');
    }
    
    /*
     * Save log for Portal
     */
    public static function saveLog($messages,$folio,$idUser,$idClient)
    {
        $allmessage = "";
        foreach ($messages as $message) {
            if(strlen($allmessage) > 0)
                $allmessage = $allmessage.",".$message;
            else $allmessage = $message;
        }
        $allmessage = "folio = ".$folio." , ".$allmessage;
        $id = OrdenEsM::idPendingClient($idClient);
        if($id > 0){
            $dt = new DateTime();
            $datetime = $dt->format('Y-m-d H:i:s');
            $log = new EventsLog();
            $log->event_id = $id;
            if($idUser > 0)
                $log->user_id = $idUser;
            else
                $log->user_id = 1;
            $log->type = 1;
            $log->description = $allmessage;
            $log->created_at = $datetime;
            $log->updated_at = $datetime;
            $log->pclient_id = Auth::user()->pclient_id;
            $log->save();
        }
    }    
}
