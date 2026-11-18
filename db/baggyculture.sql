-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3307
-- Tiempo de generación: 18-11-2026 a las 13:15:06
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
-- Estructura de tabla para la tabla `auditoria`
--

CREATE TABLE `auditoria` (
  `id_auditoria` int(11) NOT NULL,
  `nombre_tabla` varchar(50) NOT NULL,
  `operacion` varchar(50) NOT NULL,
  `fecha_operacion` date NOT NULL,
  `usuario_bd` varchar(50) NOT NULL,
  `clave_registro` varchar(50) NOT NULL,
  `momento` varchar(50) NOT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `auditoria`
--

INSERT INTO `auditoria` (`id_auditoria`, `nombre_tabla`, `operacion`, `fecha_operacion`, `usuario_bd`, `clave_registro`, `momento`, `datos_anteriores`, `datos_nuevos`) VALUES
(1, 'productos', 'INSERT', '2026-11-16', 'root@localhost', '8', 'AFTER', NULL, '{\"id_producto\": 8, \"nombre\": \"Hoodie Street Classic\", \"descripcion\": \"Polerón oversize de algodón friso premium\", \"precio\": 180, \"stock\": 15, \"categoria\": \"hoodies\", \"calificacion\": 5.00, \"estado\": \"activo\", \"foto_pro\": \"hoodie_classic.jpg\"}'),
(2, 'productos', 'DELETE', '2026-11-16', 'root@localhost', '3', 'BEFORE', '{\"id_producto\": 3, \"nombre\": \"prueba3\", \"descripcion\": \"\", \"precio\": 30, \"stock\": 2, \"categoria\": \"baggy\", \"calificacion\": 5.00, \"estado\": \"activo\", \"foto_pro\": \"prueba3.jpg\"}', NULL),
(3, 'productos', 'UPDATE', '2026-11-16', 'root@localhost', '2', 'AFTER', '{\"id_producto\": 2, \"nombre\": \"prueba2\", \"descripcion\": \"\", \"precio\": 50, \"stock\": 5, \"categoria\": \"baggy\", \"calificacion\": 3.00, \"estado\": \"activo\", \"foto_pro\": \"prueba2.jpg\"}', '{\"id_producto\": 2, \"nombre\": \"prueba2\", \"descripcion\": \"ropa de prueba de muestra pe\", \"precio\": 50, \"stock\": 10, \"categoria\": \"baggy la ropa \", \"calificacion\": 4.00, \"estado\": \"activo\", \"foto_pro\": \"prueba2.jpg\"}'),
(4, 'usuarios', 'INSERT', '2026-11-16', 'root@localhost', '7', 'AFTER', NULL, '{\"id_usuario\": 7, \"nombre\": \"prueba\", \"apellido\": \"insert\", \"email\": \"andrelex6024@gmail.com\", \"password_hash\": \"$2y$10$e9reZUpGwG.xAArRIwm3tOprtC7RUK8gxDqY8Ed2QjFVXXtQ97s5.\", \"telefono\": null, \"rol\": \"cliente\", \"foto_perfil\": \"default_user.jpg\", \"creado_en\": \"2026-11-16 09:38:16\"}'),
(5, 'usuarios', 'DELETE', '2026-11-16', 'root@localhost', '5', 'AFTER', '{\"id_usuario\": 5, \"nombre\": \"andreelex\", \"apellido\": \"flores\", \"email\": \"prueba3@gmail.com\", \"telefono\": null, \"rol\": \"admin\", \"foto_perfil\": \"default_user.jpg\", \"creado_en\": \"2026-11-07 19:16:09\"}', NULL),
(6, 'productos', 'UPDATE', '2026-11-16', 'root@localhost', '8', 'AFTER', '{\"id_producto\": 8, \"nombre\": \"Hoodie Street Classic\", \"descripcion\": \"Polerón oversize de algodón friso premium\", \"precio\": 180, \"stock\": 15, \"categoria\": \"hoodies\", \"calificacion\": 5.00, \"estado\": \"activo\", \"foto_pro\": \"hoodie_classic.jpg\"}', '{\"id_producto\": 8, \"nombre\": \"Hoodie Street Classic\", \"descripcion\": \"Polerón oversize de algodón friso premium\", \"precio\": 180, \"stock\": 15, \"categoria\": \"hoodies\", \"calificacion\": 5.00, \"estado\": \"activo\", \"foto_pro\": \"prueba3.jpg\"}'),
(7, 'usuarios', 'INSERT', '2026-11-18', 'root@localhost', '8', 'AFTER', NULL, '{\"id_usuario\": 8, \"nombre\": \"subastador\", \"apellido\": \"elsubastador\", \"email\": \"subastador@gmail.com\", \"password_hash\": \"$2y$10$ZjWi4n7Zbax19B9Gpi1v7uARCA7EaEZrxSjF4nHf8ItsgFxPqo8lu\", \"telefono\": null, \"rol\": \"cliente\", \"foto_perfil\": \"default_user.jpg\", \"creado_en\": \"2026-11-18 07:42:12\"}'),
(8, 'usuarios', 'UPDATE', '2026-11-18', 'root@localhost', '8', 'AFTER', '{\"id_usuario\": 8, \"nombre\": \"subastador\", \"apellido\": \"elsubastador\", \"email\": \"subastador@gmail.com\", \"telefono\": null, \"rol\": \"cliente\", \"foto_perfil\": \"default_user.jpg\", \"creado_en\": \"2026-11-18 07:42:12\"}', '{\"id_usuario\": 8, \"nombre\": \"subastador\", \"apellido\": \"elsubastador\", \"email\": \"subastador@gmail.com\", \"telefono\": null, \"rol\": \"subastador\", \"foto_perfil\": \"default_user.jpg\", \"creado_en\": \"2026-11-18 07:42:12\"}');

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
(2, 'prueba2', 'ropa de prueba de muestra pe', 50, 10, 'baggy la ropa ', 4.00, 'activo', 'prueba2.jpg'),
(8, 'Hoodie Street Classic', 'Polerón oversize de algodón friso premium', 180, 15, 'hoodies', 5.00, 'activo', 'prueba3.jpg');

--
-- Disparadores `productos`
--
DELIMITER $$
CREATE TRIGGER `insert_producto` AFTER INSERT ON `productos` FOR EACH ROW BEGIN 
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'productos',
        'INSERT',
        NOW(),
        USER(),
        NEW.id_producto, 
        'AFTER',
        NULL,
        JSON_OBJECT(
            'id_producto', NEW.id_producto,
            'nombre', NEW.nombre,
            'descripcion', NEW.descripcion,
            'precio', NEW.precio,
            'stock', NEW.stock,
            'categoria', NEW.categoria,
            'calificacion', NEW.calificacion,
            'estado', NEW.estado,
            'foto_pro', NEW.foto_pro
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `productos_delete` BEFORE DELETE ON `productos` FOR EACH ROW BEGIN 
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'productos',
        'DELETE',
        NOW(),
        USER(),
        OLD.id_producto,
        'BEFORE',
        JSON_OBJECT(
            'id_producto', OLD.id_producto,
            'nombre', OLD.nombre,
            'descripcion', OLD.descripcion,
            'precio', OLD.precio,
            'stock', OLD.stock,
            'categoria', OLD.categoria,
            'calificacion', OLD.calificacion,
            'estado', OLD.estado,
            'foto_pro', OLD.foto_pro
        ),
        NULL 
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_producto` AFTER UPDATE ON `productos` FOR EACH ROW BEGIN 
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'productos',
        'UPDATE',
        NOW(),
        USER(),
        NEW.id_producto, 
        'AFTER',
          JSON_OBJECT(
            'id_producto', OLD.id_producto,
            'nombre', OLD.nombre,
            'descripcion', OLD.descripcion,
            'precio', OLD.precio,
            'stock', OLD.stock,
            'categoria',OLD.categoria,
            'calificacion',OLD.calificacion,
            'estado', OLD.estado,
            'foto_pro', OLD.foto_pro
        ),
        JSON_OBJECT(
            'id_producto', NEW.id_producto,
            'nombre', NEW.nombre,
            'descripcion', NEW.descripcion,
            'precio', NEW.precio,
            'stock', NEW.stock,
            'categoria', NEW.categoria,
            'calificacion', NEW.calificacion,
            'estado', NEW.estado,
            'foto_pro', NEW.foto_pro
        )
    );
END
$$
DELIMITER ;

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
  `foto_perfil` varchar(250) NOT NULL DEFAULT 'default_user.jpg',
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `apellido`, `email`, `password_hash`, `telefono`, `rol`, `foto_perfil`, `creado_en`) VALUES
(3, 'andres', 'flores', 'andrelex602@gmail.com', '$2y$10$7SHK3sV.SizGCKsdVasCk.Wa4g7JHaeHeUU/0BUb9jmRt9YVnwMri', NULL, 'cliente', 'default_user.jpg', '2026-11-07 03:43:37'),
(4, 'admin', 'istradorpro', 'andre@baggy.com', '$2y$10$PTnNr7sd8QgBef7ovDQqGuJtlBiJ98d5MyBZr7Y3Qf6aenkxUvjDu', '45456432', 'admin', 'default_user.jpg', '2026-11-07 21:59:54'),
(6, 'baagy', 'elbaggy', 'baggycliente@gmai.com', '$2y$10$sLqOfTd2hOqt..P0oXGmA.HPB4lJCbD2wdMnE4BHU5u0uwntojCr.', '78842399', 'cliente', 'user_6_1794447693.jpg', '2026-11-09 15:50:46'),
(7, 'prueba', 'insert', 'andrelex6024@gmail.com', '$2y$10$e9reZUpGwG.xAArRIwm3tOprtC7RUK8gxDqY8Ed2QjFVXXtQ97s5.', NULL, 'cliente', 'default_user.jpg', '2026-11-16 13:38:16'),
(8, 'subastador', 'elsubastador', 'subastador@gmail.com', '$2y$10$ZjWi4n7Zbax19B9Gpi1v7uARCA7EaEZrxSjF4nHf8ItsgFxPqo8lu', NULL, 'subastador', 'default_user.jpg', '2026-11-18 11:42:12');

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `delete_usuario` AFTER DELETE ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'usuarios',
        'DELETE',
        NOW(),
        USER(),
        OLD.id_usuario,
        'AFTER',
        JSON_OBJECT(
            'id_usuario', OLD.id_usuario,
            'nombre', OLD.nombre,
            'apellido', OLD.apellido,
            'email', OLD.email,
            'telefono', OLD.telefono,
            'rol', OLD.rol,
            'foto_perfil', OLD.foto_perfil,
            'creado_en', OLD.creado_en
        ),
        NULL
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insert_usuarios` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN 
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'usuarios',
        'INSERT',
        NOW(),
        USER(),
        NEW.id_usuario, 
        'AFTER',
        NULL,
        JSON_OBJECT(
            'id_usuario', NEW.id_usuario,
            'nombre', NEW.nombre,
            'apellido', NEW.apellido,
            'email', NEW.email,
            'password_hash', NEW.password_hash,
            'telefono', NEW.telefono,
            'rol', NEW.rol,
            'foto_perfil', NEW.foto_perfil,
            'creado_en', NEW.creado_en
        )
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_usuario` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    INSERT INTO auditoria (
        nombre_tabla,
        operacion,
        fecha_operacion,
        usuario_bd,
        clave_registro,
        momento,
        datos_anteriores,
        datos_nuevos
    )
    VALUES (
        'usuarios',
        'UPDATE',
        NOW(),
        USER(),
        NEW.id_usuario,
        'AFTER',
        JSON_OBJECT(
            'id_usuario', OLD.id_usuario,
            'nombre', OLD.nombre,
            'apellido', OLD.apellido,
            'email', OLD.email,
            'telefono', OLD.telefono,
            'rol', OLD.rol,
            'foto_perfil', OLD.foto_perfil,
            'creado_en', OLD.creado_en
        ),
        JSON_OBJECT(
            'id_usuario', NEW.id_usuario,
            'nombre', NEW.nombre,
            'apellido', NEW.apellido,
            'email', NEW.email,
            'telefono', NEW.telefono,
            'rol', NEW.rol,
            'foto_perfil', NEW.foto_perfil,
            'creado_en', NEW.creado_en
        )
    );
END
$$
DELIMITER ;

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
-- Indices de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  ADD PRIMARY KEY (`id_auditoria`);

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
-- AUTO_INCREMENT de la tabla `auditoria`
--
ALTER TABLE `auditoria`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `auditoria_subastas`
--
ALTER TABLE `auditoria_subastas`
  MODIFY `id_auditoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(240) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
