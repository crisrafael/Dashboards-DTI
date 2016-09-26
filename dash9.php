<?php
include_once './classes/consultas.php';
include_once './classes/atendentes.php';

$consultas = new Consulta();
$atendentes = new Atendentes();

$Incidente = $consultas->chamadosAbertos(8);
$Problema = $consultas->chamadosAbertos(10);
$Requisicao = $consultas->chamadosAbertos(9);
$AgAte = $consultas->chamadosAguardandoAtendimento();

if (!empty($_POST)) {
    $idFila = $_POST['filas'];
    $slaDentro = $consultas->slaPorFilaSeisMeses($idFila, "dentro");
    $slaFora = $consultas->slaPorFilaSeisMeses($idFila, "fora");
//    $operadoresSD = $atendentes->retornaAtendentes($idFila);
//    $emAtendimentoOperadores = $consultas->emAtendimentoEquipes($operadoresSD["user_id"],$idFila);
//    $encerradosOperadores = $consultas->encerradosAtendente($operadoresSD["user_id"],$idFila);
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="assets/ico/graphic3.ico">

        <title>Dashboard DTI</title>

        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/plugins/morris.css" rel="stylesheet">
        <link href="css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="css/plugins/timeline.css" rel="stylesheet">
        <link href="css/principal.css" rel="stylesheet">
        <link href="css/table.css" rel="stylesheet">
        <!--        <link href="css/styles_legends.css" rel="stylesheet">-->

        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="js/plugins/morris/raphael.min.js"></script>
        <script src="js/plugins/morris/morris.min.js"></script>
        <script src="js/sb-admin-2.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/canvasjs.min.js"></script>
        <script src="js/Chart.js"></script>


    </head>

    <body>

        <div id="wrapper">

            <!-- Navigation -->
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Dashboards</a>
                </div>            

                <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav side-nav">
                        
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#indTempoReal"><i class="fa fa-fw fa-bar-chart-o"></i> Índices Tempo Real<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="indTempoReal" class="collapse">
                                <li>
                                    <a href="index.php"><i class="fa fa-fw fa-table"></i> Visão Geral</a>
                                </li>
                                <li>
                                    <a href="dash2.php"><i class="fa fa-fw fa-bar-chart-o"></i> Gráficos de Equipes</a>
                                </li>
                                <li>
                                    <a href="dash3.php"><i class="fa fa-fw fa-dashboard"></i> SLA Prioridade</a>
                                </li>
                                <li>
                                    <a href="dash4.php"><i class="fa fa-fw fa-dashboard"></i> SLA Filas</a>
                                </li>
                                <li>
                                    <a href="dash5.php"><i class="fa fa-fw fa-dashboard"></i> SLA Expirando</a>
                                </li>
                                <li>
                                    <a href="dash6.php"><i class="fa fa-fw fa-clock-o"></i> Tempo de Vida</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="javascript:;" data-toggle="collapse" data-target="#indHistoricos"><i class="fa fa-fw fa-bar-chart-o"></i> Índices Históricos<i class="fa fa-fw fa-caret-down"></i></a>
                            <ul id="indHistoricos" class="collapsed">
                                <li>
                                    <a href="dash8.php"><i class="fa fa-fw fa-table"></i> Visão Geral</a>
                                </li>
                                <li>
                                    <a href="dash9.php"><i class="fa fa-fw fa-dashboard"></i> SLA Filas</a>
                                </li>
                            </ul>
                        </li>                       

                        <li>
                            <a href="dash7.php"><i class="fa fa-fw fa-table"></i> Movimentação de Ativos</a>
                        </li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
            </nav>

            <div id="page-wrapper">

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-12 col-md-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">

                                        <div class="huge text-center"><b>MONITORAMENTO DE CHAMADOS - DTI</b></div>

                                    </div>
                                </div>

                            </div>
                        </div>            

                    </div>                                                                                
                    
                    <div class="row">
                        <form class="form-horizontal" method="post" action="dash9.php" id="form">                        
                            <div class="form-group">                                                            
                                    <label class="col-md-4 control-label">FILA:</label>                                    
                                    <div class="col-lg-3">
                                        <select class="form-control" name="filas" id="filas">
                                            <option value="0">Selecione a fila...</option>
                                                <?php
                                                $filas = $consultas->getFilas();
                                                while ($linha = mysql_fetch_assoc($filas)) {
                                                    if ($idFila == $linha['id'])
                                                        $selected = "selected";
                                                    else {
                                                        $selected = "";
                                                    }
                                                    echo "<option " . $selected . " value='" . $linha['id'] . "'>" . $linha['name'] . "</option>";
                                                }
                                                ?>                                                                        
                                        </select>
                                    </div>                        
                            </div>
                        </form>
                    </div>

                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>SLA - ÚLTIMOS 6 MESES</b></div>
                                <div class="panel-body">                                

                                    <div class="flot-chart-content" id="Grafico1" style="height: 480px;"></div> 

                                </div>
                            </div>

                        </div>                                            

                    </div>                                            

                </div>

            </div>
        </div>

        <script type="text/javascript">

            <?php if (!empty($_POST)) { ?>

                window.onload = function () {
                
                var grafico1 = new CanvasJS.Chart("Grafico1",
                {
                        animationEnabled: true,
                        legend: {
                                horizontalAlign: "center", // "center" , "right"
                                verticalAlign: "bottom",  // "top" , "bottom"                                                                                                
                        },
                        axisY:{
                            gridThickness: 0,
                            stripLines:[
                            {                
                                    value:85,
                                    color: "#337ab7",
                                    label: "Meta 85%",
                                    opacity: .6
                            }
                            ],
                            valueFormatString:"####"
                        },
                        data: [
                            {
                                    type: "line",
                                    showInLegend: true,
                                    legendText: "SLA DENTRO",
                                    color: "#3CB371",                                   
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $slaDentro["slas_dentro"] ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    legendText: "SLA FORA",
                                    color: "#d9534f",                                   
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $slaFora["slas_fora"] ?>]
                            }
                        ],
                        
                });
                                
                grafico1.render();
                
            }
            
            <?php } ?>

            $(document).ready(function () {
                $('#filas').change(function () {

                    var id = $(this).val();

                    if (id != 0) {
                        
                        $('#form').submit();

                    }

                });
            });                        

        </script>


    </body>

</html>