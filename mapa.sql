-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-06-2025 a las 23:39:34
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
-- Base de datos: `mapa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comments`
--

CREATE TABLE `comments` (
  `id_coments` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_place` int(11) NOT NULL,
  `text` text NOT NULL,
  `rating` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `comments`
--

INSERT INTO `comments` (`id_coments`, `id_user`, `id_place`, `text`, `rating`, `date`) VALUES
(1, 9, 4, 'Lugar histórico pero muy peligroso por radiación', 1, '2025-06-08'),
(2, 9, 4, 'Increíble pero requiere equipo de protección', 1, '2025-06-08'),
(3, 9, 3, 'hola', 1, '2025-06-08'),
(4, 9, 6, 'hola', 1, '2025-06-10'),
(5, 9, 4, 'hola', 1, '2025-06-10'),
(6, 9, 4, 'holaaa', 1, '2025-06-10'),
(7, 9, 4, 'test', 3, '2025-06-10'),
(8, 9, 3, '3', 0, '2025-06-10'),
(9, 9, 3, '5', 0, '2025-06-10'),
(10, 9, 3, '3', 0, '2025-06-10'),
(11, 9, 3, 'kejk', 5, '2025-06-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorites`
--

CREATE TABLE `favorites` (
  `id_favorite` int(11) NOT NULL,
  `id_place_f` int(11) NOT NULL,
  `id_user_f` int(11) NOT NULL,
  `favorites` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `favorites`
--

INSERT INTO `favorites` (`id_favorite`, `id_place_f`, `id_user_f`, `favorites`) VALUES
(1, 4, 9, 1),
(2, 3, 9, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `places`
--

CREATE TABLE `places` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT 'otro',
  `lat` decimal(10,7) NOT NULL,
  `lng` decimal(10,7) NOT NULL,
  `description` text DEFAULT NULL,
  `danger` varchar(50) DEFAULT 'baja',
  `year` int(11) DEFAULT NULL,
  `path_img` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `places`
--

INSERT INTO `places` (`id`, `name`, `type`, `lat`, `lng`, `description`, `danger`, `year`, `path_img`, `created_at`) VALUES
(3, 'maria', 'fabrica', 52.2498540, -122.3437520, 'wwww', 'media', 2020, '', '2025-06-08 06:31:32'),
(4, 'mariawwww', 'iglesia', 56.7372690, -101.6015660, 'ww', 'alta', 2020, 'public/assets/images/uploads/ruta_68452f688246c.jpg', '2025-06-08 06:36:24'),
(5, 'prueba2', 'fabrica', 33.1075900, -97.7698670, 'Prueba', 'baja', 1980, 'public/assets/images/uploads/ruta_68486ffd75e94.jpg', '2025-06-10 17:48:45'),
(6, 'prueba4', 'bunker', 51.5993000, -78.7899700, 'test', 'baja', 1980, 'public/assets/images/uploads/ruta_68487060706a0.jpg', '2025-06-10 17:50:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas`
--

CREATE TABLE `rutas` (
  `id_ruta` int(11) NOT NULL,
  `nombre` varchar(250) NOT NULL,
  `descripcion` text NOT NULL,
  `dificultad` enum('facil','media','dificil','') NOT NULL,
  `terreno` text NOT NULL,
  `etiquetas` text NOT NULL,
  `ruta_img` varchar(250) NOT NULL,
  `ruta_inicio` text NOT NULL,
  `ruta_fin` text NOT NULL,
  `duracion` time NOT NULL,
  `distancia_km` decimal(10,2) NOT NULL,
  `velocidad` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `rutas`
--

INSERT INTO `rutas` (`id_ruta`, `nombre`, `descripcion`, `dificultad`, `terreno`, `etiquetas`, `ruta_img`, `ruta_inicio`, `ruta_fin`, `duracion`, `distancia_km`, `velocidad`, `fecha_registro`) VALUES
(4, 'test', '123', 'media', 'rural', '122', 'public/assets/images/uploads/ruta_6844a07c4415d.jpg', '43.810919058027,-102.29626194411', '26.082242875994,-101.94466484995', '131:26:22', 1971.59, 5, '2025-06-07 15:26:36'),
(5, 'test', 'ddd', '', 'subterraneo', '122', 'public/assets/images/uploads/ruta_68464ccee3289.jpg', '45.066206084309,-112.85936626737', '41.746436174445,-106.88365417792', '121:29:02', 607.42, 5, '2025-06-08 21:54:06'),
(6, 'test6262', 'skss', 'media', 'rural', 'www12', 'public/assets/images/uploads/ruta_6848824fababd.jpg', '57.500853016133,35.839683701036', '47.968909124098,9.8264013957677', '33:48:16', 2028.27, 0, '2025-06-10 14:06:55'),
(7, 'test6262', 'hgg', 'dificil', 'urbano', 'www12', 'public/assets/images/uploads/ruta_684882a3dc2dd.jpg', '52.67832990774,60.446842638451', '47.257769380708,16.153956551103', '03:57:55', 3172.29, 800, '2025-06-10 14:08:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL,
  `name_users` varchar(250) NOT NULL,
  `email_user` varchar(100) NOT NULL,
  `password_user` text NOT NULL,
  `fecha_users` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id_users`, `name_users`, `email_user`, `password_user`, `fecha_users`) VALUES
(9, 'admin', 'admin@admin.com', '$2y$10$MLDgWOCSGu0gqOo47JYf.OdHrlbjOatneL80KYDN41OIUU46lqApG', '2025-06-10 16:33:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_coments`),
  ADD KEY `id_place_fk` (`id_place`),
  ADD KEY `id_user_fk` (`id_user`);

--
-- Indices de la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id_favorite`),
  ADD KEY `id_place_fk_f` (`id_place_f`),
  ADD KEY `id_user_fk_f` (`id_user_f`);

--
-- Indices de la tabla `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rutas`
--
ALTER TABLE `rutas`
  ADD PRIMARY KEY (`id_ruta`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_users`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comments`
--
ALTER TABLE `comments`
  MODIFY `id_coments` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id_favorite` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `places`
--
ALTER TABLE `places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rutas`
--
ALTER TABLE `rutas`
  MODIFY `id_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id_users` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `id_place_fk` FOREIGN KEY (`id_place`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_user_fk` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `id_place_fk_f` FOREIGN KEY (`id_place_f`) REFERENCES `places` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_user_fk_f` FOREIGN KEY (`id_user_f`) REFERENCES `users` (`id_users`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
