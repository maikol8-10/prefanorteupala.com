<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
    header("Location: login.html");
}
else
{
    require 'header.php';

    if ($_SESSION['consultaVentas']==1)
    {
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
                                <h1 class="box-title" style="font-size: 22px; font-weight: bold">Consulta de Inventarios Por Fechas</h1>
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <!-- centro -->
                            <div class="panel-body table-responsive" id="listadoregistros">
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label>Fecha Inicio</label>
                                    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
                                </div>
                                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                    <label>Fecha Fin</label>
                                    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
                                    <button id="listarVentasFechas" class="btn btn-success" style="margin-top: 10px;" onclick="listarInventariosFechas()"><i style="padding-right: 5px;" class="fa fa-eye"></i>Mostrar</button>
                                </div>
                                <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead>
                                    <th>N°</th>
                                    <th>Usuario</th>
                                    <th>Fecha</th>
                                    <th>Producto</th>
                                    <th>Precio Unid</th>
                                    <th>Cant. Construido</th>
                                    <th>Subtotal</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
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
                                        <th style="width: 115px; !important;"><h4 id="total">₡ 0.00</h4><input
                                                type="hidden" name="totalVenta"
                                                id="totalVenta"></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <!--Fin centro -->
                        </div><!-- /.box -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </section><!-- /.content -->

        </div><!-- /.content-wrapper -->
        <!--Fin-Contenido-->
        <?php
    }
    else
    {
        require 'noacceso.php';
    }

    require 'footer.php';
    ?>
    <script type="text/javascript" src="scripts/consultaInventarioFechas.js"></script>
    <?php
}
ob_end_flush();
?>




