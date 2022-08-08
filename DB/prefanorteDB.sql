-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 08-08-2022 a las 05:10:50
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `prefanorteDB`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `idCategoria` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`idCategoria`, `categoria`) VALUES
(1, 'Columna'),
(2, 'Baldosa'),
(3, 'Banquina'),
(4, 'Cargador'),
(5, 'Poste '),
(6, 'Lamina de Duroc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL,
  `identificacion` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`idCliente`, `identificacion`, `nombre`, `apellido`, `telefono`, `direccion`) VALUES
(1, '', 'Publico', 'General', '', ''),
(4, '207460313', 'Felix', 'Vallejos', '85132024', '200 metros norte de San José de Upala');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `idDetalleVenta` int(11) NOT NULL,
  `idVenta` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precioVenta` decimal(11,2) NOT NULL,
  `descuento` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`idDetalleVenta`, `idVenta`, `idProducto`, `cantidad`, `precioVenta`, `descuento`) VALUES
(14973, 9904, 236, 1, '34514.00', '0.00'),
(14974, 9905, 236, 1, '34514.00', '0.00'),
(14975, 9906, 237, 1, '6638.00', '0.00'),
(14976, 9907, 237, 1, '6638.00', '0.00'),
(14977, 9907, 236, 1, '34514.00', '0.00');

--
-- Disparadores `detalle_venta`
--
DELIMITER $$
CREATE TRIGGER `Update_Stock_Productos` AFTER INSERT ON `detalle_venta` FOR EACH ROW BEGIN
 UPDATE producto SET stock = stock - NEW.cantidad 
 WHERE producto.idProducto = NEW.idProducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `flujo_caja`
--

CREATE TABLE `flujo_caja` (
  `idFlujo` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaHora` date NOT NULL DEFAULT current_timestamp(),
  `saldo` decimal(11,0) NOT NULL,
  `totalGastos` decimal(11,0) NOT NULL DEFAULT 0,
  `totalVentas` decimal(11,0) NOT NULL DEFAULT 0,
  `totalPagoEfectivoClientes` decimal(11,0) NOT NULL DEFAULT 0,
  `totalVueltoEfectivoClientes` decimal(11,0) NOT NULL DEFAULT 0,
  `totalFinal` decimal(11,0) DEFAULT 0,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `flujo_caja`
--

