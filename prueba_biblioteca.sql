-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2025 a las 14:09:31
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
-- Base de datos: `prueba_biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autores`
--

CREATE TABLE `autores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `fecha_fallecimiento` date DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `autores`
--

INSERT INTO `autores` (`id`, `nombre`, `nacionalidad`, `fecha_nacimiento`, `fecha_fallecimiento`, `biografia`, `created_at`, `updated_at`) VALUES
(1, 'Gabriel García Márquez', 'Colombiana', '1927-03-06', '2014-04-17', 'Autor colombiano, Nobel de Literatura. Conocido por \"Cien años de soledad\".', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 'Isabel Allende', 'Chilena', '1942-08-02', NULL, 'Escritora chilena conocida por novelas de realismo mágico y familiares.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 'J.K. Rowling', 'Británica', '1965-07-31', NULL, 'Autora de la serie de Harry Potter.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 'Octavio Paz', 'Mexicana', '1914-03-31', '1998-04-19', 'Poeta y ensayista mexicano, premio Nobel de Literatura.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 'Mary Shelley', 'Británica', '1797-08-30', '1851-02-01', 'Autora de \"Frankenstein\" y pionera de la ciencia ficción.', '2025-10-17 04:02:24', '2025-10-17 04:02:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor_libro`
--

CREATE TABLE `autor_libro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `libro_id` bigint(20) UNSIGNED NOT NULL,
  `autor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `autor_libro`
--

INSERT INTO `autor_libro` (`id`, `libro_id`, `autor_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 2, 2, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 3, 3, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 4, 5, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 5, 4, '2025-10-17 04:02:24', '2025-10-17 04:02:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
(1, 'Novela', 'Obras de narrativa extensa centradas en la historia de personajes.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 'Ciencia ficción', 'Género que explora escenarios científicos y tecnológicos.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 'Fantasía', 'Género con elementos mágicos o sobrenaturales.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 'Historia', 'Obras basadas en hechos y procesos históricos.', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 'Poesía', 'Composiciones literarias en verso.', '2025-10-17 04:02:24', '2025-10-17 04:02:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero_libro`
--

CREATE TABLE `genero_libro` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `libro_id` bigint(20) UNSIGNED NOT NULL,
  `genero_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `genero_libro`
--

INSERT INTO `genero_libro` (`id`, `libro_id`, `genero_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 2, 1, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 3, 3, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 4, 2, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 5, 4, '2025-10-17 04:02:24', '2025-10-17 04:02:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `resumen` text DEFAULT NULL,
  `anio_publicacion` smallint(5) UNSIGNED DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `stock` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `resumen`, `anio_publicacion`, `isbn`, `stock`, `created_at`, `updated_at`) VALUES
