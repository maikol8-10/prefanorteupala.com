<?php
ob_start();
session_start();
if (!isset($_SESSION["nombre"])) {
    header("location: login.html");
} else {
    require "header.php";
    if ($_SESSION['acceso'] == 1) {
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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Usuarios
                                    <button class="btn btn-success" id="btnagregar" onclick="mostrarForm(true)"><i
                                                class="fa fa-plus-circle"></i> Agregar
                                    </button>
                                </h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <table id="tbllistado" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Cargo</th>
                                    <th>Usuario</th>
                                    <th>Estado</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Cargo</th>
                                    <th>Usuario</th>
                                    <th>Estado</th>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="panel-body" id="formularioregistros">
                                <form name="formulario" id="formulario" method="POST">
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Nombre(*):</label>
                                        <input type="hidden" name="idUsuario" id="idUsuario">
                                        <input type="text" class="form-control" name="nombre" id="nombre"
                                               maxlength="100"
                                               placeholder="Nombre" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Apellidos(*):</label>
                                        <input type="text" class="form-control" name="apellido" id="apellido"
                                               maxlength="20" placeholder="Apellidos" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Cargo(*):</label>
                                        <select name="cargo" id="cargo" class="form-control selectpicker" required>
                                            <option value="Administrador">Administrador</option>
                                            <option value="Vendedor">Vendedor</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Usuario (*):</label>
                                        <input type="text" class="form-control" name="usuario" id="usuario"
                                               maxlength="20"
                                               placeholder="Usuario" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Contraseña (*):</label>
                                        <input type="password" class="form-control" name="contrasena" id="contrasena"
                                               maxlength="64"
                                               placeholder="Contraseña" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <label>Permisos:</label>
                                        <ul style="list-style: none;" id="permisos">

                                        </ul>
                                    </div>
                                    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-primary" type="submit" id="btnGuardar"><i
                                                    class="fa fa-save"></i>
                                            Guardar
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
    <script type="text/javascript" src="scripts/usuario.js"></script>
    <?php
}
ob_end_flush();
?>