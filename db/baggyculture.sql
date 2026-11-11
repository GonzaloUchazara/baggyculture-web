-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 11-11-2026 a las 13:14:19
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
-- Base de datos: `baggyculture`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditoria_subastas`
--

CREATE TABLE `auditoria_subastas` (
  `id_auditoria` int(11) NOT NULL,
  `id_subasta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `precio_anterior` decimal(10,2) NOT NULL,
  `precio_nuevo` decimal(10,2) NOT NULL,
  `accion` varchar(50) DEFAULT 'NUEVA_PUJA',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria_subastas`
--

INSERT INTO `auditoria_subastas` (`id_auditoria`, `id_subasta`, `id_usuario`, `precio_anterior`, `precio_nuevo`, `accion`, `fecha_registro`) VALUES
(1, 1, 3, 10.00, 15.00, 'INCREMENTO_PRECIO', '2026-11-09 15:33:45'),
(2, 1, 3, 15.00, 65.00, 'INCREMENTO_PRECIO', '2026-11-09 15:35:27'),
(3, 1, 3, 65.00, 70.00, 'INCREMENTO_PRECIO', '2026-11-09 15:51:35'),
(4, 1, 3, 70.00, 89.00, 'INCREMENTO_PRECIO', '2026-11-09 15:52:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(240) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` int(250) NOT NULL,
  `stock` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `calificacion` decimal(3,2) NOT NULL,
  `estado` enum('activo','desactivado','agotado') NOT NULL DEFAULT 'activo',
  `foto_pro` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `categoria`, `calificacion`, `estado`, `foto_pro`) VALUES
