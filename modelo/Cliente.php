<?php
require "../config/conexion.php";

class Cliente
{
//Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para insertar registro
    public function insertar($identificacion, $nombre, $apellido, $telefono, $direccion)
    {
        $sql = "INSERT INTO cliente (identificacion,nombre,apellido,telefono,direccion) VALUES ('$identificacion','$nombre','$apellido','$telefono','$direccion')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para editar registro
    public function editar($idCliente, $identificacion, $nombre, $apellido, $telefono, $direccion)
    {
        $sql = "UPDATE cliente SET identificacion='$identificacion', nombre='$nombre',apellido='$apellido',telefono='$telefono',direccion='$direccion' WHERE idCliente='$idCliente'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idCliente)
    {
        $sql = "SELECT * FROM cliente WHERE idCliente='$idCliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un metodo para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM cliente WHERE nombre<>'Publico' AND apellido<>'General'";
        return ejecutarConsulta($sql);
    }
    public function listarTodos()
    {
        $sql = "SELECT * FROM cliente";
        return ejecutarConsulta($sql);
    }
}