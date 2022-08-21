<?php
require_once "../config/conexion.php";

class Venta
{
//Implementamos nuestro constructor
    public function __construct()
    {

    }

    //Implementamos un método para insertar registros
    public function insertar($idCliente, $idUsuario, $tipoPago, $tipoComprobante, $numeroComprobante, $fechaHora, $pagoCliente, $vueltoCliente, $totalDescuento, $totalVenta, $iva, $totalTransporte, $idProducto, $cantidad, $precioVenta)
    {
        date_default_timezone_set("America/Costa_Rica");
        $fecha = date('Y-m-d H:i:s');
        $estado = "Aceptado";
        $sql = "INSERT INTO venta (idCliente,idUsuario,tipoPago,tipoComprobante,numeroComprobante,fechaHora,pagoCliente,vueltoCliente, totalDescuento, totalVenta,iva,totalTransporte, estado)
		VALUES ('$idCliente','$idUsuario','$tipoPago' ,'$tipoComprobante','$numeroComprobante','$fecha','$pagoCliente','$vueltoCliente','$totalDescuento','$totalVenta','$iva','$totalTransporte','$estado')";
        //return ejecutarConsulta($sql);
        $idVentaNew = ejecutarConsulta_retornarID($sql);
        $num_elementos = 0;
        $sw = true;
        while ($num_elementos < count($idProducto)) {
            $sql_detalle = "INSERT INTO detalle_venta (idVenta, idProducto,cantidad,precioVenta) VALUES ('$idVentaNew', '$idProducto[$num_elementos]','$cantidad[$num_elementos]','$precioVenta[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;
            $num_elementos = $num_elementos + 1;
        }
        return $sw;
    }

    public function disminuirTotalFinal($totalVenta, $idUsuario, $fechaHora)
    {
        $sql = "UPDATE flujo_caja SET totalFinal = totalFinal - '$totalVenta' WHERE flujo_caja.idUsuario = '$idUsuario' AND flujo_caja.fechaHora= '$fechaHora'";
        return ejecutarConsulta($sql);
    }

    public function disminuirTotalVenta($totalVenta, $idUsuario, $fechaHora)
    {
        $sql = "UPDATE flujo_caja SET totalVentas = totalVentas - '$totalVenta' WHERE flujo_caja.idUsuario = '$idUsuario' AND flujo_caja.fechaHora= '$fechaHora'";
        return ejecutarConsulta($sql);
    }

    public function aumentarSaldo($vueltoCliente, $idUsuario, $fechaHora)
    {
        $sql = "UPDATE flujo_caja SET saldo = saldo + '$vueltoCliente' WHERE flujo_caja.idUsuario = '$idUsuario' AND flujo_caja.fechaHora= '$fechaHora'";
        return ejecutarConsulta($sql);
    }


    //Implementamos un método para anular la venta
    public function anular($idVenta)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idVenta='$idVenta'";
        $rspta = $this->mostrar($idVenta);
        $encode = json_encode($rspta);
        $decode = json_decode($encode);
        $this->disminuirTotalVenta($decode->totalVenta, $decode->idUsuario, $decode->fecha);
        //$this->disminuirTotalFinal($decode->totalVenta, $decode->idUsuario, $decode->fecha);
        $this->aumentarSaldo($decode->vueltoCliente, $decode->idUsuario, $decode->fecha);

