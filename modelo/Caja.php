<?php
require "../config/conexion.php";

class Caja
{
    public function __construct()
    {

    }

    public function insertar($idUsuario, $fechaHora, $saldo)
    {
        date_default_timezone_set("America/Costa_Rica"); //Set de fecha local
        if ($fechaHora <> date("Y-m-d")) {
            $estado = "cerrada";
        } else {
            $estado = "abierta";
        }
        $sql = "INSERT INTO flujo_caja (idUsuario,fechaHora,saldo,estado) VALUES ('$idUsuario','$fechaHora','$saldo','$estado')";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para editar registro
    public function editar($idFlujo, $idUsuario, $saldo, $fechaHora)
    {
        $sql = "UPDATE flujo_caja SET idUsuario='$idUsuario',saldo='$saldo',fechaHora='$fechaHora' WHERE idFlujo='$idFlujo'";
        return ejecutarConsulta($sql);
    }

    public function listar()
    {
        $sql = "SELECT f.idFlujo,DATE(f.fechaHora) as fecha,f.idUsuario,CONCAT(u.nombre,' ',u.apellido) as usuario, u.idUsuario,f.saldo,f.totalGastos,f.totalVentas,f.totalPagoEfectivoClientes,f.totalVueltoEfectivoClientes,f.totalFinal,f.estado FROM flujo_caja f INNER JOIN usuario u ON f.idUsuario=u.idUsuario";
        return ejecutarConsulta($sql);
    }

    public function mostrarSaldo($idUsuario)
    {
        date_default_timezone_set("America/Costa_Rica");
        $fechaHoy = date("Y-m-d");
        $sql = "SELECT f.saldo, f.estado FROM flujo_caja f WHERE idUsuario='$idUsuario' AND fechaHora='$fechaHoy'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para anular la venta
    public function cerrar($idFlujo)
    {
        $sql = "UPDATE flujo_caja SET estado='cerrada' WHERE idFlujo='$idFlujo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para anular la venta
    public function abrir($idFlujo)
    {
        $sql = "UPDATE flujo_caja SET estado='abierta' WHERE idFlujo='$idFlujo'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un metodo para mostrar los datos de un registro a modificar
    public function mostrar($idFlujo)
    {
        $sql = "SELECT * FROM flujo_caja WHERE idFlujo='$idFlujo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function reporteCaja($idFlujo)
    {
        $sql = "SELECT f.idFlujo,DATE(f.fechaHora) as fecha,f.idUsuario,CONCAT(u.nombre,' ',u.apellido) as usuario, u.idUsuario,f.saldo,f.totalGastos,f.totalVentas,f.totalPagoEfectivoClientes,f.totalVueltoEfectivoClientes,f.totalFinal,f.estado FROM flujo_caja f INNER JOIN usuario u ON f.idUsuario=u.idUsuario WHERE idFlujo='$idFlujo'";
        return ejecutarConsulta($sql);
    }
}