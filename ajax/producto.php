<?php
if (strlen(session_id()) < 1)
    session_start();
require_once "../modelo/Producto.php";
$producto = new Producto();
//Si existe el objeto lo validamos, sino existe enviamos un texto vacio
$idProducto = isset($_POST["idProducto"]) ? limpiarCadena($_POST["idProducto"]) : "";
$idCategoria = isset($_POST["idCategoria"]) ? limpiarCadena($_POST["idCategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$precio = isset($_POST["precio"]) ? limpiarCadena($_POST["precio"]) : "";

switch ($_GET["op"]) {
    case 'mostrar':
        $respuesta = $producto->mostrar($idProducto);
        echo json_encode($respuesta);
        break;
    case 'listar':
        $respuesta = $producto->listar();
        $data = array();
        while ($reg = $respuesta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->estado) ? '<button class="btn btn-primary" onclick="mostrar(' . $reg->idProducto . ')"><i class="fa fa-pencil"></i> Editar</button>' .
                    ' <button class="btn btn-warning" onclick="desactivar(' . $reg->idProducto . ')"><i class="fa fa-close"></i> Desactivar</button>' :
                    '<button class="btn btn-primary" onclick="mostrar(' . $reg->idProducto . ')"><i class="fa fa-pencil"></i> Editar</button>' .
                    ' <button class="btn btn-success" onclick="activar(' . $reg->idProducto . ')"><i class="fa fa-check"></i> Activar</button>',
                "1" => $reg->categoria,
                "2" => $reg->descripcion,
                "3" =>'₡' . $reg->precio,
                "4" => $reg->codigo,
                "5" => ($reg->estado) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Informacion para los dataTables
            "iTotalRecords" => count($data),//Enviamos el total de registros al datatable
            "iTotalDisplayRecords" => count($data), //Enviamos el total de registros al visualizador
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'guardaryeditar':
        if (empty($idProducto)) {
            $respuesta = $producto->insertar($idCategoria,$codigo, $descripcion, $precio);
            echo $respuesta ? "Producto registrada" : "Producto no se pudo registrar, puede que ya exista el código ingresado";
            //echo $respuesta ? "Producto registrada" : $idProducto;
        } else {
            $respuesta = $producto->editar($idProducto,$idCategoria, $codigo, $descripcion, $precio);
            echo $respuesta ? "Producto actualizada" : "Producto no se pudo actualizar";
        }
        break;
    case 'desactivar':
        $rspta = $producto->desactivar($idProducto);
        echo $rspta ? "Producto desactivada" : "Producto no se puede desactivar";
        break;

    case 'activar':
        $rspta = $producto->activar($idProducto);
        echo $rspta ? "Producto activada" : "Producto no se puede activar";
        break;
    case 'listarCodigos':
        $respuesta = $producto->listar();
        $data = array();
        while ($reg = $respuesta->fetch_object()) {
            $data[] = array(
                $reg->idProducto,
                $reg->descripcion,
                $reg->precio,
                $reg->codigo
            );
        }
        $results = array(
            "data" => $data);
        echo json_encode($results);
        break;

    case 'selectCategoriaTodos':
        require_once "../modelo/Categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->listar();
        while ($reg = $rspta->fetch_object()) {
            echo '<option value=' . $reg->idCategoria . '>' . $reg->categoria . '</option>';
        }
        break;
}