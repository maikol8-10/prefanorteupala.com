<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Prefa Norte Upala</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/personalizado.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">
    <link rel="apple-touch-icon" href="../public/images/prefaNorte.png">
    <link rel="shortcut icon" href="../public/images/prefaNorte.png">
    <!--DATATABLES CSS-->
    <link rel="stylesheet" type="text/css" href="../public/datatables/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../public/datatables/css/responsive.dataTables.min.css">
    <!-- jQuery 2.1.4 -->
    <script src="../public/js/plugins/jQuery/jquery-3.1.1.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="../public/bootstrap/js/bootstrap.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../public/js/app/app.min.js"></script>
    <script src="../public/js/utils/utilities.js"></script>
    <!-- DATATABLES JS-->
    <script src="../public/DataTable/datatables.min.js"></script>
    <script src="../public/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../public/datatables/js/dataTables.bootstrap.min.js"></script>
    <script src="../public/datatables/js/dataTables.responsive.min.js"></script>
    <script src="../public/datatables/js/responsive.bootstrap.min.js"></script>
    <script src="../public/datatables/js/dataTables.buttons.min.js"></script>
    <script src="../public/datatables/js/buttons.html5.min.js"></script>
    <script src="../public/datatables/js/buttons.colVis.min.js"></script>
    <script src="../public/datatables/js/jszip.min.js"></script>
    <script src="../public/datatables/js/pdfmake.min.js"></script>
    <script src="../public/datatables/js/vfs_fonts.js"></script>
    <!--crypto
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9/sha256.js"></script>-->
    <!-- SELECTPICKER-->
    <link rel="stylesheet" type="text/css" href="../public/js/plugins/selectPicker/bootstrap-select.min.css">
    <script src="../public/js/plugins/selectPicker/bootstrap-select.min.js"></script>
    <!-- BOOTBOX-->
    <script src="../public/js/plugins/bootbox/bootbox.min.js"></script>
    <script src="../public/js/plugins/bootbox/bootbox.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="./escritorio.php" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>PN</b>U</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg" style="font-size: 14px"><b>PREFA NORTE </b>UPALA</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="./escritorio.php" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Messages: style can be found in dropdown.less-->
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!--<img src="../public/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                            <i class="fa fa-user"></i>
                            <span class="hidden-xs"><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellidos']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="../public/images/prefaNorte.png" class="img-circle" alt="User Image">
                                <p>
                                    PREFA NORTE UPALA
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat"><i
                                                class="fa fa-sign-out"></i>Salir</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!--<div class="user-panel">
                <div class="pull-left image">
                    <i class="fa fa-user"></i>
                </div>
                <div class="pull-left info">
                    <p><?php echo $_SESSION['nombre'] . " " . $_SESSION['apellidos']; ?></p>
                </div>
            </div>-->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu tree">
                <li class="header"> MENU DE NAVEGACIÓN</li>
                <?php
                if ($_SESSION['escritorio'] === 1) {
                    echo '<li> <a href="escritorio.php"><i class="fa fa-laptop"></i> <span>Escritorio</span></a></li>';
                }
                ?>
                <?php
                if ($_SESSION['tienda'] === 1) {
                    echo '<li class="treeview">
                    <a href="#">
                        <i class="fa fa-home"></i>
                        <span>Tienda</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="caja.php"><i class="fa fa-inbox"></i> Caja</a></li>
                        <li><a href="gasto.php  "><i class="fa fa-money"></i> Gastos</a></li>
                        <li><a href="producto.php"><i class="fa fa-barcode"></i> Producto</a></li>
                        <li><a href="inventario.php"><i class="fa fa-barcode"></i> Agregar Inventario</a></li>
                        <!--<li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>-->
                    </ul>
                </li>';
                }
                ?>

                <?php
                if ($_SESSION['ventas'] === 1) {
                    echo '<li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-cart"></i>
                        <span>Ventas</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="venta.php"><i class="fa fa-money"></i> Ventas</a></li>
                        <li><a href="cliente.php"><i class="fa fa-users"></i> Clientes</a></li>
                    </ul>
                </li>';
                }
                ?>
                <?php
                if ($_SESSION['acceso'] === 1) {
                    echo '<li class="treeview">
                    <a href="#">
                        <i class="fa fa-key"></i> <span>Acceso</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="usuario.php"><i class="fa fa-users"></i> Usuarios</a></li>
                        <li><a href="permiso.php"><i class="fa fa-unlock-alt"></i> Permisos</a></li>
                    </ul>
                </li>';
                }
                ?>
                <?php
                if ($_SESSION['consultaVentas'] === 1) {
                    echo '<li class="treeview">
                    <a href="#">
                        <i class="fa fa-area-chart"></i> <span>Consultas</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="consultaVentasCliente.php"><i class="fa fa-bar-chart"></i>Ventas Por Cliente</a></li>
                        <li><a href="consultaVentasFechas.php"><i class="fa fa-bar-chart"></i>Ventas Por Fechas</a></li>
                        <li><a href="consultaGastosFechas.php"><i class="fa fa-bar-chart"></i>Gastos Por Fechas</a></li>
                    </ul>
                </li>';
                }
                ?>
                <!--<li>
                    <a href="#">
                        <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                        <small class="label pull-right bg-red">PDF</small>
                    </a>
                </li>-->
                <li>
                    <a href="./acerca.php">
                        <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                        <small class="label pull-right bg-blue">MYTEK</small>
                    </a>
                </li>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>