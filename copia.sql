-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: siprof
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aceptaciondetarea`
--

DROP TABLE IF EXISTS `aceptaciondetarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aceptaciondetarea` (
  `IDaceptacion` int NOT NULL AUTO_INCREMENT,
  `Fechadeestado` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','Confirmada','rechazada') DEFAULT NULL,
  `IDasignaciondelabor` int NOT NULL,
  `IDtrabajador` int NOT NULL,
  PRIMARY KEY (`IDaceptacion`),
  KEY `IDasignaciondelabor` (`IDasignaciondelabor`),
  KEY `IDtrabajador` (`IDtrabajador`),
  CONSTRAINT `aceptaciondetarea_ibfk_1` FOREIGN KEY (`IDasignaciondelabor`) REFERENCES `asignaciondelabor` (`IDasignaciondelabor`),
  CONSTRAINT `aceptaciondetarea_ibfk_2` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aceptaciondetarea`
--

LOCK TABLES `aceptaciondetarea` WRITE;
/*!40000 ALTER TABLE `aceptaciondetarea` DISABLE KEYS */;
/*!40000 ALTER TABLE `aceptaciondetarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `asignaciondelabor`
--

DROP TABLE IF EXISTS `asignaciondelabor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `asignaciondelabor` (
  `IDasignaciondelabor` int NOT NULL AUTO_INCREMENT,
  `Descripcionlabor` varchar(255) DEFAULT NULL,
  `Tarea` varchar(255) NOT NULL,
  `Fechainicio` varchar(255) DEFAULT NULL,
  `Fechafin` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','proceso','cancelada') DEFAULT NULL,
  `IDadministrador` int NOT NULL,
  `IDtrabajador` int NOT NULL,
  `IDlote` int NOT NULL,
  PRIMARY KEY (`IDasignaciondelabor`),
  KEY `IDadministrador` (`IDadministrador`),
  KEY `IDtrabajador` (`IDtrabajador`),
  KEY `IDlote` (`IDlote`),
  CONSTRAINT `asignaciondelabor_ibfk_1` FOREIGN KEY (`IDadministrador`) REFERENCES `usuario` (`IDusuario`),
  CONSTRAINT `asignaciondelabor_ibfk_2` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`),
  CONSTRAINT `asignaciondelabor_ibfk_3` FOREIGN KEY (`IDlote`) REFERENCES `lote` (`IDlote`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `asignaciondelabor`
--

LOCK TABLES `asignaciondelabor` WRITE;
/*!40000 ALTER TABLE `asignaciondelabor` DISABLE KEYS */;
INSERT INTO `asignaciondelabor` VALUES (1,'recoger por surcos el cafe maduro','recoleccion de cafe','2026-05-12T13:20','2026-05-12T13:20','pendiente',1,4,1),(2,'buen uso de herrmienta y buena recoleccion','recoleccion de cafe','2026-05-12T21:53','2026-05-12T21:53','pendiente',1,2,1);
/*!40000 ALTER TABLE `asignaciondelabor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `confirmaciondetarea`
--

DROP TABLE IF EXISTS `confirmaciondetarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `confirmaciondetarea` (
  `IDconfirmaciondetarea` int NOT NULL AUTO_INCREMENT,
  `Fechadeconfirmacion` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente','Confirmada','rechazada') DEFAULT NULL,
  `IDasignaciondelabor` int NOT NULL,
  `IDaceptaciondetarea` int NOT NULL,
  PRIMARY KEY (`IDconfirmaciondetarea`),
  KEY `IDasignaciondelabor` (`IDasignaciondelabor`),
  KEY `IDaceptaciondetarea` (`IDaceptaciondetarea`),
  CONSTRAINT `confirmaciondetarea_ibfk_1` FOREIGN KEY (`IDasignaciondelabor`) REFERENCES `asignaciondelabor` (`IDasignaciondelabor`),
  CONSTRAINT `confirmaciondetarea_ibfk_2` FOREIGN KEY (`IDaceptaciondetarea`) REFERENCES `aceptaciondetarea` (`IDaceptacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `confirmaciondetarea`
--

LOCK TABLES `confirmaciondetarea` WRITE;
/*!40000 ALTER TABLE `confirmaciondetarea` DISABLE KEYS */;
/*!40000 ALTER TABLE `confirmaciondetarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `controldecultivo`
--

DROP TABLE IF EXISTS `controldecultivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `controldecultivo` (
  `IDcontroldecultivo` int NOT NULL AUTO_INCREMENT,
  `Tipocontrol` varchar(255) NOT NULL,
  `Valorregistrado` varchar(255) DEFAULT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Estado` enum('abierto','proceso','resuelto') DEFAULT NULL,
  `Fechareporte` varchar(255) DEFAULT NULL,
  `Fechasolucion` varchar(255) DEFAULT NULL,
  `IDcultivo` int NOT NULL,
  `IDfase` int NOT NULL,
  `IDusuario` int NOT NULL,
  PRIMARY KEY (`IDcontroldecultivo`),
  KEY `IDcultivo` (`IDcultivo`),
  KEY `IDfase` (`IDfase`),
  KEY `IDusuario` (`IDusuario`),
  CONSTRAINT `controldecultivo_ibfk_1` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`),
  CONSTRAINT `controldecultivo_ibfk_2` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  CONSTRAINT `controldecultivo_ibfk_3` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `controldecultivo`
--

LOCK TABLES `controldecultivo` WRITE;
/*!40000 ALTER TABLE `controldecultivo` DISABLE KEYS */;
/*!40000 ALTER TABLE `controldecultivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cultivo`
--

DROP TABLE IF EXISTS `cultivo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cultivo` (
  `IDcultivo` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Fechainicio` varchar(255) DEFAULT NULL,
  `Fechacosecha` varchar(255) DEFAULT NULL,
  `Estado` enum('Activo','cosechado','cancelado') DEFAULT NULL,
  `IDfase` int NOT NULL,
  `IDlote` int NOT NULL,
  PRIMARY KEY (`IDcultivo`),
  KEY `IDfase` (`IDfase`),
  KEY `IDlote` (`IDlote`),
  CONSTRAINT `cultivo_ibfk_1` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  CONSTRAINT `cultivo_ibfk_2` FOREIGN KEY (`IDlote`) REFERENCES `lote` (`IDlote`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cultivo`
--

LOCK TABLES `cultivo` WRITE;
/*!40000 ALTER TABLE `cultivo` DISABLE KEYS */;
INSERT INTO `cultivo` VALUES (1,'Cafe caturro','2026-05-13','2026-05-30','Activo',1,2);
/*!40000 ALTER TABLE `cultivo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fase`
--

DROP TABLE IF EXISTS `fase`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fase` (
  `IDfase` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Duracion` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`IDfase`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fase`
--

LOCK TABLES `fase` WRITE;
/*!40000 ALTER TABLE `fase` DISABLE KEYS */;
INSERT INTO `fase` VALUES (1,'Germinacion','El cultivo ya va floreciendo, Ya le salieron sus primeras hojas y raices','30 Dias');
/*!40000 ALTER TABLE `fase` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingresoegreso`
--

DROP TABLE IF EXISTS `ingresoegreso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ingresoegreso` (
  `IDregistro` int NOT NULL AUTO_INCREMENT,
  `Tipo` varchar(50) NOT NULL,
  `Concepto` varchar(255) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Fecha` varchar(255) NOT NULL,
  `IDusuario` int NOT NULL,
  PRIMARY KEY (`IDregistro`),
  KEY `IDusuario` (`IDusuario`),
  CONSTRAINT `ingresoegreso_ibfk_1` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingresoegreso`
--

LOCK TABLES `ingresoegreso` WRITE;
/*!40000 ALTER TABLE `ingresoegreso` DISABLE KEYS */;
/*!40000 ALTER TABLE `ingresoegreso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lote`
--

DROP TABLE IF EXISTS `lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lote` (
  `IDlote` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Ubicacion` varchar(255) DEFAULT NULL,
  `Area` varchar(100) DEFAULT NULL,
  `Estado` enum('Activo','cosechado','cancelado') DEFAULT NULL,
  PRIMARY KEY (`IDlote`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lote`
--

LOCK TABLES `lote` WRITE;
/*!40000 ALTER TABLE `lote` DISABLE KEYS */;
INSERT INTO `lote` VALUES (1,'B','SUR','2','Activo'),(2,'A','Norte','1','Activo');
/*!40000 ALTER TABLE `lote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materiales`
--

DROP TABLE IF EXISTS `materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materiales` (
  `IDmateriales` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Tipo` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Cantidad` int DEFAULT '0',
  `Unidad` varchar(50) DEFAULT NULL,
  `StockMinimo` int DEFAULT '0',
  `Precio` decimal(10,2) DEFAULT NULL,
  `Estado` enum('activo','agotado','inactivo') DEFAULT NULL,
  PRIMARY KEY (`IDmateriales`),
  UNIQUE KEY `Nombre` (`Nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materiales`
--

LOCK TABLES `materiales` WRITE;
/*!40000 ALTER TABLE `materiales` DISABLE KEYS */;
/*!40000 ALTER TABLE `materiales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientoinventario`
--

DROP TABLE IF EXISTS `movimientoinventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientoinventario` (
  `IDmovimiento` int NOT NULL AUTO_INCREMENT,
  `Tipomovimiento` varchar(50) NOT NULL,
  `Cantidad` int NOT NULL,
  `Fecha` varchar(255) NOT NULL,
  `Motivo` varchar(255) DEFAULT NULL,
  `IDmateriales` int NOT NULL,
  `IDusuario` int NOT NULL,
  PRIMARY KEY (`IDmovimiento`),
  KEY `IDmateriales` (`IDmateriales`),
  KEY `IDusuario` (`IDusuario`),
  CONSTRAINT `movimientoinventario_ibfk_1` FOREIGN KEY (`IDmateriales`) REFERENCES `materiales` (`IDmateriales`),
  CONSTRAINT `movimientoinventario_ibfk_2` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientoinventario`
--

LOCK TABLES `movimientoinventario` WRITE;
/*!40000 ALTER TABLE `movimientoinventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientoinventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pago`
--

DROP TABLE IF EXISTS `pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pago` (
  `IDpago` int NOT NULL AUTO_INCREMENT,
  `Fechapago` varchar(255) NOT NULL,
  `Monto` decimal(10,2) NOT NULL,
  `Tipopago` varchar(100) NOT NULL,
  `Estado` enum('Pendiente','pagado') DEFAULT NULL,
  `IDtrabajador` int NOT NULL,
  PRIMARY KEY (`IDpago`),
  KEY `IDtrabajador` (`IDtrabajador`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`IDtrabajador`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pago`
--

LOCK TABLES `pago` WRITE;
/*!40000 ALTER TABLE `pago` DISABLE KEYS */;
INSERT INTO `pago` VALUES (8,'2026-05-13',50000.00,'Jornal','Pendiente',4),(9,'2026-05-13',480000.00,'Jornal','pagado',9);
/*!40000 ALTER TABLE `pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produccion` (
  `IDproduccion` int NOT NULL AUTO_INCREMENT,
  `Fecha` varchar(255) DEFAULT NULL,
  `Cantidad` varchar(255) DEFAULT NULL,
  `Costo` varchar(255) DEFAULT NULL,
  `Tipo` varchar(255) DEFAULT NULL,
  `IDusuario` int NOT NULL,
  `IDcultivo` int NOT NULL,
  PRIMARY KEY (`IDproduccion`),
  KEY `IDusuario` (`IDusuario`),
  KEY `IDcultivo` (`IDcultivo`),
  CONSTRAINT `produccion_ibfk_1` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`),
  CONSTRAINT `produccion_ibfk_2` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produccion`
--

LOCK TABLES `produccion` WRITE;
/*!40000 ALTER TABLE `produccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimiento`
--

DROP TABLE IF EXISTS `seguimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimiento` (
  `IDseguimiento` int NOT NULL AUTO_INCREMENT,
  `Fecharegistro` varchar(255) NOT NULL,
  `Actividad` varchar(255) NOT NULL,
  `Descripcion` varchar(255) DEFAULT NULL,
  `Estado` enum('pendiente',' proceso','finalizado') DEFAULT NULL,
  `FechaProxRevision` varchar(255) DEFAULT NULL,
  `IDcultivo` int NOT NULL,
  `IDfase` int NOT NULL,
  `IDusuario` int NOT NULL,
  PRIMARY KEY (`IDseguimiento`),
  KEY `IDcultivo` (`IDcultivo`),
  KEY `IDfase` (`IDfase`),
  KEY `IDusuario` (`IDusuario`),
  CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`IDcultivo`) REFERENCES `cultivo` (`IDcultivo`),
  CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`IDfase`) REFERENCES `fase` (`IDfase`),
  CONSTRAINT `seguimiento_ibfk_3` FOREIGN KEY (`IDusuario`) REFERENCES `usuario` (`IDusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimiento`
--

LOCK TABLES `seguimiento` WRITE;
/*!40000 ALTER TABLE `seguimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `seguimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `IDusuario` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(255) NOT NULL,
  `Correo` varchar(255) NOT NULL,
  `Celular` varchar(20) DEFAULT NULL,
  `Contrasena` varchar(255) NOT NULL,
  `Niveldeacceso` varchar(50) NOT NULL,
  `Estado` enum('Activo','Inactivo') NOT NULL,
  PRIMARY KEY (`IDusuario`),
  UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'Cristian Alejandro Suaza Ruiz','cristian@gmail.com','3105503596','$2y$10$sq11774pNcq5/h0h8OJZW.mOQPXsnZh3cX3/pz7X0bxKdO5wgFMpG','administrador\r\n\r\n\r\n','Activo'),(2,'Cristian Ramirez','ramirez@gmail.com','3105503567','ra1234','trabajador','Activo'),(3,'Alex Duvan Ramirez','alex@gmail.com','3107678899','$2y$10$pPuy7PTPcBYUzFXJpE21U.WxUYYPJYtL4wyKx99cxNIGP5BeygUDW','trabajador','Activo'),(4,'Alex darley fuentez','darley@gmail.com','3207578999','12345','trabajador','Activo'),(5,'jaime cruz','jaime@gmail.com','3105504587','1234','trabajador','Activo'),(6,'Oscar medina','oscar@gmail.com','3105503596','oscar310550#','trabajador','Activo'),(7,'Jeimar Ruiz','Jeimar18@gmail.com','3227879090','$2y$10$A5BK4C.49lldoluKvgBvIuqu51O8wiLH.u8pZRtbuI3rX7kkyygj6','trabajador','Activo'),(8,'jean ruiz','jean12@gmail.com','3105503596','$2y$10$3KCS0Dx0APR1nuzobRwgwO0iPSU9r0E5Cd3n66yI100VKXBA5J.Vq','trabajador','Activo'),(9,'Jaime Losada','jaime18@gmail.com','3333399997','$2y$10$9ZBRW4Km/AVGj93YKWSS4.IL.OPqbDfwq8Cx/KGmULP.UuCYcuUAS','Mayordomo','Activo');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-13 22:27:20
