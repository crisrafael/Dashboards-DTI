<?php

class ConexaoBD {

       
    public static function conectar() {
        $host = "estaleiro";
        $user = "otrs_user";
        $banco = "otrs";
        $password = "0r7$*1019";            
        
        $conn = mysql_connect($host,$user,$password);
        $connect = mysql_select_db($banco, $conn);
        return $connect;
    }
           
   
}
