<?php
if (strlen(session_id()) < 1)
    session_start();

require_once "../modelo/Inventario.php";
$inventario = new Inventario();

$idInventario = isset($_POST["idInventario"]) ? limpiarCadena($_POST["idInventario"]) : "";
$idProducto = isset($_POST["idProducto"]) ? limpiarCadena($_POST["idProducto"]) : "";
$idUsuario = $_SESSION["idUsuario"];
$cantidadConstruido = isset($_POST["cantidadConstruido"]) ? limpiarCadena($_POST["cantidadConstruido"]) : "";
$fecha = isset($_POST["fecha"]) ? limpiarCadena($_POST["fecha"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        /*echo "<pre>";
        var_dump($idInventario,$idProducto,$idUsuario,$cantidadConstruido, $fecha);
        echo "</pre>";
        exit;*/

        if (empty($idInventario)) {
            $respuesta = $inventario->insertar($idProducto, $idUsuario, $cantidadConstruido, $fecha);
            echo $respuesta ? "Inventario registrado" : "Inventario no registrado";
        } else {
            $respuesta = $inventario->editar($idInventario, $idProducto, $idUsuario, $cantidadConstruido, $fecha);
            echo $respuesta ? "Inventario actualizado" : "Inventario no se pudo actualizar";
        }
        break;

    case 'listar':
        $rspta = $inventario->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->estado == 1 ? '<button class="btn btn-primary" onclick="noAplicarStock(' . $reg->idInventario . ')"><i class="fa fa-pencil"></i> Anular</button>' : '',
                "1" => $reg->fecha,
                "2" => $reg->categoria . ' ' . $reg->descripcion,
                "3" => $reg->cantidadConstruido,
                "4" => '₡' . $reg->precio,
                "5" => '₡' . $reg->precio * $reg->cantidadConstruido . '.00',
                "6" => ($reg->estado == 1) ? '<span class="label bg-green">Aplicado</span>' :
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
    case 'anular':
        $rspta = $inventario->anular($idInventario);
        echo $rspta ? "Inventario anulado" : "Inventario no se puede anular";
        break;
}

