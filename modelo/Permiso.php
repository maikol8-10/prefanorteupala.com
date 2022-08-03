<?php
require "../config/conexion.php";

class Permiso
{
//Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM permiso";
        return ejecutarConsulta($sql);
    }

}