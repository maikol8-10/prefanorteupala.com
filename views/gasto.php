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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Gastos de hoy
                                    <button class="btn btn-success" id="btnAgregar" onclick="mostrarForm(true)"><i
                                                class="fa fa-plus-circle"></i> Agregar
                                    </button>
                                </h1>
                                <div id="contentSaldo" class="pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tablaListado"
                                       class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Descripcion</th>
                                    <th>Monto</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>N°</th>
                                    <th>Fecha</th>
                                    <th>Descripcion</th>
                                    <th>Monto</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistro">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Monto(*):</label>
                                        <input type="hidden" name="idGasto" id="idGasto" hidden>
                                        <input type="number" class="form-control" name="monto" id="monto" min="1" max="999999" maxlength="50"
                                               required >
                                    </div>
                                    <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Fecha(*):</label>
                                        <input type="date" class="form-control" readonly name="fechaHora" id="fechaHora"
                                               required="">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Descripcion:</label>
                                        <textarea type="text" class="form-control" name="descripcion" id="descripcion"
                                                  maxlength="50"
                                                  required></textarea>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="button" id="btnGuardar"><i
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
    <script type="text/javascript" src="scripts/gasto.js"></script>
    <script type="text/javascript" src="scripts/saldoEfectivo.js"></script>
    <?php
}
ob_end_flush();
?>
