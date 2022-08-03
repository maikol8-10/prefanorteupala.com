<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelo/Venta.php";

$venta = new Venta();

$idVenta = isset($_POST["idVenta"]) ? limpiarCadena($_POST["idVenta"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$idCliente = isset($_POST["idCliente"]) ? limpiarCadena($_POST["idCliente"]) : "";
$idUsuario = $_SESSION["idUsuario"];
$tipoPago = isset($_POST["tipoPago"]) ? limpiarCadena($_POST["tipoPago"]) : "";
$tipoComprobante = isset($_POST["tipoComprobante"]) ? limpiarCadena($_POST["tipoComprobante"]) : "";
$numeroComprobante = isset($_POST["numeroComprobante"]) ? limpiarCadena($_POST["numeroComprobante"]) : "";
$fechaHora = isset($_POST["fechaHora"]) ? limpiarCadena($_POST["fechaHora"]) : "";
$pagoCliente = isset($_POST["pagoCliente"]) ? limpiarCadena($_POST["pagoCliente"]) : "";
$vueltoCliente = isset($_POST["vueltoCliente"]) ? limpiarCadena($_POST["vueltoCliente"]) : "";
$totalVenta = isset($_POST["totalVenta"]) ? limpiarCadena($_POST["totalVenta"]) : "";
$iva = isset($_POST["totalIvaFinal"]) ? limpiarCadena($_POST["totalIvaFinal"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idVenta)) {

            $rspta = $venta->insertar($idCliente, $idUsuario, $tipoPago, $tipoComprobante, $numeroComprobante, $fechaHora, $pagoCliente, $vueltoCliente, $totalVenta,$iva, $_POST["idProducto"], $_POST["cantidad"], $_POST["precioVenta"], $_POST["descuento"]);
            /*foreach ($_POST["cantidad"] as $value) {
                          echo $value, "\n";
                      }*/
            echo $rspta ? "Venta registrada" : "No se pudieron registrar todos los datos de la venta";
        } else {
        }
        break;

    case 'anular':
        $rspta = $venta->anular($idVenta);
        echo $rspta ? "Venta anulada" : "Venta no se puede anular";
        break;
    case 'anularApartado':
        $rspta = $venta->anularApartado($idVenta);
        echo $rspta ? "Apartado anulado" : "Apartado no se puede anular";
        break;
    case 'finalizarApartado':
        $rspta = $venta->finalizarApartado($idVenta);
        echo $rspta ? "Apartado finalizado" : "Apartado no se puede finalizar";
        break;

    case 'mostrar':
        $rspta = $venta->mostrar($idVenta);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listarDetalle':
        //Recibimos el idingreso
        $id = $_GET['id'];
        $rspta = $venta->listarDetalle($id);
        $total = 0;
        echo '<thead style="background-color:#ff3f06; color: #ffffff">
                                    <th>Opciones</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Descuento %</th>
                                    <th>Subtotal</th>
                                </thead>';

        while ($reg = $rspta->fetch_object()) {
            $descuentoColones = substr($reg->descuentoColones, 0, -5);
            echo '<tr class="filas"><td></td><td>'. $reg->categoria .' '. $reg->descripcion . '</td><td>' . $reg->cantidad . '</td><td>' . '₡' . $reg->precioVenta . '</td><td>' . $reg->descuento . '%' . '</td><td>' . '₡' . ($reg->precioVenta * $reg->cantidad - $descuentoColones) . '</td></tr>';
            $total = $total + ($reg->precioVenta * $reg->cantidad - $descuentoColones);
        }
        echo '<tfoot>
              <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: end; !important; vertical-align: middle; !important;">TOTAL</th>
                    <th><h4 id="total">₡' . $total . '</h4><input type="hidden" name="totalVenta" id="totalVenta"></th>
              </tr> 
              <tr id="tr-pago" hidden>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: end; !important; vertical-align: middle; !important;">PAGO CLIENTE
                    </th>
                    <th style="width: 115px; !important;"><input type="number"name="pagoCliente" id="pagoCliente"class="form-control"
                     min="0" max="999999"></th>
              </tr>
              <tr id="tr-vuelto" hidden>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th style="text-align: end; !important; vertical-align: middle; !important;">VUELTO
                    </th>
                    <th style="width: 115px; !important;"><h4 id="vuelto">₡ 0</h4><input type="hidden"name="vueltoCliente"
                    id="vueltoCliente"class="form-control" min="0" max="999999"></th>
              </tr>
              </tfoot>';
        break;

    case 'listar':
        $rspta = $venta->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->tipoComprobante == 'Ticket') {
                $url = '../reportes/exTicket.php?id=';

            } else {
                $url = '../reportes/exFactura.php?id=';
            }
            $data[] = array(
                "0" => (($reg->estado == 'Aceptado') ? '<button class="btn btn-primary" onclick="mostrar(' . $reg->idVenta . ')"><i class="fa fa-eye"></i></button>' .
                        ' <button class="btn btn-warning" onclick="anular(' . $reg->idVenta . ')"><i class="fa fa-close"></i></button>' :
                        '<button class="btn btn-primary" onclick="mostrar(' . $reg->idVenta . ')"><i class="fa fa-eye"></i></button>') .
                    ' <a target="_blank" href="' . $url . $reg->idVenta . '"><button class="btn btn-bitbucket"><i class="fa fa-print"></i></button></a>',
                "1" => $reg->numeroComprobante,
                "2" => $reg->fecha,
                "3" => $reg->cliente,
                "4" => $reg->usuario,
                "5" => $reg->tipoPago,
                // "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "6" => '₡' . $reg->totalVenta,
                "7" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el dataTables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;
    /*case 'listarApartados':
        $rspta = $venta->listarApartados();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->tipoComprobante == 'Ticket') {
                $url = '../reportes/exTicket.php?id=';

            } else {
                $url = '../reportes/exFactura.php?id=';
            }
            $data[] = array(
                "0" => (($reg->estado == 'Pendiente') ? '<button class="btn btn-success" onclick="finalizarApartado(' . $reg->idVenta . ')"><i class="fa fa-check"></i></button>' .
                        ' <button class="btn btn-danger" onclick="anularApartado(' . $reg->idVenta . ')"><i class="fa fa-close"></i></button>' .
                        ' <a class="btn btn-success" href="https://api.whatsapp.com/send?phone=506' . $reg->telefono . '&text=Saludos,%20para%20recordale%20que%20tiene%20un%20apartado%20pendiente%20en%20Steph%20Boutique%20Americana" target="_blank"><i class="fa fa-whatsapp"></i></a>' :
                        '<button class="btn btn-primary" onclick="mostrar(' . $reg->idVenta . ')"><i class="fa fa-eye"></i></button>') .
                    ' <a target="_blank" href="' . $url . $reg->idVenta . '"><button class="btn btn-bitbucket"><i class="fa fa-file"></i></button></a>',
                "1" => $reg->fecha,
                "2" => $reg->cliente,
                "3" => $reg->telefono,
                "4" => $reg->tipoPago,
                // "5"=>$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "5" => '₡' . $reg->totalVenta,
                "6" => ($reg->estado == 'Pendiente') ? '<span class="label bg-yellow">Pendiente</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el dataTables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;*/

    case 'selectClienteTodos':
        require_once "../modelo/Cliente.php";
        $cliente = new Cliente();
        $rspta = $cliente->listarTodos();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idCliente . '>' . $reg->nombre . ' ' . $reg->apellido . '</option>';
        }
        break;
    case 'selectCliente':
        require_once "../modelo/Cliente.php";
        $cliente = new Cliente();
        $rspta = $cliente->listar();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idCliente . '>' . $reg->nombre . ' ' . $reg->apellido . '</option>';
        }
        break;

    case 'listarProductoVenta':
        require_once "../modelo/Producto.php";
        $producto = new Producto();
        $rspta = $producto->listarActivosVenta();
        //Vamos a declarar un array
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-primary" onclick="agregarDetalle(' . $reg->idProducto . ', \'' . $reg->categoria.' '. $reg->descripcion . '\',\'' . $reg->precio . '\')"><span class="fa fa-shopping-cart"></span> Agregar</button>',
                "1" => $reg->categoria.' '.$reg->descripcion,
                "2" => $reg->precio,
                "3" => $reg->codigo
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el dataTables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;

    case 'mostrarSaldoFlujo':
        require_once "../modelo/Caja.php";
        $flujoCaja = new caja();
        $rspta = $flujoCaja->mostrarSaldo($idUsuario);
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data = array($reg->saldo, $reg->estado);
            $results = array(
                "data" => $data);
            echo json_encode($results);
        }
        break;

    case 'numeroComprobante':
        $rspta = $venta->numeroComprobante();
        $reg = $rspta->fetch_object();
        echo $reg->numero;
        break;

    case 'ultimaVenta':
        $rspta = $venta->ultimaVenta();
        $reg = $rspta->fetch_object();
        echo $reg->idVenta;
        break;

    case 'buscarProducto':
        $rspta = $venta->buscarProducto($codigo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
}
?>