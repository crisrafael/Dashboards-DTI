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
    $operadoresSD = $atendentes->retornaAtendentes($idFila);
    $emAtendimentoOperadores = $consultas->emAtendimentoEquipes($operadoresSD["user_id"],$idFila);
    $encerradosOperadores = $consultas->encerradosAtendente($operadoresSD["user_id"],$idFila);
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
                            <ul id="indTempoReal" class="collapsed">
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
                            <ul id="indHistoricos" class="collapse">
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
                        <form class="form-horizontal" method="post" action="dash2.php" id="form">                        
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
                        <div class="col-lg-3 col-md-4">

                            <div class="panel panel-primary">

                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-plus-circle fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $AgAte["total"]; ?></div>
                                            <div>Aguardando atendimento</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-file-text fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $Requisicao["total"]; ?></div>
                                            <div>Requisições</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <div class="panel panel-yellow">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-cog fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $Incidente["total"]; ?></div>
                                            <div>Incidentes</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="panel panel-red">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-warning fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $Problema["total"]; ?></div>
                                            <div>Problemas</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>CHAMADOS EM ATENDIMENTO</b></div>
                                <div class="panel-body">                                

                                    <div class="flot-chart-content" id="Grafico1" style="height: 400px;"></div> 

                                </div>
                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>CHAMADOS ENCERRADOS NO MÊS</b></div>
                                <div class="panel-body">

                                    <div class="flot-chart-content" id="Grafico2" style="height: 400px;"></div>                                                                    

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


                var chart = new CanvasJS.Chart("Grafico2",
                        {
                            animationEnabled: true,
                            animationDuration: 2000,
                            legend: {
                                horizontalAlign: 'center',
                                verticalAlign: 'bottom',
                                fontSize: 25,
                                fontFamily: "Arial"
                            },
                            toolTip: {
                             shared: true
                             },
                            axisY: {
                                tickThickness: 0,
                                lineThickness: 0,
                                valueFormatString: " ",
                                gridThickness: 0
                            },
                            axisX: {
                                tickThickness: 0,
                                lineThickness: 0,
                                labelFontColor: "black",
                                labelFontSize: 20
                            },
                            data: [
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Requisição",
                                    indexLabelFontStyle: "bold",
                                    //indexLabelFontSize: 25,
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(79, 198, 38, 0.5)",
                                    dataPoints: [<?php echo $encerradosOperadores["Requisição"] ?>]
                                },
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Incidente",
                                    //indexLabelFontSize: 25,
                                    indexLabelFontStyle: "bold",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(222, 197, 39, 0.5)",
                                    dataPoints: [<?php echo $encerradosOperadores["Incidente"] ?>]
                                },
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Problema",
                                    //indexLabelFontSize: 25,
                                    indexLabelFontStyle: "bold",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(223, 51, 38, 0.5)",
                                    dataPoints: [<?php echo $encerradosOperadores["Problema"] ?>]
                                }

                            ]

                        });

                var chart2 = new CanvasJS.Chart("Grafico1",
                        {
                            animationEnabled: true,
                            animationDuration: 2000,
                            legend: {
                                horizontalAlign: 'center',
                                verticalAlign: 'bottom',
                                fontSize: 25,
                                fontFamily: "Arial"
                            },
                            /*toolTip: {
                             shared: true
                             },*/
                            axisY: {
                                tickThickness: 0,
                                lineThickness: 0,
                                valueFormatString: " ",
                                gridThickness: 0
                            },
                            axisX: {
                                tickThickness: 0,
                                lineThickness: 0,
                                labelFontColor: "black",
                                labelFontSize: 20
                            },
                            data: [
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Em Atendimento",
                                    //indexLabelFontSize: 25,
                                    indexLabelFontStyle: "bold",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(151,187,205,0.5)",                                    
                                    dataPoints: [<?php echo $emAtendimentoOperadores["total"]; ?>]
                                }
                            ]

                        });

                chart.render();
                chart2.render();
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