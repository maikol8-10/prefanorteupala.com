<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
} else {
    require 'header.php';

    if ($_SESSION['ventas'] == 1) {
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
                                <div id="contentAdd" hidden>
                                    <h1 id="tittleVA" class="box-title" style="font-size: 22px; font-weight: bold">
                                        Ventas</h1>
                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i
                                                class="fa fa-plus-circle"></i> Agregar
                                    </button>

                                </div>
                                <div id="contentSaldo" class="pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado"
                                       class="table table-striped table-bordered table-condensed table-hover"
                                       style="width: 100%;!important;">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vendedor(a)</th>
                                    <th>Tipo Pago</th>
                                    <th>Total Venta</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Cliente</th>
                                    <th>Vendedor(a)</th>
                                    <th>Tipo Pago</th>
                                    <th>Total Venta</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistros" hidden>
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-8 col-md-8 col-sm-8 col-xs-12">
                                        <label>Cliente(*):</label>
                                        <input type="hidden" name="idVenta" id="idVenta" hidden>
                                        <select id="idCliente" name="idCliente" class="form-control selectpicker"
                                                data-live-search="true" required>
                                        </select>
                                        <a id="goClientes" href="./cliente.php?op=registrar" type="button"
                                           style="margin-top: 5px"
                                           class="btn btn-success"><i style="padding-right: 5px;"
                                                                      class="fa fa-plus"></i>Registrar</a>
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Fecha(*):</label>
                                        <input type="date" class="form-control" readonly name="fechaHora" id="fechaHora"
                                               required="">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo de Pago(*):</label>
                                        <select name="tipoPago" id="tipoPago"
                                                class="form-control selectpicker" required="">
                                            <option value="SinpeMovil">Sinpe Movil</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Tipo Comprobante(*):</label>
                                        <select name="tipoComprobante" id="tipoComprobante"
                                                class="form-control selectpicker" required="">
                                            <option value="Ticket">Ticket</option>
                                            <!--<option value="Factura">Factura</option>-->
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                        <label>Número:</label>
                                        <input type="number" class="form-control" name="numeroComprobante"
                                               id="numeroComprobante" maxlength="10" placeholder=""
                                               style="font-size: 24px; font-weight: bold; color: darkred;" readonly>
                                    </div>
                                    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12"
                                         style="margin-top: 25px;">
                                        <a data-toggle="modal" href="#myModal">
                                            <button id="btnAgregarArt" type="button" class="btn btn-success"><span
                                                        class="fa fa-plus"></span> Agregar Producto
                                            </button>
                                        </a>
                                    </div>

                                    <div class="form-group col-lg-2 col-md-2 col-sm-6 col-xs-12" style="width: 100%">
                                        <label>CLICK PARA LEER PRODUCTO</label>
                                        <input type="number" class="form-control" name="delay" id="ingreso"
                                               maxlength="100" placeholder=""
                                               style="font-size: 24px; font-weight: bold; color: #0c0c0c;">
                                        <div id="respuesta" hidden></div>
                                    </div>

                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12">
                                        <table id="detalles"
                                               class="table table-striped table-bordered table-condensed table-hover">
                                            <thead style="background-color:#ff3f06; !important; color: #ffffff; !important;">
                                            <th>Opciones</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>IVA %</th>
                                            <th>Descuento %</th>
                                            <th>Subtotal</th>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th id="thTotalSubtotal"
                                                    style="text-align: end; vertical-align: middle; !important;">
                                                    SUBTOTAL
                                                </th>
                                                <th style="width: 115px; !important;"><h4 style="margin: 0;"
                                                                                          id="subTotalV">₡ 0.00</h4>
                                                    <input
                                                            type="hidden" name="subtotalVenta"
                                                            id="subtotalVenta"></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th id="thTotalIva"
                                                    style="text-align: end; vertical-align: middle; !important;">
                                                    IVA
                                                </th>
                                                <th style="width: 115px; !important;"><h4 style="margin: 0;"
                                                                                          id="montoIva">₡ 0.00</h4>
                                                    <input
                                                            type="hidden" name="totalIvaFinal"
                                                            id="totalIvaFinal"></th>
                                            </tr>


                                            <tr id="tr-pago" hidden>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: end; !important; vertical-align: middle; !important;">
                                                    PAGO CLIENTE
                                                </th>
                                                <th style="width: 115px; !important;"><input type="number"
                                                                                             onchange="modificarSubTotales()"
                                                                                             name="pagoCliente"
                                                                                             id="pagoCliente"
                                                                                             class="form-control"
                                                                                             min="0" max="999999"></th>
                                            </tr>
                                            <tr id="tr-vuelto" hidden>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: end; !important; vertical-align: middle; !important;">
                                                    VUELTO
                                                </th>
                                                <th style="width: 115px; !important;"><h4 id="vuelto">₡ 0</h4><input
                                                            type="hidden"
                                                            name="vueltoCliente"
                                                            id="vueltoCliente"
                                                            class="form-control" min="0" max="999999"></th>
                                            </tr>

                                            <tr id="tr-transporte">
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th style="text-align: end; !important; vertical-align: middle; !important;">
                                                    TRANSPORTE
                                                </th>
                                                <th style="width: 115px; !important;"><input type="number"
                                                                                             onchange="modificarSubTotales()"
                                                                                             onkeyup="modificarSubTotales()"
                                                                                             value="0"
                                                                                             name="totalTransporte"
                                                                                             id="totalTransporte"
                                                                                             class="form-control"
                                                                                             min="0" max="999999"></th>
                                            </tr>

                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th id="thTotal"
                                                    style="text-align: end; vertical-align: middle; !important;">
                                                    TOTAL
                                                </th>
                                                <th style="width: 115px; !important;"><h4 style="margin: 0;" id="total">
                                                        ₡ 0.00</h4><input
                                                            type="hidden" name="totalVenta"
                                                            id="totalVenta"></th>
                                            </tr>
                                            </tfoot>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div id="contentSaldo" class="pull-right">
                                            <button id="btnCalcularTotales" type="button"
                                                    onclick="modificarSubTotales()"
                                                    class="btn btn-success"><i class="fa fa-calculator"></i>
                                                Calcular
                                            </button>
                                        </div>
                                        <button class="btn btn-primary" type="button" id="btnGuardar"><i
                                                    class="fa fa-save"></i> Guardar
                                        </button>

                                        <button id="btnCancelar" class="btn btn-danger" onclick="cancelarform()"
                                                type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="width: 45% !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Seleccione un Producto</h4>
                    </div>
                    <div class="modal-body">
                        <table id="tblarticulos" class="table table-striped table-bordered table-condensed table-hover"
                               style="width: 100% !important;">
                            <thead>
                            <th>Opciones</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Código</th>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                            <th>Opciones</th>
                            <th>Producto</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Código</th>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin modal -->
        <?php
    } else {
        require 'noacceso.php';
    }
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/venta.js"></script>
    <script type="text/javascript" src="scripts/saldoEfectivo.js"></script>
    <script type="text/javascript" src="scripts/PDFCombrobante.js"></script>
    <?php
}
ob_end_flush();
?>