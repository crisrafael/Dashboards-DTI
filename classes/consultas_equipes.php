<?php

include_once 'conexaoBD.php';

class Consulta {

    private $con;

    public function __construct() {
        $this->con = ConexaoBD::conectar();
    }
    
    public function chamadosAbertos($tipo) {

        $query = "select count(*) as total from ticket
        where type_id = $tipo and ticket_state_id in (1,4,11,12,13,15)";

        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }
    
    public function chamadosAguardandoAtendimento($fila) {

        $query = "select count(t.tn) as total from ticket t 
                    join ticket_state ts on (t.ticket_state_id = ts.id) 
                    join ticket_type ttype on (ttype.id = t.type_id)
                    where t.user_id = 1
                    and ts.id = 12
                    and t.queue_id = $fila";
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }
        
    public function emAtendimentoEquipes($operadores,$idFila){
        
        $query = "select CONCAT(u.first_name,' ',u.last_name) as 'operador',
	count(t.tn) as 'total'
        from ticket t
        join users u on (t.user_id = u.id)
        join ticket_state ts on (t.ticket_state_id = ts.id) 
        join queue q on (t.queue_id = q.id)
        where ts.id = 4
        and u.id in ($operadores)
	and t.ticket_lock_id = 2
        and q.id = $idFila
        group by u.first_name
        order by u.first_name desc";              
        
        $result = mysql_query($query);
        
        $ret["operador"] = "";
        $ret["total"] = "";
        
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["operador"] != "")
                $ret["operador"].=", ";
            if ($ret["total"] != "")
                $ret["total"].=", ";
            
