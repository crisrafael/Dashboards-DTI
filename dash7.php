<?php
include_once './classes/consultas.php';
session_start();
$consultas = new Consulta();

$datainicial = @$_POST['datainicial'];
$datafinal = @$_POST['datafinal'];
$botaoGerar = @$_POST['gerar'];

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
        <link href="font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

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
                //"searching": false,
                "info": false,
                "paging": false
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
                                        <div class="huge text-center"><b>RELATÓRIO DE MOVIMENTAÇÕES</b></div>                 
                                    </div>             
                                </div>
                            </div>     
                        </div>            
                    </div>                                       

                    <div class="row">
                        <div class="col-md-8 col-md-offset-3">
                            <form class="form-inline" role="form" method="post" action="?go=relatorio">
                                <div class="form-group" id="fldinicial">
                                    <label>Data Inicial:</label>
                                    <input type="text" class="form-control controls input-append date form_datetime" id="datainicial" name="datainicial" placeholder="Data Inicial">
                                </div>
                                <div class="form-group" id="fldfinal">
                                    <label>Data Final:</label>
                                    <input type="text" class="form-control controls input-append date form_datetime" id="datafinal" name="datafinal" placeholder="Data Final">
                                </div>
                                <button type="submit" class="btn btn-primary" id="gerar" name="gerar">Gerar Relatório</button>
                                <?php if (isset($botaoGerar) && !empty($datainicial) && !empty($datafinal)){
                                    echo "<button type='button' class='btn btn-success' " . "onclick="."\""."location.href="."'" ."./classes/exportaDados.php"."'".";"."\"".">Exportar "."<span class='fa fa-fw fa-file-excel-o' aria-hidden='true'></span>"."</button>";
                                }
                                ?>
                                
                            </form>                                                        
                        </div>                        
                    </div>

                    <br>
                    <div class="row">                        

                        <div class="col-lg-12">
                            <div class="panel panel-primary">
                                <div class="panel-heading"><b>RELATÓRIO DE MOVIMENTAÇÃO DE EQUIPAMENTO</b></div>                            
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <?php
                                        if (!empty($datainicial) && !empty($datafinal)) {
                                            $tabela = $consultas->montaTabelaInventario($datainicial, $datafinal);
                                            echo $tabela;                                            
                                            echo "<script>$(document).ready(function () { $('#tabela').dataTable({ 'order': [[9, 'asc']] }); }); </script>";
                                            echo "<script>$('#datainicial').attr('value','$datainicial'); $('#datafinal').attr('value','$datafinal');</script>";
                                        }
                                        ?>
                                    </div>                                          
                                </div>                                                           
                            </div>                                                            
                        </div>


                    </div>


                </div>

            </div>

        </div>


    </body>

    <script type="text/javascript" src="bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <script type="text/javascript" src="bootstrap-datetimepicker/js/bootstrap-datetimepicker.pt-BR.js" charset="UTF-8"></script>

    <script type="text/javascript">
            $('.form_datetime').datetimepicker({
                todayBtn: 1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1,
                language: 'pt-BR',
                format: 'yyyy-mm-dd hh:ii'
            });

    </script>

</html>

<?php

if (@$_GET['go'] == 'relatorio') {
    
    if (empty($datainicial) || empty($datafinal)) {
        echo "<script>$('#fldinicial').attr('class','form-group has-error'); $('#fldfinal').attr('class','form-group has-error');</script>";
    } else {        
        $_SESSION['datainicial'] = $datainicial;
        $_SESSION['datafinal'] = $datafinal;
    }
}
?>