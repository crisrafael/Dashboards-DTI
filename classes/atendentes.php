<?php
include_once 'conexaoBD.php';

class Atendentes {
    
    private $con;

    public function __construct() {
        $this->con = ConexaoBD::conectar();
    }
    
    public function retornaAtendentes($idFila) {
        $query = "select CONCAT(u.first_name,' ',u.last_name) as 'atendente',
                    u.id as 'user_id'
                    from users u
                    join personal_queues pq on (u.id = pq.user_id)
                    where pq.queue_id = $idFila";
        
        $result = mysql_query($query);
        
        $ret["user_id"] = "";
        $ret["atendente"] = "";
        
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["user_id"] != "")
                $ret["user_id"].=", ";
            if ($ret["atendente"] != "")
                $ret["atendente"].=", ";
            
            $ret["user_id"] .= $linha["user_id"];
            $ret["atendente"] .= $linha["atendente"];
            
        }
        
        return $ret;
    }            
}
    

