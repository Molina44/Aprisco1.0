-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-06-2025 a las 21:40:46
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
-- Base de datos: `aprisco1.1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabras`
--

CREATE TABLE `cabras` (
  `id_cabra` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `madre` int(11) DEFAULT NULL,
  `padre` int(11) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` enum('MACHO','HEMBRA') NOT NULL,
  `id_raza` int(11) DEFAULT NULL,
  `color` varchar(30) DEFAULT NULL,
  `id_propietario_actual` int(11) DEFAULT NULL,
  `estado` enum('ACTIVA','INACTIVA') DEFAULT 'ACTIVA',
  `fecha_registro` date DEFAULT curdate(),
  `creado_por` int(11) DEFAULT NULL,
  `modificado_por` int(11) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controles_sanitarios`
--

CREATE TABLE `controles_sanitarios` (
  `id_control` int(11) NOT NULL,
  `id_cabra` int(11) NOT NULL,
  `fecha_control` date NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `peso_nacer_kg` decimal(5,2) DEFAULT NULL,
  `condicion_especial` enum('vacia','preñada','lactante','nacimiento') DEFAULT NULL,
  `fama_hoja` tinyint(4) DEFAULT NULL CHECK (`fama_hoja` between 1 and 5),
  `orejas` enum('normal','anormal') DEFAULT NULL,
  `mucosas` enum('normal','anormal') DEFAULT NULL,
  `vitaminacion` varchar(100) DEFAULT NULL,
  `purga` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL,
  `c_corporal` enum('1','2','3','4','5') DEFAULT NULL,
  `genitales` enum('normal','anormal') DEFAULT NULL,
  `ubre` enum('normal','anormal') DEFAULT NULL,
  `foto_ubre` varchar(100) DEFAULT NULL,
  `drack_score` enum('1','2','3','4','5') DEFAULT NULL,
  `famacha` enum('1','2','3','4','5') DEFAULT NULL,
  `sin_muda` enum('si','no') DEFAULT NULL,
  `pinzas` enum('1','2') DEFAULT NULL,
  `primeros_medios` enum('1','2') DEFAULT NULL,
  `segundos_medios` enum('1','2') DEFAULT NULL,
  `extremos` enum('1','2') DEFAULT NULL,
  `desgaste` enum('si','no') DEFAULT NULL,
  `perdidas_dentales` enum('si','no') DEFAULT NULL,
  `cascos` enum('normal','anormal') DEFAULT NULL,
  `e_interdigital` enum('normal','anormal') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documentos_cabras`
--

CREATE TABLE `documentos_cabras` (
  `id_documento` int(11) NOT NULL,
  `id_cabra` int(11) NOT NULL,
  `tipo_documento` varchar(50) DEFAULT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `fecha_subida` datetime DEFAULT current_timestamp(),
  `subido_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos_reproductivos`
--

CREATE TABLE `eventos_reproductivos` (
  `id_evento` int(11) NOT NULL,
  `id_cabra` int(11) NOT NULL,
  `fecha_evento` date NOT NULL,
  `tipo_evento` enum('CELO','MONTA','INSEMINACION','DIAGNOSTICO_GESTACION','GESTANTE','ABORTO','SECADO','VACIA') NOT NULL,
  `id_semental` int(11) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `eventos_reproductivos`
--
DELIMITER $$
CREATE TRIGGER `validar_evento_reproductivo` BEFORE INSERT ON `eventos_reproductivos` FOR EACH ROW BEGIN
    DECLARE sexo_animal ENUM('MACHO', 'HEMBRA');
    SELECT sexo INTO sexo_animal FROM cabras WHERE id_cabra = NEW.id_cabra;
    IF sexo_animal != 'HEMBRA' AND NEW.tipo_evento IN ('GESTANTE', 'ABORTO', 'SECADO') THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Solo las hembras pueden tener este evento reproductivo';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_propiedad`
--

CREATE TABLE `historial_propiedad` (
  `id_historial` int(11) NOT NULL,
  `id_cabra` int(11) NOT NULL,
  `id_propietario` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `motivo_cambio` varchar(100) DEFAULT NULL,
  `precio_transaccion` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partos`
--

CREATE TABLE `partos` (
  `id_parto` int(11) NOT NULL,
  `id_madre` int(11) NOT NULL,
  `id_padre` int(11) DEFAULT NULL,
  `fecha_parto` date NOT NULL,
  `numero_crias` tinyint(4) DEFAULT 1,
  `peso_total_crias` decimal(5,2) DEFAULT NULL,
  `tipo_parto` enum('SIMPLE','GEMELAR','TRIPLE','MULTIPLE') DEFAULT 'SIMPLE',
  `dificultad` enum('NORMAL','ASISTIDO','CESAREO') DEFAULT 'NORMAL',
  `observaciones` text DEFAULT NULL,
  `registrado_por` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `partos`
--
DELIMITER $$
CREATE TRIGGER `validar_parto_hembra` BEFORE INSERT ON `partos` FOR EACH ROW BEGIN
    DECLARE sexo_madre ENUM('MACHO', 'HEMBRA');
    SELECT sexo INTO sexo_madre FROM cabras WHERE id_cabra = NEW.id_madre;
    IF sexo_madre != 'HEMBRA' THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error: Solo las hembras pueden tener partos';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propietarios`
--

CREATE TABLE `propietarios` (
  `id_propietario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `identificacion` varchar(30) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_registro` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `razas`
--

CREATE TABLE `razas` (
  `id_raza` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `razas`
--

INSERT INTO `razas` (`id_raza`, `nombre`) VALUES
(1, 'Alpina'),
(2, 'Anglonubiana'),
(3, 'Saanen'),
(4, 'Cruces');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_ultimo_acceso` timestamp NULL DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `telefono`, `fecha_registro`, `fecha_ultimo_acceso`, `activo`) VALUES
(1, 'Jorge Enrique Nuñez Molina', 'jorgenunez4m@gmail.com', '$2y$10$3tGP7vyufSfqyGFPyONWBeDYFFmQh7jZQ/KSrNyKinnw9/NvoianO', '3203986078', '2025-06-13 14:32:43', '2025-06-24 18:48:45', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cabras`
--
ALTER TABLE `cabras`
  ADD PRIMARY KEY (`id_cabra`),
  ADD KEY `madre` (`madre`),
  ADD KEY `padre` (`padre`),
  ADD KEY `id_raza` (`id_raza`),
  ADD KEY `id_propietario_actual` (`id_propietario_actual`),
  ADD KEY `creado_por` (`creado_por`),
  ADD KEY `modificado_por` (`modificado_por`);

--
-- Indices de la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  ADD PRIMARY KEY (`id_control`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_cabra_fecha` (`id_cabra`,`fecha_control`);

--
-- Indices de la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_cabra` (`id_cabra`),
  ADD KEY `subido_por` (`subido_por`);

--
-- Indices de la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  ADD PRIMARY KEY (`id_evento`),
  ADD KEY `id_semental` (`id_semental`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_cabra_evento` (`id_cabra`,`fecha_evento`);

--
-- Indices de la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_propietario` (`id_propietario`),
  ADD KEY `idx_cabra_propiedad` (`id_cabra`,`fecha_inicio`);

--
-- Indices de la tabla `partos`
--
ALTER TABLE `partos`
  ADD PRIMARY KEY (`id_parto`),
  ADD KEY `id_padre` (`id_padre`),
  ADD KEY `registrado_por` (`registrado_por`),
  ADD KEY `idx_madre_fecha` (`id_madre`,`fecha_parto`);

--
-- Indices de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  ADD PRIMARY KEY (`id_propietario`),
  ADD UNIQUE KEY `identificacion` (`identificacion`);

--
-- Indices de la tabla `razas`
--
ALTER TABLE `razas`
  ADD PRIMARY KEY (`id_raza`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_activo` (`activo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cabras`
--
ALTER TABLE `cabras`
  MODIFY `id_cabra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  MODIFY `id_control` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  MODIFY `id_evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `partos`
--
ALTER TABLE `partos`
  MODIFY `id_parto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `propietarios`
--
ALTER TABLE `propietarios`
  MODIFY `id_propietario` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `razas`
--
ALTER TABLE `razas`
  MODIFY `id_raza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cabras`
--
ALTER TABLE `cabras`
  ADD CONSTRAINT `cabras_ibfk_1` FOREIGN KEY (`madre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `cabras_ibfk_2` FOREIGN KEY (`padre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `cabras_ibfk_3` FOREIGN KEY (`id_raza`) REFERENCES `razas` (`id_raza`),
  ADD CONSTRAINT `cabras_ibfk_4` FOREIGN KEY (`id_propietario_actual`) REFERENCES `propietarios` (`id_propietario`),
  ADD CONSTRAINT `cabras_ibfk_5` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `cabras_ibfk_6` FOREIGN KEY (`modificado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `controles_sanitarios`
--
ALTER TABLE `controles_sanitarios`
  ADD CONSTRAINT `controles_sanitarios_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `controles_sanitarios_ibfk_2` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `documentos_cabras`
--
ALTER TABLE `documentos_cabras`
  ADD CONSTRAINT `documentos_cabras_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `documentos_cabras_ibfk_2` FOREIGN KEY (`subido_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `eventos_reproductivos`
--
ALTER TABLE `eventos_reproductivos`
  ADD CONSTRAINT `eventos_reproductivos_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `eventos_reproductivos_ibfk_2` FOREIGN KEY (`id_semental`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `eventos_reproductivos_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `historial_propiedad`
--
ALTER TABLE `historial_propiedad`
  ADD CONSTRAINT `historial_propiedad_ibfk_1` FOREIGN KEY (`id_cabra`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `historial_propiedad_ibfk_2` FOREIGN KEY (`id_propietario`) REFERENCES `propietarios` (`id_propietario`);

--
-- Filtros para la tabla `partos`
--
ALTER TABLE `partos`
  ADD CONSTRAINT `partos_ibfk_1` FOREIGN KEY (`id_madre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `partos_ibfk_2` FOREIGN KEY (`id_padre`) REFERENCES `cabras` (`id_cabra`),
  ADD CONSTRAINT `partos_ibfk_3` FOREIGN KEY (`registrado_por`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
