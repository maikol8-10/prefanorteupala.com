<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['tienda'] == 1) {
        ?>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
        </head>
        <body onload="window.print();">
        <?php

        require_once "../modelo/Caja.php";
        $caja = new Caja();
        $rspta = $caja->reporteCaja($_GET["id"]);
        $reg = $rspta->fetch_object();

        $title = "REPORTE DE FLUJO DE CAJA";
        $telefono = "+506 71571234";

        ?>
        <div class="zona_impresion">
            <!-- codigo imprimir -->
            <br>
            <table border="0" align="center" width="300px">
                <tr>
                    <td align="center" style="display: flex; flex-direction: column;">
                        <!-- Mostramos los datos de la empresa en el documento HTML -->
                        <!--.::<strong> <?php ?></strong>::.<br>-->
                        <img src="../public/images/prefaNorte.png" class="img-responsive col-md-12" alt="img-boutique"
                             style="align-self: center; width: 100px;">
                    </td>

                <tr>
                    <td align="center"><?php echo $title; ?></td>
                </tr>
                <tr>
                    <td align="center"><?php echo $reg->usuario; ?></td>
                </tr>
                <tr>
                    <td align="center"></td>
                </tr>
                </tr>
                <tr>
                    <td align="center" style="font-weight: bold"><?php echo $reg->fecha; ?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
            </table>
            <table border="0" align="center" width="300px">
                <tbody style="display: flex; flex-direction: column">
                <tr>
                    <td style="font-weight: bold">Saldo
                        Inicial: <?php $saldoInicial = ($reg->saldo + $reg->totalVentas + $reg->totalVueltoEfectivoClientes + $reg->totalGastos) - ($reg->totalVentas);
                        echo '' . $reg->saldo; ?></td>
                    <td align="right" style="font-weight: bold">Total
                        Ventas: <?php echo '' . $reg->totalVentas; ?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                </tbody>
            </table>
            <br>
            <!-- Mostramos los detalles de la venta en el documento HTML -->
            <table border="0" align="center" width="300px">
                <tbody style="display: flex; flex-direction: column">
                <tr>
                    <td>Total Gastos==</td>
                    <td>Total Efectivo==</td>
                    <td align="right">Total Caja</td>
                </tr>
               <!-- <tr>
                    <td colspan="3">=======================================</td>
                </tr>-->
                <?php
                echo "<tr>";
                echo "<td>-" . $reg->totalGastos . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";
                echo "<td>" . (($reg->saldo + $reg->totalPagoEfectivoClientes) - ($reg->totalVueltoEfectivoClientes + $reg->totalGastos))."&nbsp&nbsp&nbsp</td>";
                echo "<td align='right'> " . (($reg->saldo + $reg->totalVentas) - ($reg->totalVueltoEfectivoClientes + $reg->totalGastos)) . "</td>";
                echo "</tr>";
                ?>
                <!-- Mostramos los totales de la venta en el documento HTML -->
                </tbody>
            </table>
            <br>
        </div>
        <p>&nbsp;</p>


        </body>
        </html>
        <?php
    } else {
        echo 'No tiene permiso para visualizar el reporte';
    }

}
ob_end_flush();
?>