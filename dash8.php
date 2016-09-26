<?php
include_once './classes/consultas.php';

$consultas = new Consulta();
$Incidente = $consultas->chamadosAbertos(8);
$Problema = $consultas->chamadosAbertos(10);
$Requisicao = $consultas->chamadosAbertos(9);
$AgAte = $consultas->chamadosAguardandoAtendimento();

$encerradosUNIRITTER = $consultas->encerradosInstituicaoSeisMeses("UNIRITTER");
$encerradosFADERGS = $consultas->encerradosInstituicaoSeisMeses("FADERGS");
$encerradosIBMR = $consultas->encerradosInstituicaoSeisMeses("IBMR");
$encerradosFAPA = $consultas->encerradosInstituicaoSeisMeses("FAPA");

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

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>CHAMADOS DOS ÚLTIMOS 6 MESES</b></div>
                                <div class="panel-body">                                
                                    <div class="col-lg-6">
                                        <div class="flot-chart-content" id="Grafico1" style="height: 380px;"></div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="flot-chart-content" id="Grafico2" style="height: 380px;"></div>
                                    </div>                                    
                                    <div class="col-lg-12">
                                        <div class="flot-chart-content" id="Grafico3" style="height: 380px;"></div>
                                    </div>                                    
                                </div>                   
                                <!--<div class="alert-info"><strong>Clique nas legendas para interação com os gráficos.</strong></div>-->
                            </div>
                            

                        </div>

                    </div>                                            

                </div>

            </div>
        </div>

        <script type="text/javascript">
            window.onload = function () {
                
                var grafico1 = new CanvasJS.Chart("Grafico1",
                {
                        animationEnabled: true,
                        title:{
                            text: "Por Instituição",
                            color: "#337ab7",
                            fontSize: 20
                        },
                        axisY:{
                            gridThickness: 1
                        },
                        data: [
                            {
                                    type: "spline",
                                    showInLegend: true,
                                    color: "#d9534f",
                                    legendText: "UNIRITTER",
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $encerradosUNIRITTER["total"]?>]
                            },
                            {
                                    type: "spline",
                                    showInLegend: true,
                                    color: "#3CB371",
                                    legendText: "FADERGS",
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $encerradosFADERGS["total"]?>]
                            },
                            {
                                    type: "spline",
                                    showInLegend: true,
                                    color: "#f0ad4e",
                                    legendText: "IBMR",
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $encerradosIBMR["total"]?>]
                            },
                            {
                                    type: "spline",
                                    showInLegend: true,
                                    color: "#337ab7",
                                    legendText: "FAPA",
                                    indexLabel: "{y}",
                                    dataPoints: [<?php echo $encerradosFAPA["total"]?>]
                            }
                        ],
                        legend: {
                                horizontalAlign: "center", // "center" , "right"
                                verticalAlign: "bottom",  // "top" , "bottom"                                
                                cursor: "pointer",
                                itemclick: function (e) {
                                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                                e.dataSeries.visible = false;
                                        } else {
                                                e.dataSeries.visible = true;
                                }
                                grafico1.render();
                                }
                        }
                });
                var grafico2 = new CanvasJS.Chart("Grafico2",
                {
                        animationEnabled: true,
                        title:{
                            text: "Por Equipes",
                            fontSize: 20
                        },
                        axisY:{
                            gridThickness: 1
                        },
                        data: [
                            
                            {
                                    type: "line",
                                    showInLegend: true,
                                    color: "#337ab7",
                                    legendText: "SERVICE DESK",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $ServiceDesk = $consultas->encerradosEquipesSeisMeses(7);
                                                    echo $ServiceDesk["total"]
                                                ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    color: "#2ECCFA",
                                    legendText: "ANALISTAS FUNCIONAIS",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $Analistas = $consultas->encerradosEquipesSeisMeses(34);
                                                    echo $Analistas["total"]
                                                ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    //color: "#f0ad4e",
                                    legendText: "INFRAESTRUTURA",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $Infra = $consultas->encerradosEquipesSeisMeses(20);
                                                    echo $Infra["total"]
                                                ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    color: "#d9534f",
                                    legendText: "SUPORTE UNIRITTER",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $SuporteUnR = $consultas->encerradosEquipesSeisMeses("8,9,10,23");
                                                    echo $SuporteUnR["total"]
                                                ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    color: "#3CB371",
                                    legendText: "SUPORTE FADERGS",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $SuporteFad = $consultas->encerradosEquipesSeisMeses("14,12,26,11,13,15");
                                                    echo $SuporteFad["total"]
                                                ?>]
                            },
                            {
                                    type: "line",
                                    showInLegend: true,
                                    color: "#f0ad4e",
                                    legendText: "SUPORTE IBMR",
                                    //indexLabel: "{y}",
                                    dataPoints: [<?php $SuporteIbmr = $consultas->encerradosEquipesSeisMeses("16,17,18");
                                                    echo $SuporteIbmr["total"]
                                                ?>]
                            }
                        ],
                        legend: {
                                horizontalAlign: "center", // "center" , "right"
                                verticalAlign: "bottom",  // "top" , "bottom"                        
                                cursor: "pointer",
                                itemclick: function (e) {
                                        if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                                                e.dataSeries.visible = false;
                                        } else {
                                                e.dataSeries.visible = true;
                                }
                                grafico2.render();
                                }
                        }
                });
                var grafico3 = new CanvasJS.Chart("Grafico3",
                {
                        animationEnabled: true,
                        title:{
                            text: "Por Hora de Abertura",
                            fontSize: 20
                        },
                        axisY:{
                            gridThickness: 1
                        },
                        data: [
                            {
                                    type: "spline",
                                    //showInLegend: true,
                                    color: "#337ab7",                                    
                                    indexLabel: "{y}",
                                    dataPoints: [<?php $PorHora = $consultas->abertosPorHoraSeisMeses();
                                                    echo $PorHora["total"]
                                                ?>]
                            }
                        ]                        
                });
                
                grafico1.render();
                grafico2.render();
                grafico3.render();
            }                                   

        </script>


    </body>

</html>