(1, 'Cien años de soledad', 'Novela sobre la familia Buendía y el pueblo de Macondo.', 1967, '9780307474728', 5, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 'La casa de los espíritus', 'Saga familiar con elementos sobrenaturales.', 1982, '9780553381683', 3, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 'Harry Potter y la piedra filosofal', 'Primer libro de la saga de Hogwarts.', 1997, '9780747532699', 10, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 'Frankenstein', 'Novela gótica sobre la creación de la criatura.', 1818, '9780141439471', 4, '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 'El laberinto de la soledad', 'Ensayo sobre la identidad mexicana y social.', 1950, '9780307387049', 10, '2025-10-17 04:02:24', '2025-10-17 07:22:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_10_16_100000_create_usuarios_table', 1),
(2, '2025_10_16_100100_create_autores_table', 1),
(3, '2025_10_16_100200_create_generos_table', 1),
(4, '2025_10_16_100300_create_libros_table', 1),
(5, '2025_10_16_100400_create_autor_libro_table', 1),
(6, '2025_10_16_100500_create_genero_libro_table', 1),
(7, '2025_10_16_100600_create_prestamos_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `libro_id` bigint(20) UNSIGNED NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('activo','devuelto','vencido') NOT NULL DEFAULT 'activo',
  `observaciones` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id`, `usuario_id`, `libro_id`, `fecha_prestamo`, `fecha_vencimiento`, `fecha_devolucion`, `estado`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2025-09-20', '2025-10-04', NULL, 'activo', 'Préstamo reciente.', '2025-09-20 15:00:00', '2025-09-20 15:00:00'),
(2, 2, 1, '2025-08-01', '2025-08-15', '2025-08-10', 'devuelto', 'Devuelto antes de la fecha.', '2025-08-01 14:30:00', '2025-08-10 19:00:00'),
(3, 3, 4, '2025-07-10', '2025-07-24', NULL, 'vencido', 'Usuario no ha devuelto el libro.', '2025-07-10 17:00:00', '2025-07-24 05:00:00'),
(4, 4, 2, '2025-10-01', '2025-10-15', NULL, 'activo', 'Reserva para lectura en sala.', '2025-10-01 13:00:00', '2025-10-01 13:00:00'),
(5, 5, 5, '2025-06-05', '2025-06-19', '2025-06-18', 'devuelto', 'Préstamo de ensayo.', '2025-06-05 16:00:00', '2025-06-18 21:00:00'),
(6, 6, 3, '2025-10-17', '2025-10-24', NULL, 'activo', 'prueba', '2025-10-17 08:02:10', '2025-10-17 08:02:10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tipo_identificacion` varchar(20) NOT NULL,
  `numero_identificacion` varchar(30) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `tipo_identificacion`, `numero_identificacion`, `telefono`, `direccion`, `fecha_nacimiento`, `created_at`, `updated_at`) VALUES
(1, 'Ana Torres', 'ana.torres@example.com', 'CC', '1002003001', '3215550101', 'Calle 12 #34-56, Bogotá', '1995-04-10', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(2, 'Luis Fernández', 'luis.fernandez@example.com', 'CC', '1002003002', '3105550102', 'Carrera 5 #67-89, Medellín', '1988-11-22', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(3, 'María Gómez', 'maria.gomez@example.com', 'CC', '1002003003', '3005550103', 'Av. Siempre Viva 742, Cali', '2000-01-15', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(4, 'Carlos Pérez', 'carlos.perez@example.com', 'CE', 'X1234567', '3155550104', 'Calle 9 #10-11, Barranquilla', '1975-06-30', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(5, 'Sofía Martínez', 'sofia.martinez@example.com', 'CC', '1002003005', '3205550105', 'Transversal 8 #22-33, Bogotá', '1992-09-05', '2025-10-17 04:02:24', '2025-10-17 04:02:24'),
(6, 'Juan Esteban Rojas', 'juan.rojas@example.com', 'CC', '1005006007', '3145552233', 'Carrera 45 #15-23,Bogota', '1999-05-09', '2025-10-17 07:59:12', '2025-10-17 07:59:12');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `autores_nombre_unique` (`nombre`);

--
-- Indices de la tabla `autor_libro`
--
ALTER TABLE `autor_libro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `autor_libro_libro_id_autor_id_unique` (`libro_id`,`autor_id`),
  ADD KEY `autor_libro_autor_id_foreign` (`autor_id`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `generos_nombre_unique` (`nombre`);

--
-- Indices de la tabla `genero_libro`
--
ALTER TABLE `genero_libro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genero_libro_libro_id_genero_id_unique` (`libro_id`,`genero_id`),
  ADD KEY `genero_libro_genero_id_foreign` (`genero_id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libros_isbn_unique` (`isbn`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prestamos_libro_id_index` (`libro_id`),
  ADD KEY `prestamos_usuario_id_index` (`usuario_id`),
  ADD KEY `prestamos_estado_index` (`estado`),
  ADD KEY `prestamos_fecha_vencimiento_index` (`fecha_vencimiento`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD UNIQUE KEY `usuarios_numero_identificacion_unique` (`numero_identificacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autores`
--
ALTER TABLE `autores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `autor_libro`
--
ALTER TABLE `autor_libro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `genero_libro`
--
ALTER TABLE `genero_libro`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `autor_libro`
--
ALTER TABLE `autor_libro`
  ADD CONSTRAINT `autor_libro_autor_id_foreign` FOREIGN KEY (`autor_id`) REFERENCES `autores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `autor_libro_libro_id_foreign` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `genero_libro`
--
ALTER TABLE `genero_libro`
  ADD CONSTRAINT `genero_libro_genero_id_foreign` FOREIGN KEY (`genero_id`) REFERENCES `generos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `genero_libro_libro_id_foreign` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_libro_id_foreign` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `prestamos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
