<?php

include_once 'conexaoBD.php';

class Consulta {

    private $con;

    public function __construct() {
        $this->con = ConexaoBD::conectar();        
    }	       	

    public function encerradosGeral() {

        $query = "select count(*) as total from ticket
	where ticket_state_id in (2,3,10) and
	change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')";

        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }

    public function chamadosAbertos($tipo) {

        $query = "select count(*) as total from ticket
        where type_id = $tipo and ticket_state_id in (1,4,11,12,13,15)";

        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }

    public function chamadosAguardandoAtendimento() {

        $query = "select count(t.tn) as total from ticket t 
        join ticket_state ts on (t.ticket_state_id = ts.id) 
        join ticket_type ttype on (ttype.id = t.type_id)
        where t.user_id = 1 and ts.id = 12";
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }

    public function chamadosAguardandoAtendimentoFilas($fila) {
        $query = "select count(t.tn) as total from ticket t 
        join ticket_state ts on (t.ticket_state_id = ts.id) 
        join ticket_type ttype on (ttype.id = t.type_id)
        where t.user_id = 1 and ts.id = 12 and t.queue_id = $fila";
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }

    public function chamadosEmAtendimentoFilas($fila) {

        $query = "select count(t.tn) as total from ticket t 
        join ticket_state ts on (t.ticket_state_id = ts.id) 
        where ts.id = 4 and t.queue_id = $fila";
        $result = mysql_query($query);
        return mysql_fetch_assoc($result);
    }