        $rsptad = $this->ventaDetalle($idVenta);
        while ($regd = $rsptad->fetch_object()) {
            $this->aumentarStock($regd->cantidad, $regd->idProducto);
        }
        return ejecutarConsulta($sql);
    }

    public function aumentarStock($cantidadConstruido, $idProducto)
    {
        $sql = "UPDATE producto SET stock = stock + '$cantidadConstruido' WHERE producto.idProducto = '$idProducto'";
        return ejecutarConsulta($sql);
    }

    public function anularApartado($idVenta)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idVenta='$idVenta'";
        return ejecutarConsulta($sql);
    }

    public function aumentarTotalVenta($totalVenta, $idUsuario, $fechaHora)
    {
        $sql = "UPDATE flujo_caja SET totalVentas = totalVentas + '$totalVenta' WHERE flujo_caja.idUsuario = '$idUsuario' AND flujo_caja.fechaHora= '$fechaHora'";
        return ejecutarConsulta($sql);
    }

    public function finalizarApartado($idVenta)
    {
        $sql = "UPDATE venta SET estado='Aceptado', tipoVenta='contado',totalVenta=totalventa*2 WHERE idVenta='$idVenta'";
        $rspta = $this->mostrar($idVenta);
        $encode = json_encode($rspta);
        $decode = json_decode($encode);
        $this->aumentarTotalVenta($decode->totalVenta, $decode->idUsuario, $decode->fecha);
        return ejecutarConsulta($sql);
    }


    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($idVenta)
    {
        $sql = "SELECT v.idVenta,v.numeroComprobante,DATE(v.fechaHora) as fecha,v.idCliente,c.nombre as cliente,u.idUsuario,u.nombre as usuario, v.tipoComprobante,v.tipoPago,v.totalVenta,v.vueltoCliente,v.estado FROM venta v INNER JOIN cliente c ON v.idCliente=c.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario WHERE v.idVenta='$idVenta'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarDetalle($idVenta)
    {
        $sql = "SELECT dv.idVenta,dv.idProducto, c.categoria, a.descripcion,dv.cantidad,dv.precioVenta FROM detalle_venta dv inner join producto  a on dv.idProducto=a.idProducto  inner join categoria  c on c.idCategoria=a.idCategoria where dv.idVenta='$idVenta'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT v.idVenta,DATE(v.fechaHora) as fecha,v.idCliente,v.tipoComprobante,CONCAT(p.nombre,' ',p.apellido) as cliente,u.idUsuario, u.usuario as usuario,v.tipoPago,v.numeroComprobante,v.totalVenta,v.estado FROM venta v INNER JOIN cliente p ON v.idCliente=p.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario ORDER by v.idVenta desc";
        return ejecutarConsulta($sql);
    }

    public function listarApartados()
    {
        /*$sql = "SELECT v.idVenta,v.numeroComprobante,DATE(v.fechaHora) as fecha,v.idCliente,v.tipoComprobante,CONCAT(p.nombre,' ',p.apellido) as cliente,p.telefono, v.tipoPago,v.totalVenta,v.estado FROM venta v INNER JOIN cliente p ON v.idCliente=p.idCliente WHERE v.tipoVenta= 'apartado' ORDER by v.idVenta desc";
        return ejecutarConsulta($sql);*/
    }

    public function numeroComprobante()
    {
        $sql = "SELECT MAX(numeroComprobante) AS numero FROM venta";
        return ejecutarConsulta($sql);
    }

    public function ventaCabeceraFactura($idVenta)
    {
        $sql = "SELECT v.idVenta,v.idCliente,CONCAT(c.nombre,' ',c.apellido) as cliente,c.direccion,c.identificacion,c.telefono,v.idUsuario,v.totalTransporte,u.nombre as usuario,v.tipoComprobante,v.numeroComprobante,v.fechaHora as fecha,v.tipoPago,v.pagoCliente,v.vueltoCliente, v.totalDescuento,v.totalVenta, v.iva FROM venta v INNER JOIN cliente c ON v.idCliente=c.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario WHERE v.idVenta='$idVenta'";
        //return ejecutarConsulta($sql);
        return ejecutarConsultaSimpleFila($sql);
    }

    public function ventaCabecera($idVenta)
    {
        $sql = "SELECT v.idVenta,v.idCliente,CONCAT(c.nombre,' ',c.apellido) as cliente,c.direccion,c.identificacion,c.telefono,v.idUsuario, v.totalTransporte,u.nombre as usuario,v.tipoComprobante,v.numeroComprobante,date(v.fechaHora) as fecha,v.tipoPago,v.pagoCliente,v.vueltoCliente, v.totalDescuento, v.totalVenta, v.iva FROM venta v INNER JOIN cliente c ON v.idCliente=c.idCliente INNER JOIN usuario u ON v.idUsuario=u.idUsuario WHERE v.idVenta='$idVenta'";
        return ejecutarConsulta($sql);
    }

    public function ventaDetalle($idVenta)
    {
        $sql = "SELECT c.categoria, p.descripcion ,p.codigo,d.cantidad,d.precioVenta,d.idProducto FROM detalle_venta d 
INNER JOIN producto  p ON d.idProducto=p.idProducto 
INNER JOIN categoria  c ON p.idCategoria=c.idCategoria  WHERE d.idVenta='$idVenta'";
        return ejecutarConsulta($sql);
    }

    public function ultimaVenta()
    {
        $sql = "select * from venta order by idVenta desc limit 1";
        return ejecutarConsulta($sql);
    }

    public function buscarProducto($codigo)
    {
        $sql = "SELECT idProducto, codigo, descripcion, precio, estado FROM producto  WHERE codigo = '$codigo'";
        return ejecutarConsultaSimpleFila($sql);
    }

}