INSERT INTO `flujo_caja` (`idFlujo`, `idUsuario`, `fechaHora`, `saldo`, `totalGastos`, `totalVentas`, `totalPagoEfectivoClientes`, `totalVueltoEfectivoClientes`, `totalFinal`, `estado`) VALUES
(432, 19, '2022-08-02', '174497', '0', '234683', '60000', '13498', '0', 'cerrada'),
(433, 19, '2022-08-07', '150000', '5000', '42015', '60000', '13498', '0', 'abierta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `idGasto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaHora` date NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `monto` decimal(11,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`idGasto`, `idUsuario`, `fechaHora`, `descripcion`, `monto`) VALUES
(9, 19, '2022-08-07', 'Pago Prueba', '5000');

--
-- Disparadores `gastos`
--
DELIMITER $$
CREATE TRIGGER `tr_DeleteTotalGastosSaldo` AFTER DELETE ON `gastos` FOR EACH ROW BEGIN

        UPDATE flujo_caja SET totalGastos = totalGastos - OLD.monto 
 WHERE flujo_caja.idUsuario = OLD.idUsuario AND flujo_caja.fechaHora= OLD.fechaHora;
 

    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_UpdateTotalGastos` AFTER INSERT ON `gastos` FOR EACH ROW BEGIN
 UPDATE flujo_caja SET totalGastos = totalGastos + NEW.monto 
 WHERE flujo_caja.idUsuario = NEW.idUsuario AND flujo_caja.fechaHora= NEW.fechaHora;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `idInventario` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `cantidadConstruido` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`idInventario`, `idProducto`, `idUsuario`, `cantidadConstruido`, `fecha`, `estado`) VALUES
(9, 236, 19, 6, '2022-08-07', 1),
(10, 236, 19, 1, '2022-08-07', 2),
(11, 237, 19, 5, '2022-08-07', 2),
(12, 237, 19, 2, '2022-08-07', 1);

--
-- Disparadores `inventario`
--
DELIMITER $$
CREATE TRIGGER `UPDATE_STOCK_PRODUCTO` AFTER INSERT ON `inventario` FOR EACH ROW BEGIN
 UPDATE producto SET stock = stock + NEW.cantidadConstruido 
 WHERE producto.idProducto = NEW.idProducto;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `idPermiso` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`idPermiso`, `nombre`) VALUES
(1, 'Escritorio'),
(2, 'Tienda'),
(3, 'Ventas'),
(4, 'Acceso'),
(5, 'Consulta ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL,
  `idCategoria` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `precio` decimal(11,2) NOT NULL DEFAULT 0.00,
  `stock` int(11) NOT NULL DEFAULT 0,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `idCategoria`, `codigo`, `descripcion`, `precio`, `stock`, `estado`) VALUES
(236, 1, 'CDUCH3', 'Ducha 3,80', '34514.00', 5, 1),
(237, 1, 'BAL2MT', '2,00 mt', '6638.00', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `cargo` varchar(20) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `condicion` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombre`, `apellido`, `cargo`, `usuario`, `contrasena`, `condicion`) VALUES
(19, 'Michael', 'Vallejos', 'Administrador', 'mvallejos', 'dcd7b81c20d2683eeb98a6d6507d60740493edefd3ee934d4d626f19b4e9afc2', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_permiso`
--

CREATE TABLE `usuario_permiso` (
  `idUsuarioPermiso` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idPermiso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario_permiso`
--

INSERT INTO `usuario_permiso` (`idUsuarioPermiso`, `idUsuario`, `idPermiso`) VALUES
(167, 19, 1),
(168, 19, 2),
(169, 19, 3),
(170, 19, 4),
(171, 19, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `idVenta` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `tipoPago` varchar(50) NOT NULL,
  `tipoComprobante` varchar(10) NOT NULL,
  `numeroComprobante` int(11) NOT NULL,
  `fechaHora` date NOT NULL,
  `pagoCliente` decimal(11,2) NOT NULL DEFAULT 0.00,
  `vueltoCliente` decimal(11,2) NOT NULL DEFAULT 0.00,
  `totalVenta` decimal(11,2) NOT NULL,
  `iva` decimal(11,2) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `venta`
--

INSERT INTO `venta` (`idVenta`, `idCliente`, `idUsuario`, `tipoPago`, `tipoComprobante`, `numeroComprobante`, `fechaHora`, `pagoCliente`, `vueltoCliente`, `totalVenta`, `iva`, `estado`) VALUES
(9904, 1, 19, 'SinpeMovil', 'Ticket', 1, '2022-08-07', '0.00', '0.00', '39000.82', '4486.82', 'Anulado'),
(9905, 1, 19, 'Efectivo', 'Ticket', 2, '2022-08-07', '50000.00', '10999.18', '39000.82', '4486.82', 'Aceptado'),
(9906, 1, 19, 'Efectivo', 'Ticket', 3, '2022-08-07', '10000.00', '2499.06', '7500.94', '862.94', 'Aceptado'),
(9907, 1, 19, 'SinpeMovil', 'Ticket', 4, '2022-08-07', '0.00', '0.00', '46501.76', '5349.76', 'Anulado');

--
-- Disparadores `venta`
--
DELIMITER $$
CREATE TRIGGER `tr_UpdateAumentarTotalVentas` AFTER INSERT ON `venta` FOR EACH ROW BEGIN
 UPDATE flujo_caja SET totalVentas = totalVentas + NEW.totalVenta 
 WHERE flujo_caja.idUsuario = NEW.idUsuario AND flujo_caja.fechaHora= NEW.fechaHora;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_UpdateTotalesEfectivos` AFTER INSERT ON `venta` FOR EACH ROW BEGIN
UPDATE flujo_caja SET totalPagoEfectivoClientes = totalPagoEfectivoClientes + NEW.pagoCliente 
 WHERE flujo_caja.idUsuario = NEW.idUsuario AND flujo_caja.fechaHora= NEW.fechaHora;
 
 UPDATE flujo_caja SET totalVueltoEfectivoClientes = totalVueltoEfectivoClientes + NEW.vueltoCliente 
 WHERE flujo_caja.idUsuario = NEW.idUsuario AND flujo_caja.fechaHora= NEW.fechaHora;
 
 END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`idCategoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idCliente`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`idDetalleVenta`),
  ADD KEY `idPrenda` (`idProducto`),
  ADD KEY `idVenta` (`idVenta`);

--
-- Indices de la tabla `flujo_caja`
--
ALTER TABLE `flujo_caja`
  ADD PRIMARY KEY (`idFlujo`),
  ADD UNIQUE KEY `idUsuario_2` (`idUsuario`,`fechaHora`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`idGasto`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`idInventario`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`idPermiso`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `idCategoria` (`idCategoria`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- Indices de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD PRIMARY KEY (`idUsuarioPermiso`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idPermiso` (`idPermiso`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`idVenta`),
  ADD UNIQUE KEY `numeroComprobante` (`numeroComprobante`),
  ADD KEY `idCliente` (`idCliente`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `idCategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `idDetalleVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14978;

--
-- AUTO_INCREMENT de la tabla `flujo_caja`
--
ALTER TABLE `flujo_caja`
  MODIFY `idFlujo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=434;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `idGasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `idInventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `idPermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  MODIFY `idUsuarioPermiso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `idVenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9908;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`idVenta`) REFERENCES `venta` (`idVenta`);

--
-- Filtros para la tabla `flujo_caja`
--
ALTER TABLE `flujo_caja`
  ADD CONSTRAINT `flujo_caja_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`idProducto`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventario_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `usuario_permiso`
--
ALTER TABLE `usuario_permiso`
  ADD CONSTRAINT `usuario_permiso_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `usuario_permiso_ibfk_2` FOREIGN KEY (`idPermiso`) REFERENCES `permiso` (`idPermiso`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`idCliente`) REFERENCES `cliente` (`idCliente`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
