<?php
if (strlen(session_id()) < 1)
    session_start();
require_once "../modelo/Gasto.php";

$gasto = new Gasto();
$idGasto = isset($_POST["idGasto"]) ? limpiarCadena($_POST["idGasto"]) : "";
$monto = isset($_POST["monto"]) ? limpiarCadena($_POST["monto"]) : "";
$fechaHora = isset($_POST["fechaHora"]) ? limpiarCadena($_POST["fechaHora"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$idUsuario = $_SESSION["idUsuario"];

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idGasto)) {
            $respuesta = $gasto->insertar($monto, $fechaHora, $descripcion, $idUsuario);
            echo $respuesta ? "Gasto registrado" : "Gasto no registrado";
        } else {
            $respuesta = $gasto->editar($idGasto, $monto, $fechaHora, $descripcion, $idUsuario);
            echo $respuesta ? "Gasto actualizado" : "Gasto no se pudo actualizar";
        }
        break;

    case 'listar':
        $respuesta = $gasto->listar($idUsuario);
        $data = array();
        while ($reg = $respuesta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-danger" onclick="eliminar(' . $reg->idGasto . ')"><i class="fa fa-trash"></i> Eliminar</button>',
                "1" => $reg->idGasto,
                "2" => $reg->fechaHora,
                "3" => $reg->descripcion,
                "4" => '₡' . $reg->monto
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para los dataTables
            "iTotalRecords" => count($data),//Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //Enviamos el total de registros al visualizador
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'mostrar':
        $rspta = $gasto->mostrar($idGasto);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
    case 'eliminar':
        $rspta = $gasto->eliminar($idGasto, $idUsuario);
        echo $idUsuario;
        echo $rspta ? "Gasto eliminado" : "Gasto no se puede eliminar";
        break;
}