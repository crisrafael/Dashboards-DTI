<?php
include_once '../classes/consultas_equipes.php';
include_once '../classes/atendentes.php';

$consultas = new Consulta();
$atendentes = new Atendentes();

$fila = 23;

$nomeFila = $consultas->getNomeFila($fila);
$AgAte = $consultas->chamadosAguardandoAtendimento($fila);
$abertosTipo = $consultas->chamadosAbertosTipo($fila);
$atendentesSD = $atendentes->retornaAtendentes($fila);
$emAtendimentoOperadores = $consultas->emAtendimentoEquipes($atendentesSD["user_id"],$fila);
$encerradosOperadores = $consultas->encerradosAtendente($atendentesSD["user_id"],$fila);

?>

<!DOCTYPE html>

<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1" name="viewport">


        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/plugins/metisMenu/metisMenu.min.js"></script>
        <script src="../js/plugins/morris/raphael.min.js"></script>
        <script src="../js/plugins/morris/morris.min.js"></script>
        <script src="../js/sb-admin-2.js"></script>
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../js/canvasjs.min.js"></script>
        <script src='../js/flipclock.js'></script>

        <link href="../css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
        <link href="../css/plugins/timeline.css" rel="stylesheet">
        <link href="../css/sb-admin-2.css" rel="stylesheet">
        <link href="../css/plugins/morris.css" rel="stylesheet">
        <link href="../font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="../css/principal.css" rel="stylesheet">
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/table.css" rel="stylesheet">
        <link href="../css/styles_legends.css" rel="stylesheet">        
        <link href="../css/flipclock.css" rel="stylesheet">        

    </head>
    
    <script type="text/javascript">

            setTimeout(function () {
                window.location = "slas.php";

            }, 15000);

    </script>

    <body>

        <div id="wrapper">

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

                <div class="col-lg-12 col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <div class="row">

                                <div class="huge text-center"><b><?php echo $nomeFila['name'] ?></b></div>

                            </div>
                        </div>

                    </div>
                </div>            

            </div>                       
            
            <div id="page-wrapper">
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
                                        <div class="huge"><?php echo $abertosTipo['Requisicao']; ?></div>
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
                                        <div class="huge"><?php echo $abertosTipo['Incidente']; ?></div>
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
                                        <div class="huge"><?php echo $abertosTipo['Problema']; ?></div>
                                        <div>Problemas</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                
                <br><br>

                <div class="row">

                    <div class="col-lg-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading text-center"><b>CHAMADOS EM ATENDIMENTO</b></div>
                            <div class="panel-body">                                

                                <div class="flot-chart-content" id="Grafico1" style="height: 400px;"></div> 

                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">

                        <div class="panel panel-primary">
                            <div class="panel-heading text-center"><b>CHAMADOS ENCERRADOS NO MÊS</b></div>
                            <div class="panel-body">

                                <div class="flot-chart-content" id="Grafico2" style="height: 400px;"></div>                                                                    

                            </div>
                        </div>

                    </div>                    

                </div>
                
                <div class="row">
                    <div class="clock"></div>
                </div>
                
                                
            </div>  
            
            

        </div>
        
        
                                        
        
        <script type="text/javascript">
            var clock;

            $(document).ready(function () {
                clock = $('.clock').FlipClock({
                    clockFace: 'TwentyFourHourClock'
                });
            });
        </script>

        <script type="text/javascript">
                      

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
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(79, 198, 38, 0.5)",                                    
                                    dataPoints: [<?php echo $encerradosOperadores["Requisição"] ?>]
                                },
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Incidente",                                    
                                    indexLabelFontStyle: "bold",
                                    indexLabelPlacement: "inside",
                                    indexLabelFontColor: "black",
                                    color: "rgba(222, 197, 39, 0.5)",                                    
                                    dataPoints: [<?php echo $encerradosOperadores["Incidente"] ?>]
                                }
                                ,
                                {
                                    type: "stackedBar",
                                    showInLegend: true,
                                    name: "Problema",                                    
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
            };
        </script>

    </body>

</html>
