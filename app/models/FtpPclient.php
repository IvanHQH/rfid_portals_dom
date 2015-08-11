<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FtpPclient
 *
 * @author Arellano
 */
class FtpPclient  extends BaseModel{
    //var $server_name;    
    var $prodsPerUnit;
    var $prodsGroup;
    
    /*function FtpPclient($server_name)
    {
        $this->server_name = $server_name;
    }     */ 
    
    public function ContentFileToJson($ftp_user_name,$ftp_user_pass,$server_file,$local_file)
    {
        $filename = 'ftp://'.$ftp_user_name.':'.$ftp_user_pass.
                '@'.$this->server_name.'/'.$local_file;
        //echo $filename;die();
        $handle = fopen($filename, "r");
        
        $row = "";
        $all = "{\"products\":[";
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $elements = explode("|", $line);
                $row = "{";
                for($i = 0;$i <= count($elements); $i++){          
                    if($this->col_name == $i)
                        $row = $row.",\"name\":\"".$elements[$i]."\"";
                    if($this->col_code == $i)
                        $row = $row.",\"upc\":\"".$elements[$i]."\"";
                    elseif( $this->col_quantity == $i)
                        $row = $row.",\"quantity\":\"".$elements[$i]."\"";                    
                }
                $row = $row."}";
                $all = $all.",".$row;
            }
            $all = $all."]}"; 
            fclose($handle);
            $all = str_replace("[,", "[", $all);
            $all = str_replace("{,", "{", $all);
        } else {
            $all = "{\"products\": []}";
        }                  
        $data = json_decode($all);
        $this->prodsPerUnit = $data->products;
        return $this->prodsPerUnit;         
    }
    
    public function groupContent($prodPerUnit)
    {
        //$this->prodsGroup = array();
        $prodsGroup = array();
        foreach($prodPerUnit as $prod)
        {
            $pos = -1;
            $j = 0;
            foreach ($prodsGroup as $upc){
                $pos = -1;
                if($upc->upc == $prod->upc){
                    $pos = $j;
                    break;
                }                            
                $j = $j + 1;
            }                   
            if($pos == -1){
                $p = new UPCRowView($prod->upc,$prod->name,1);
                array_push($prodsGroup,$p);
            }else
               $prodsGroup[$pos]->quantity = $prodsGroup[$pos]->quantity+1;                       
        }
        return $prodsGroup;         
    }
    
    public function groupContentFileToJson($ftp_user_name,$ftp_user_pass,$server_file,$local_file)
    {
        $content = $this->ContentFileToJson($ftp_user_name,$ftp_user_pass,$server_file,$local_file);
        $listProducts = $this->groupContent($content);
        
        $row = "";
        $all = "{\"products\":[";
        
        foreach ($listProducts as $prod){
            //echo $prod->name."<br>";    
            $row = "{";
            $row = $row."\"name\":\"".$prod->name."\"";
            $row = $row.",\"upc\":\"".$prod->upc."\"";
            $row = $row.",\"quantity\":\"".$prod->quantity."\"";
            
            $row = $row."}";
            $all = $all.",".$row;
        }               
        $all = $all."]}";
        $all = str_replace("[,", "[", $all);
        return $all;
    }
    
}
