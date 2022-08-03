<?php
require_once "../modelo/Cliente.php";

$cliente = new Cliente();

$idCliente = isset($_POST["idCliente"]) ? limpiarCadena($_POST["idCliente"]) : "";
$identificacion = isset($_POST["identificacion"]) ? limpiarCadena($_POST["identificacion"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? limpiarCadena($_POST["apellido"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idCliente)) {
            $rspta = $cliente->insertar($identificacion, $nombre, $apellido, $telefono, $direccion);
            echo $rspta ? "Cliente registrado" : "Cliente no se pudo registrar";
        } else {
            $rspta = $cliente->editar($idCliente, $identificacion, $nombre, $apellido, $telefono, $direccion);
            echo $rspta ? "Cliente actualizado" : "Cliente no se pudo actualizar";
        }
        break;

    case 'eliminar':
        $rspta = $cliente->eliminar($idCliente);
        echo $rspta ? "Persona eliminada" : "Persona no se puede eliminar";
        break;

    case 'mostrar':
        $rspta = $cliente->mostrar($idCliente);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $cliente->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="btn btn-primary" onclick="mostrar(' . $reg->idCliente . ')"><i class="fa fa-pencil"></i> Editar</button>',
                "1" => $reg->identificacion,
                "2" => $reg->nombre,
                "3" => $reg->apellido,
                "4" => $reg->telefono,
                "5" => $reg->direccion
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el dataTables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;


}
?>