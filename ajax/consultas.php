<?php
require_once "../modelo/Consulta.php";

$consulta = new Consulta();


switch ($_GET["op"]) {
    case 'ventasPorCliente':
        $idcliente = $_REQUEST["idcliente"];

        $rspta = $consulta->ventasPorCliente($idcliente);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->numeroComprobante,
                "1" => $reg->fecha,
                "2" => $reg->usuario,
                "3" => $reg->cliente,
                "4" => $reg->tipoPago,
                "5" => '₡' . $reg->totalVenta,
                "6" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;

    case 'ventasPorFecha':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->ventasPorFecha($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->numeroComprobante,
                "1" => $reg->fecha,
                "2" => $reg->usuario,
                "3" => $reg->cliente,
                "4" => $reg->tipoPago,
                "5" => '₡' . $reg->totalVenta,
                "6" => ($reg->estado == 'Aceptado') ? '<span class="label bg-green">Aceptado</span>' :
                    '<span class="label bg-red">Anulado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;
    case 'gastosPorFecha':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->gastosPorFecha($fecha_inicio, $fecha_fin);
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->idGasto,
                "1" => $reg->usuario,
                "2" => $reg->fecha,
                "3" => $reg->descripcion,
                "4" => '₡' . $reg->monto
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el datatables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);

        break;
    case 'totalVentasPorFechas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $consulta->totalVentasPorFechas($fecha_inicio, $fecha_fin);
        $regV = $rspta->fetch_object();
        $totalV = $regV->totalVenta;
        echo $totalV;
        break;
    case 'totalGastosPorFechas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $rspta = $consulta->totalGastosPorFecha($fecha_inicio, $fecha_fin);
        $regV = $rspta->fetch_object();
        $totalV = $regV->totalGastos;
        echo $totalV;
        break;
}
?>