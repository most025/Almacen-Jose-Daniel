-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2025 a las 16:25:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `almacen_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carga_productos`
--

CREATE TABLE `carga_productos` (
  `id_carga` int(11) NOT NULL,
  `nombre_producto` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `precio_compra` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `fecha_carga` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carga_productos`
--

INSERT INTO `carga_productos` (`id_carga`, `nombre_producto`, `descripcion`, `categoria`, `precio_compra`, `precio_venta`, `fecha_carga`) VALUES
(1, 'ACTIMEL Frutilla', 'Botellita 100 gr x 1 Frutilla', 'Bebidas', 1186.95, 1795.00, '2025-10-25 23:18:12'),
(2, 'ACTIMEL Multifruta', 'Botellita 100 gr x 1 Multifruta', 'Bebidas', 1186.95, 1795.00, '2025-10-28 00:11:44'),
(3, 'Arroz 1 kg', 'jhj', 'Alimentos', 899.00, 1222.00, '2025-10-28 12:04:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_productos`
--

CREATE TABLE `inventario_productos` (
  `id_inventario` int(11) NOT NULL,
  `id_carga` int(11) NOT NULL,
  `stockDisponible` int(11) DEFAULT 0,
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_productos`
--

INSERT INTO `inventario_productos` (`id_inventario`, `id_carga`, `stockDisponible`, `fecha_actualizacion`) VALUES
(1, 1, 18, '2025-10-28 12:01:28'),
(2, 2, 7, '2025-10-28 12:01:28'),
(3, 3, 5, '2025-10-28 12:09:02');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_productos`
--

CREATE TABLE `ofertas_productos` (
  `id_oferta` int(11) NOT NULL,
  `id_inventario` int(11) NOT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT 0.00,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('Activa','Inactiva') DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

CREATE TABLE `ventas_productos` (
  `id_venta` int(11) NOT NULL,
  `id_inventario` int(11) NOT NULL,
  `cantidad_vendida` int(11) NOT NULL,
  `total_venta` decimal(10,2) NOT NULL,
  `fecha_venta` datetime DEFAULT current_timestamp(),
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`id_venta`, `id_inventario`, `cantidad_vendida`, `total_venta`, `fecha_venta`, `metodo_pago`) VALUES
(1, 1, 1, 1795.00, '2025-10-28 11:41:08', 'Efectivo'),
(2, 2, 1, 1795.00, '2025-10-28 11:41:08', 'Efectivo'),
(3, 2, 1, 1795.00, '2025-10-28 11:41:32', 'Transferencia'),
(4, 1, 1, 1795.00, '2025-10-28 12:01:28', 'Transferencia'),
(5, 2, 1, 1795.00, '2025-10-28 12:01:28', 'Transferencia'),
(6, 3, 2, 2444.00, '2025-10-28 12:09:02', 'Efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carga_productos`
--
ALTER TABLE `carga_productos`
  ADD PRIMARY KEY (`id_carga`);

--
-- Indices de la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  ADD PRIMARY KEY (`id_inventario`),
  ADD KEY `id_carga` (`id_carga`);

--
-- Indices de la tabla `ofertas_productos`
--
ALTER TABLE `ofertas_productos`
  ADD PRIMARY KEY (`id_oferta`),
  ADD KEY `id_inventario` (`id_inventario`);

--
-- Indices de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_inventario` (`id_inventario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carga_productos`
--
ALTER TABLE `carga_productos`
  MODIFY `id_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ofertas_productos`
--
ALTER TABLE `ofertas_productos`
  MODIFY `id_oferta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  ADD CONSTRAINT `inventario_productos_ibfk_1` FOREIGN KEY (`id_carga`) REFERENCES `carga_productos` (`id_carga`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ofertas_productos`
--
ALTER TABLE `ofertas_productos`
  ADD CONSTRAINT `ofertas_productos_ibfk_1` FOREIGN KEY (`id_inventario`) REFERENCES `inventario_productos` (`id_inventario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD CONSTRAINT `ventas_productos_ibfk_1` FOREIGN KEY (`id_inventario`) REFERENCES `inventario_productos` (`id_inventario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
