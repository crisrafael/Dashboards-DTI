<?php

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
                window.location = "index.php";

            }, 42000);

    </script>

    <body>

        <div id="wrapper">

            <div class="row">

                <div class="col-lg-12 col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">

                                <div class="huge text-center"><b>MONITORAMENTO NAGIOS</b></div>

                            </div>
                        </div>

                    </div>
                </div>            

            </div>                                              
            
            <div id="page-wrapper">
                
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe class="embed-responsive-item" src="http://nagios.uniritter.edu.br/nagvis/frontend/nagvis-js/index.php?mod=Map&act=view&show=UNIRITTER_CAN_Firewall&rotation=UNIRITTER_CAN&rotationStep=0"></iframe>
                </div>    
                
            </div>
            
        </div>               

    </body>

</html>
