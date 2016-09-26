<?php
include_once './classes/consultas.php';

$consultas = new Consulta();

if (!empty($_POST)) {
    $idArea = $_POST['areas'];
    $tabela = $consultas->slaVencendo($idArea);
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
        <!--<link href="https://cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet">
            <link href="css/styles_legends.css" rel="stylesheet">-->

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
                    "order": [[0, "asc"]]
                    
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
                        <form class="form-horizontal" method="post" action="dash5.php" id="form">                        
                            <div class="form-group">                                                            
                                    <label class="col-md-4 control-label">ÁREA:</label>                                    
                                    <div class="col-lg-3">
                                        <select class="form-control" name="areas" id="areas">
                                            <?php
                                            $area0 = $area1 = $area2 = $area3 = $area4 = $area5 = $area6 = "";
                                            switch ($idArea){
                                                case 0:
                                                $area0 = "selected";
                                                break;
                                                case 1:
                                                $area1 = "selected";
                                                break;
                                                case 2:
                                                $area2 = "selected";
                                                break;
                                                case 3:
                                                $area3 = "selected";
                                                break;
                                                case 4:
                                                $area4 = "selected";
                                                break;
                                                case 5:
                                                $area5 = "selected";
                                                break;
                                                case 6:
                                                $area6 = "selected";
                                                break;                                                                                                    
                                            }                                            
                                            echo '<option value="0"'.$area0.'>Selecione a área...</option>
                                                  <option value="1"'.$area1.'>Field Support UNIRITTER</option>
                                                  <option value="2"'.$area2.'>Field Support FADERGS</option>
                                                  <option value="3"'.$area3.'>Field Support IBMR</option>
                                                  <option value="4"'.$area4.'>Service Desk</option>
                                                  <option value="5"'.$area5.'>Infraestrutura</option>
                                                  <option value="6"'.$area6.'>Desenvolvimento</option>'                                            
                                            ?>                                            
                                        </select>                                                                                
                                    </div>
                                    
                            </div>
                        </form>
                    </div>


                    <div class="row">

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>CHAMADOS COM SLA EXPIRANDO EM 2 HORAS</b></div>                            
                                <div class="panel-body">
                                    <div class="table-responsive">                                        
                                        <?php if (!empty($_POST)) {
                                            echo $tabela;
                                        }?>
                                    </div>                                          
                                </div>                                                           
                            </div>                                                            
                        </div>


                    </div>


                </div>

            </div>

        </div>


        <script type="text/javascript">
            $(document).ready(function () {
                $('#areas').change(function () {

                    var id = $(this).val();

                    if (id != 0) {
                        
                        $('#form').submit();

                    }

                });
            });  
        </script>

    </body>

</html>