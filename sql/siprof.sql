-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-03-2026 a las 19:37:05
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
-- Base de datos: `siprof`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aceptaciondetarea`
--

CREATE TABLE `aceptaciondetarea` (
  `IDaceptacion` int(11) NOT NULL,
  `Fechadeestado` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','Confirmada','rechazada') DEFAULT NULL,
  `IDasignaciondelabor` int(11) NOT NULL,
  `IDtrabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciondelabor`
--

CREATE TABLE `asignaciondelabor` (
  `IDasignaciondelabor` int(11) NOT NULL,
  `Descripcionlabor` varchar(255) DEFAULT NULL,
  `Tarea` varchar(255) NOT NULL,
  `Fechainicio` varchar(255) DEFAULT NULL,
  `Fechafin` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','proceso','cancelada') DEFAULT NULL,
  `IDadministrador` int(11) NOT NULL,
  `IDtrabajador` int(11) NOT NULL,
  `IDlote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `confirmaciondetarea`
--

CREATE TABLE `confirmaciondetarea` (
  `IDconfirmaciondetarea` int(11) NOT NULL,
  `Fechadeconfirmacion` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','Confirmada','rechazada') DEFAULT NULL,
  `IDasignaciondelabor` int(11) NOT NULL,
  `IDaceptaciondetarea` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `controldecultivo`
--

CREATE TABLE `controldecultivo` (
  `IDcontroldecultivo` int(11) NOT NULL,
  `Tipocontrol` varchar(255) NOT NULL,
  `Valorregistrado` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Estado` enum('abierto','proceso','resuelto') DEFAULT NULL,
  `Fechareporte` varchar(255) DEFAULT NULL,
  `Fechasolucion` varchar(255) DEFAULT NULL,
  `IDcultivo` int(11) NOT NULL,
  `IDfase` int(11) NOT NULL,
  `IDusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cultivo`
--

CREATE TABLE `cultivo` (
  `IDcultivo` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Fechainicio` varchar(255) DEFAULT NULL,
  `Fechacosecha` varchar(255) DEFAULT NULL,
  `Estado` enum('Activo','cosechado','cancelado') DEFAULT NULL,
  `IDfase` int(11) NOT NULL,
  `IDlote` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fase`
--

CREATE TABLE `fase` (
  `IDfase` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Duracion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresoegreso`
--

CREATE TABLE `ingresoegreso` (
  `IDregistro` int(11) NOT NULL,
  `Tipo` varchar(50) NOT NULL,
  `Concepto` varchar(255) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Fecha` varchar(255) NOT NULL,
  `IDusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lote`
--

CREATE TABLE `lote` (
  `IDlote` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Ubicacion` varchar(255) DEFAULT NULL,
  `Area` varchar(100) DEFAULT NULL,
  `Estado` enum('Activo','cosechado','cancelado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materiales`
--

CREATE TABLE `materiales` (
  `IDmateriales` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Tipo` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT 0,
  `Unidad` varchar(50) DEFAULT NULL,
  `StockMinimo` int(11) DEFAULT 0,
  `Precio` decimal(10,2) DEFAULT NULL,
  `Estado` enum('activo','agotado','inactivo') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientoinventario`
--

CREATE TABLE `movimientoinventario` (
  `IDmovimiento` int(11) NOT NULL,
  `Tipomovimiento` varchar(50) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Fecha` varchar(255) NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `IDmateriales` int(11) NOT NULL,
  `IDusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `IDpago` int(11) NOT NULL,
  `Fechapago` varchar(255) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Tipopago` varchar(100) NOT NULL,
  `Estado` enum('Pendiente','pagado') DEFAULT NULL,
  `IDtrabajador` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `produccion`
--

CREATE TABLE `produccion` (
  `IDproduccion` int(11) NOT NULL,
  `Fecha` varchar(255) DEFAULT NULL,
  `Cantidad` varchar(255) DEFAULT NULL,
  `Costo` varchar(255) DEFAULT NULL,
  `Tipo` varchar(255) DEFAULT NULL,
  `IDusuario` int(11) NOT NULL,
  `IDcultivo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `IDseguimiento` int(11) NOT NULL,
  `Fecharegistro` varchar(255) NOT NULL,
  `Actividad` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente',' proceso','finalizado') DEFAULT NULL,
  `FechaProxRevision` varchar(255) DEFAULT NULL,
  `IDcultivo` int(11) NOT NULL,
  `IDfase` int(11) NOT NULL,
  `IDusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `IDusuario` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  `Celular` varchar(20) DEFAULT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Niveldeacceso` varchar(50) NOT NULL,
  `Estado` enum('Activo','Inactivo') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `aceptaciondetarea`
--
ALTER TABLE `aceptaciondetarea`
  ADD PRIMARY KEY (`IDaceptacion`),
  ADD KEY `IDasignaciondelabor` (`IDasignaciondelabor`),
  ADD KEY `IDtrabajador` (`IDtrabajador`);

--
-- Indices de la tabla `asignaciondelabor`
--
ALTER TABLE `asignaciondelabor`
  ADD PRIMARY KEY (`IDasignaciondelabor`),
  ADD KEY `IDadministrador` (`IDadministrador`),
  ADD KEY `IDtrabajador` (`IDtrabajador`),
  ADD KEY `IDlote` (`IDlote`);

--
-- Indices de la tabla `confirmaciondetarea`
--
ALTER TABLE `confirmaciondetarea`
  ADD PRIMARY KEY (`IDconfirmaciondetarea`),
  ADD KEY `IDasignaciondelabor` (`IDasignaciondelabor`),
  ADD KEY `IDaceptaciondetarea` (`IDaceptaciondetarea`);

--
-- Indices de la tabla `controldecultivo`
--
ALTER TABLE `controldecultivo`
  ADD PRIMARY KEY (`IDcontroldecultivo`),
  ADD KEY `IDcultivo` (`IDcultivo`),
  ADD KEY `IDfase` (`IDfase`),
  ADD KEY `IDusuario` (`IDusuario`);

--
-- Indices de la tabla `cultivo`
--
ALTER TABLE `cultivo`
  ADD PRIMARY KEY (`IDcultivo`),
  ADD KEY `IDfase` (`IDfase`),
  ADD KEY `IDlote` (`IDlote`);

--
-- Indices de la tabla `fase`
--
ALTER TABLE `fase`
  ADD PRIMARY KEY (`IDfase`),
  ADD UNIQUE KEY `Nombre` (`Nombre`);

--
-- Indices de la tabla `ingresoegreso`
--
ALTER TABLE `ingresoegreso`
  ADD PRIMARY KEY (`IDregistro`),
  ADD KEY `IDusuario` (`IDusuario`);

--
-- Indices de la tabla `lote`
--
ALTER TABLE `lote`
  ADD PRIMARY KEY (`IDlote`),
  ADD UNIQUE KEY `Nombre` (`Nombre`);

--
-- Indices de la tabla `materiales`
--
ALTER TABLE `materiales`
  ADD PRIMARY KEY (`IDmateriales`),
  ADD UNIQUE KEY `Nombre` (`Nombre`);

--
-- Indices de la tabla `movimientoinventario`
--
ALTER TABLE `movimientoinventario`
  ADD PRIMARY KEY (`IDmovimiento`),
  ADD KEY `IDmateriales` (`IDmateriales`),
  ADD KEY `IDusuario` (`IDusuario`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`IDpago`),
  ADD KEY `IDtrabajador` (`IDtrabajador`);

--
-- Indices de la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD PRIMARY KEY (`IDproduccion`),
  ADD KEY `IDusuario` (`IDusuario`),
  ADD KEY `IDcultivo` (`IDcultivo`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`IDseguimiento`),
  ADD KEY `IDcultivo` (`IDcultivo`),
  ADD KEY `IDfase` (`IDfase`),
  ADD KEY `IDusuario` (`IDusuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`IDusuario`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `aceptaciondetarea`
--
ALTER TABLE `aceptaciondetarea`
  MODIFY `IDaceptacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asignaciondelabor`
--
ALTER TABLE `asignaciondelabor`
  MODIFY `IDasignaciondelabor` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `confirmaciondetarea`
--
ALTER TABLE `confirmaciondetarea`
  MODIFY `IDconfirmaciondetarea` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `controldecultivo`
--
ALTER TABLE `controldecultivo`
  MODIFY `IDcontroldecultivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cultivo`
--
ALTER TABLE `cultivo`
  MODIFY `IDcultivo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `fase`
--
ALTER TABLE `fase`
  MODIFY `IDfase` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresoegreso`
--
ALTER TABLE `ingresoegreso`
  MODIFY `IDregistro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lote`
--
ALTER TABLE `lote`
  MODIFY `IDlote` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `materiales`
--
ALTER TABLE `materiales`
  MODIFY `IDmateriales` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientoinventario`
--
ALTER TABLE `movimientoinventario`
  MODIFY `IDmovimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `IDpago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `produccion`
--
ALTER TABLE `produccion`
  MODIFY `IDproduccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `IDseguimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `IDusuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `aceptaciondetarea`
--
ALTER TABLE `aceptaciondetarea`
  ADD CONSTRAINT `aceptaciondetarea_ibfk_1` FOREIGN KEY (`IDasignaciondelabor`) REFERENCES `asignaciondelabor` (`IDasignaciondelabor`),
  ADD CONSTRAINT `aceptaciondetarea_ibfk_2` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`);

--
-- Filtros para la tabla `asignaciondelabor`
--
ALTER TABLE `asignaciondelabor`
  ADD CONSTRAINT `asignaciondelabor_ibfk_1` FOREIGN KEY (`IDadministrador`) REFERENCES `usuario` (`IDusuario`),
  ADD CONSTRAINT `asignaciondelabor_ibfk_2` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`),
  ADD CONSTRAINT `asignaciondelabor_ibfk_3` FOREIGN KEY (`IDlote`) REFERENCES `lote` (`IDlote`);

--
-- Filtros para la tabla `confirmaciondetarea`
--
ALTER TABLE `confirmaciondetarea`
  ADD CONSTRAINT `confirmaciondetarea_ibfk_1` FOREIGN KEY (`IDasignaciondelabor`) REFERENCES `asignaciondelabor` (`IDasignaciondelabor`),
  ADD CONSTRAINT `confirmaciondetarea_ibfk_2` FOREIGN KEY (`IDaceptaciondetarea`) REFERENCES `aceptaciondetarea` (`IDaceptacion`);

--
-- Filtros para la tabla `controldecultivo`
--
ALTER TABLE `controldecultivo`
  ADD CONSTRAINT `controldecultivo_ibfk_1` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`),
  ADD CONSTRAINT `controldecultivo_ibfk_2` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  ADD CONSTRAINT `controldecultivo_ibfk_3` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`);

--
-- Filtros para la tabla `cultivo`
--
ALTER TABLE `cultivo`
  ADD CONSTRAINT `cultivo_ibfk_1` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  ADD CONSTRAINT `cultivo_ibfk_2` FOREIGN KEY (`IDlote`) REFERENCES `lote` (`IDlote`);

--
-- Filtros para la tabla `ingresoegreso`
--
ALTER TABLE `ingresoegreso`
  ADD CONSTRAINT `ingresoegreso_ibfk_1` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`);

--
-- Filtros para la tabla `movimientoinventario`
--
ALTER TABLE `movimientoinventario`
  ADD CONSTRAINT `movimientoinventario_ibfk_1` FOREIGN KEY (`IDmateriales`) REFERENCES `materiales` (`IDmateriales`),
  ADD CONSTRAINT `movimientoinventario_ibfk_2` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`);

--
-- Filtros para la tabla `produccion`
--
ALTER TABLE `produccion`
  ADD CONSTRAINT `produccion_ibfk_1` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`),
  ADD CONSTRAINT `produccion_ibfk_2` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`);

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`),
  ADD CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  ADD CONSTRAINT `seguimiento_ibfk_3` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
