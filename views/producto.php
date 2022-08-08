<?php
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("location: login.html");
} else {
    require "header.php";
    if ($_SESSION['tienda'] == 1) {
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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Producto </h1>

                                <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i
                                                class="fa fa-plus-circle"></i> Agregar
                                    </button>
                                <a id="btnVerCodigos" href="./codigos.php" class="btn btn-bitbucket"
                                   style="background-color: #2B2B2D; !important;"><i
                                            style="padding-right: 5px;" class="fa fa-book"></i> Ver Códigos</a>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tablaListado"
                                       class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>Categoria</th>
                                    <th>Descripcion</th>
                                    <th>Precio</th>
                                    <th>Código</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Categoria</th>
                                    <th>Descripcion</th>
                                    <th>Precio</th>
                                    <th>Código</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistro">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                        <label>Cliente(*):</label>
                                        <input type="hidden" name="idVenta" id="idVenta" hidden>
                                        <select id="idCategoria" name="idCategoria" class="form-control selectpicker"
                                                data-live-search="true" required>
                                        </select>

                                        <label>Descripcion:</label>
                                        <input type="hidden" name="idProducto" id="idProducto">
                                        <input type="text" class="form-control" name="descripcion" id="descripcion" maxlength="50"
                                               placeholder="Descripcion" required>

                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Código:</label>
                                        <input type="text" class="form-control" name="codigo" id="codigo"
                                               placeholder="Código Barras" style="margin-bottom: 10px;" required>
                                        <button class="btn btn-success" type="button" onclick="generarbarcode()">
                                            <i class="fa fa-barcode"></i> Generar
                                        </button>
                                        <button class="btn btn-primary" type="button" onclick="imprimir()"><i class="fa fa-print"></i> Imprimir
                                        </button>
                                        <div id="print">
                                            <svg id="barcode"></svg>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Precio:</label>
                                        <input type="number" class="form-control" name="precio" id="precio" maxlength="50"
                                               placeholder="Precio" required>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i
                                                    class="fa fa-save"></i>
                                            Guardar
                                        </button>

                                        <button class="btn btn-danger" onclick="cancelarForm()" type="button"><i
                                                    class="fa fa-arrow-circle-left"></i> Cancelar
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
        <?php
    } else {
        require 'sinacceso.php';
    }
    require "footer.php";
    ?>
    <script type="text/javascript" src="../public/js/plugins/JsBarcode/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="scripts/producto.js"></script>
    <script type="text/javascript" src="../views/scripts/codigos.js"></script>
    <script type="text/javascript" src="../public/js/plugins/PrintArea/jquery.PrintArea.js"></script>
    <?php
}
ob_end_flush();
?>