(1, 'prueba1', '', 100, 10, 'prueba', 5.00, 'activo', 'prueba1.jpg'),
(2, 'prueba2', '', 50, 5, 'baggy', 3.00, 'activo', 'prueba2.jpg'),
(3, 'prueba3', '', 30, 2, 'baggy', 5.00, 'activo', 'prueba3.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pujas`
--

CREATE TABLE `pujas` (
  `id_puja` int(11) NOT NULL,
  `id_subasta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_puja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pujas`
--

INSERT INTO `pujas` (`id_puja`, `id_subasta`, `id_usuario`, `monto`, `fecha_puja`) VALUES
(1, 1, 3, 15.00, '2026-11-09 15:33:45'),
(2, 1, 3, 65.00, '2026-11-09 15:35:27'),
(3, 1, 3, 70.00, '2026-11-09 15:51:35'),
(4, 1, 3, 89.00, '2026-11-09 15:52:39');

--
-- Disparadores `pujas`
--
DELIMITER $$
CREATE TRIGGER `actualizar_subasta_tras_puja` AFTER INSERT ON `pujas` FOR EACH ROW BEGIN
UPDATE subastas 
    SET precio_actual = NEW.monto,
        id_ganador = NEW.id_usuario
    WHERE id_subasta = NEW.id_subasta;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subastas`
--

CREATE TABLE `subastas` (
  `id_subasta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `precio_base` decimal(10,2) NOT NULL,
  `precio_actual` decimal(10,2) NOT NULL,
  `incremento_minimo` decimal(10,2) DEFAULT 5.00,
  `fecha_inicio` datetime NOT NULL,
  `fecha_fin` datetime NOT NULL,
  `id_ganador` int(11) DEFAULT NULL,
  `estado` enum('activa','finalizada','cancelada') DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subastas`
--

INSERT INTO `subastas` (`id_subasta`, `id_producto`, `precio_base`, `precio_actual`, `incremento_minimo`, `fecha_inicio`, `fecha_fin`, `id_ganador`, `estado`) VALUES
(1, 1, 10.00, 89.00, 5.00, '2026-11-09 09:50:11', '2026-11-09 12:50:11', 3, 'activa');

--
-- Disparadores `subastas`
--
DELIMITER $$
CREATE TRIGGER `auditoria_subasta_precio` BEFORE UPDATE ON `subastas` FOR EACH ROW BEGIN
    IF OLD.precio_actual <> NEW.precio_actual THEN
        INSERT INTO auditoria_subastas (
            id_subasta, 
            id_usuario, 
            precio_anterior, 
            precio_nuevo, 
            accion
        ) VALUES (
            OLD.id_subasta, 
            NEW.id_ganador, 
            OLD.precio_actual, 
            NEW.precio_actual, 
            'INCREMENTO_PRECIO'
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `rol` enum('cliente','admin','asesor','subastador') DEFAULT 'cliente',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password_hash`, `telefono`, `rol`, `creado_en`) VALUES
(3, 'andres', 'flores', 'andrelex602@gmail.com', '$2y$10$7SHK3sV.SizGCKsdVasCk.Wa4g7JHaeHeUU/0BUb9jmRt9YVnwMri', NULL, 'cliente', '2026-11-07 03:43:37'),
(4, 'admin', 'istrador', 'andre@baggy.com', '2380784', '45456432', 'admin', '2026-11-07 21:59:54'),
(5, 'andreelex', 'flores', 'prueba3@gmail.com', '$2y$10$0.kfB5o8xpwTYV.vQE4OQ.0FHumGZcTiH33ZrnMWz1tfvX3rHYR5S', NULL, 'admin', '2026-11-07 23:16:09'),
(6, 'baagy', 'elbaggy', 'baggycliente@gmai.com', '$2y$10$sLqOfTd2hOqt..P0oXGmA.HPB4lJCbD2wdMnE4BHU5u0uwntojCr.', '78842399', 'cliente', '2026-11-09 15:50:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `variantes_producto`
--

CREATE TABLE `variantes_producto` (
  `id_variante` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `talla` varchar(10) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `foto1_pro` varchar(250) NOT NULL,
  `foto2_pro` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `variantes_producto`
--

INSERT INTO `variantes_producto` (`id_variante`, `id_producto`, `talla`, `color`, `foto1_pro`, `foto2_pro`) VALUES
(10, 1, 'S', 'Beige', 'variante11.jpg', 'variante12.jpg'),
(11, 1, 'M', 'Beige', 'variante11.jpg', 'variante12.jpg'),
(12, 1, 'S', 'Blanco', 'variante21.jpg', 'variante22.jpg'),
(13, 1, 'M', 'Blanco', 'variante21.jpg', 'variante22.jpg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditoria_subastas`
--
ALTER TABLE `auditoria_subastas`
  ADD PRIMARY KEY (`id_auditoria`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `pujas`
--
ALTER TABLE `pujas`
  ADD PRIMARY KEY (`id_puja`),
  ADD KEY `id_subasta` (`id_subasta`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD PRIMARY KEY (`id_subasta`),
  ADD KEY `id_producto` (`id_producto`),
  ADD KEY `id_ganador` (`id_ganador`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `variantes_producto`
--
ALTER TABLE `variantes_producto`
  ADD PRIMARY KEY (`id_variante`),
  ADD KEY `id_producto` (`id_producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditoria_subastas`
--
ALTER TABLE `auditoria_subastas`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(240) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pujas`
--
ALTER TABLE `pujas`
  MODIFY `id_puja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `subastas`
--
ALTER TABLE `subastas`
  MODIFY `id_subasta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `variantes_producto`
--
ALTER TABLE `variantes_producto`
  MODIFY `id_variante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pujas`
--
ALTER TABLE `pujas`
  ADD CONSTRAINT `pujas_ibfk_1` FOREIGN KEY (`id_subasta`) REFERENCES `subastas` (`id_subasta`) ON DELETE CASCADE,
  ADD CONSTRAINT `pujas_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD CONSTRAINT `subastas_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE,
  ADD CONSTRAINT `subastas_ibfk_2` FOREIGN KEY (`id_ganador`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL;

--
-- Filtros para la tabla `variantes_producto`
--
ALTER TABLE `variantes_producto`
  ADD CONSTRAINT `variantes_producto_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
