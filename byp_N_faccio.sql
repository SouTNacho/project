-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-09-2026 a las 21:47:01
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
-- Base de datos: `byp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompaniante`
--

CREATE TABLE `acompaniante` (
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrativo`
--

CREATE TABLE `administrativo` (
  `id_administrativo` varchar(10) NOT NULL,
  `permisos` varchar(50) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrativo`
--

INSERT INTO `administrativo` (`id_administrativo`, `permisos`, `id_funcionario`) VALUES
('FA00000001', 'high', 2),
('FA00000002', 'mid', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administra_documento`
--

CREATE TABLE `administra_documento` (
  `id_administra_documento` int(11) NOT NULL,
  `accion` varchar(50) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_administrativo` varchar(10) NOT NULL,
  `id_documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambulancia`
--

CREATE TABLE `ambulancia` (
  `id_ambulancia` int(11) NOT NULL,
  `matricula` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` year(4) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `ultimo_mantenimiento` date DEFAULT NULL COMMENT 'ingresar la ultima vez que se le hizo mantenimiento, es nulo por si nunca se le hizo',
  `Fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `id_conductor` varchar(10) NOT NULL,
  `vencimiento_carnet` date NOT NULL,
  `categoria_carnet` varchar(20) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductor`
--

INSERT INTO `conductor` (`id_conductor`, `vencimiento_carnet`, `categoria_carnet`, `id_funcionario`) VALUES
('DR00000003', '2030-10-10', 'F', 4),
('DR00000004', '2032-10-10', 'C', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `copiloto`
--

CREATE TABLE `copiloto` (
  `id_copiloto` varchar(10) NOT NULL,
  `especialidad` varchar(50) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `copiloto`
--

INSERT INTO `copiloto` (`id_copiloto`, `especialidad`, `id_funcionario`) VALUES
('CO00000005', 'Trauma', 5),
('CO00000006', 'Primeros Auxilios', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `id_departamento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `archivo` varchar(100) NOT NULL,
  `version` varchar(10) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_estado_documento` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento`
--

CREATE TABLE `elemento` (
  `id_elemento` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `subtipo` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento_traslado`
--

CREATE TABLE `elemento_traslado` (
  `id_elemento_traslado` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_traslado` int(11) NOT NULL,
  `id_elemento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id_encuesta` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `enlace` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_documento`
--

CREATE TABLE `estado_documento` (
  `id_estado_documento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_documento`
--

INSERT INTO `estado_documento` (`id_estado_documento`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_funcionario`
--

CREATE TABLE `estado_funcionario` (
  `id_estado_funcionario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_funcionario`
--

INSERT INTO `estado_funcionario` (`id_estado_funcionario`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Licencia médica'),
(3, 'Licencia anual'),
(4, 'Seguro de paro'),
(5, 'Suspendido'),
(6, 'Inactivo'),
(7, 'Jubilado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_solicitud`
--

CREATE TABLE `estado_solicitud` (
  `id_estado_solicitud` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_solicitud`
--

INSERT INTO `estado_solicitud` (`id_estado_solicitud`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'Aceptada'),
(3, 'Rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_traslado`
--

CREATE TABLE `estado_traslado` (
  `id_estado_traslado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_traslado`
--

INSERT INTO `estado_traslado` (`id_estado_traslado`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'En curso'),
(3, 'En pausa'),
(4, 'Finalizado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionario`
--

CREATE TABLE `funcionario` (
  `id_funcionario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nacionalidad` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `localidad` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `numero_puerta` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `cargo` varchar(50) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_estado_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcionario`
--

INSERT INTO `funcionario` (`id_funcionario`, `nombre`, `apellido`, `cedula`, `nacionalidad`, `fecha_nacimiento`, `departamento`, `localidad`, `direccion`, `numero_puerta`, `email`, `cargo`, `fecha_ingreso`, `pass`, `id_estado_funcionario`) VALUES
(1, 'Administrador', 'Super User', '00000000', 'Uruguayo/a', '1010-10-10', 'Montevideo', 'Montevideo', 'Av. Italia s/n - Montevideo', '11600', 'atencionalusuario@hc.edu.uy', 'SU', '1010-10-10', '$2y$10$0KvyaZEqJ.1h0BMhpTbWde62FEnA1XCidlAdJJXzMbW4FRlGYfX6W', 1),
(2, 'Ejemplo', 'Uno', '00000001', 'Argentino/a', '1990-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 001', '001', 'exampleuno@email.com', 'FA', '2010-10-10', '$2y$10$shcLVAjGUlXXcqyN3CIrue5MhvpyCsCI2EOXVHZkhlwdluQlqTiXq', 1),
(3, 'Ejemplo', 'Dos', '00000002', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 002', '001 Bis', 'exampledos@email.com', 'FA', '2020-10-10', '$2y$10$2J279HMUZUY4Nc81t5HsoezRmHeGgnrUgLnepCCO/6sgCV/AMoZi6', 1),
(4, 'Ejemplo', 'Tres', '00000003', 'Uruguayo/a', '1990-10-10', 'Canelones', 'Salinas', 'Calle Falsa 003', '002', 'exampletres@email.com', 'DR', '2010-10-10', '$2y$10$RpMKIvf80mfbEXB7FpSF6uQlpe82ExcS.pSem9jVx23eR02XPdK9O', 1),
(5, 'Ejemplo', 'Cinco', '00000005', 'Uruguayo/a', '1990-10-10', 'Durazno', 'Durazno', 'Calle Falsa 005', '003 Bis', 'examplecinco@email.com', 'CO', '2010-10-10', '$2y$10$tOdSIUbtDD/ca77wluKfbuPCH/yvxyYqeTq3jM2.VpwLUnCinfGcC', 1),
(6, 'Ejemplo', 'Cuatro', '00000004', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Cerro Porteño', 'Calle Falsa 004', '666 Bis', 'examplecuatro@email.com', 'DR', '2020-10-10', '$2y$10$cZzbZqQOHeYcd/r2S4mTVeYd41tVPY5z/CvHkUqEZMqimb6Palc5G', 1),
(7, 'Ejemplo', 'Seis', '00000006', 'Peruano/a', '2000-10-10', 'Montevideo', 'Casavalle', 'Calle Falsa 006', '007 Bis', 'exampleseis@email.com', 'CO', '2020-10-10', '$2y$10$o/MH1Br4Cv4N44muWl9KBumc0bR5QSAZv7QgaAgl8kQkmJnxP5mYC', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_traslado`
--

CREATE TABLE `historial_traslado` (
  `id_historial_traslado` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` varchar(255) NOT NULL,
  `id_traslado` int(11) NOT NULL,
  `id_estado_traslado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `id_localidad` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_departamento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `muestra`
--

CREATE TABLE `muestra` (
  `id_muestra` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `subtipo` varchar(50) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `cedula_persona` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `cedula_persona` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente_acompaniante`
--

CREATE TABLE `paciente_acompaniante` (
  `id_persona_acompaniante` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_paciente` int(11) NOT NULL,
  `cedula_acompaniante` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr`
--

CREATE TABLE `qr` (
  `id_qr` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_vencimiento` datetime NOT NULL,
  `id_documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responde_encuesta`
--

CREATE TABLE `responde_encuesta` (
  `id_encuesta_respuesta` int(11) NOT NULL,
  `id_encuesta` int(11) NOT NULL,
  `valor_satisfaccion` decimal(3,2) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `cedula_persona` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `id_ruta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `origen` varchar(50) NOT NULL,
  `destino` varchar(50) NOT NULL,
  `Fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ruta`
--

INSERT INTO `ruta` (`id_ruta`, `nombre`, `origen`, `destino`, `Fecha_registro`, `descripcion`) VALUES
(6, 'ignacios', 'edr', 'sws', '2026-08-29 18:58:22', 'PRUEBA1descr'),
(8, 'Paysandu Norte', 'Hospital de clínicas', 'mi casa', '2026-09-02 19:00:30', 'PRUEBA1descrIPCIONN'),
(9, 'ignacio', 'eerrr', 'a pposas', '2026-09-02 19:21:32', 'PRUEBA2descrIPCIONN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_traslado`
--

CREATE TABLE `solicitud_traslado` (
  `id_solicitud` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_administrativo` varchar(10) NOT NULL,
  `id_estado_solicitud` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `super_usuario`
--

CREATE TABLE `super_usuario` (
  `id_super_usuario` varchar(10) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefono_funcionario`
--

CREATE TABLE `telefono_funcionario` (
  `id_telefono` int(11) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefono_funcionario`
--

INSERT INTO `telefono_funcionario` (`id_telefono`, `telefono`, `id_funcionario`) VALUES
(1, '+59899090901', 2),
(2, '+59899090902', 3),
(3, '+59899090903', 4),
(4, '+59899090905', 5),
(5, '+59899090666', 6),
(6, '+59899090906', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado`
--

CREATE TABLE `traslado` (
  `id_traslado` int(11) NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime NOT NULL,
  `id_solicitud` int(11) NOT NULL,
  `id_copiloto` varchar(10) NOT NULL,
  `id_conductor` varchar(10) NOT NULL,
  `id_ambulancia` int(11) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_ubicacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `id_localidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion_ruta`
--

CREATE TABLE `ubicacion_ruta` (
  `id_ubicacion_ruta` int(11) NOT NULL,
  `orden` tinyint(4) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `id_ubicacion` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD PRIMARY KEY (`id_administrativo`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`);

--
-- Indices de la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  ADD PRIMARY KEY (`id_administra_documento`),
  ADD KEY `id_administrativo` (`id_administrativo`),
  ADD KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD PRIMARY KEY (`id_ambulancia`),
  ADD UNIQUE KEY `matricula_unique` (`matricula`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`id_conductor`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`);

--
-- Indices de la tabla `copiloto`
--
ALTER TABLE `copiloto`
  ADD PRIMARY KEY (`id_copiloto`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`id_departamento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_estado_documento` (`id_estado_documento`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `elemento`
--
ALTER TABLE `elemento`
  ADD PRIMARY KEY (`id_elemento`);

--
-- Indices de la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  ADD PRIMARY KEY (`id_elemento_traslado`),
  ADD KEY `id_traslado` (`id_traslado`),
  ADD KEY `id_elemento` (`id_elemento`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id_encuesta`);

--
-- Indices de la tabla `estado_documento`
--
ALTER TABLE `estado_documento`
  ADD PRIMARY KEY (`id_estado_documento`);

--
-- Indices de la tabla `estado_funcionario`
--
ALTER TABLE `estado_funcionario`
  ADD PRIMARY KEY (`id_estado_funcionario`);

--
-- Indices de la tabla `estado_solicitud`
--
ALTER TABLE `estado_solicitud`
  ADD PRIMARY KEY (`id_estado_solicitud`);

--
-- Indices de la tabla `estado_traslado`
--
ALTER TABLE `estado_traslado`
  ADD PRIMARY KEY (`id_estado_traslado`);

--
-- Indices de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`id_funcionario`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_estado_funcionario` (`id_estado_funcionario`);

--
-- Indices de la tabla `historial_traslado`
--
ALTER TABLE `historial_traslado`
  ADD PRIMARY KEY (`id_historial_traslado`),
  ADD KEY `id_traslado` (`id_traslado`),
  ADD KEY `id_estado_traslado` (`id_estado_traslado`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`id_localidad`),
  ADD UNIQUE KEY `nombre_departamento` (`nombre`,`id_departamento`),
  ADD KEY `id_departamento` (`id_departamento`);

--
-- Indices de la tabla `muestra`
--
ALTER TABLE `muestra`
  ADD PRIMARY KEY (`id_muestra`),
  ADD KEY `cedula_persona` (`cedula_persona`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `cedula_persona` (`cedula_persona`);

--
-- Indices de la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  ADD PRIMARY KEY (`id_persona_acompaniante`),
  ADD KEY `cedula_acompaniante` (`cedula_acompaniante`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cedula`),
  ADD UNIQUE KEY `telefono` (`telefono`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `qr`
--
ALTER TABLE `qr`
  ADD PRIMARY KEY (`id_qr`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `responde_encuesta`
--
ALTER TABLE `responde_encuesta`
  ADD PRIMARY KEY (`id_encuesta_respuesta`),
  ADD KEY `id_encuesta` (`id_encuesta`),
  ADD KEY `cedula_persona` (`cedula_persona`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`id_ruta`),
  ADD UNIQUE KEY `Nombre` (`nombre`);

--
-- Indices de la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `id_administrativo` (`id_administrativo`),
  ADD KEY `id_estado_solicitud` (`id_estado_solicitud`);

--
-- Indices de la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  ADD PRIMARY KEY (`id_super_usuario`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`);

--
-- Indices de la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  ADD PRIMARY KEY (`id_telefono`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`,`telefono`);

--
-- Indices de la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD PRIMARY KEY (`id_traslado`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_copiloto` (`id_copiloto`),
  ADD KEY `id_conductor` (`id_conductor`),
  ADD KEY `id_ambulancia` (`id_ambulancia`),
  ADD KEY `id_ruta` (`id_ruta`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`),
  ADD KEY `id_localidad` (`id_localidad`);

--
-- Indices de la tabla `ubicacion_ruta`
--
ALTER TABLE `ubicacion_ruta`
  ADD PRIMARY KEY (`id_ubicacion_ruta`),
  ADD KEY `id_ruta` (`id_ruta`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  MODIFY `id_administra_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  MODIFY `id_ambulancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `elemento`
--
ALTER TABLE `elemento`
  MODIFY `id_elemento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  MODIFY `id_elemento_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id_encuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `estado_documento`
--
ALTER TABLE `estado_documento`
  MODIFY `id_estado_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_funcionario`
--
ALTER TABLE `estado_funcionario`
  MODIFY `id_estado_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `estado_solicitud`
--
ALTER TABLE `estado_solicitud`
  MODIFY `id_estado_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_traslado`
--
ALTER TABLE `estado_traslado`
  MODIFY `id_estado_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `id_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `historial_traslado`
--
ALTER TABLE `historial_traslado`
  MODIFY `id_historial_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `id_localidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `muestra`
--
ALTER TABLE `muestra`
  MODIFY `id_muestra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  MODIFY `id_persona_acompaniante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `qr`
--
ALTER TABLE `qr`
  MODIFY `id_qr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `responde_encuesta`
--
ALTER TABLE `responde_encuesta`
  MODIFY `id_encuesta_respuesta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `id_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `traslado`
--
ALTER TABLE `traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamento`
--
ALTER TABLE `departamento`
  MODIFY `id_departamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD CONSTRAINT `administrativo_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  ADD CONSTRAINT `administra_documento_ibfk_1` FOREIGN KEY (`id_administrativo`) REFERENCES `administrativo` (`id_administrativo`),
  ADD CONSTRAINT `administra_documento_ibfk_2` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`);

--
-- Filtros para la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD CONSTRAINT `conductor_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `copiloto`
--
ALTER TABLE `copiloto`
  ADD CONSTRAINT `copiloto_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_estado_documento`) REFERENCES `estado_documento` (`id_estado_documento`),
  ADD CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`);

--
-- Filtros para la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  ADD CONSTRAINT `elemento_traslado_ibfk_1` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`),
  ADD CONSTRAINT `elemento_traslado_ibfk_2` FOREIGN KEY (`id_elemento`) REFERENCES `elemento` (`id_elemento`);

--
-- Filtros para la tabla `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `funcionario_ibfk_1` FOREIGN KEY (`id_estado_funcionario`) REFERENCES `estado_funcionario` (`id_estado_funcionario`);

--
-- Filtros para la tabla `historial_traslado`
--
ALTER TABLE `historial_traslado`
  ADD CONSTRAINT `historial_traslado_ibfk_1` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`),
  ADD CONSTRAINT `historial_traslado_ibfk_2` FOREIGN KEY (`id_estado_traslado`) REFERENCES `estado_traslado` (`id_estado_traslado`);

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `localidad_ibfk_1` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id_departamento`);

--
-- Filtros para la tabla `muestra`
--
ALTER TABLE `muestra`
  ADD CONSTRAINT `muestra_ibfk_1` FOREIGN KEY (`cedula_persona`) REFERENCES `persona` (`cedula`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`cedula_persona`) REFERENCES `persona` (`cedula`);

--
-- Filtros para la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  ADD CONSTRAINT `paciente_acompaniante_ibfk_1` FOREIGN KEY (`cedula_acompaniante`) REFERENCES `acompaniante` (`cedula`),
  ADD CONSTRAINT `paciente_acompaniante_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `qr`
--
ALTER TABLE `qr`
  ADD CONSTRAINT `QR_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`);

--
-- Filtros para la tabla `responde_encuesta`
--
ALTER TABLE `responde_encuesta`
  ADD CONSTRAINT `responde_encuesta_ibfk_1` FOREIGN KEY (`id_encuesta`) REFERENCES `encuesta` (`id_encuesta`),
  ADD CONSTRAINT `responde_encuesta_ibfk_2` FOREIGN KEY (`cedula_persona`) REFERENCES `persona` (`cedula`);

--
-- Filtros para la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  ADD CONSTRAINT `solicitud_traslado_ibfk_1` FOREIGN KEY (`id_administrativo`) REFERENCES `administrativo` (`id_administrativo`),
  ADD CONSTRAINT `solicitud_traslado_ibfk_2` FOREIGN KEY (`id_estado_solicitud`) REFERENCES `estado_solicitud` (`id_estado_solicitud`);

--
-- Filtros para la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  ADD CONSTRAINT `super_usuario_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  ADD CONSTRAINT `telefono_funcionario_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD CONSTRAINT `traslado_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_traslado` (`id_solicitud`),
  ADD CONSTRAINT `traslado_ibfk_2` FOREIGN KEY (`id_copiloto`) REFERENCES `copiloto` (`id_copiloto`),
  ADD CONSTRAINT `traslado_ibfk_3` FOREIGN KEY (`id_conductor`) REFERENCES `conductor` (`id_conductor`),
  ADD CONSTRAINT `traslado_ibfk_4` FOREIGN KEY (`id_ambulancia`) REFERENCES `ambulancia` (`id_ambulancia`),
  ADD CONSTRAINT `traslado_ibfk_5` FOREIGN KEY (`id_ruta`) REFERENCES `ruta` (`id_ruta`),
  ADD CONSTRAINT `traslado_ibfk_6` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`);

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`id_localidad`) REFERENCES `localidad` (`id_localidad`);

--
-- Filtros para la tabla `ubicacion_ruta`
--
ALTER TABLE `ubicacion_ruta`
  ADD CONSTRAINT `ubicacion_ruta_ibfk_1` FOREIGN KEY (`id_ruta`) REFERENCES `ruta` (`id_ruta`),
  ADD CONSTRAINT `ubicacion_ruta_ibfk_2` FOREIGN KEY (`id_ubicacion`) REFERENCES `ubicacion` (`id_ubicacion`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;