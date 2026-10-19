-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2026 a las 14:20:08
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
-- Estructura de tabla para la tabla `accion`
--

CREATE TABLE `accion` (
  `id_accion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `accion`
--

INSERT INTO `accion` (`id_accion`, `nombre`) VALUES
(2, 'Activar'),
(1, 'Cargar'),
(3, 'Desactivar'),
(5, 'Eliminar'),
(4, 'Modificar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompaniante`
--

CREATE TABLE `acompaniante` (
  `id_acompaniante` int(11) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `id_estado_acompaniante` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `acompaniante`
--

INSERT INTO `acompaniante` (`id_acompaniante`, `cedula`, `nombre`, `apellido`, `id_estado_acompaniante`) VALUES
(1, '50987462', 'Diego', 'Maradona', 1),
(2, '50959837', 'Franz', 'Beckenbauer', 1),
(3, '50950383', 'Alfredo', 'Di Stéfano', 3),
(4, '54037001', 'Francis', 'Valderrama', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrativo`
--

CREATE TABLE `administrativo` (
  `id_administrativo` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `permisos` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administrativo`
--

INSERT INTO `administrativo` (`id_administrativo`, `codigo`, `permisos`, `pass`, `id_funcionario`) VALUES
(1, 'FA00000001', 'high', '$2y$10$XR.7nhZX2MJH7XVdks1Xr.yqGaAumosLgdiLw1Qp8.MOu/U5ldeWy', 1),
(2, 'FA00000002', 'mid', '$2y$10$GaeJRF5DWnfNo1vAWR78R.obo6ql.zVpSLS0MSRVDEFW2UhyDWVCe', 2),
(3, 'FA00000003', 'high', '$2y$10$75pOzeQAMtzUw6rGWI5ly.SmkXNyoxpQhS1kFPV8SD5IUV6N8FFdG', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administra_documento`
--

CREATE TABLE `administra_documento` (
  `id_administra_documento` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_accion` int(11) NOT NULL,
  `codigo_administrativo` varchar(10) NOT NULL,
  `id_documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administra_documento`
--

INSERT INTO `administra_documento` (`id_administra_documento`, `fecha_hora`, `id_accion`, `codigo_administrativo`, `id_documento`) VALUES
(1, '2026-08-10 14:45:04', 1, 'FA00000001', 1),
(2, '2026-08-10 14:45:21', 1, 'FA00000001', 2),
(3, '2026-08-10 14:45:33', 1, 'FA00000001', 3),
(4, '2026-08-10 14:46:07', 3, 'FA00000001', 1),
(5, '2026-08-10 14:46:10', 2, 'FA00000001', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambulancia`
--

CREATE TABLE `ambulancia` (
  `id_ambulancia` int(11) NOT NULL,
  `matricula` varchar(10) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `modelo` varchar(50) NOT NULL,
  `anio` year(4) NOT NULL CHECK (`anio` >= 1900),
  `descripcion` text NOT NULL,
  `id_estado_ambulancia` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambulancia`
--

INSERT INTO `ambulancia` (`id_ambulancia`, `matricula`, `marca`, `modelo`, `anio`, `descripcion`, `id_estado_ambulancia`) VALUES
(1, 'SAM1003', 'Mercedez Benz', 'Sprinter', '2025', 'Especializada en transporte de insumos', 1),
(2, 'SAM1002', 'Fiat', 'Ducato', '2023', 'Especializada en transporte de pacientes', 2),
(3, 'SAM1004', 'Renault', 'Master', '2026', 'Propósito general', 1),
(4, 'SAM1090', 'Mercedes', 'Spatrovich', '2026', 'Especial para transportar turrix', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_estado_categoria` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre`, `id_estado_categoria`) VALUES
(1, 'Protocolos', 1),
(2, 'Procedimientos', 1),
(3, 'Manuales', 1),
(4, 'Instructivos', 1),
(5, 'Normativas', 1),
(6, 'Formularios', 1),
(7, 'Circulares y Comunicados', 1),
(8, 'Guías Clínicas', 1),
(9, 'Capacitación', 1),
(10, 'Documentación Técnica', 1),
(11, 'Mantenimiento', 1),
(12, 'Calidad', 1),
(13, 'Seguridad', 1),
(14, 'Recursos Humanos', 1),
(15, 'Otros', 1),
(16, 'F en el chat', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `id_conductor` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `vencimiento_carnet` date NOT NULL,
  `categoria_carnet` varchar(20) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `conductor`
--

INSERT INTO `conductor` (`id_conductor`, `codigo`, `vencimiento_carnet`, `categoria_carnet`, `pass`, `id_funcionario`) VALUES
(1, 'DR00000001', '2030-10-10', 'E', 'HASH...', 3),
(2, 'DR00000002', '2032-10-10', 'C', 'HASH...', 5),
(3, 'DR00000003', '2030-02-20', 'F', '$2y$10$UZh85qyrpqsnZoS3rrbz.e4LCTUh9m40Yxcqz6WzGdk4q5HXQPKWG', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `copiloto`
--

CREATE TABLE `copiloto` (
  `id_copiloto` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `especialidad` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_funcionario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `copiloto`
--

INSERT INTO `copiloto` (`id_copiloto`, `codigo`, `especialidad`, `pass`, `id_funcionario`) VALUES
(1, 'CO00000001', 'Trauma', 'HASH...', 4),
(2, 'CO00000002', 'Soporte Vital Básico', 'HASH...', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento`
--

CREATE TABLE `documento` (
  `id_documento` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `archivo` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_estado_documento` int(11) NOT NULL DEFAULT 1,
  `id_categoria` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `documento`
--

INSERT INTO `documento` (`id_documento`, `nombre`, `archivo`, `fecha_creacion`, `id_estado_documento`, `id_categoria`, `id_servicio`) VALUES
(1, 'Analisis de sangre', '/uploads/documents/archivo1.pdf', '2026-08-10 14:45:04', 1, 4, 1),
(2, 'Carnet de salud', '/uploads/documents/archivo2.pdf', '2026-08-10 14:45:21', 1, 3, 1),
(3, 'Dialisis', '/uploads/documents/archivo3.pdf', '2026-08-10 14:45:33', 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento`
--

CREATE TABLE `elemento` (
  `id_elemento` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `id_subtipo_elemento` int(11) NOT NULL,
  `id_estado_elemento` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `elemento`
--

INSERT INTO `elemento` (`id_elemento`, `codigo`, `nombre`, `tipo`, `descripcion`, `id_subtipo_elemento`, `id_estado_elemento`) VALUES
(1, 'M001', 'Alcohol Rectificado', 'Biológico', 'Botella de 1000ml', 1, 1),
(2, 'I0001', 'Guantes de nitrilo', 'No Biológico', 'Caja de 100 unidades', 12, 1),
(3, 'H0001', 'Desfibrilador', 'No Biológico', 'Desfibrilador portátil', 8, 1),
(4, 'I0002', 'Gasas rectificadoras', 'Biológico', 'Caja de paquetes de gasas rectificadoras', 3, 3),
(5, 'M001220', 'Pene del Gorila', 'Biológico', 'Un platano de gorila', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `elemento_traslado`
--

CREATE TABLE `elemento_traslado` (
  `id_elemento_traslado` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_traslado` int(11) NOT NULL,
  `id_elemento` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL CHECK (`cantidad` >= 1)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuesta`
--

CREATE TABLE `encuesta` (
  `id_encuesta` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `contenido` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`contenido`)),
  `id_estado_encuesta` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `encuesta`
--

INSERT INTO `encuesta` (`id_encuesta`, `titulo`, `contenido`, `id_estado_encuesta`, `id_servicio`) VALUES
(1, 'Encuesta Documentos Primera Prueba', '{\"title\":\"Encuesta Documentos Primera Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la facilidad para acceder a los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con la rapidez para obtener los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con este servicio\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Encontró el documento requerido\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 1, 1),
(2, 'Encuesta Documentos Segunda Prueba', '{\"title\":\"Encuesta Documentos Segunda Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la facilidad para acceder a los documentos\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba satisfacción\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 2, 1),
(3, 'Encuesta Traslados Primera Prueba', '{\"title\":\"Encuesta Traslados Primera Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la atención recibida del personal\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Qué tan satisfecho está con las condiciones y comodidad de el traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"ué tan satisfecho está con la puntualidad del traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"El traslado se realizó según lo esperado\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 2, 2),
(4, 'Encuesta Traslados Segunda Prueba', '{\"title\":\"Encuesta Traslados Segunda Prueba\",\"questions\":[{\"title\":\"Qué tan satisfecho está con la puntualidad del traslado\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Pregunta de prueba satisacción\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Recomendaría este servicio\",\"type\":\"boolean\",\"options\":{\"1\":\"Si\",\"2\":\"No\"}}]}', 1, 2),
(5, 'Prueba exitosa', '{\"title\":\"Prueba exitosa\",\"questions\":[{\"title\":\"Habia una vez\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Habia una vez\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}},{\"title\":\"Habia una vez\",\"type\":\"satisfaction\",\"options\":{\"1\":\"Muy insatisfecho\",\"2\":\"Insatisfecho\",\"3\":\"Ni satisfecho ni insatisfecho\",\"4\":\"Satisfecho\",\"5\":\"Muy satisfecho\"}}]}', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_acompaniante`
--

CREATE TABLE `estado_acompaniante` (
  `id_estado_acompaniante` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_acompaniante`
--

INSERT INTO `estado_acompaniante` (`id_estado_acompaniante`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_ambulancia`
--

CREATE TABLE `estado_ambulancia` (
  `id_estado_ambulancia` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_ambulancia`
--

INSERT INTO `estado_ambulancia` (`id_estado_ambulancia`, `nombre`) VALUES
(1, 'Activa'),
(3, 'Eliminada'),
(2, 'Inactiva'),
(4, 'Mantenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_categoria`
--

CREATE TABLE `estado_categoria` (
  `id_estado_categoria` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_categoria`
--

INSERT INTO `estado_categoria` (`id_estado_categoria`, `nombre`) VALUES
(1, 'Activa'),
(2, 'Inactiva');

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
(3, 'Eliminado'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_elemento`
--

CREATE TABLE `estado_elemento` (
  `id_estado_elemento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_elemento`
--

INSERT INTO `estado_elemento` (`id_estado_elemento`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_encuesta`
--

CREATE TABLE `estado_encuesta` (
  `id_estado_encuesta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_encuesta`
--

INSERT INTO `estado_encuesta` (`id_estado_encuesta`, `nombre`) VALUES
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
(3, 'Eliminado'),
(2, 'Inactivo'),
(8, 'Jubilado'),
(4, 'Licencia anual'),
(7, 'Licencia médica'),
(5, 'Seguro de paro'),
(6, 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_muestra`
--

CREATE TABLE `estado_muestra` (
  `id_estado_muestra` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_muestra`
--

INSERT INTO `estado_muestra` (`id_estado_muestra`, `nombre`) VALUES
(1, 'Activa'),
(4, 'Descartada'),
(3, 'Eliminada'),
(2, 'Inactiva');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_paciente`
--

CREATE TABLE `estado_paciente` (
  `id_estado_paciente` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_paciente`
--

INSERT INTO `estado_paciente` (`id_estado_paciente`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(4, 'Fallecido'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_ruta`
--

CREATE TABLE `estado_ruta` (
  `id_estado_ruta` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_ruta`
--

INSERT INTO `estado_ruta` (`id_estado_ruta`, `nombre`) VALUES
(1, 'Activa'),
(3, 'Eliminada'),
(2, 'Inactiva');

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
(2, 'Aceptada'),
(1, 'Pendiente'),
(3, 'Rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_subtipo_elemento`
--

CREATE TABLE `estado_subtipo_elemento` (
  `id_estado_subtipo_elemento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_subtipo_elemento`
--

INSERT INTO `estado_subtipo_elemento` (`id_estado_subtipo_elemento`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_super_usuario`
--

CREATE TABLE `estado_super_usuario` (
  `id_estado_super_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_super_usuario`
--

INSERT INTO `estado_super_usuario` (`id_estado_super_usuario`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(2, 'Inactivo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_tipo_muestra`
--

CREATE TABLE `estado_tipo_muestra` (
  `id_estado_tipo_muestra` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_tipo_muestra`
--

INSERT INTO `estado_tipo_muestra` (`id_estado_tipo_muestra`, `nombre`) VALUES
(1, 'Activo'),
(2, 'Inactivo');

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
(2, 'En curso'),
(3, 'En pausa'),
(4, 'Finalizado'),
(1, 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_ubicacion`
--

CREATE TABLE `estado_ubicacion` (
  `id_estado_ubicacion` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_ubicacion`
--

INSERT INTO `estado_ubicacion` (`id_estado_ubicacion`, `nombre`) VALUES
(1, 'Activo'),
(3, 'Eliminado'),
(2, 'Inactivo');

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
  `fecha_ingreso` date NOT NULL,
  `id_estado_funcionario` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `funcionario`
--

INSERT INTO `funcionario` (`id_funcionario`, `nombre`, `apellido`, `cedula`, `nacionalidad`, `fecha_nacimiento`, `departamento`, `localidad`, `direccion`, `numero_puerta`, `email`, `fecha_ingreso`, `id_estado_funcionario`) VALUES
(1, 'Ejemplo', 'Uno', '00000001', 'Argentino/a', '1990-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 001', '001', 'exampleuno@email.com', '2010-10-10', 1),
(2, 'Ejemplo', 'Dos', '00000002', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Montevideo', 'Calle Falsa 002', '001 Bis', 'exampledos@email.com', '2020-10-10', 1),
(3, 'Ejemplo', 'Tres', '00000003', 'Uruguayo/a', '1990-10-10', 'Canelones', 'Salinas', 'Calle Falsa 003', '002', 'exampletres@email.com', '2010-10-10', 1),
(4, 'Ejemplo', 'Cinco', '00000005', 'Uruguayo/a', '1990-10-10', 'Durazno', 'Durazno', 'Calle Falsa 005', '003 Bis', 'examplecinco@email.com', '2010-10-10', 1),
(5, 'Ejemplo', 'Cuatro', '00000004', 'Uruguayo/a', '2000-10-10', 'Montevideo', 'Cerro Porteño', 'Calle Falsa 004', '666 Bis', 'examplecuatro@email.com', '2020-10-10', 1),
(6, 'Ejemplo', 'Seis', '00000006', 'Chino/a', '2000-10-10', 'Montevideo', 'Casavalle', 'Calle Falsa 006', '007 Bis', 'exampleseis@email.com', '2020-10-10', 6),
(7, 'Peru', 'Exito', '00000111', 'Afgano/a', '2000-10-10', 'Paysandú', 'Paysandú', 'Prueba dirreccion', '1 Bis', 'examplenuevemilllll@email.com', '2020-10-10', 1);

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
-- Estructura de tabla para la tabla `mensaje_contacto`
--

CREATE TABLE `mensaje_contacto` (
  `id_mensaje` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(254) NOT NULL,
  `mensaje` text NOT NULL,
  `consentimiento` tinyint(1) NOT NULL,
  `fecha_consentimiento` datetime NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mensaje_contacto`
--

INSERT INTO `mensaje_contacto` (`id_mensaje`, `nombre`, `correo`, `mensaje`, `consentimiento`, `fecha_consentimiento`, `fecha_envio`) VALUES
(1, 'Steven Malzoni', 'stevenmalzoni@gmail.com', 'sasassassas', 1, '2026-10-19 09:05:50', '2026-10-19 09:05:50'),
(2, 'Steven Malzoni', 'stevenmalzoni@gmail.com', 'mensaje de prueba', 1, '2026-10-19 09:15:19', '2026-10-19 09:15:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `muestra`
--

CREATE TABLE `muestra` (
  `id_muestra` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_tipo_muestra` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_estado_muestra` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `muestra`
--

INSERT INTO `muestra` (`id_muestra`, `codigo`, `descripcion`, `id_tipo_muestra`, `id_paciente`, `id_estado_muestra`) VALUES
(1, 'A2349185', 'Perú es good', 1, 1, 1),
(2, 'A4908959', 'Muetra de prueba', 5, 2, 1),
(3, 'A4902416', 'Muestra de prueba', 3, 3, 1),
(4, 'A2238731', 'ASDASdasdasdasd', 4, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `muestra_traslado`
--

CREATE TABLE `muestra_traslado` (
  `id_muestra_traslado` int(11) NOT NULL,
  `id_muestra` int(11) NOT NULL,
  `id_traslado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `cedula` varchar(8) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_estado_paciente` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `cedula`, `nombre`, `apellido`, `fecha_nacimiento`, `telefono`, `direccion`, `email`, `id_estado_paciente`) VALUES
(1, '23441242', 'Jhon', 'Durante', '2008-08-08', '+59892784324', 'Calle Falsa 123', 'jhon234@gmail.com', 1),
(2, '23441241', 'Paolo', 'Guerrero', '2008-08-08', '+59892784325', 'Calle Falsa 1 4123', 'paolo2344@gmail.com', 1),
(3, '23441247', 'Xavier', 'Hernandéz', '2006-06-23', '+59892784321', 'Calle Falsa 2 1451', 'xavi232344@gmail.com', 3),
(4, '54029389', 'Fransisco', 'Días', '2005-10-05', '+59899731665', '18 de Julio 1958', 'Franciscou@gmail.com', 3),
(5, '54029241', 'Fransisco', 'Prueba Dura', '2010-10-10', '+59899565342', '18 de Julio 19585', 'paolo23442341@gmail.com', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente_acompaniante`
--

CREATE TABLE `paciente_acompaniante` (
  `id_paciente_acompaniante` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_paciente` int(11) NOT NULL,
  `id_acompaniante` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qr`
--

CREATE TABLE `qr` (
  `id_qr` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `qr`
--

INSERT INTO `qr` (`id_qr`, `token`, `fecha_creacion`, `id_documento`) VALUES
(1, '6db3c9490a327205037d517872176966b8df32ed4a61a6d1ea186299b86c38f1', '2026-08-10 14:45:04', 1),
(2, '9516422c1593a0767ba9786e664acf226f24bc0912d3325d1ca568f8106886c9', '2026-08-10 14:45:21', 2),
(3, '797c3d782dddcb7dfcc27a47978a46a820311fc9dde5c9844c5da7f742658598', '2026-08-10 14:45:33', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta_encuesta`
--

CREATE TABLE `respuesta_encuesta` (
  `id_respuesta_encuesta` int(11) NOT NULL,
  `id_encuesta` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `respuestas` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`respuestas`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta_encuesta`
--

INSERT INTO `respuesta_encuesta` (`id_respuesta_encuesta`, `id_encuesta`, `fecha_hora`, `respuestas`) VALUES
(1, 1, '2026-09-11 14:45:51', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}'),
(2, 1, '2026-09-11 14:46:07', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"4\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"4\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}'),
(3, 1, '2026-09-11 14:46:35', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"3\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"2\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"2\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}'),
(4, 1, '2026-10-13 14:25:48', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"2\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"1\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"1\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"2\"}]}'),
(5, 1, '2026-10-13 14:26:38', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"3\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"3\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"4\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"2\"}]}'),
(6, 1, '2026-10-13 15:48:44', '{\"encuesta_id\":1,\"titulo\":\"Encuesta Documentos Primera Prueba\",\"respuestas\":[{\"pregunta\":\"quest0\",\"type\":\"satisfaction\",\"respuesta\":\"3\"},{\"pregunta\":\"quest1\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest2\",\"type\":\"satisfaction\",\"respuesta\":\"5\"},{\"pregunta\":\"quest3\",\"type\":\"boolean\",\"respuesta\":\"1\"},{\"pregunta\":\"quest4\",\"type\":\"boolean\",\"respuesta\":\"1\"}]}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `id_ruta` int(11) NOT NULL,
  `origen` varchar(50) NOT NULL,
  `destino` varchar(50) NOT NULL,
  `id_estado_ruta` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ruta`
--

INSERT INTO `ruta` (`id_ruta`, `origen`, `destino`, `id_estado_ruta`) VALUES
(1, 'Hospital de Clínicas', 'Medica Uruguaya', 1),
(2, 'Hospital de Clínicas', 'Hospital Maciel', 2),
(3, 'Medica Uruguaya', 'Hospital de Clínicas', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `nombre`) VALUES
(1, 'Documento'),
(2, 'Traslado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud_traslado`
--

CREATE TABLE `solicitud_traslado` (
  `id_solicitud` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_estado_solicitud` int(11) NOT NULL,
  `codigo_administrativo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subtipo_elemento`
--

CREATE TABLE `subtipo_elemento` (
  `id_subtipo_elemento` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo` int(11) NOT NULL,
  `id_estado_subtipo_elemento` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subtipo_elemento`
--

INSERT INTO `subtipo_elemento` (`id_subtipo_elemento`, `nombre`, `tipo`, `id_estado_subtipo_elemento`) VALUES
(1, 'Medicamentos e insumos de origen biológico', 1, 1),
(2, 'Productos derivados de la sangre', 1, 1),
(3, 'Productos derivados de tejidos', 1, 1),
(4, 'Material biológico para investigación', 1, 1),
(5, 'Otros', 1, 1),
(6, 'Medicamentos e insumos de origen no biológico', 2, 1),
(7, 'Instrumental médico', 2, 1),
(8, 'Equipamiento médico', 2, 1),
(9, 'Equipamiento tecnológico', 2, 1),
(10, 'Material de mantenimiento', 2, 1),
(11, 'Material de limpieza', 2, 1),
(12, 'Elementos de protección', 2, 1),
(13, 'Mobiliario', 2, 1),
(14, 'Otros', 2, 1),
(15, 'f en el chat', 2, 1),
(16, 'f en el chat', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `super_usuario`
--

CREATE TABLE `super_usuario` (
  `id_super_usuario` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `permisos` varchar(10) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_estado_super_usuario` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `super_usuario`
--

INSERT INTO `super_usuario` (`id_super_usuario`, `codigo`, `nombre`, `permisos`, `pass`, `id_estado_super_usuario`) VALUES
(1, 'SU00000001', 'HeadAdmin', 'All', '$2y$10$msGlqBNrDLzQJ6nYJhRu0OFbeXgrOENQEyiQzhj8mZ5NfNY1n5LGG', 1),
(2, 'SU00000002', 'SubHeadAdmin', 'All', '$2y$10$GVGb2jSChT9baX732a1L8OyBJfsovJ1U5lUdRSSWlKkoMsikJogcS', 1),
(3, 'SU00000003', 'Federico Telo Clava', 'High', '$2y$10$rfRIJPDcrNKBc0vGFs2cPO3WK9GHsyo0R8mgYTlHuFjkrXzOw3g6.', 3);

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
(7, '+59896998979', 1),
(1, '+59899090901', 1),
(2, '+59899090902', 2),
(3, '+59899090903', 3),
(4, '+59899090905', 4),
(5, '+59899090666', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_muestra`
--

CREATE TABLE `tipo_muestra` (
  `id_tipo_muestra` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_estado_tipo_muestra` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_muestra`
--

INSERT INTO `tipo_muestra` (`id_tipo_muestra`, `nombre`, `id_estado_tipo_muestra`) VALUES
(1, 'Orina', 1),
(2, 'Heces', 1),
(3, 'Secreciones', 1),
(4, 'Esputo', 1),
(5, 'Saliva', 1),
(6, 'Líquidos corporales', 1),
(7, 'Tejido', 1),
(8, 'Células', 1),
(9, 'Médula ósea', 1),
(10, 'Material reproductivo', 1),
(11, 'Material dermatológico', 1),
(12, 'Material de heridas', 1),
(13, 'Otros', 1),
(14, 'Chimuela', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `traslado`
--

CREATE TABLE `traslado` (
  `id_traslado` int(11) NOT NULL,
  `hora_inicio` datetime DEFAULT NULL,
  `hora_fin` datetime DEFAULT NULL,
  `id_solicitud` int(11) NOT NULL,
  `codigo_copiloto` varchar(10) NOT NULL,
  `codigo_conductor` varchar(10) NOT NULL,
  `id_ambulancia` int(11) NOT NULL,
  `id_ruta` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id_ubicacion` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `departamento` varchar(50) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `latitud` decimal(10,7) NOT NULL,
  `longitud` decimal(10,7) NOT NULL,
  `id_estado_ubicacion` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_ubicacion`, `nombre`, `departamento`, `direccion`, `latitud`, `longitud`, `id_estado_ubicacion`) VALUES
(1, 'Hospital Maciel', 'Montevideo', '25 de Mayo 174', -34.9080048, -56.2120319, 1),
(2, 'Hospital de Clínicas', 'Montevideo', '25 de Mayo 174', -34.9080048, -56.2120319, 1),
(3, 'Medica Uruguaya', 'Montevideo', '25 de Mayo 174', -34.9082467, -56.2079000, 1);

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
-- Indices de la tabla `accion`
--
ALTER TABLE `accion`
  ADD PRIMARY KEY (`id_accion`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  ADD PRIMARY KEY (`id_acompaniante`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `id_estado_acompaniante` (`id_estado_acompaniante`);

--
-- Indices de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD PRIMARY KEY (`id_administrativo`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  ADD PRIMARY KEY (`id_administra_documento`),
  ADD KEY `id_accion` (`id_accion`),
  ADD KEY `codigo_administrativo` (`codigo_administrativo`),
  ADD KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD PRIMARY KEY (`id_ambulancia`),
  ADD UNIQUE KEY `matricula` (`matricula`),
  ADD KEY `id_estado_ambulancia` (`id_estado_ambulancia`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_estado_categoria` (`id_estado_categoria`);

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`id_conductor`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `copiloto`
--
ALTER TABLE `copiloto`
  ADD PRIMARY KEY (`id_copiloto`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`),
  ADD UNIQUE KEY `codigo` (`codigo`);

--
-- Indices de la tabla `documento`
--
ALTER TABLE `documento`
  ADD PRIMARY KEY (`id_documento`),
  ADD KEY `id_estado_documento` (`id_estado_documento`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `elemento`
--
ALTER TABLE `elemento`
  ADD PRIMARY KEY (`id_elemento`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_subtipo_elemento` (`id_subtipo_elemento`),
  ADD KEY `id_estado_elemento` (`id_estado_elemento`);

--
-- Indices de la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  ADD PRIMARY KEY (`id_elemento_traslado`),
  ADD UNIQUE KEY `id_traslado` (`id_traslado`,`id_elemento`),
  ADD KEY `id_elemento` (`id_elemento`);

--
-- Indices de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD PRIMARY KEY (`id_encuesta`),
  ADD UNIQUE KEY `titulo` (`titulo`),
  ADD KEY `id_servicio` (`id_servicio`),
  ADD KEY `id_estado_encuesta` (`id_estado_encuesta`);

--
-- Indices de la tabla `estado_acompaniante`
--
ALTER TABLE `estado_acompaniante`
  ADD PRIMARY KEY (`id_estado_acompaniante`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_ambulancia`
--
ALTER TABLE `estado_ambulancia`
  ADD PRIMARY KEY (`id_estado_ambulancia`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_categoria`
--
ALTER TABLE `estado_categoria`
  ADD PRIMARY KEY (`id_estado_categoria`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_documento`
--
ALTER TABLE `estado_documento`
  ADD PRIMARY KEY (`id_estado_documento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_elemento`
--
ALTER TABLE `estado_elemento`
  ADD PRIMARY KEY (`id_estado_elemento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_encuesta`
--
ALTER TABLE `estado_encuesta`
  ADD PRIMARY KEY (`id_estado_encuesta`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_funcionario`
--
ALTER TABLE `estado_funcionario`
  ADD PRIMARY KEY (`id_estado_funcionario`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_muestra`
--
ALTER TABLE `estado_muestra`
  ADD PRIMARY KEY (`id_estado_muestra`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_paciente`
--
ALTER TABLE `estado_paciente`
  ADD PRIMARY KEY (`id_estado_paciente`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_ruta`
--
ALTER TABLE `estado_ruta`
  ADD PRIMARY KEY (`id_estado_ruta`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_solicitud`
--
ALTER TABLE `estado_solicitud`
  ADD PRIMARY KEY (`id_estado_solicitud`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_subtipo_elemento`
--
ALTER TABLE `estado_subtipo_elemento`
  ADD PRIMARY KEY (`id_estado_subtipo_elemento`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_super_usuario`
--
ALTER TABLE `estado_super_usuario`
  ADD PRIMARY KEY (`id_estado_super_usuario`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_tipo_muestra`
--
ALTER TABLE `estado_tipo_muestra`
  ADD PRIMARY KEY (`id_estado_tipo_muestra`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_traslado`
--
ALTER TABLE `estado_traslado`
  ADD PRIMARY KEY (`id_estado_traslado`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `estado_ubicacion`
--
ALTER TABLE `estado_ubicacion`
  ADD PRIMARY KEY (`id_estado_ubicacion`),
  ADD UNIQUE KEY `nombre` (`nombre`);

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
-- Indices de la tabla `mensaje_contacto`
--
ALTER TABLE `mensaje_contacto`
  ADD PRIMARY KEY (`id_mensaje`);

--
-- Indices de la tabla `muestra`
--
ALTER TABLE `muestra`
  ADD PRIMARY KEY (`id_muestra`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_tipo_muestra` (`id_tipo_muestra`),
  ADD KEY `id_estado_muestra` (`id_estado_muestra`);

--
-- Indices de la tabla `muestra_traslado`
--
ALTER TABLE `muestra_traslado`
  ADD PRIMARY KEY (`id_muestra_traslado`),
  ADD UNIQUE KEY `id_muestra` (`id_muestra`,`id_traslado`),
  ADD KEY `id_traslado` (`id_traslado`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_estado_paciente` (`id_estado_paciente`);

--
-- Indices de la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  ADD PRIMARY KEY (`id_paciente_acompaniante`),
  ADD KEY `id_acompaniante` (`id_acompaniante`),
  ADD KEY `id_paciente` (`id_paciente`);

--
-- Indices de la tabla `qr`
--
ALTER TABLE `qr`
  ADD PRIMARY KEY (`id_qr`),
  ADD UNIQUE KEY `token` (`token`),
  ADD UNIQUE KEY `id_documento` (`id_documento`);

--
-- Indices de la tabla `respuesta_encuesta`
--
ALTER TABLE `respuesta_encuesta`
  ADD PRIMARY KEY (`id_respuesta_encuesta`),
  ADD KEY `id_encuesta` (`id_encuesta`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`id_ruta`),
  ADD UNIQUE KEY `origen` (`origen`,`destino`),
  ADD KEY `id_estado_ruta` (`id_estado_ruta`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `codigo_administrativo` (`codigo_administrativo`),
  ADD KEY `id_estado_solicitud` (`id_estado_solicitud`);

--
-- Indices de la tabla `subtipo_elemento`
--
ALTER TABLE `subtipo_elemento`
  ADD PRIMARY KEY (`id_subtipo_elemento`),
  ADD UNIQUE KEY `nombre` (`nombre`,`tipo`),
  ADD KEY `id_estado_subtipo_elemento` (`id_estado_subtipo_elemento`);

--
-- Indices de la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  ADD PRIMARY KEY (`id_super_usuario`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_estado_super_usuario` (`id_estado_super_usuario`);

--
-- Indices de la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  ADD PRIMARY KEY (`id_telefono`),
  ADD UNIQUE KEY `id_funcionario` (`id_funcionario`,`telefono`);

--
-- Indices de la tabla `tipo_muestra`
--
ALTER TABLE `tipo_muestra`
  ADD PRIMARY KEY (`id_tipo_muestra`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_estado_tipo_muestra` (`id_estado_tipo_muestra`);

--
-- Indices de la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD PRIMARY KEY (`id_traslado`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `codigo_copiloto` (`codigo_copiloto`),
  ADD KEY `codigo_conductor` (`codigo_conductor`),
  ADD KEY `id_ambulancia` (`id_ambulancia`),
  ADD KEY `id_ruta` (`id_ruta`),
  ADD KEY `id_paciente` (`id_paciente`),
  ADD KEY `id_servicio` (`id_servicio`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id_ubicacion`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD KEY `id_estado_ubicacion` (`id_estado_ubicacion`);

--
-- Indices de la tabla `ubicacion_ruta`
--
ALTER TABLE `ubicacion_ruta`
  ADD PRIMARY KEY (`id_ubicacion_ruta`),
  ADD UNIQUE KEY `id_ruta` (`id_ruta`,`orden`),
  ADD KEY `id_ubicacion` (`id_ubicacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accion`
--
ALTER TABLE `accion`
  MODIFY `id_accion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  MODIFY `id_acompaniante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `administrativo`
--
ALTER TABLE `administrativo`
  MODIFY `id_administrativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  MODIFY `id_administra_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  MODIFY `id_ambulancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `conductor`
--
ALTER TABLE `conductor`
  MODIFY `id_conductor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `copiloto`
--
ALTER TABLE `copiloto`
  MODIFY `id_copiloto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `documento`
--
ALTER TABLE `documento`
  MODIFY `id_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `elemento`
--
ALTER TABLE `elemento`
  MODIFY `id_elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  MODIFY `id_elemento_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `encuesta`
--
ALTER TABLE `encuesta`
  MODIFY `id_encuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado_acompaniante`
--
ALTER TABLE `estado_acompaniante`
  MODIFY `id_estado_acompaniante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_ambulancia`
--
ALTER TABLE `estado_ambulancia`
  MODIFY `id_estado_ambulancia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_categoria`
--
ALTER TABLE `estado_categoria`
  MODIFY `id_estado_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_documento`
--
ALTER TABLE `estado_documento`
  MODIFY `id_estado_documento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_elemento`
--
ALTER TABLE `estado_elemento`
  MODIFY `id_estado_elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_encuesta`
--
ALTER TABLE `estado_encuesta`
  MODIFY `id_estado_encuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_funcionario`
--
ALTER TABLE `estado_funcionario`
  MODIFY `id_estado_funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `estado_muestra`
--
ALTER TABLE `estado_muestra`
  MODIFY `id_estado_muestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_paciente`
--
ALTER TABLE `estado_paciente`
  MODIFY `id_estado_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_ruta`
--
ALTER TABLE `estado_ruta`
  MODIFY `id_estado_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_solicitud`
--
ALTER TABLE `estado_solicitud`
  MODIFY `id_estado_solicitud` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_subtipo_elemento`
--
ALTER TABLE `estado_subtipo_elemento`
  MODIFY `id_estado_subtipo_elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_super_usuario`
--
ALTER TABLE `estado_super_usuario`
  MODIFY `id_estado_super_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `estado_tipo_muestra`
--
ALTER TABLE `estado_tipo_muestra`
  MODIFY `id_estado_tipo_muestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estado_traslado`
--
ALTER TABLE `estado_traslado`
  MODIFY `id_estado_traslado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_ubicacion`
--
ALTER TABLE `estado_ubicacion`
  MODIFY `id_estado_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `mensaje_contacto`
--
ALTER TABLE `mensaje_contacto`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `muestra`
--
ALTER TABLE `muestra`
  MODIFY `id_muestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `muestra_traslado`
--
ALTER TABLE `muestra_traslado`
  MODIFY `id_muestra_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  MODIFY `id_paciente_acompaniante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `qr`
--
ALTER TABLE `qr`
  MODIFY `id_qr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `respuesta_encuesta`
--
ALTER TABLE `respuesta_encuesta`
  MODIFY `id_respuesta_encuesta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `id_ruta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  MODIFY `id_solicitud` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `subtipo_elemento`
--
ALTER TABLE `subtipo_elemento`
  MODIFY `id_subtipo_elemento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  MODIFY `id_super_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_muestra`
--
ALTER TABLE `tipo_muestra`
  MODIFY `id_tipo_muestra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `traslado`
--
ALTER TABLE `traslado`
  MODIFY `id_traslado` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  MODIFY `id_ubicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ubicacion_ruta`
--
ALTER TABLE `ubicacion_ruta`
  MODIFY `id_ubicacion_ruta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `acompaniante`
--
ALTER TABLE `acompaniante`
  ADD CONSTRAINT `acompaniante_ibfk_1` FOREIGN KEY (`id_estado_acompaniante`) REFERENCES `estado_acompaniante` (`id_estado_acompaniante`);

--
-- Filtros para la tabla `administrativo`
--
ALTER TABLE `administrativo`
  ADD CONSTRAINT `administrativo_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `administra_documento`
--
ALTER TABLE `administra_documento`
  ADD CONSTRAINT `administra_documento_ibfk_1` FOREIGN KEY (`id_accion`) REFERENCES `accion` (`id_accion`),
  ADD CONSTRAINT `administra_documento_ibfk_2` FOREIGN KEY (`codigo_administrativo`) REFERENCES `administrativo` (`codigo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `administra_documento_ibfk_3` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`);

--
-- Filtros para la tabla `ambulancia`
--
ALTER TABLE `ambulancia`
  ADD CONSTRAINT `ambulancia_ibfk_1` FOREIGN KEY (`id_estado_ambulancia`) REFERENCES `estado_ambulancia` (`id_estado_ambulancia`);

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`id_estado_categoria`) REFERENCES `estado_categoria` (`id_estado_categoria`);

--
-- Filtros para la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD CONSTRAINT `conductor_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `copiloto`
--
ALTER TABLE `copiloto`
  ADD CONSTRAINT `copiloto_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `documento`
--
ALTER TABLE `documento`
  ADD CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_estado_documento`) REFERENCES `estado_documento` (`id_estado_documento`),
  ADD CONSTRAINT `documento_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`),
  ADD CONSTRAINT `documento_ibfk_3` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `elemento`
--
ALTER TABLE `elemento`
  ADD CONSTRAINT `elemento_ibfk_1` FOREIGN KEY (`id_subtipo_elemento`) REFERENCES `subtipo_elemento` (`id_subtipo_elemento`),
  ADD CONSTRAINT `elemento_ibfk_2` FOREIGN KEY (`id_estado_elemento`) REFERENCES `estado_elemento` (`id_estado_elemento`);

--
-- Filtros para la tabla `elemento_traslado`
--
ALTER TABLE `elemento_traslado`
  ADD CONSTRAINT `elemento_traslado_ibfk_1` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`),
  ADD CONSTRAINT `elemento_traslado_ibfk_2` FOREIGN KEY (`id_elemento`) REFERENCES `elemento` (`id_elemento`);

--
-- Filtros para la tabla `encuesta`
--
ALTER TABLE `encuesta`
  ADD CONSTRAINT `encuesta_ibfk_1` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`),
  ADD CONSTRAINT `encuesta_ibfk_2` FOREIGN KEY (`id_estado_encuesta`) REFERENCES `estado_encuesta` (`id_estado_encuesta`);

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
-- Filtros para la tabla `muestra`
--
ALTER TABLE `muestra`
  ADD CONSTRAINT `muestra_ibfk_1` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`),
  ADD CONSTRAINT `muestra_ibfk_2` FOREIGN KEY (`id_tipo_muestra`) REFERENCES `tipo_muestra` (`id_tipo_muestra`),
  ADD CONSTRAINT `muestra_ibfk_3` FOREIGN KEY (`id_estado_muestra`) REFERENCES `estado_muestra` (`id_estado_muestra`);

--
-- Filtros para la tabla `muestra_traslado`
--
ALTER TABLE `muestra_traslado`
  ADD CONSTRAINT `muestra_traslado_ibfk_1` FOREIGN KEY (`id_muestra`) REFERENCES `muestra` (`id_muestra`),
  ADD CONSTRAINT `muestra_traslado_ibfk_2` FOREIGN KEY (`id_traslado`) REFERENCES `traslado` (`id_traslado`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `paciente_ibfk_1` FOREIGN KEY (`id_estado_paciente`) REFERENCES `estado_paciente` (`id_estado_paciente`);

--
-- Filtros para la tabla `paciente_acompaniante`
--
ALTER TABLE `paciente_acompaniante`
  ADD CONSTRAINT `paciente_acompaniante_ibfk_1` FOREIGN KEY (`id_acompaniante`) REFERENCES `acompaniante` (`id_acompaniante`),
  ADD CONSTRAINT `paciente_acompaniante_ibfk_2` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`);

--
-- Filtros para la tabla `qr`
--
ALTER TABLE `qr`
  ADD CONSTRAINT `qr_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`);

--
-- Filtros para la tabla `respuesta_encuesta`
--
ALTER TABLE `respuesta_encuesta`
  ADD CONSTRAINT `respuesta_encuesta_ibfk_1` FOREIGN KEY (`id_encuesta`) REFERENCES `encuesta` (`id_encuesta`);

--
-- Filtros para la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD CONSTRAINT `ruta_ibfk_1` FOREIGN KEY (`id_estado_ruta`) REFERENCES `estado_ruta` (`id_estado_ruta`);

--
-- Filtros para la tabla `solicitud_traslado`
--
ALTER TABLE `solicitud_traslado`
  ADD CONSTRAINT `solicitud_traslado_ibfk_1` FOREIGN KEY (`codigo_administrativo`) REFERENCES `administrativo` (`codigo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `solicitud_traslado_ibfk_2` FOREIGN KEY (`id_estado_solicitud`) REFERENCES `estado_solicitud` (`id_estado_solicitud`);

--
-- Filtros para la tabla `subtipo_elemento`
--
ALTER TABLE `subtipo_elemento`
  ADD CONSTRAINT `subtipo_elemento_ibfk_1` FOREIGN KEY (`id_estado_subtipo_elemento`) REFERENCES `estado_subtipo_elemento` (`id_estado_subtipo_elemento`);

--
-- Filtros para la tabla `super_usuario`
--
ALTER TABLE `super_usuario`
  ADD CONSTRAINT `super_usuario_ibfk_1` FOREIGN KEY (`id_estado_super_usuario`) REFERENCES `estado_super_usuario` (`id_estado_super_usuario`);

--
-- Filtros para la tabla `telefono_funcionario`
--
ALTER TABLE `telefono_funcionario`
  ADD CONSTRAINT `telefono_funcionario_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`id_funcionario`);

--
-- Filtros para la tabla `tipo_muestra`
--
ALTER TABLE `tipo_muestra`
  ADD CONSTRAINT `tipo_muestra_ibfk_1` FOREIGN KEY (`id_estado_tipo_muestra`) REFERENCES `estado_tipo_muestra` (`id_estado_tipo_muestra`);

--
-- Filtros para la tabla `traslado`
--
ALTER TABLE `traslado`
  ADD CONSTRAINT `traslado_ibfk_1` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud_traslado` (`id_solicitud`),
  ADD CONSTRAINT `traslado_ibfk_2` FOREIGN KEY (`codigo_copiloto`) REFERENCES `copiloto` (`codigo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `traslado_ibfk_3` FOREIGN KEY (`codigo_conductor`) REFERENCES `conductor` (`codigo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `traslado_ibfk_4` FOREIGN KEY (`id_ambulancia`) REFERENCES `ambulancia` (`id_ambulancia`),
  ADD CONSTRAINT `traslado_ibfk_5` FOREIGN KEY (`id_ruta`) REFERENCES `ruta` (`id_ruta`),
  ADD CONSTRAINT `traslado_ibfk_6` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`),
  ADD CONSTRAINT `traslado_ibfk_7` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`);

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`id_estado_ubicacion`) REFERENCES `estado_ubicacion` (`id_estado_ubicacion`);

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
