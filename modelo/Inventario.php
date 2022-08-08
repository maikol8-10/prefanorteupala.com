<?php
require "../config/conexion.php";


class Inventario
{
    //Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para insertar registro
    public function insertar($idProducto, $idUsuario, $cantidadConstruido, $fecha)
    {
        $sql = "INSERT INTO inventario (idProducto,idUsuario, cantidadConstruido, fecha) VALUES ('$idProducto','$idUsuario','$cantidadConstruido','$fecha')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para editar registro
    public function editar($idInventario, $idProducto, $idUsuario, $cantidadConstruido, $fecha)
    {
        $sql = "UPDATE inventario SET idCategoria='$idProducto', idUsuario='$idUsuario',cantidadConstruido='$cantidadConstruido',fecha='$fecha' WHERE idInventario='$idInventario'";
        return ejecutarConsulta($sql);
    }


    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idInventario)
    {
        $sql = "SELECT * FROM inventario WHERE idInventario='$idInventario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT inventario.idInventario, inventario.estado, inventario.cantidadConstruido, inventario.fecha, categoria.categoria, producto.descripcion, producto.precio
FROM inventario
INNER JOIN producto ON inventario.idProducto = producto.idProducto
INNER JOIN categoria ON categoria.idCategoria = producto.idCategoria;";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para anular la venta
    public function anular($idInventario)
    {
        $sql = "UPDATE inventario SET estado=2 WHERE idInventario='$idInventario'";
        $rspta = $this->mostrar($idInventario);
        $encode = json_encode($rspta);
        $decode = json_decode($encode);
        $this->disminuirStock($decode->cantidadConstruido, $decode->idProducto, $decode->fecha);
        return ejecutarConsulta($sql);
    }

    public function disminuirStock($cantidadConstruido, $idProducto)
    {
        $sql = "UPDATE producto SET stock = stock - '$cantidadConstruido' WHERE producto.idProducto = '$idProducto'";
        return ejecutarConsulta($sql);
    }
}