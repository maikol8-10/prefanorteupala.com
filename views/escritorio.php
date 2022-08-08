<?php
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("location: login.html");
} else {
    require "header.php";
    if ($_SESSION['escritorio'] == 1) {
        require_once "../modelo/Consulta.php";
        $consulta = new Consulta();
        $responseVenta = $consulta->totalVentaHoy();
        $regV = $responseVenta->fetch_object();
        $totalV = $regV->totalVenta;

        $responseVenta = $consulta->totalInventariosHoy();
        $regG = $responseVenta->fetch_object();
        $totalG = $regG->totalGastos;


        //Datos para mostrar el gráfico de barras de las compras
        $compras10 = $consulta->ventasUltimos10Dias();
        $fechasc = '';
        $totalesc = '';
        while ($regfechac = $compras10->fetch_object()) {
            $fechasc = $fechasc . '"' . $regfechac->fecha . '",';
            $totalesc = $totalesc . $regfechac->total . ',';
        }
        //Quitamos la última coma
        $fechasc = substr($fechasc, 0, -1);
        $totalesc = substr($totalesc, 0, -1);

        //Datos para mostrar el gráfico de barras de las ventas
        $ventas12 = $consulta->ventasUltimos_12meses();
        $fechasv = '';
        $totalesv = '';
        while ($regfechav = $ventas12->fetch_object()) {
            $fechasv = $fechasv . '"' . $regfechav->fecha . '",';
            $totalesv = $totalesv . $regfechav->total . ',';
        }

        //Quitamos la última coma
        $fechasv = substr($fechasv, 0, -1);
        $totalesv = substr($totalesv, 0, -1);

        $gastos5 = $consulta->totalInventariosUltimos5Meses();
        $fechasG = '';
        $totalesG = '';
        while ($regfechaG = $gastos5->fetch_object()) {
            $fechasG = $fechasG . '"' . $regfechaG->fecha . '",';
            $totalesG = $totalesG . $regfechaG->total . ',';
        }

        //Quitamos la última coma
        $fechasG = substr($fechasG, 0, -1);
        $totalesG = substr($totalesG, 0, -1);

        ?>
        <!--Contenido-->
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Escritorio
                                </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h4 style="font-size:17px;">
                                                <strong>₡ <?php echo $totalV; ?></strong>
                                            </h4>
                                            <p>Total de las ventas de Hoy</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                        <a href="venta.php?view=ventas" class="small-box-footer">Ir a ventas <i
                                                    class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-red">
                                        <div class="inner">
                                            <h4 style="font-size:17px;">
                                                <strong>₡ <?php echo $totalG; ?></strong>
                                            </h4>
                                            <p>Total de Inventarios de Hoy</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                        <a href="inventario.php" class="small-box-footer">Ir a Inventarios <i
                                                    class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <!--<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="small-box bg-yellow">
                                        <div class="inner">
                                            <h4 style="font-size:17px;">
                                                <strong>₡ <?php echo $totalApartados; ?></strong>
                                            </h4>
                                            <p>Total de los apartados de Hoy</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                        <a href="./venta.php?view=apartados" class="small-box-footer">Ir a apartados <i
                                                    class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>-->
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"
                                             style="font-weight: bold;!important; font-size: 16px;!important;">
                                            Ventas de los últimos 10 días
                                        </div>
                                        <div class="box-body">
                                            <canvas id="ventas" width="400" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"
                                             style="font-weight: bold;!important; font-size: 16px;!important;">
                                            Ventas de los últimos 12 meses
                                        </div>
                                        <div class="box-body">
                                            <canvas id="ventasUltimosDoceMeses" width="400" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <div class="box box-primary">
                                        <div class="box-header with-border"
                                             style="font-weight: bold;!important; font-size: 16px;!important;">
                                            Inventarios de los últimos 5 meses
                                        </div>
                                        <div class="box-body">
                                            <canvas id="gastosUltimosCincoMeses" width="400" height="300"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <?php
    } else {
        require 'sinacceso.php';
    }
    require "footer.php";
    ?>
    <script type="text/javascript" src="../public/js/plugins/chartjs/Chart.min.js"></script>
    <script type="text/javascript" src="../public/js/plugins/chartjs/Chart.bundle.min.js"></script>
    <script type="text/javascript">
        var ctx = document.getElementById("ventas").getContext('2d');
        var compras = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $fechasc; ?>],
                datasets: [{
                    label: 'Ventas en ₡ de los últimos 10 días',
                    data: [<?php echo $totalesc; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById("ventasUltimosDoceMeses").getContext('2d');
        var ventas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $fechasv; ?>],
                datasets: [{
                    label: 'Ventas en ₡ de los últimos 12 Meses',
                    data: [<?php echo $totalesv; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var ctx = document.getElementById("gastosUltimosCincoMeses").getContext('2d');
        var ventas = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php echo $fechasG; ?>],
                datasets: [{
                    label: 'Inventarios en ₡ de los últimos 5 Meses',
                    data: [<?php echo $totalesG; ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
    <?php
}
ob_end_flush();
?>
