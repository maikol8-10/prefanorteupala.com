<?php
//Activamos el almacenamiento en el buffer
ob_start();
if (strlen(session_id()) < 1)
    session_start();

if (!isset($_SESSION["nombre"])) {
    echo 'Debe ingresar al sistema correctamente para visualizar el reporte';
} else {
    if ($_SESSION['ventas'] == 1) {
        ?>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
            <link href="../public/css/ticket.css" rel="stylesheet" type="text/css">
        </head>
        <body onload="window.print();">
        <?php

        //Incluímos la clase Venta
        require_once "../modelo/Venta.php";
        //Instanaciamos a la clase con el objeto venta
        $venta = new Venta();
        //En el objeto $rspta Obtenemos los valores devueltos del método ventacabecera del modelo
        $rspta = $venta->ventaCabecera($_GET["id"]);
        //Recorremos todos los valores obtenidos
        $reg = $rspta->fetch_object();

        //Establecemos los datos de la empresa
        $empresa = "PREFA NORTE UPALA";
        $documento = "Miguel Mora Alvarez - Ced. 204040153";
        $direccion = "100 Oeste del cementerio de San Fernando de Upala";
        $telefono = "+506 2470 1818";
        $email = "prefanortefactura@gmail.com ";

        ?>
        <div class="zona_impresion">
            <!-- codigo imprimir -->
            <br>
            <table border="0" align="center" width="260px">
                <tr>
                    <td style="display: flex; flex-direction: column; text-align: center">
                        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo $empresa; ?>
                        <!-- Mostramos los datos de la empresa en el documento HTML -->
                        <!--.::<strong> <?php echo $empresa; ?></strong>::.<br>-->
                        <!-- <img src="../public/images/logoSteph.png" class="img-responsive col-md-12" alt="img-boutique"
                             style="align-self: center; width: 100px;">-->
                    </td>
                <tr>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td align="center"><?php echo $documento; ?></td>
                </tr>
                <tr>
                    <td></td>
                </tr>
                <tr>
                    <td align="center"><?php echo $direccion; ?></td>
                </tr>
                <tr>
                    <td align="center">Telefono: <?php echo $telefono; ?></td>
                </tr>
                <tr>
                    <td align="center"></td>
                </tr>
                </tr>
                <tr>
                    <td>Fecha: <?php echo $reg->fecha; ?></td>
                </tr>
                <tr>
                    <!-- Mostramos los datos del cliente en el documento HTML -->
                    <td> <?php if ($reg->cliente !== "Publico General") {
                            echo 'Cliente' . ": " . $reg->cliente;
                        } ?></td>
                </tr>
                <tr>
                    <td><?php
                        if ($reg->identificacion != "") {
                            echo 'Identificacion' . ": " . $reg->identificacion;
                        } ?></td>

                </tr>
                <tr>
                    <td>N de Tiquete: <?php echo $reg->numeroComprobante; ?></td>
                </tr>
            </table>
            <br>
            <!-- Mostramos los detalles de la venta en el documento HTML -->
            <table border="0" align="center" width="260px">
                <tbody style="display: flex; flex-direction: column">
                <tr>
                    <td>CANT======</td>
                    <td>DESCRIPCION======</td>
                    <td>TOTAL</td>
                </tr>
                <!-- <tr>
                     <td colspan="3">=======================================</td>
                 </tr>-->
                <?php
                $rsptad = $venta->ventaDetalle($_GET["id"]);
                $cantidad = 0;
                $descuentoTotal = 0;
                $subtotal = 0;
                while ($regd = $rsptad->fetch_object()) {
                    $descuentoColones = substr($regd->descuentoColones, 0, -5);
                    echo "<tr>";
                    echo "<td>" . $regd->cantidad . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";
                    echo "<td>" . $regd->categoria . ' ' . $regd->descripcion . "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>";
                    echo "<td align='right'> " . ($regd->precioVenta * $regd->cantidad - $descuentoColones) . "</td>";
                    echo "</tr>";
                    $cantidad += $regd->cantidad;
                    $descuentoTotal += $regd->descuentoColones;
                    $subtotal += $regd->precioVenta * $regd->cantidad;
                }
                ?>
                <!-- Mostramos los totales de la venta en el documento HTML -->
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr style="display: flex; justify-content: flex-end;">
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td align="right">===CRC===</td>
                    <td></td>
                </tr>
                <tr style="display: flex; justify-content: flex-end;">
                    <td>&nbsp;</td>
                    <td align="right">Subtotal:</td>
                    <td align="right"> <?php echo $subtotal; ?></td>
                </tr>
                <tr style="display: flex; justify-content: flex-end;">
                    <td>&nbsp;</td>
                    <td align="right">Descuento:</td>
                    <td align="right"> <?php echo $descuentoTotal; ?></td>
                </tr>
                <tr style="display: flex; justify-content: flex-end;">
                    <td>&nbsp;</td>
                    <td align="right">IVA:</td>
                    <td align="right"> <?php echo $reg->iva; ?></td>
                </tr>
                <tr style="display: flex; justify-content: flex-end;">
                    <td>&nbsp;</td>
                    <td align="right"><b>TOTAL:</b></td>
                    <td align="right"><b> <?php echo $reg->totalVenta; ?></b></td>
                </tr>
                <?php if ($reg->tipoPago === 'Efectivo') { ?>
                    <tr style="display: flex; justify-content: flex-end;">
                        <td>&nbsp;</td>
                        <td align="right">Pago:</td>
                        <td align="right"> <?php echo $reg->pagoCliente; ?></td>
                    </tr>
                    <tr style="display: flex; justify-content: flex-end;">
                        <td>&nbsp;</td>
                        <td align="right">Vuelto:</td>
                        <td align="right"> <?php echo $reg->vueltoCliente; ?></td>
                    </tr>
                <?php } ?>


                <tr>
                    <td colspan="3">N de articulos: <?php echo $cantidad; ?></td>
                </tr>
                <tr>
                    <td colspan="3">Medio de pago: <?php echo $reg->tipoPago; ?></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" align="center">Gracias por su compra ;)!</td>
                </tr>
                <!--<tr>
                    <td colspan="3" align="center">Steph BoutiqueAmericana</td>
                </tr>-->
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                </tbody>
            </table>
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