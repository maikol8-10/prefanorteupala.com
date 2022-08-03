<?php
require "../config/conexion.php";
require_once "../modelo/Caja.php";

class Usuario
{
    //Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para insertar registro
    public function insertar($nombre, $apellido, $cargo, $usuario, $contrasena, $permisos)
    {
        $sql = "INSERT INTO usuario (nombre, apellido, cargo, usuario, contrasena, condicion) 
        VALUES ('$nombre', '$apellido', '$cargo', '$usuario', '$contrasena', '1')";
        //return ejecutarConsulta($sql);
        $idUsuarioNew = ejecutarConsulta_retornarID($sql);
        $num_elementos = 0;
        $sw = true;
        while ($num_elementos < count($permisos)) {
            $sql_detalle = "INSERT INTO usuario_permiso(idUsuario, idPermiso)VALUES('$idUsuarioNew','$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos = $num_elementos + 1;
        }
        return $sw;
    }

    //Implementamos un metodo para editar registro
    public function editar($idUsuario, $nombre, $apellido, $cargo, $usuario, $contrasena, $permisos)
    {
        $sql = "UPDATE usuario SET nombre='$nombre',apellido='$apellido',cargo='$cargo',usuario='$usuario',contrasena='$contrasena' WHERE idUsuario='$idUsuario'";
        ejecutarConsulta($sql);

        //Eliminamos todos los permisos asignados para volverlos a registrar
        $sqldel = "DELETE FROM usuario_permiso WHERE idUsuario='$idUsuario'";
        ejecutarConsulta($sqldel);
        $num_elementos = 0;
        $sw = true;
        while ($num_elementos < count($permisos)) {
            $sql_detalle = "INSERT INTO usuario_permiso(idUsuario, idPermiso) VALUES('$idUsuario', '$permisos[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos = $num_elementos + 1;
        }

        return $sw;
    }

    public function desactivar($idUsuario)
    {
        $sql = "UPDATE usuario SET condicion='0' WHERE idUsuario='$idUsuario'";
        return ejecutarConsulta($sql);
    }

    public function activar($idUsuario)
    {
        $sql = "UPDATE usuario SET condicion='1' WHERE idUsuario='$idUsuario'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idUsuario)
    {
        $sql = "SELECT * FROM usuario WHERE idUsuario='$idUsuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un metodo para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM usuario";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los permisos marcados
    public function listarmarcados($idUsuario)
    {
        $sql = "SELECT * FROM usuario_permiso WHERE idUsuario='$idUsuario'";
        return ejecutarConsulta($sql);
    }

    //Función para verificar el acceso al sistema
    public function verificar($user, $password)
    {
        $this->actualizarCaja();
        $sql = "SELECT idUsuario,nombre,apellido,cargo,usuario FROM usuario WHERE usuario='$user' AND contrasena='$password' AND condicion='1'";
        return ejecutarConsulta($sql);
    }

    public function actualizarCaja()
    {
        date_default_timezone_set("America/Costa_Rica"); //Set de fecha local
        $fechaHora = date("Y-m-d");
        $sql = "UPDATE flujo_caja SET estado='cerrada' WHERE estado='abierta' AND fechaHora<>'$fechaHora'";
        return ejecutarConsulta($sql);
    }

}