    public function chamadosEncerradosHoje($fila) {

        $query = "select count(t.tn) as total from ticket t 
        join ticket_state ts on (t.ticket_state_id = ts.id)
        join ticket_state_type tst on (ts.type_id = tst.id)
        where tst.id = 3 and t.queue_id = $fila and (t.change_time >= DATE_FORMAT(NOW(), '%y-%m-%d'))";
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
  
  
    public function encerradosAtendente($atendentes, $idFila) {

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
        and q.id not in (21,22,24,25,27,28,29)
		and q.valid_id = 1
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
        join queue q on (t.queue_id = q.id)
        where t.ticket_state_id in (2,3,10) and
        t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')
		and q.valid_id = 1
        and q.id not in (21,22,24,25,27,28,29)";

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
        and t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')	
		and q.id not in (21,22,24,25,27,28,29)
		and q.valid_id = 1
        GROUP BY q.name
        ORDER BY fila desc";

		//and t.change_time between '2016-06-01 00:01' and '2016-06-30 23:59'
		
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
    
    public function slaPorFilaSeisMeses($idFila,$sla) {

        $query = "select month(t.change_time) as 'mes',
        count(IF(get_tempo_chamado(t.id) > TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_fora',            
        count(IF(get_tempo_chamado(t.id) <= TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')), 1, null)) AS 'slas_dentro',
        count(t.tn) AS 'total_encerrados'
        from ticket t        
        join sla on (t.sla_id = sla.id)
        where t.ticket_state_id in (2,3,10)
	and t.queue_id = $idFila
        and year(t.change_time) = year(now())
        and month(t.change_time) >= month(now()) - 6
        and month(t.change_time) <> month(now())
        group by mes";		
		
        $result = mysql_query($query);

        $ret["mes"] = "";
        $ret["slas_fora"] = "";        
        $ret["slas_dentro"] = "";
        $ret["total_encerrados"] = "";

        while ($linha = mysql_fetch_array($result)) {
            if ($ret["mes"] != "")
                $ret["mes"].=", ";
            if ($ret["slas_fora"] != "")
                $ret["slas_fora"].=", ";            
            if ($ret["slas_dentro"] != "")
                $ret["slas_dentro"].=", ";
            if ($ret["total_encerrados"] != "")
                $ret["total_encerrados"].=", ";
            
            switch ($linha["mes"]) {
                case 1:
                $mes = "Jan";
                break;
                case 2:
                $mes = "Fev";
                break;
                case 3:
                $mes = "Mar";
                break;
                case 4:
                $mes = "Abr";
                break;
                case 5:
                $mes = "Mai";
                break;
                case 6:
                $mes = "Jun";
                break;
                case 7:
                $mes = "Jul";
                break;
                case 8:
                $mes = "Ago";
                break;
                case 9:
                $mes = "Set";
                break;
                case 10:
                $mes = "Out";
                break;
                case 11:
                $mes = "Nov";
                break;
                case 12:
                $mes = "Dez";
                break;                
            }
            
            $percentSlaFora = round(($linha["slas_fora"] / $linha["total_encerrados"]) * 100, 2);
            $percentSlaDentro = round(($linha["slas_dentro"] / $linha["total_encerrados"]) * 100, 2);
            
            if ($sla = "dentro"){
                $ret["slas_dentro"] .= "{label: " . "\"".$mes."\"". ", y: " . $percentSlaDentro . ", indexLabel: " . "\"" . $percentSlaDentro . " %\"" . "}";
            }
            if ($sla = "fora"){
                $ret["slas_fora"] .= "{label: " . "\"".$mes."\"". ", y: " . $percentSlaFora . ", indexLabel: " . "\"" . $percentSlaFora . " %\"" . "}";
            }                                                
            
        }

        return $ret;
    }

    public function slaPorOperador($operadores) {
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
        AND u.id in ($operadores)
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
    
    public function slaVencendo($area){
        
        switch ($area) {
            case 1:
            $filas = "8,9,10,23"; //uniritter
            break;
            case 2:
            $filas = "11,12,13,14,15,26"; //fadergs
            break;
            case 3:
            $filas = "16,17,18"; //ibmr 
            break;
            case 4:
            $filas = "7"; //service desk
            break;
            case 5:
            $filas = "20"; //infraestrutura
            break;
            case 6:
            $filas = "19"; //desenvolvimento
            break;
        }           
        
        $query = "SELECT q.name AS 'fila',
                    t.tn as 'chamado',
                    get_tempo_chamado(t.id) as 'idade',
                    TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')) as 'sla',
                    CONCAT(u.first_name, ' ', u.last_name) as 'proprietario'
                    FROM ticket t
                    JOIN queue q ON (t.queue_id = q.id)
                    JOIN sla ON (t.sla_id = sla.id)
                    JOIN users u ON (t.user_id = u.id)
                    WHERE 
                    t.ticket_state_id IN (1,4,11,12,13)
                    AND get_tempo_chamado(t.id) < TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00'))
                    AND get_tempo_chamado(t.id) >= TIMEDIFF(TIME(CONCAT(FLOOR(sla.solution_time/60),':00:00')),'02:00:00')
                    AND q.id in ($filas)
                    ORDER BY q.name";
        
        $result = mysql_query($query);

        $table = "<table class='table table-action' id='tabela'>
                  <thead>
                    <tr>
                        <th class='t-medium t-status'><b>FILA</b></th>
                        <th class='t-medium t-scheduled'><b>CHAMADO</b></th>
                        <th class='t-medium t-scheduled'><b>IDADE</b></th>
                        <th class='t-medium t-scheduled'><b>SLA</b></th>
                        <th class='t-medium t-scheduled'><b>PROPRIETÁRIO</b></th>                        
                    </tr>
                  </thead><tbody>";
        
        while ($linha = mysql_fetch_array($result)) {
            $table .= "<tr>
                            <td>".utf8_encode($linha[0])."</td>
                            <td>".utf8_encode($linha[1])."</td>
                            <td>".utf8_encode($linha[2])."</td>
                            <td>".utf8_encode($linha[3])."</td>
                            <td>".utf8_encode($linha[4])."</td>                            
                        </tr>";
        }
        
        $table .= "</tbody></table>";
        
        return $table;            
        
    }

    public function encerradosInstituicao() {

        $query = "select dfv.value_text as 'Instituicao',
	count(t.tn) as 'encerrados'
	from ticket t
	join dynamic_field_value dfv on (t.id = dfv.object_id)
	where t.ticket_state_id in (2,3,10) and dfv.field_id = 17 and
	t.change_time >= (SELECT ADDDATE(LAST_DAY(SUBDATE(CURDATE(), INTERVAL 1 MONTH)), 1) AS 'first')	
	group by dfv.value_text";

        $result = mysql_query($query);

        $ret["Instituicao"] = "";
        $ret["encerrados"] = "";

        $indexLabelFontColor = array("#3CB371", "#337ab7", "#f0ad4e", "#d9534f");
        $indexAnt = 0; //indice do array       

        $consulta = new Consulta();
        $encerradosGeral = $consulta->encerradosGeral();

        while ($linha = mysql_fetch_array($result)) {
            $indexCor = $indexAnt;
            if ($ret["Instituicao"] != "")
                $ret["Instituicao"].=", ";
            if ($ret["encerrados"] != "")
                $ret["encerrados"].=", ";

            $percentInstituicao = round(($linha["encerrados"] / $encerradosGeral["total"]) * 100, 2);

            $ret["encerrados"] .="{y: " . $linha["encerrados"].=", indexLabel: " . "\"" . $linha["Instituicao"] . " - " . $percentInstituicao . " %\"" . ", indexLabelFontColor: " . "\"" . $indexLabelFontColor[$indexCor] . "\"" . "}";
            $indexAnt++;
        }

        return $ret;
    }
    
    public function abertosTempodeVida() {

        $query = "select q.name as fila,
        count(if((DATEDIFF(NOW(),t.create_time)) = 0, 1, null)) as 'Mesmo dia',
	count(if((DATEDIFF(NOW(),t.create_time)) = 1, 1, null)) as '1 dia',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 2 and DATEDIFF(NOW(),t.create_time) <= 5, 1, null)) as '2-5 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 6 and DATEDIFF(NOW(),t.create_time) <= 10, 1, null)) as '6-10 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 11 and DATEDIFF(NOW(),t.create_time) <= 20, 1, null)) as '11-20 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 21 and DATEDIFF(NOW(),t.create_time) <= 30, 1, null)) as '21-30 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) > 30, 1, null)) as 'Mais de 30 dias'
        from ticket t
        join queue q on (t.queue_id = q.id)
        where t.ticket_state_id in (1,4,11,12,13,15)
        and q.valid_id = 1
		and q.id not in (21,22,24,25,27,28,29)		
        group by queue_id
	order by q.name desc";

        $result = mysql_query($query);

        $ret["fila"] = "";
        $ret["Mesmo dia"] = "";
        $ret["1 dia"] = "";
        $ret["2-5 dias"] = "";
        $ret["6-10 dias"] = "";
        $ret["11-20 dias"] = "";
        $ret["21-30 dias"] = "";
        $ret["Mais de 30 dias"] = "";

        while ($linha = mysql_fetch_array($result)) {
            if ($ret["fila"] != "")
                $ret["fila"].=", ";
            if ($ret["Mesmo dia"] != "")
                $ret["Mesmo dia"].=", ";
            if ($ret["1 dia"] != "")
                $ret["1 dia"].=", ";
            if ($ret["2-5 dias"] != "")
                $ret["2-5 dias"].=", ";
            if ($ret["6-10 dias"] != "")
                $ret["6-10 dias"].=", ";
            if ($ret["11-20 dias"] != "")
                $ret["11-20 dias"].=", ";
            if ($ret["21-30 dias"] != "")
                $ret["21-30 dias"].=", ";
            if ($ret["Mais de 30 dias"] != "")
                $ret["Mais de 30 dias"].=", ";

            if ($linha["Mesmo dia"] <= 0) {
                $ret["Mesmo dia"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["Mesmo dia"] .="{y: " . $linha["Mesmo dia"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["1 dia"] <= 0) {
                $ret["1 dia"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["1 dia"] .="{y: " . $linha["1 dia"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["2-5 dias"] <= 0) {
                $ret["2-5 dias"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["2-5 dias"] .="{y: " . $linha["2-5 dias"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["6-10 dias"] <= 0) {
                $ret["6-10 dias"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["6-10 dias"] .="{y: " . $linha["6-10 dias"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["11-20 dias"] <= 0) {
                $ret["11-20 dias"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["11-20 dias"] .="{y: " . $linha["11-20 dias"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["21-30 dias"] <= 0) {
                $ret["21-30 dias"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["21-30 dias"] .="{y: " . $linha["21-30 dias"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";

            if ($linha["Mais de 30 dias"] <= 0) {
                $ret["Mais de 30 dias"] .="{y: " . "\"" . "\"" . ", label: " . "\"" . $linha["fila"] . "\"" . "}";
            } else
                $ret["Mais de 30 dias"] .="{y: " . $linha["Mais de 30 dias"].=", label: " . "\"" . $linha["fila"] . "\"" . "}";
        }

        return $ret;
    }

    public function abertosTempodeVidaGeral() {
        $query = "select  count(if((DATEDIFF(NOW(),t.create_time)) = 0, 1, null)) as 'Mesmo dia',
	count(if((DATEDIFF(NOW(),t.create_time)) = 1, 1, null)) as '1 dia',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 2 and DATEDIFF(NOW(),t.create_time) <= 5, 1, null)) as '2-5 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 6 and DATEDIFF(NOW(),t.create_time) <= 10, 1, null)) as '6-10 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 11 and DATEDIFF(NOW(),t.create_time) <= 20, 1, null)) as '11-20 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) >= 21 and DATEDIFF(NOW(),t.create_time) <= 30, 1, null)) as '21-30 dias',
	count(if((DATEDIFF(NOW(),t.create_time)) > 30, 1, null)) as 'Mais de 30 dias'
        from ticket t 
		join queue q on (t.queue_id = q.id)
        where t.ticket_state_id in (1,4,11,12,13,15)
		and q.valid_id = 1
        and q.id not in (21,22,24,25,27,28,29)";

        $result = mysql_query($query);

        return mysql_fetch_assoc($result);
    }
    
    public function getFilas() {
        $query = "SELECT * from queue
                    where id not in (21,22,24,25,27,28,29)
                    order by name";
        
        $result = mysql_query($query);

        return $result;
    }

    public function montaTabelaInventario($datainicial, $datafinal) {
        
        $query = "select h.patrimonio AS 'Patrimônio',
                    t.tn AS 'Número da Movimentação',
		    gc.name as 'Tipo',
                    h.instituicao_in AS 'Instituição Origem',
                    h.unidade_in AS 'Unidade Origem',
                    h.local_out AS 'Local Origem',
                    g2.name AS 'Status Origem',
                    h.instituicao_out AS 'Instituição Destino',
                    h.unidade_out AS 'Unidade Destino',
                    h.local_in AS 'Local Destino',
                    g.name AS 'Status Destino',
                    DATE_FORMAT((h.create_time), '%d/%m/%Y %H:%i')
		    
                    from historico_movimentacao_patrimonio h
                            join general_catalog g on(g.id = h.status_in)
                            join general_catalog g2 on(g2.id = h.status_out)	
                            join configitem ci on (ci.id = h.configitem_id)
                            join xml_storage xs on (xs.xml_key = ci.last_version_id)
                            join general_catalog gc on(gc.id = xs.xml_content_value)
			    join ticket t on (t.id = h.ticket_id)	

                    where h.create_time between '$datainicial' and '$datafinal'
                    and xs.xml_content_key = '[1]{''Version''}[1]{''Tipo''}[1]{''Content''}'";
        
        $result = mysql_query($query);
        
        $table = "<table class='table table-action' id='tabela'>
                  <thead>
                    <tr>
                        <th class='t-medium t-status'><b>Patrimônio</b></th>
                        <th class='t-medium t-status'><b>Número da Movimentação</b></th>
                        <th class='t-medium t-scheduled'><b>Tipo</b></th>
                        <th class='t-medium t-scheduled'><b>Instituição Origem</b></th>
                        <th class='t-medium t-scheduled'><b>Unidade Origem</b></th>
                        <th class='t-medium t-scheduled'><b>Local Origem</b></th>
                        <th class='t-medium t-scheduled'><b>Status Origem</b></th>
                        <th class='t-medium t-scheduled'><b>Instituição Destino</b></th>
                        <th class='t-medium t-scheduled'><b>Unidade Destino</b></th>
                        <th class='t-medium t-scheduled'><b>Local Destino</b></th>
                        <th class='t-medium t-scheduled'><b>Status Destino</b></th>
                        <th class='t-medium t-scheduled'><b>Data da Movimentação</b></th>                        
                    </tr>
                  </thead><tbody>";
        
        while ($linha = mysql_fetch_array($result)) {
            $table .= "<tr>
                            <td>".utf8_encode($linha[0])."</td>
                            <td>".utf8_encode($linha[1])."</td>
                            <td>".utf8_encode($linha[2])."</td>
                            <td>".utf8_encode($linha[3])."</td>
                            <td>".utf8_encode($linha[4])."</td>
                            <td>".utf8_encode($linha[5])."</td>
                            <td>".utf8_encode($linha[6])."</td>
                            <td>".utf8_encode($linha[7])."</td>
                            <td>".utf8_encode($linha[8])."</td>                            
                            <td>".utf8_encode($linha[9])."</td>                        
                            <td>".utf8_encode($linha[10])."</td>                                                                                                         
                            <td>".utf8_encode($linha[11])."</td>                                                                                                         
                        </tr>";
        }
        
        $table .= "</tbody></table>";
        
        return $table;            
    }
    
    public function montaTabela() {
        $query = "select q.name as 'filas',
                count(if(ts.id = 12 and t.user_id = 1, 1, null)) as 'aguardando atendimento',
                        count(if(ts.id = 4, 1, null)) as 'em atendimento',
                        count(if(ts.id in (2,3,10) and (t.change_time >= DATE_FORMAT(NOW(), '%y-%m-%d')), 1, null)) as 'encerrados hoje'
                        from ticket t
                        join queue q on (t.queue_id = q.id)
                        join ticket_state ts on (t.ticket_state_id = ts.id)
                        where q.valid_id = 1
                and q.id not in (21,22,24,25,27,28,29)
                        group by t.queue_id
                order by 2 desc";
        
        $result = mysql_query($query);
        
        $table = "<table class='table table-action' id='tabela'>
                  <thead>
                    <tr>
                        <th style="."\""."text-align: center;"."\""." class='t-medium t-status'><b>FILA</b></th>
                        <th style="."\""."text-align: center;"."\""." class='t-medium t-inactive'><b>AGUARD. ATENDIMENTO</b></th>
                        <th style="."\""."text-align: center;"."\""." class='t-medium t-scheduled'><b>EM ATENDIMENTO</b></th>
                        <th style="."\""."text-align: center;"."\""." class='t-medium t-active'><b>ENCERRADOS HOJE</b></th>
                    </tr>
                  </thead><tbody>";
        
        while ($linha = mysql_fetch_array($result)) {
              if ($linha['filas'] == "Service Desk" || $linha['filas'] == "Analistas Funcionais" || $linha['filas'] == "Infraestrutura"){
                  $table .= "<tr class='blue'>
                            <td class='t-status t-status'><h4><b>".utf8_encode($linha['filas'])."</b></h4></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-inactive'><h4><b>".$linha['aguardando atendimento']."</b></h4></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-scheduled'><h4><b>".$linha['em atendimento']."</b></h4></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-active'><h4><b>".$linha['encerrados hoje']."</b></h4></td>
                        </tr>";
              } else {
                $table .= "<tr>
                            <td class='t-status t-status'><b>".utf8_encode($linha['filas'])."</b></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-inactive'><b>".$linha['aguardando atendimento']."</b></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-scheduled'><b>".$linha['em atendimento']."</b></td>
                            <td style="."\""."text-align: center;"."\""." class='t-status t-active'><b>".$linha['encerrados hoje']."</b></td>
                        </tr>";
              }
                                            
        }
        
        $table .= "</tbody></table>";
        
        return $table;            
    }
    
    public function exportaInventario($datainicial, $datafinal) {
        
        $query = "select h.patrimonio AS 'Patrimônio',
                    t.tn AS 'Número da Movimentação',
		    gc.name as 'Tipo',
                    h.instituicao_in AS 'Instituição Origem',
                    h.unidade_in AS 'Unidade Origem',
                    h.local_out AS 'Local Origem',
                    g2.name AS 'Status Origem',
                    h.instituicao_out AS 'Instituição Destino',
                    h.unidade_out AS 'Unidade Destino',
                    h.local_in AS 'Local Destino',
                    g.name AS 'Status Destino',
                    DATE_FORMAT((h.create_time), '%d/%m/%Y %H:%i')
		    
                    from historico_movimentacao_patrimonio h
                            join general_catalog g on(g.id = h.status_in)
                            join general_catalog g2 on(g2.id = h.status_out)	
                            join configitem ci on (ci.id = h.configitem_id)
                            join xml_storage xs on (xs.xml_key = ci.last_version_id)
                            join general_catalog gc on(gc.id = xs.xml_content_value)
			    join ticket t on (t.id = h.ticket_id)

                    where h.create_time between '$datainicial' and '$datafinal'
                    and xs.xml_content_key = '[1]{''Version''}[1]{''Tipo''}[1]{''Content''}'";
        
        $result = mysql_query($query);
                       
        return $result;
    }
    
    public function encerradosInstituicaoSeisMeses ($instituicao){
        
        $query = "select month(t.change_time) as 'mes',
        count(t.tn) as 'total'
        from ticket t
        join dynamic_field_value dfv on (t.id = dfv.object_id)
        where t.ticket_state_id in (2,3,10)
        and dfv.field_id = 17
        and dfv.value_text = '$instituicao'
        and year(t.change_time) = year(now())
        and month(t.change_time) >= month(now()) - 6
        and month(t.change_time) <> month(now())
        group by mes";
        
        $result = mysql_query($query);
        
        $ret["mes"] = "";
        $ret["total"] = "";
                
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["mes"] != "")
                $ret["mes"].=", ";
            if ($ret["total"] != "")
                $ret["total"].=", ";
            
            switch ($linha["mes"]) {
                case 1:
                $mes = "Jan";
                break;
                case 2:
                $mes = "Fev";
                break;
                case 3:
                $mes = "Mar";
                break;
                case 4:
                $mes = "Abr";
                break;
                case 5:
                $mes = "Mai";
                break;
                case 6:
                $mes = "Jun";
                break;
                case 7:
                $mes = "Jul";
                break;
                case 8:
                $mes = "Ago";
                break;
                case 9:
                $mes = "Set";
                break;
                case 10:
                $mes = "Out";
                break;
                case 11:
                $mes = "Nov";
                break;
                case 12:
                $mes = "Dez";
                break;                
            }
                        
            $ret["total"] .= "{label: " . "\"".$mes."\"". ", y: " . $linha["total"] . "}";                   
                    
        }
        
        return $ret;
        
    }
    
    public function encerradosEquipesSeisMeses($id){
        
        $query = "select month(change_time) as 'mes',
        count(tn) as 'total'
        from ticket
        where ticket_state_id in (2,3,10)
        and queue_id in ($id)
        and year(change_time) = year(now())
        and month(change_time) >= month(now()) - 6
        and month(change_time) <> month(now())
        group by mes";
        
        $result = mysql_query($query);
        
        $ret["mes"] = "";
        $ret["total"] = "";
                
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["mes"] != "")
                $ret["mes"].=", ";
            if ($ret["total"] != "")
                $ret["total"].=", ";
            
            switch ($linha["mes"]) {
                case 1:
                $mes = "Jan";
                break;
                case 2:
                $mes = "Fev";
                break;
                case 3:
                $mes = "Mar";
                break;
                case 4:
                $mes = "Abr";
                break;
                case 5:
                $mes = "Mai";
                break;
                case 6:
                $mes = "Jun";
                break;
                case 7:
                $mes = "Jul";
                break;
                case 8:
                $mes = "Ago";
                break;
                case 9:
                $mes = "Set";
                break;
                case 10:
                $mes = "Out";
                break;
                case 11:
                $mes = "Nov";
                break;
                case 12:
                $mes = "Dez";
                break;                
            }
                                    
            $ret["total"] .= "{label: " . "\"".$mes."\"". ", y: " . $linha["total"] . "}";             
                    
        }
        
        return $ret;
        
    }
    
    public function abertosPorHoraSeisMeses(){
        
        $query = "select concat(date_format(create_time,'%H'),':00') as 'hora',
        count(tn) as 'total'
        from ticket
        where ticket_state_id in (2,3,10)
        and queue_id not in (21,22,24,25,27,28,29)
        and year(create_time) = year(now())
        and month(create_time) >= month(now()) - 6
        and month(create_time) <> month(now())
        group by hora";
        
        $result = mysql_query($query);
        
        $ret["hora"] = "";
        $ret["total"] = "";
                
        while ($linha = mysql_fetch_array($result)) {
            if ($ret["hora"] != "")
                $ret["hora"].=", ";
            if ($ret["total"] != "")
                $ret["total"].=", ";
                                                            
            $ret["total"] .= "{label: " . "\"".$linha["hora"]."\"". ", y: " . $linha["total"] . "}";             
                    
        }
        
        return $ret;
        
    }
    
}



