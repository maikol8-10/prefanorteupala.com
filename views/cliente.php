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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Clientes
                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i
                                                class="fa fa-plus-circle"></i> Agregar
                                    </button>
                                </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado"
                                       class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Teléfono</th>
                                    <th>Dirección</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" style="height: 400px;" id="formularioregistros" hidden>
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Identificacion:</label>
                                        <input type="hidden" name="idCliente" id="idCliente">
                                        <input type="number" class="form-control" name="identificacion" id="identificacion"
                                               maxlength="100" placeholder="Identificacion del cliente" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre:</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                               maxlength="20" placeholder="Nombre" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Apellidos:</label>
                                        <input type="text" class="form-control" name="apellido" id="apellido"
                                               maxlength="70" placeholder="Apellidos">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Teléfono:</label>
                                        <input type="text" class="form-control" name="telefono" id="telefono"
                                               maxlength="20" placeholder="Teléfono">
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Dirección:</label>
                                        <input type="text" class="form-control" name="direccion" id="direccion" maxlength="50"
                                               placeholder="Dirección">
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i
                                                    class="fa fa-save"></i> Guardar
                                        </button>

                                        <button class="btn btn-danger" onclick="cancelarform()" type="button"><i
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
    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/cliente.js"></script>
    <?php
}
ob_end_flush();
?>