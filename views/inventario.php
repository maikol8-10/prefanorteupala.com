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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Inventario </h1>

                                <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i
                                        class="fa fa-plus-circle"></i> Agregar
                                </button>
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
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Cantidad Construida</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Cantidad Construida</th>
                                    <th>Precio Unitario</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistro">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">


                                        <label>Fecha(*):</label>
                                        <input type="date" class="form-control" name="fecha" id="fecha"
                                               required="">


                                        <label>Producto(*):</label>
                                        <input type="hidden" name="idInventario" id="idInventario" hidden>
                                        <select id="idProducto" name="idProducto" class="form-control selectpicker"
                                                data-live-search="true" required>
                                        </select>



                                    </div>

                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">


                                        <label>Cantidad Construida:</label>
                                        <input type="number" class="form-control" name="cantidadConstruido" id="cantidadConstruido" maxlength="50"
                                               placeholder="Cantidad Construida" required>
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
    <script type="text/javascript" src="scripts/inventario.js"></script>
    <?php
}
ob_end_flush();
?>

