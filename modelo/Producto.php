<?php
require "../config/conexion.php";

class Producto
{
    //Implementamos el constructor
    public function __construct()
    {

    }

    //Implementamos un metodo para insertar registro
    public function insertar($idCategoria, $codigo, $descripcion, $precio)
    {
        $sql = "INSERT INTO producto (idCategoria, codigo,descripcion, precio, estado) VALUES ('$idCategoria',' $codigo','$descripcion', $precio,'1')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para editar registro
    public function editar($idProducto, $idCategoria, $codigo, $descripcion, $precio)
    {
        $sql = "UPDATE producto SET idCategoria='$idCategoria', codigo='$codigo',descripcion='$descripcion',precio='$precio' WHERE idProducto='$idProducto'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idProducto)
    {
        $sql = "SELECT * FROM producto WHERE idProducto='$idProducto'";
        return ejecutarConsultaSimpleFila($sql);
    }

    //Implementamos un metodo para listar los registros
    public function listar()
    {
        $sql = "SELECT categoria.categoria,producto.idProducto, producto.idCategoria, producto.descripcion, producto.precio, producto.codigo, producto.estado
FROM producto
INNER JOIN categoria ON producto.idCategoria = categoria.idCategoria;";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para desactivar registros
    public function desactivar($idProducto)
    {
        $sql = "UPDATE producto SET estado='0' WHERE idProducto='$idProducto'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para activar registros
    public function activar($idProducto)
    {
        $sql = "UPDATE producto SET estado='1' WHERE idProducto='$idProducto'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros activos, su último precio y el stock (vamos a unir con el último registro de la tabla detalle_ingreso)
    public function listarActivosVenta()
    {
        $sql = "SELECT a.idProducto,a.codigo, c.categoria, a.descripcion,a.precio,a.estado FROM producto a inner join categoria  c on c.idCategoria=a.idCategoria WHERE a.estado='1'";
        return ejecutarConsulta($sql);
    }
}
