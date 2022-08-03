<?php
require "../config/conexion.php";
class Categoria
{
    //Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM categoria";
        return ejecutarConsulta($sql);
    }
}