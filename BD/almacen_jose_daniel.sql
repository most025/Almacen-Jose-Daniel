-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2025 a las 22:51:23
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
-- Base de datos: `almacen_jose_daniel`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carga_productos`
--

CREATE TABLE `carga_productos` (
  `id_carga` int(11) NOT NULL,
  `nombre_producto` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `fecha_carga` datetime DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carga_productos`
--

INSERT INTO `carga_productos` (`id_carga`, `nombre_producto`, `descripcion`, `id_categoria`, `precio_compra`, `precio_venta`, `fecha_carga`, `estado`) VALUES
(1, 'Leche ilolay', 'Leche de segunda marca.', 1, 250.99, 450.99, NULL, 'Inactivo'),
(2, 'La Serenicima', 'La más natural', 1, 1200.99, 3200.00, NULL, 'Activo'),
(3, 'Arroz Integral 0000-Tio Carlos.', 'Paquete de arroz integral.', 2, 2973.99, 7550.99, NULL, 'Inactivo'),
(4, 'Leche en polvo', 'aaaa', 1, 500.00, 1500.00, NULL, 'Inactivo'),
(5, 'Arroz doña nona', 'alimento para toda la familia', 3, 800.99, 2800.99, NULL, 'Inactivo'),
(6, 'yogur La Serenicima', 'Sabor frutilla.', 1, 800.00, 3800.00, NULL, 'Activo'),
(7, 'Arroz Integral 0000-Tio Tito.', 'el mas rico', 2, 4000.00, 6000.00, NULL, 'Activo'),
(8, 'Arroz Integral 0000 -Tio Jose.', 'Super rico.', 2, 4500.00, 6500.00, NULL, 'Activo'),
(9, 'Arroz Integral 0000-Tia laura', 'El arroz familiar para el fin de semana.', 2, 4500.00, 6500.00, NULL, 'Activo'),
(10, 'Arroz Integral 0000-Tia laura.', 'hhjujuuuju', 2, 4500.00, 6500.00, NULL, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `descripcion`, `estado`) VALUES
(1, 'Lacteos', 'todos los lacteos.', 'Activo'),
(2, 'Cereales', 'Alimentos a base hidratos de carbonos y proteínas.', 'Activo'),
(3, 'Alimentos', 'todos los alimentos', 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `dni` int(100) DEFAULT NULL,
  `telefono` int(11) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `dni`, `telefono`, `estado`) VALUES
(1, 'jorge', 'sanchez', 40593079, 3444549, 'Activo'),
(2, 'Sofia', 'Algañaraz', 40593078, 3444556, 'Inactivo'),
(3, 'Jorge ', 'marquez', 30678987, 2567899, 'Activo'),
(4, 'Fatima', 'Martinez', 23894567, 264345069, 'Activo'),
(5, 'Benito', 'Clavel', 20154908, 264589076, 'Activo'),
(6, 'Benito', 'suarez', 13456089, 264987034, 'Activo'),
(7, 'Jose ', 'Diaz', 127686960, 264988089, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_productos`
--

CREATE TABLE `inventario_productos` (
  `id_inventario` int(11) NOT NULL,
  `id_carga` int(11) DEFAULT NULL,
  `stockDisponible` int(11) DEFAULT NULL,
  `fecha_actualizacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario_productos`
--

INSERT INTO `inventario_productos` (`id_inventario`, `id_carga`, `stockDisponible`, `fecha_actualizacion`) VALUES
(1, 1, 20, NULL),
(2, 2, 340, NULL),
(3, 3, 504, NULL),
(4, 4, 70, NULL),
(5, 5, 120, NULL),
(6, 6, 116, NULL),
(7, 7, 298, NULL),
(8, 8, 300, NULL),
(9, 9, 307, NULL),
(10, 10, 305, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_productos`
--

CREATE TABLE `ofertas_productos` (
  `id_oferta` int(11) NOT NULL,
  `id_inventario` int(11) DEFAULT NULL,
  `descuento_porcentaje` decimal(5,2) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('Activa','Inactiva') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(100) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id_proveedor`, `nombre_proveedor`, `direccion`, `telefono`, `email`, `estado`) VALUES
(1, 'almacen sanchez', 'falsa 323 af', '3444549', 'almacensanchez@nhygygyg.com', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rol` varchar(50) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`, `descripcion`) VALUES
(1, 'administrador', 'tiene control total del sistema'),
(2, 'Vendedor', 'Se ocupa de la parte de ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) DEFAULT NULL,
  `clave` varchar(100) DEFAULT NULL,
  `id_rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `clave`, `id_rol`) VALUES
(1, 'raiz45', '12345', 1),
(2, 'empleado1', '123456', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas_productos`
--

CREATE TABLE `ventas_productos` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `descripcion` varchar(100) DEFAULT NULL,
  `total_venta` decimal(10,2) DEFAULT NULL,
  `fecha_venta` datetime DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas_productos`
--

INSERT INTO `ventas_productos` (`id_venta`, `id_cliente`, `descripcion`, `total_venta`, `fecha_venta`, `metodo_pago`) VALUES
(1, 1, 'La Serenicima x5', 16000.00, '2025-11-16 00:00:00', 'Efectivo'),
(2, 3, 'yogur La Serenicima x7', 26600.00, '2025-11-16 00:00:00', 'Transferencia'),
(3, 4, 'Arroz Integral 0000-Tio Tito. x2', 12000.00, '2025-11-17 00:00:00', 'Transferencia'),
(4, 7, 'Arroz Integral 0000 -Tio Jose. x4', 26000.00, '2025-11-17 00:00:00', 'Efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carga_productos`
--
ALTER TABLE `carga_productos`
  ADD PRIMARY KEY (`id_carga`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

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
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carga_productos`
--
ALTER TABLE `carga_productos`
  MODIFY `id_carga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  MODIFY `id_inventario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `ofertas_productos`
--
ALTER TABLE `ofertas_productos`
  MODIFY `id_oferta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carga_productos`
--
ALTER TABLE `carga_productos`
  ADD CONSTRAINT `carga_productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `inventario_productos`
--
ALTER TABLE `inventario_productos`
  ADD CONSTRAINT `inventario_productos_ibfk_1` FOREIGN KEY (`id_carga`) REFERENCES `carga_productos` (`id_carga`);

--
-- Filtros para la tabla `ofertas_productos`
--
ALTER TABLE `ofertas_productos`
  ADD CONSTRAINT `ofertas_productos_ibfk_1` FOREIGN KEY (`id_inventario`) REFERENCES `inventario_productos` (`id_inventario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);

--
-- Filtros para la tabla `ventas_productos`
--
ALTER TABLE `ventas_productos`
  ADD CONSTRAINT `ventas_productos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
