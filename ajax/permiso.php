<?php
require_once "../modelo/Permiso.php";
$permiso = new Permiso();
switch ($_GET["op"]) {
    case 'listar':
        $respuesta = $permiso->listar();
        $data = Array();
        while ($reg = $respuesta->fetch_object()) {
            $data[] = Array(
                "0" => $reg->nombre
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para los dataTables
            "iTotalRecords" => count($data),//Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //Enviamos el total de registros al visualizador
            "aaData" => $data);
        echo json_encode($results);
        break;
}