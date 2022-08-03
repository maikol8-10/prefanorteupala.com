<?php
session_start();
require_once "../modelo/Usuario.php";
$user = new Usuario();

$idUsuario = isset($_POST["idUsuario"]) ? limpiarCadena($_POST["idUsuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$apellido = isset($_POST["apellido"]) ? limpiarCadena($_POST["apellido"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$usuario = isset($_POST["usuario"]) ? limpiarCadena($_POST["usuario"]) : "";
$contrasena = isset($_POST["contrasena"]) ? limpiarCadena($_POST["contrasena"]) : "";
switch ($_GET["op"]) {
    case 'guardaryeditar':
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $contrasena);
        if (empty($idUsuario)) {
            $rspta = $user->insertar($nombre, $apellido, $cargo, $usuario, $clavehash, $_POST['permiso']);
            echo $rspta  ? "Usuario registrado" : "No se pudieron registrar todos los datos del usuario";
        } else {
            $rspta = $user->editar($idUsuario, $nombre, $apellido, $cargo, $usuario, $clavehash, $_POST['permiso']);
            echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
        }
        break;

    case 'desactivar':
        $rspta = $user->desactivar($idUsuario);
        echo $rspta ? "Usuario desactivado" : "Usuario no se puede desactivar";
        break;

    case 'activar':
        $rspta = $user->activar($idUsuario);
        echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
        break;

    case 'mostrar':
        $rspta = $user->mostrar($idUsuario);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $user->listar();
        //Vamos a declarar un array
        $data = array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? '<button class="btn btn-primary" onclick="mostrar(' . $reg->idUsuario . ')"><i class="fa fa-pencil"></i> Editar</button>' .
                    ' <button class="btn btn-warning" onclick="desactivar(' . $reg->idUsuario . ')"><i class="fa fa-close"></i> Desactivar</button>' :
                    '<button class="btn btn-primary" onclick="mostrar(' . $reg->idUsuario . ')"><i class="fa fa-pencil"></i> Editar</button>' .
                    ' <button class="btn btn-success" onclick="activar(' . $reg->idUsuario . ')"><i class="fa fa-check"></i> Activar</button>',
                "1" => $reg->nombre,
                "2" => $reg->apellido,
                "3" => $reg->cargo,
                "4" => $reg->usuario,
                "5" => ($reg->condicion) ? '<span class="label bg-green">Activado</span>' :
                    '<span class="label bg-red">Desactivado</span>'
            );
        }
        $results = array(
            "sEcho" => 1, //Información para el dataTables
            "iTotalRecords" => count($data), //enviamos el total registros al datatable
            "iTotalDisplayRecords" => count($data), //enviamos el total registros a visualizar
            "aaData" => $data);
        echo json_encode($results);
        break;
    case 'permisos':
        require_once "../modelo/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();
        //Obtener los permisos asignados al usuario
        $id = $_GET['id'];
        $marcados = $user->listarmarcados($id);
        //Declaramos el array para almacenar todos los permisos marcados
        $valores = array();

        //Almacenar los permisos asignados al usuario en el array
        while ($per = $marcados->fetch_object()) {
            array_push($valores, $per->idPermiso);
        }
        //Mostramos la lista de permisos en la vista y si están o no marcados
        while ($reg = $rspta->fetch_object()) {
            $sw = in_array($reg->idPermiso, $valores) ? 'checked' : '';
            echo '<li><input type="checkbox" ' . $sw . ' style="margin-right: 5px" name="permiso[]"  checked value="' . $reg->idPermiso . '">' . $reg->nombre . '</li>';
        }
        break;
    case 'verificar':
        $usuarioAccess = $_POST['usuarioAccess'];
        $contrasenaAccess = $_POST['contrasenaAccess'];
        //Hash SHA256 en la contraseña
        $clavehash = hash("SHA256", $contrasenaAccess);
        $rspta = $user->verificar($usuarioAccess, $clavehash);
        $fetch = $rspta->fetch_object();
        if (isset($fetch)) {
            //Declaramos las variables de sesión
            $_SESSION['idUsuario'] = $fetch->idUsuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['apellidos'] = $fetch->apellido;
            $_SESSION['usuario'] = $fetch->usuario;
            //Obtenemos los permisos del usuario
            $marcados = $user->listarmarcados($fetch->idUsuario);
            //Declaramos el array para almacenar todos los permisos marcados
            $valores = array();
            //Almacenamos los permisos marcados en el array
            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idPermiso);
            }
            //Determinamos los accesos del usuario
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['tienda'] = 1 : $_SESSION['tienda'] = 0;
            in_array(3, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
            in_array(4, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
            in_array(5, $valores) ? $_SESSION['consultaVentas'] = 1 : $_SESSION['consultaVentas'] = 0;
        }
        echo json_encode($fetch);
        break;
    case 'salir':
        session_unset();
        session_destroy();
        header("Location: ../index.php");
}
?>