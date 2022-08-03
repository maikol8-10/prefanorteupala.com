<?php
require "../config/conexion.php";

class Gasto
{
    public function __construct()
    {

    }

    public function disminuirSaldoPorGasto($monto, $fechaHora, $idUsuario)
    {
        $sql = "UPDATE flujo_caja SET saldo = saldo - '$monto' WHERE flujo_caja.fechaHora= '$fechaHora' AND flujo_caja.idUsuario='$idUsuario' ";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para insertar registro
    public function insertar($monto, $fechaHora, $descripcion, $idUsuario)
    {
        $sql = "INSERT INTO gastos (idUsuario,fechaHora,descripcion,monto) VALUES ('$idUsuario','$fechaHora','$descripcion','$monto')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para editar registro
    public function editar($idGasto, $monto, $fechaHora, $descripcion)
    {
        $sql = "UPDATE gastos SET monto='$monto', fechaHora='$fechaHora',descripcion='$descripcion' WHERE idGasto='$idGasto'";
        return ejecutarConsulta($sql);
    }

    public function listar($idUsuario)
    {
        date_default_timezone_set("America/Costa_Rica");
        $fechaHoy = date("Y-m-d");
        $sql = "SELECT * FROM gastos WHERE fechaHora='$fechaHoy' AND idUsuario='$idUsuario'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idGasto)
    {
        $sql = "SELECT * FROM gastos WHERE idGasto='$idGasto'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function eliminar($idGasto, $idUsuario)
    {
        $sql = "DELETE FROM gastos WHERE idGasto='$idGasto'";
        return ejecutarConsulta($sql);
    }

}