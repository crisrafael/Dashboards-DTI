<?php
include_once './classes/consultas.php';

$consultas = new Consulta();

$SLAs = $consultas->SLAs();
$slaGeral = $consultas->slaGeral();
$slaSoma = $slaGeral["4h"] + $slaGeral["8h"] + $slaGeral["14h"] + $slaGeral["28h"] + $slaGeral["42h"];

$filas = $consultas->slaPorFila();
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
        <link href="css/sb-admin-2.css" rel="stylesheet">
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
                            <div class="col-lg-2 col-md-4">

                                <div class="panel panel-red">

                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaGeral["4h"]; ?></div>
                                                <div>SLA 4h</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaGeral["8h"]; ?></div>
                                                <div>SLA 8h</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-lg-2 col-md-4">
                                <div class="panel panel-green">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaGeral["14h"]; ?></div>
                                                <div>SLA 14h</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <div class="panel panel-grey">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaGeral["28h"]; ?></div>
                                                <div>SLA 28h</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaGeral["42h"]; ?></div>
                                                <div>SLA 42h</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <div class="panel panel-black">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-warning fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $slaSoma; ?></div>
                                                <div>Total</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row">

                            <div class="col-md-12">

                                <div class="panel panel-primary">
                                    <div class="panel-heading"><b>CHAMADOS ENCERRADOS - SLA</b></div>
                                    <div class="panel-body">

                                        <div class="flot-chart-content" id="Grafico1" style="height: 500px;"></div>


                                    </div>
                                </div>

                            </div>                                                            

                        </div>
                    

                </div>

            </div>
        </div>

        <script type="text/javascript">

            window.onload = function () {
                              
                var chart = new CanvasJS.Chart("Grafico1",
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
                                interval: 1                                
                                //labelFontSize: 20
                            },
                            data: [
                                {
                                    type: "stackedBar100",
                                    showInLegend: true,
                                    name: "Fora da SLA",
                                    indexLabelPlacement: "inside",
                                    color: "rgba(223, 51, 38, 0.5)",
                                    //indexLabelFontStyle: "bold",
                                    //indexLabelFontSize: 25,
                                    //indexLabelFontColor: "black",                                    
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php echo $filas["slas_fora"]; ?>]
                                },
                                {
                                    type: "stackedBar100",
                                    showInLegend: true,
                                    name: "Dentro da SLA",
                                    indexLabelPlacement: "inside",
                                    color: "rgba(79, 198, 38, 0.5)",
                                    //indexLabelFontSize: 25,
                                    //indexLabelFontStyle: "bold",                                    
                                    //indexLabelFontColor: "black",                                    
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php echo $filas["slas_dentro"]; ?>]
                                }
                               
                            ]

                        });

                chart.render();

            };

        </script>  

    </body>

</html>