            $ret["total"] .="{y: " . $linha["total"].=", label: " . "\"" . $linha["operador"] . "\"" . ", indexLabel: " . "\"" . $linha["total"] . " \"" . "}";
                        
        }
        
        return $ret;        
        
    }
    
    public function chamadosAbertosTipo ($fila) {
        
        $query = "select count(if(type_id = 8, 1, null)) as 'Incidente',
                count(if(type_id = 9, 1, null)) as 'Requisicao',
                count(if(type_id = 10, 1, null)) as 'Problema'
                from ticket
                where ticket_state_id in (1,4,11,12,13,15)
                and queue_id = $fila";
        
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
        
    }


        public function encerradosAtendente($atendentes,$idFila) {

        $query = "select CONCAT(u.first_name,' ',u.last_name) as 'atendente',
                    count(if(tp.id = 8, 1, null)) as 'Incidente',
                    count(if(tp.id = 9, 1, null)) as 'Requisição',
                    count(if(tp.id = 10, 1, null)) as 'Problema'
                    from ticket t
                    join users u on (t.user_id = u.id)
                    join ticket_state ts on (t.ticket_state_id = ts.id)                    
                    join ticket_type tp on (t.type_id = tp.id)
                    join queue q on (t.queue_id = q.id)
                    where t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
                    and t.ticket_state_id IN (2,3,10)
                    and t.user_id in ($atendentes)
                    and q.id = $idFila
                    group by atendente
                    order by atendente desc";

        $result = mysql_query($query);
        
        $ret["atendente"] = "";
        $ret["Incidente"] = "";
        $ret["Requisição"] = "";
        $ret["Problema"] = "";
        
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["atendente"] != "")
                $ret["atendente"].=", ";
            if ($ret["Incidente"] != "")
                $ret["Incidente"].=", ";
            if ($ret["Requisição"] != "")
                $ret["Requisição"].=", ";
            if ($ret["Problema"] != "")
                $ret["Problema"].=", ";
                       
            if ($linha['Incidente'] == 0){
                $ret["Incidente"] .="{y: " . $linha["Incidente"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . "\"" . "}";
            } else {
                $ret["Incidente"] .="{y: " . $linha["Incidente"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . $linha["Incidente"] . " \"" . "}";
            }
            if ($linha['Requisição'] == 0){
                $ret["Requisição"] .="{y: " . $linha["Requisição"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . "\"" . "}";
            } else {
                $ret["Requisição"] .="{y: " . $linha["Requisição"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . $linha["Requisição"] . " \"" . "}";
            }
            if ($linha['Problema'] == 0){
                $ret["Problema"] .="{y: " . $linha["Problema"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . "\"" . "}";
            } else {
                $ret["Problema"] .="{y: " . $linha["Problema"].=", label: " . "\"" . $linha["atendente"] . "\"" . ", indexLabel: " . "\"" . $linha["Problema"] . " \"" . "}";
            }
                    
        }
        
        return $ret;
    }

    
    public function slaPorOperador() {
        $query = "SELECT CONCAT(u.first_name,' ',u.last_name) AS 'operador',
        count(IF(get_tempo_chamado(t.id) > TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_fora',
        count(IF(get_tempo_chamado(t.id) <= TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_dentro',
	count(t.tn) AS 'total_encerrados'
        FROM ticket t
        JOIN users u ON (t.user_id = u.id)
        JOIN sla ON (t.sla_id = sla.id)
        WHERE
        t.ticket_state_id IN (2,3,10) 
        AND t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
        AND u.id in (12,15,25,7)
        GROUP BY u.first_name
        ORDER BY u.first_name";

        $result = mysql_query($query);

        $ret["operador"] = "";
        $ret["slas_fora"] = "";
        $ret["slas_dentro"] = "";
        $ret["total_encerrados"] = "";

        while ($linha = mysql_fetch_array($result)) {
            if ($ret["operador"] != "")
                $ret["operador"].=", ";
            if ($ret["slas_fora"] != "")
                $ret["slas_fora"].=", ";
            if ($ret["total_encerrados"] != "")
                $ret["total_encerrados"].=", ";
            if ($ret["slas_dentro"] != "")
                $ret["slas_dentro"].=", ";

            $percentSlaFora = round(($linha["slas_fora"] / $linha["total_encerrados"]) * 100, 2);
            $percentSlaDentro = round(($linha["slas_dentro"] / $linha["total_encerrados"]) * 100, 2);


            if ($linha["slas_fora"] <= 0) {
                $ret["slas_fora"] .="{y: " . $linha["slas_fora"].=", label: " . "\"" . $linha["operador"] . "\"" . "}";
            } else
                $ret["slas_fora"] .="{y: " . $linha["slas_fora"].=", label: " . "\"" . $linha["operador"] . "\"" . ", indexLabel: " . "\"" . $percentSlaFora . " %\"" . "}";

            if ($linha["slas_dentro"] <= 0) {
                $ret["slas_dentro"] .="{y: " . $linha["slas_dentro"].=", label: " . "\"" . $linha["operador"] . "\"" . "}";
            } else
                $ret["slas_dentro"] .="{y: " . $linha["slas_dentro"].=", label: " . "\"" . $linha["operador"] . "\"" . ", indexLabel: " . "\"" . $percentSlaDentro . " %\"" . "}";
        }

        return $ret;
    }
    
    public function getNomeFila ($idFila) {
        $query = "select name as 'name' from queue
                    where id = $idFila";
        
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
        
    }
    
    public function SLAs() {
        $query = "select q.name as 'fila',
        count(if(get_tempo_chamado(t.id) > '04:00:00' and t.sla_id = 9, 1, null)) as '4h',	
        count(if(get_tempo_chamado(t.id) > '08:00:00' and t.sla_id = 8, 1, null)) as '8h',
        count(if(get_tempo_chamado(t.id) > '14:00:00' and t.sla_id = 7, 1, null)) as '14h',
        count(if(get_tempo_chamado(t.id) > '28:00:00' and t.sla_id = 6, 1, null)) as '28h',
        count(if(get_tempo_chamado(t.id) > '42:00:00' and t.sla_id = 5, 1, null)) as '42h',

	count(if(get_tempo_chamado(t.id) > '04:00:00' and t.sla_id = 9, 1, null)) +	
        count(if(get_tempo_chamado(t.id) > '08:00:00' and t.sla_id = 8, 1, null)) +
        count(if(get_tempo_chamado(t.id) > '14:00:00' and t.sla_id = 7, 1, null)) +
        count(if(get_tempo_chamado(t.id) > '28:00:00' and t.sla_id = 6, 1, null)) +
        count(if(get_tempo_chamado(t.id) > '42:00:00' and t.sla_id = 5, 1, null)) as total
	from ticket t
        join queue q on (t.queue_id = q.id)
        where t.ticket_state_id in (2,3,10) and
        t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
        and q.id not in (22,24,25,27,28,29)
        group by q.name
	having (total > 0)
        order by fila desc";

        $result = mysql_query($query);

        $ret["fila"] = "";
        $ret["4h"] = "";
        $ret["8h"] = "";
        $ret["14h"] = "";
        $ret["28h"] = "";
        $ret["42h"] = "";

        while ($linha = mysql_fetch_array($result)) {
            if ($ret["fila"] != "")
                $ret["fila"].=", ";
            if ($ret["4h"] != "")
                $ret["4h"].=", ";
            if ($ret["8h"] != "")
                $ret["8h"].=", ";
            if ($ret["14h"] != "")
                $ret["14h"].=", ";
            if ($ret["28h"] != "")
                $ret["28h"].=", ";
            if ($ret["42h"] != "")
                $ret["42h"].=", ";

            if ($linha["4h"] <= 0) {
                $ret["4h"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else {
                $ret["4h"] .="{y: " . $linha["4h"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            }
            if ($linha["8h"] <= 0) {
                $ret["8h"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else {
                $ret["8h"] .="{y: " . $linha["8h"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            }
            if ($linha["14h"] <= 0) {
                $ret["14h"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else {
                $ret["14h"] .="{y: " . $linha["14h"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            }
            if ($linha["28h"] <= 0) {
                $ret["28h"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else {
                $ret["28h"] .="{y: " . $linha["28h"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            }
            if ($linha["42h"] <= 0) {
                $ret["42h"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else {
                $ret["42h"] .="{y: " . $linha["42h"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            }
        }

        return $ret;
    }

    public function slaGeral() {
        $query = "select count(if(get_tempo_chamado(t.id) > '04:00:00' and t.sla_id = 9, 1, null)) as '4h',
       count(if(get_tempo_chamado(t.id) > '08:00:00' and t.sla_id = 8, 1, null)) as '8h',
       count(if(get_tempo_chamado(t.id) > '14:00:00' and t.sla_id = 7, 1, null)) as '14h',
       count(if(get_tempo_chamado(t.id) > '28:00:00' and t.sla_id = 6, 1, null)) as '28h',
       count(if(get_tempo_chamado(t.id) > '42:00:00' and t.sla_id = 5, 1, null)) as '42h'
        from ticket t
        JOIN queue q ON (t.queue_id = q.id)
        where t.ticket_state_id in (2,3,10) and
        t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
        and q.id not in (22,24,25,27,28,29)";

        $result = mysql_query($query);

        return mysql_fetch_assoc($result);
    }

    public function slaPorFila() {

        $query = "SELECT q.name AS 'fila',
        count(IF(get_tempo_chamado(t.id) > TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_fora',    
        count(t.tn) AS 'total_encerrados', 
        count(IF(get_tempo_chamado(t.id) <= TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_dentro'
        FROM ticket t
        JOIN queue q ON (t.queue_id = q.id)
        JOIN sla ON (t.sla_id = sla.id)
        WHERE 
        t.ticket_state_id IN (2,3,10) 
        AND t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
        and q.id not in (22,24,25,27,28,29)
        GROUP BY q.name
        ORDER BY fila desc";

        $result = mysql_query($query);

        $ret["fila"] = "";
        $ret["slas_fora"] = "";
        $ret["total_encerrados"] = "";
        $ret["slas_dentro"] = "";

        while ($linha = mysql_fetch_array($result)) {
            if ($ret["fila"] != "")
                $ret["fila"].=", ";
            if ($ret["slas_fora"] != "")
                $ret["slas_fora"].=", ";
            if ($ret["total_encerrados"] != "")
                $ret["total_encerrados"].=", ";
            if ($ret["slas_dentro"] != "")
                $ret["slas_dentro"].=", ";

            $percentSlaFora = round(($linha["slas_fora"] / $linha["total_encerrados"]) * 100, 2);
            $percentSlaDentro = round(($linha["slas_dentro"] / $linha["total_encerrados"]) * 100, 2);

            if ($linha["slas_fora"] <= 0) {
                $ret["slas_fora"] .="{y: " . $linha["slas_fora"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["slas_fora"] .="{y: " . $linha["slas_fora"].=", label: " . "\"" . $linha["fila"] . "\"" . ", indexLabel: " . "\"" . $percentSlaFora . " %\"" . "}";

            if ($linha["slas_dentro"] <= 0) {
                $ret["slas_dentro"] .="{y: " . $linha["slas_dentro"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["slas_dentro"] .="{y: " . $linha["slas_dentro"].=", label: " . "\"" . $linha["fila"] . "\"" . ", indexLabel: " . "\"" . $percentSlaDentro . " %\"" . "}";
        }

        return $ret;
    }

}
?>


