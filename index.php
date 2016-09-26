<?php
include_once './classes/consultas.php';

$consultas = new Consulta();

$tabela = $consultas->montaTabela();
$AgAte = $consultas->chamadosAguardandoAtendimento();
$encerradosInstituicao = $consultas->encerradosInstituicao();

$Incidente = $consultas->chamadosAbertos(8);
$Problema = $consultas->chamadosAbertos(10);
$Requisicao = $consultas->chamadosAbertos(9);

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

        <script type="text/javascript">
            $.extend($.fn.dataTable.defaults, {
                "searching": false,
                "info": false,
                "paging": false
            });

            $(document).ready(function () {
                $('#tabela').dataTable({
                    "order": [[1, "desc"]]
                });
            });

        </script>


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

                        <div class="col-lg-6">

                            <div class="panel panel-primary">

                                <div class="panel-heading"><b>CHAMADOS POR FILAS</b></div>                            

                                <div class="panel-body">
                                    <div class="table-responsive">
                                                 <?php echo $tabela ?>                                   
                                    </div>                                          
                                </div>                                                           
                            </div>                                                            
                        </div>
                        <div class="col-lg-3 col-md-4">

                            <div class="panel panel-primary">

                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-plus-circle fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $AgAte["total"]; ?></div>
                                            <div>Ag. atendimento</div>
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

                        <div class="col-lg-6">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>ENCERRADOS POR INSTITUIÇÃO</b></div>
                                <div class="panel-body">


                                    <div class="labeled-chart-container">
                                        <div class="canvas-holder">
                                            <div class="flot-chart-content" id="chartContainer" style="height: 400px;"></div>                                                                                                                                                                                
                                        </div>                                                                                    
                                    </div>                                                                                                                                                                

                                </div>
                            </div>
                        </div>                                                                

                        </div>


                        </div>

                        </div>

                        </div>


        <script type="text/javascript">
            window.onload = function () {
                CanvasJS.addColorSet("instituicoes",
                        [
                            "#3CB371", //fadergs
                            "#337ab7", //fapa
                            "#f0ad4e", //ibmr
                            "#d9534f" //uniritter

                        ]);

                var chart = new CanvasJS.Chart("chartContainer",
                        {
                            animationEnabled: true,
                            animationDuration: 1000,
                            colorSet: "instituicoes",
                            data: [
                                {
                                    type: "doughnut",
                                    dataPoints: [<?php echo $encerradosInstituicao["encerrados"]; ?>]
                                }
                            ]
                        });

                chart.render();
            }
        </script>

    </body>

</html>