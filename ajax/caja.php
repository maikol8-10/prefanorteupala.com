<?php
if (strlen(session_id()) < 1)
    session_start();
require_once "../modelo/Caja.php";

$caja = new Caja();
$idFlujo = isset($_POST["idFlujo"]) ? limpiarCadena($_POST["idFlujo"]) : "";
//$idUsuario = $_SESSION["idUsuario"];
$idUsuario = isset($_POST["idUsuario"]) ? limpiarCadena($_POST["idUsuario"]) : "";
$saldo = isset($_POST["saldo"]) ? limpiarCadena($_POST["saldo"]) : "";
$fechaHora = isset($_POST["fechaHora"]) ? limpiarCadena($_POST["fechaHora"]) : "";
switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idFlujo)) {
            $respuesta = $caja->insertar($idUsuario, $fechaHora, $saldo);
            echo $respuesta ? "Flujo de caja registrada" : "Flujo no registrado, puede que ya exista";
        } else {
            $respuesta = $caja->editar($idFlujo, $idUsuario, $saldo, $fechaHora);
            echo $respuesta ? "Flujo de caja actualizado" : "Flujo de caja no se pudo actualizar";
        }
        break;
    case 'selectUsuario':
        require_once "../modelo/Usuario.php";
        $usuario = new Usuario();
        $rspta = $usuario->listar();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idUsuario . '>' . $reg->nombre . ' ' . $reg->apellido . '</option>';
        }
        break;
    case 'listar':
        $respuesta = $caja->listar();
        $data = array();
        date_default_timezone_set("America/Costa_Rica");
        while ($reg = $respuesta->fetch_object()) {
            $url = '../reportes/reporteCaja.php?id=';
            $saldoInicial = ($reg->saldo + $reg->totalVentas + $reg->totalVueltoEfectivoClientes + $reg->totalGastos) - ($reg->totalVentas);
            $data[] = array(
                "0" => ($reg->estado === 'abierta' && $reg->fecha === date("Y-m-d")) ? ' <button class="btn btn-warning" onclick="cerrar(' . $reg->idFlujo . ')"><i class="fa fa-close"></i> Cerrar</button>' . ' <a target="_blank" href="' . $url . $reg->idFlujo . '"><button class="btn btn-bitbucket" !important;"><i class="fa fa-print"></i></button></a>' : (($reg->estado === 'cerrada' && $reg->fecha === date("Y-m-d")) ?
                    ' <button class="btn btn-success" onclick="abrir(' . $reg->idFlujo . ')"><i class="fa fa-openid"></i> Abrir</button>' . ' <a target="_blank" href="' . $url . $reg->idFlujo . '"><button class="btn btn-bitbucket" !important;"><i class="fa fa-print"></i></button></a>' : ' <a target="_blank" href="' . $url . $reg->idFlujo . '"><button class="btn btn-bitbucket"><i class="fa fa-print"></i></button></a>'),
                "1" => $reg->usuario,
                "2" => $reg->fecha,
                "3" => '₡' . $reg->saldo,
                "4" => '₡-' . $reg->totalGastos,
                "5" => '₡' . $reg->totalVentas,
                "6" => '₡' . (($reg->saldo + $reg->totalPagoEfectivoClientes) - ($reg->totalVueltoEfectivoClientes + $reg->totalGastos)),
                "7" => '₡' . (($reg->saldo + $reg->totalVentas + $reg->totalVueltoEfectivoClientes) - ($reg->totalVueltoEfectivoClientes + $reg->totalGastos)),
                "8" => ($reg->estado === 'abierta') ? '<span class="label bg-green">Abierta</span>' :
                    '<span class="label bg-red">Cerrada</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para los dataTables
            "iTotalRecords" => count($data),//Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //Enviamos el total de registros al visualizador
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'cerrar':
        $rspta = $caja->cerrar($idFlujo);
        echo $rspta ? "Flujo de caja cerrada" : "Flujo de caja no se puede cerrar";
        break;
    case 'abrir':
        $rspta = $caja->abrir($idFlujo);
        echo $rspta ? "Flujo de caja abierta" : "Flujo de caja no se puede abrir";
        break;
    case 'mostrar':
        $rspta = $caja->mostrar($idFlujo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
}