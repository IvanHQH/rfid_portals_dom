<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EPC
 *
 * @author Arellano
 */
class EPC {
		
        /*
         * Struct Pclient Table Data Base : 
         * id,
         * use_mode_id,
         * name,
         * company_prefix,
         * reference_serial,
         * created_at,
         * updated_at,
         * deleted_at,
         * logo
         */    
    
        
        public $epc = null;

        function __construct($epc = null) {
                if (!is_null($epc)) {
                        $this -> epc = $epc;
                }
        }

        private function _convBase($numberInput, $fromBaseInput, $toBaseInput) {
                if ($fromBaseInput == $toBaseInput) return $numberInput;
                $fromBase = str_split($fromBaseInput, 1);
                $toBase = str_split($toBaseInput, 1);
                $number = str_split($numberInput, 1);
                $fromLen = strlen($fromBaseInput);
                $toLen = strlen($toBaseInput);
                $numberLen = strlen($numberInput);
                $retval='';
                if ($toBaseInput == '0123456789') {
                        $retval=0;
                        for ($i = 1; $i <= $numberLen; $i++)
                                $retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase), bcpow($fromLen, $numberLen - $i)));
                        return $retval;
                }
                if ($fromBaseInput != '0123456789')
                        $base10 = $this -> _convBase($numberInput, $fromBaseInput, '0123456789');
                else
                        $base10 = $numberInput;
                if ($base10 < strlen($toBaseInput))
                        return $toBase[$base10];
                while($base10 != '0') {
                        $retval = $toBase[bcmod($base10, $toLen)].$retval;
                        $base10 = bcdiv($base10, $toLen, 0);
                }
                return $retval;
        }

        private function _checkDigit($str) {
                $digitsArray = str_split($str);
                $sum = 0;
                $sum1 = 0;
                $sum3 = 0;
                $cnt = 1;
                foreach ($digitsArray as $digit) {
                        if ($cnt % 2 != 0) {
                                $sum3 += $digit;
                        } else {
                                $sum1 += $digit;
                        }
                        $cnt ++;
                }
                $sum = $sum1 + ($sum3 * 3);
                return (round(ceil($sum / 10) * 10) - $sum);
        }

        function setEpc($epc) {
                $this -> epc = $epc;
        }

        function getUpc($debug = false) {
                if (!is_null($this -> epc)) {
                        $bin = $this -> _convBase($this -> epc, '0123456789ABCDEF', '01');
                        if (strlen($bin) != 96) {
                                $l = 96 - strlen($bin);
                                $bin = str_pad($bin, 96, '0', STR_PAD_LEFT);
                                if ($debug) {
                                        echo $bin . '<br/>';
                                }
                        }
                        $header = substr($bin, 0, 8);
                        $filter = substr($bin, 8, 3);
                        $partition = substr($bin, 11, 3);
                        $companyPrefix = bindec(substr($bin, 14, 24));
                        $itemReferenceNumber = bindec(substr($bin, 38, 20));
                        $serialNumber = substr($bin, 58, 38);

                        if ($debug) {
                                echo 'Header (8 bits): ' . $header . '<br/>';
                                echo 'Filter: (3 bits): ' . $filter . '<br/>';
                                echo 'Partition: (3 bits): ' . $partition . '<br/>';
                                echo 'Company Prefix: (24 bits): ' . $companyPrefix . '<br/>';
                                echo 'Indicator Digit + Item Reference Number (20 bits): ' . $itemReferenceNumber . '<br/>';
                                echo 'Serial Number (38 bits): ' . $serialNumber . '<br/>';
                        }
                        $checkDigit = $this -> _checkDigit($companyPrefix . $itemReferenceNumber);
                        return $companyPrefix . $itemReferenceNumber . $checkDigit;
                }
        }
        
        /*
         * Suggest the next UPC for a product new
         */
        public function suggestUPC(){
            $dec = self::nextReferenceSerial();
            $epc = "3034".
                    Auth::user()->pclient->company_prefix.
                    $dec.
                    "000000000";
            self::setEpc($epc);
            $upc = self::getUpc();
            //echo $upc;die(); 
            if(strlen($upc) == 12){
                //echo $upc;die(); 
                return $upc;
            }
            else 
                return "";
        }
    
        public function nextReferenceSerial(){
            $dec = hexdec(Auth::user()->pclient->reference_serial)+4;
            $dec = dechex($dec);            
            $dec = strtoupper($dec);         
            return $dec;
        }
        
}
