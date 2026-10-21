-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2026 a las 23:45:57
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
-- Base de datos: `baggy_culture`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesores`
--

CREATE TABLE `asesores` (
  `id_asesor` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asesores`
--

INSERT INTO `asesores` (`id_asesor`, `nombre`, `apellido`, `correo`, `telefono`) VALUES
('10', 'Pablo', 'Castro', 'pablo.castro@baggy.com', '71010009'),
('11', 'Laura', 'Mamani', 'laura.mamani@baggy.com', '71010010'),
('2', 'Carlos', 'Ramirez', 'carlos.ramirez@baggy.com', '71010001'),
('3', 'Valentina', 'Rojas', 'valentina.rojas@baggy.com', '71010002'),
('4', 'Diego', 'Mendoza', 'diego.mendoza@baggy.com', '71010003'),
('5', 'Gabriela', 'Flores', 'gabriela.flores@baggy.com', '71010004'),
('6', 'Martin', 'Quispe', 'martin.quispe@baggy.com', '71010005'),
('7', 'Sofia', 'Vargas', 'sofia.vargas@baggy.com', '71010006'),
('8', 'Andres', 'Lopez', 'andres.lopez@baggy.com', '71010007'),
('9', 'Camila', 'Torrez', 'camila.torrez@baggy.com', '71010008');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`) VALUES
('10', 'Gorras', 'Gorras urbanas'),
('11', 'Medias', 'Medias y calcetines'),
('12', 'Conjuntos', 'Conjuntos completos'),
('13', 'Camisas', 'Camisas casuales'),
('14', 'Joyeria', 'Accesorios y joyeria urbana'),
('5', 'Hoodies', 'Sudaderas y hoodies urbanos'),
('6', 'Poleras', 'Poleras de estilo streetwear'),
('7', 'Chaquetas', 'Chaquetas y prendas exteriores'),
('8', 'Shorts', 'Shorts urbanos'),
('9', 'Mochilas', 'Mochilas y bolsos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `nombre_completo` varchar(201) GENERATED ALWAYS AS (concat(`nombre`,' ',`apellido`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`) VALUES
('10', 'Sergio', 'Mamani', 'sergio.mamani@mail.com', '70010007', 'Sucre'),
('11', 'Alejandra', 'Lopez', 'alejandra.lopez@mail.com', '70010008', 'Tarija'),
('12', 'Rodrigo', 'Castro', 'rodrigo.castro@mail.com', '70010009', 'La Paz'),
('13', 'Fernanda', 'Rojas', 'fernanda.rojas@mail.com', '70010010', 'El Alto'),
('4', 'Miguel', 'Choque', 'miguel.choque@mail.com', '70010001', 'La Paz'),
('5', 'Carla', 'Mendoza', 'carla.mendoza@mail.com', '70010002', 'El Alto'),
('6', 'Pedro', 'Flores', 'pedro.flores@mail.com', '70010003', 'Cochabamba'),
('7', 'Lucia', 'Ramos', 'lucia.ramos@mail.com', '70010004', 'Santa Cruz'),
('8', 'Marco', 'Vargas', 'marco.vargas@mail.com', '70010005', 'La Paz'),
('9', 'Paola', 'Quispe', 'paola.quispe@mail.com', '70010006', 'Oruro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

CREATE TABLE `cotizacion` (
  `id_cotizacion` varchar(20) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `id_asesor` varchar(20) NOT NULL,
  `id_cliente` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cotizacion`
--

INSERT INTO `cotizacion` (`id_cotizacion`, `fecha`, `descripcion`, `total`, `estado`, `id_usuario`, `id_asesor`, `id_cliente`) VALUES
('10', '2026-10-19 12:30:00', 'Cotizacion Medias', 50.00, 'Aprobada', '12', '8', '10'),
('11', '2026-10-19 12:35:00', 'Cotizacion Conjunto', 350.00, 'Pendiente', '13', '9', '11'),
('12', '2026-10-19 12:40:00', 'Cotizacion Camisa', 160.00, 'Aprobada', '14', '10', '12'),
('13', '2026-10-19 12:45:00', 'Cotizacion Cadena', 100.00, 'Pendiente', '15', '11', '13'),
('4', '2026-10-19 12:00:00', 'Cotizacion Hoodie', 180.00, 'Aprobada', '6', '2', '4'),
('5', '2026-10-19 12:05:00', 'Cotizacion Polera', 90.00, 'Pendiente', '7', '3', '5'),
('6', '2026-10-19 12:10:00', 'Cotizacion Chaqueta', 320.00, 'Aprobada', '8', '4', '6'),
('7', '2026-10-19 12:15:00', 'Cotizacion Short', 140.00, 'Pendiente', '9', '5', '7'),
('8', '2026-10-19 12:20:00', 'Cotizacion Mochila', 210.00, 'Aprobada', '10', '6', '8'),
('9', '2026-10-19 12:25:00', 'Cotizacion Gorra', 75.00, 'Pendiente', '11', '7', '9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id_detalle` int(11) NOT NULL,
  `id_pedido` varchar(20) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `id_producto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id_detalle`, `id_pedido`, `cantidad`, `precio_unitario`, `subtotal`, `id_producto`) VALUES
(1, '6', 1, 180.00, 180.00, '7'),
(2, '7', 1, 90.00, 90.00, '8'),
(3, '8', 1, 320.00, 320.00, '9'),
(4, '9', 1, 140.00, 140.00, '10'),
(5, '10', 1, 210.00, 210.00, '11'),
(6, '11', 1, 75.00, 75.00, '12'),
(7, '12', 1, 50.00, 50.00, '13'),
(8, '13', 1, 350.00, 350.00, '14'),
(9, '14', 1, 160.00, 160.00, '15'),
(10, '15', 1, 100.00, 100.00, '16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `envios`
--

CREATE TABLE `envios` (
  `id_envio` varchar(20) NOT NULL,
  `direccion` text NOT NULL,
  `fecha_envio` datetime DEFAULT NULL,
  `fecha_entrega` datetime DEFAULT NULL,
  `estado` varchar(50) NOT NULL,
  `id_pedido` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `envios`
--

INSERT INTO `envios` (`id_envio`, `direccion`, `fecha_envio`, `fecha_entrega`, `estado`, `id_pedido`) VALUES
('10', 'Calle Bolivar 230', '2026-10-19 11:00:00', '2026-10-21 12:00:00', 'Entregado', '12'),
('11', 'Av. La Paz 670', '2026-10-19 11:10:00', NULL, 'Preparando', '13'),
('12', 'Calle Murillo 910', '2026-10-19 11:20:00', NULL, 'En camino', '14'),
('13', 'Av. Camacho 150', '2026-10-19 11:30:00', '2026-10-20 17:00:00', 'Entregado', '15'),
('4', 'Av. Arce 120', '2026-10-19 10:00:00', '2026-10-20 14:00:00', 'Entregado', '6'),
('5', 'Av. Juan Pablo II 450', '2026-10-19 10:10:00', NULL, 'Preparando', '7'),
('6', 'Av. America 780', '2026-10-19 10:20:00', NULL, 'En camino', '8'),
('7', 'Av. Busch 340', '2026-10-19 10:30:00', '2026-10-21 16:00:00', 'Entregado', '9'),
('8', 'Calle Comercio 560', '2026-10-19 10:40:00', NULL, 'Preparando', '10'),
('9', 'Av. 6 de Agosto 890', '2026-10-19 10:50:00', NULL, 'En camino', '11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id_metodo_pago` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id_metodo_pago`, `nombre`) VALUES
(4, 'Efectivo'),
(3, 'QR'),
(1, 'Tarjeta'),
(2, 'Transferencia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` varchar(20) NOT NULL,
  `fecha_pago` datetime DEFAULT current_timestamp(),
  `monto` decimal(10,2) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `id_pedido` varchar(20) NOT NULL,
  `id_metodo_pago` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `fecha_pago`, `monto`, `estado`, `id_pedido`, `id_metodo_pago`) VALUES
('10', '2026-10-19 09:25:00', 75.00, 'Aprobado', '11', 3),
('11', '2026-10-19 09:30:00', 50.00, 'Aprobado', '12', 4),
('12', '2026-10-19 09:35:00', 350.00, 'Pendiente', '13', 2),
('13', '2026-10-19 09:40:00', 160.00, 'Aprobado', '14', 1),
('14', '2026-10-19 09:45:00', 100.00, 'Aprobado', '15', 3),
('5', '2026-10-19 09:00:00', 180.00, 'Aprobado', '6', 1),
('6', '2026-10-19 09:05:00', 90.00, 'Pendiente', '7', 2),
('7', '2026-10-19 09:10:00', 320.00, 'Aprobado', '8', 3),
('8', '2026-10-19 09:15:00', 140.00, 'Aprobado', '9', 4),
('9', '2026-10-19 09:20:00', 210.00, 'Pendiente', '10', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` varchar(20) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `id_usuario` varchar(20) NOT NULL,
  `anio_pedido` int(11) GENERATED ALWAYS AS (year(`fecha`)) STORED,
  `mes_pedido` int(11) GENERATED ALWAYS AS (month(`fecha`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `fecha`, `estado`, `total`, `id_usuario`) VALUES
('10', '2026-10-19 08:30:00', 'Pendiente', 210.00, '10'),
('11', '2026-10-19 08:35:00', 'Enviado', 75.00, '11'),
('12', '2026-10-19 08:40:00', 'Completado', 50.00, '12'),
('13', '2026-10-19 08:45:00', 'Pendiente', 350.00, '13'),
('14', '2026-10-19 08:50:00', 'Enviado', 160.00, '14'),
('15', '2026-10-19 08:55:00', 'Completado', 100.00, '15'),
('6', '2026-10-19 08:10:00', 'Completado', 180.00, '6'),
('7', '2026-10-19 08:15:00', 'Pendiente', 90.00, '7'),
('8', '2026-10-19 08:20:00', 'Enviado', 320.00, '8'),
('9', '2026-10-19 08:25:00', 'Completado', 140.00, '9');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` varchar(20) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `id_categoria` varchar(20) NOT NULL,
  `nombre_lower` varchar(150) GENERATED ALWAYS AS (lcase(`nombre`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `imagen`, `id_categoria`) VALUES
('10', 'Short Cargo Beige', 'Short cargo beige', 140.00, 25, NULL, '8'),
('11', 'Mochila Urban Black', 'Mochila negra urbana', 210.00, 18, NULL, '9'),
('12', 'Gorra Classic Black', 'Gorra negra clasica', 75.00, 40, NULL, '10'),
('13', 'Medias Street Pack', 'Pack de medias urbanas', 50.00, 50, NULL, '11'),
('14', 'Conjunto Urban Grey', 'Conjunto urbano gris', 350.00, 10, NULL, '12'),
('15', 'Camisa Street White', 'Camisa blanca streetwear', 160.00, 22, NULL, '13'),
('16', 'Cadena Urban Silver', 'Cadena estilo urbano', 100.00, 30, NULL, '14'),
('7', 'Hoodie Black Oversize', 'Hoodie negro oversize', 180.00, 20, NULL, '5'),
('8', 'Polera Urban White', 'Polera blanca urbana', 90.00, 35, NULL, '6'),
('9', 'Chaqueta Street Black', 'Chaqueta urbana negra', 320.00, 12, NULL, '7');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` varchar(20) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `correo_lower` varchar(150) GENERATED ALWAYS AS (lcase(`correo`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `correo`, `contrasena`, `telefono`, `direccion`) VALUES
('10', 'Diego', 'Torrez', 'diego.torrez@mail.com', '1234', '76510005', 'Santa Cruz'),
('11', 'Camila', 'Vargas', 'camila.vargas@mail.com', '1234', '76510006', 'Oruro'),
('12', 'Javier', 'Condori', 'javier.condori@mail.com', '1234', '76510007', 'La Paz'),
('13', 'Daniela', 'Rojas', 'daniela.rojas@mail.com', '1234', '76510008', 'Sucre'),
('14', 'Fernando', 'Lopez', 'fernando.lopez@mail.com', '1234', '76510009', 'Tarija'),
('15', 'Natalia', 'Castro', 'natalia.castro@mail.com', '1234', '76510010', 'La Paz'),
('6', 'Luis', 'Fernandez', 'luis.fernandez@mail.com', '1234', '76510001', 'La Paz'),
('7', 'Andrea', 'Quispe', 'andrea.quispe@mail.com', '1234', '76510002', 'El Alto'),
('8', 'Mateo', 'Flores', 'mateo.flores@mail.com', '1234', '76510003', 'Cochabamba'),
('9', 'Valeria', 'Mamani', 'valeria.mamani@mail.com', '1234', '76510004', 'La Paz');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_control_pagos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_control_pagos` (
`id_pago` varchar(20)
,`id_pedido` varchar(20)
,`monto` decimal(10,2)
,`metodo_pago` varchar(50)
,`estado_pago` varchar(50)
,`fecha_pago` datetime
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_detalles_pedidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_detalles_pedidos` (
`id_pedido` varchar(20)
,`fecha` datetime
,`estado` varchar(50)
,`total` decimal(10,2)
,`cliente_nombre` varchar(201)
,`cliente_correo` varchar(150)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_detalle_compras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_detalle_compras` (
`id_pedido` varchar(20)
,`producto` varchar(150)
,`cantidad` int(11)
,`precio_unitario` decimal(10,2)
,`subtotal` decimal(10,2)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_inventario_productos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_inventario_productos` (
`id_producto` varchar(20)
,`producto` varchar(150)
,`precio` decimal(10,2)
,`stock` int(11)
,`categoria` varchar(100)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_resumen_cotizaciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_resumen_cotizaciones` (
`id_cotizacion` varchar(20)
,`fecha` datetime
,`total` decimal(10,2)
,`estado` varchar(50)
,`usuario_responsable` varchar(201)
,`asesor_encargado` varchar(201)
,`cliente_nombre` varchar(201)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_seguimiento_envios`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_seguimiento_envios` (
`id_envio` varchar(20)
,`id_pedido` varchar(20)
,`direccion_envio` text
,`fecha_envio` datetime
,`fecha_entrega` datetime
,`estado_envio` varchar(50)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_usuarios_activos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_usuarios_activos` (
`id_usuario` varchar(20)
,`usuario` varchar(201)
,`correo` varchar(150)
,`total_pedidos` bigint(21)
,`total_gastado` decimal(32,2)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_control_pagos`
--
DROP TABLE IF EXISTS `vista_control_pagos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_control_pagos`  AS SELECT `pagos`.`id_pago` AS `id_pago`, `pagos`.`id_pedido` AS `id_pedido`, `pagos`.`monto` AS `monto`, `metodos_pago`.`nombre` AS `metodo_pago`, `pagos`.`estado` AS `estado_pago`, `pagos`.`fecha_pago` AS `fecha_pago` FROM ((`pagos` join `metodos_pago` on(`pagos`.`id_metodo_pago` = `metodos_pago`.`id_metodo_pago`)) join `pedidos` on(`pagos`.`id_pedido` = `pedidos`.`id_pedido`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_detalles_pedidos`
--
DROP TABLE IF EXISTS `vista_detalles_pedidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_detalles_pedidos`  AS SELECT `pedidos`.`id_pedido` AS `id_pedido`, `pedidos`.`fecha` AS `fecha`, `pedidos`.`estado` AS `estado`, `pedidos`.`total` AS `total`, concat(`usuarios`.`nombre`,' ',`usuarios`.`apellido`) AS `cliente_nombre`, `usuarios`.`correo` AS `cliente_correo` FROM (`pedidos` join `usuarios` on(`pedidos`.`id_usuario` = `usuarios`.`id_usuario`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_detalle_compras`
--
DROP TABLE IF EXISTS `vista_detalle_compras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_detalle_compras`  AS SELECT `detalle_pedido`.`id_pedido` AS `id_pedido`, `productos`.`nombre` AS `producto`, `detalle_pedido`.`cantidad` AS `cantidad`, `detalle_pedido`.`precio_unitario` AS `precio_unitario`, `detalle_pedido`.`subtotal` AS `subtotal` FROM (`detalle_pedido` join `productos` on(`detalle_pedido`.`id_producto` = `productos`.`id_producto`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_inventario_productos`
--
DROP TABLE IF EXISTS `vista_inventario_productos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_inventario_productos`  AS SELECT `productos`.`id_producto` AS `id_producto`, `productos`.`nombre` AS `producto`, `productos`.`precio` AS `precio`, `productos`.`stock` AS `stock`, `categorias`.`nombre` AS `categoria` FROM (`productos` join `categorias` on(`productos`.`id_categoria` = `categorias`.`id_categoria`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_resumen_cotizaciones`
--
DROP TABLE IF EXISTS `vista_resumen_cotizaciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_resumen_cotizaciones`  AS SELECT `cotizacion`.`id_cotizacion` AS `id_cotizacion`, `cotizacion`.`fecha` AS `fecha`, `cotizacion`.`total` AS `total`, `cotizacion`.`estado` AS `estado`, concat(`usuarios`.`nombre`,' ',`usuarios`.`apellido`) AS `usuario_responsable`, concat(`asesores`.`nombre`,' ',`asesores`.`apellido`) AS `asesor_encargado`, concat(`clientes`.`nombre`,' ',`clientes`.`apellido`) AS `cliente_nombre` FROM (((`cotizacion` join `usuarios` on(`cotizacion`.`id_usuario` = `usuarios`.`id_usuario`)) join `asesores` on(`cotizacion`.`id_asesor` = `asesores`.`id_asesor`)) join `clientes` on(`cotizacion`.`id_cliente` = `clientes`.`id_cliente`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_seguimiento_envios`
--
DROP TABLE IF EXISTS `vista_seguimiento_envios`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_seguimiento_envios`  AS SELECT `envios`.`id_envio` AS `id_envio`, `envios`.`id_pedido` AS `id_pedido`, `envios`.`direccion` AS `direccion_envio`, `envios`.`fecha_envio` AS `fecha_envio`, `envios`.`fecha_entrega` AS `fecha_entrega`, `envios`.`estado` AS `estado_envio` FROM `envios` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_usuarios_activos`
--
DROP TABLE IF EXISTS `vista_usuarios_activos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_usuarios_activos`  AS SELECT `usuarios`.`id_usuario` AS `id_usuario`, concat(`usuarios`.`nombre`,' ',`usuarios`.`apellido`) AS `usuario`, `usuarios`.`correo` AS `correo`, count(`pedidos`.`id_pedido`) AS `total_pedidos`, coalesce(sum(`pedidos`.`total`),0) AS `total_gastado` FROM (`usuarios` left join `pedidos` on(`usuarios`.`id_usuario` = `pedidos`.`id_usuario`)) GROUP BY `usuarios`.`id_usuario`, `usuarios`.`nombre`, `usuarios`.`apellido`, `usuarios`.`correo` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asesores`
--
ALTER TABLE `asesores`
  ADD PRIMARY KEY (`id_asesor`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `idx_clientes_correo` (`correo`),
  ADD KEY `idx_clientes_nombre_completo` (`nombre_completo`);

--
-- Indices de la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD PRIMARY KEY (`id_cotizacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_asesor` (`id_asesor`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id_detalle`,`id_pedido`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `envios`
--
ALTER TABLE `envios`
  ADD PRIMARY KEY (`id_envio`),
  ADD UNIQUE KEY `id_pedido` (`id_pedido`),
  ADD KEY `idx_envios_estado` (`estado`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id_metodo_pago`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD UNIQUE KEY `id_pedido` (`id_pedido`),
  ADD KEY `fk_pagos_metodos_pago` (`id_metodo_pago`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_pedidos_estado` (`estado`),
  ADD KEY `idx_pedidos_anio` (`anio_pedido`),
  ADD KEY `idx_pedidos_mes` (`mes_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `idx_productos_nombre` (`nombre`),
  ADD KEY `idx_productos_nombre_lower` (`nombre_lower`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `idx_usuarios_correo_lower` (`correo_lower`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id_metodo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_asesor`) REFERENCES `asesores` (`id_asesor`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cotizacion_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `envios`
--
ALTER TABLE `envios`
  ADD CONSTRAINT `envios_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_metodos_pago` FOREIGN KEY (`id_metodo_pago`) REFERENCES `metodos_pago` (`id_metodo_pago`),
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
