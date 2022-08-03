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
        <style>
            #listaCodigos {
                display: flex;
                justify-content: space-around;
                flex-wrap: wrap;
            }

            .barcode {
                width: 200px;
            }

            .content-barcode {
                display: flex;
                flex-direction: column;
                text-align: center;
            }

            .info-prenda {
                display: flex;
                flex-direction: column;
                text-align: center;
            }
        </style>
        <div class="content-wrapper">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <div id="contentAdd">
                                    <h1 id="tittleVA" class="box-title" style="font-size: 22px; font-weight: bold">
                                        Códigos</h1>
                                    <button class="btn btn-success" id="btnagregar" onclick="printAll()"><i
                                                class="fa fa-print"></i> Imprimir Códigos
                                    </button>
                                    <a id="btnVerPrendas" href="producto.php" class="btn btn-bitbucket"
                                       style="background-color: #2B2B2D; !important;"><i
                                                style="padding-right: 5px;" class="fa fa-book"></i> Ver
                                        Prendas</a>

                                </div>
                            </div>
                            <div class="panel-body" id="listaCodigos">

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
    <script type="text/javascript" src="../public/js/plugins/JsBarcode/JsBarcode.all.min.js"></script>
    <script type="text/javascript" src="scripts/producto.js"></script>
    <script type="text/javascript" src="../views/scripts/codigos.js"></script>
    <script type="text/javascript" src="../public/js/plugins/PrintArea/jquery.PrintArea.js"></script>
    <?php
}
ob_end_flush();
?>

