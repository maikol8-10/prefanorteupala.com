<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/conexion.php";

class Consulta
{
//Implementamos nuestro constructor
    public function __construct()
    {

    }

    public function totalVentaHoy()
    {
        date_default_timezone_set("America/Costa_Rica"); //Set de fecha local
        $fechaHora = date("Y-m-d");
        $sql = "SELECT IFNULL(SUM(totalVenta),0) as totalVenta FROM venta WHERE DATE(fechaHora)='$fechaHora' AND estado='Aceptado'";
        return ejecutarConsulta($sql);
    }
    public function totalApartadosHoy()
    {
        date_default_timezone_set("America/Costa_Rica"); //Set de fecha local
        $fechaHora = date("Y-m-d");
        $sql = "SELECT IFNULL(SUM(totalVenta),0) as totalVenta FROM venta WHERE DATE(fechaHora)='$fechaHora' AND estado='Pendiente'";
        return ejecutarConsulta($sql);
    }

    public function totalGastosHoy()
    {
        date_default_timezone_set("America/Costa_Rica"); //Set de fecha local
        $fechaHora = date("Y-m-d");
        $sql = "SELECT IFNULL(SUM(monto),0) as totalGastos FROM gastos WHERE DATE(fechaHora)='$fechaHora'";
        return ejecutarConsulta($sql);
    }

    public function totalGastosUltimos5Meses()
    {
        $sql = "SELECT DATE_FORMAT(fechaHora,'%M') as fecha,SUM(monto) as total FROM gastos GROUP by MONTH(fechaHora) ORDER BY fechaHora DESC limit 0,5";
        return ejecutarConsulta($sql);
    }

    public function ventasUltimos10Dias()
    {
        $sql = "SELECT CONCAT(DAY(fechaHora),'-',MONTH(fechaHora)) as fecha,SUM(totalVenta) as total FROM venta GROUP by fechaHora ORDER BY fechaHora DESC limit 0,10";
        return ejecutarConsulta($sql);
    }

    public function ventasPorCliente($idCliente)
    {
        $sql = "SELECT DATE(v.fechaHora) as fecha,u.nombre as usuario, CONCAT(p.nombre,' ',p.apellido) as cliente,v.tipoPago,v.numeroComprobante,v.totalVenta,v.estado FROM venta v INNER JOIN cliente p ON v.idCliente=p.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario WHERE v.idCliente='$idCliente' AND v.estado<>'pendiente'";
        return ejecutarConsulta($sql);
    }

    public function ventasPorFecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(v.fechaHora) as fecha,u.nombre as usuario, CONCAT(p.nombre,' ',p.apellido) as cliente,v.tipoPago,v.numeroComprobante,v.totalVenta,v.estado FROM venta v INNER JOIN cliente p ON v.idCliente=p.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario WHERE DATE(v.fechaHora)>='$fecha_inicio' AND DATE(v.fechaHora)<='$fecha_fin' AND v.estado<>'Pendiente'";
        return ejecutarConsulta($sql);
    }
    public function gastosPorFecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT g.idGasto, DATE(g.fechaHora) as fecha, g.descripcion, g.monto,u.idUsuario, u.usuario as usuario FROM gastos g INNER JOIN usuario u ON g.idUsuario=u.idUsuario WHERE DATE(g.fechaHora)>='$fecha_inicio' AND DATE(g.fechaHora)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }

    public function totalGastosPorFecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT IFNULL(SUM(monto),0) as totalGastos FROM gastos g WHERE DATE(g.fechaHora)>='$fecha_inicio' AND DATE(g.fechaHora)<='$fecha_fin'";
        return ejecutarConsulta($sql);
    }

    public function totalVentasPorFechas($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT IFNULL(SUM(totalVenta),0) as totalVenta FROM venta v WHERE DATE(v.fechaHora)>='$fecha_inicio' AND DATE(v.fechaHora)<='$fecha_fin' AND v.estado<>'Anulado'";
        return ejecutarConsulta($sql);
    }

    public function ventasUltimos_12meses()
    {
        $sql = "SELECT DATE_FORMAT(fechaHora,'%M') as fecha,SUM(totalVenta) as total FROM venta GROUP by MONTH(fechaHora) ORDER BY fechaHora DESC limit 0,10";
        return ejecutarConsulta($sql);
    }
}