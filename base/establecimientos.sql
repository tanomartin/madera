-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-01-2018 a las 15:56:19
-- Versión del servidor: 5.6.11-log
-- Versión de PHP: 5.3.27

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `madera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `establecimientos`
--

CREATE TABLE IF NOT EXISTS `establecimientos` (
  `codigo` int(4) NOT NULL AUTO_INCREMENT,
  `codigoprestador` int(4) NOT NULL,
  `nombre` char(100) CHARACTER SET latin1 NOT NULL,
  `domicilio` char(50) CHARACTER SET latin1 NOT NULL,
  `codlocali` int(6) NOT NULL,
  `codprovin` int(2) NOT NULL,
  `indpostal` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `numpostal` int(4) DEFAULT NULL,
  `alfapostal` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `telefono1` bigint(10) DEFAULT NULL,
  `ddn1` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `telefono2` bigint(10) DEFAULT NULL,
  `ddn2` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `telefonofax` bigint(10) DEFAULT NULL,
  `ddnfax` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `email` char(60) CHARACTER SET latin1 DEFAULT NULL,
  `circulo` int(1) NOT NULL,
  `calidad` int(1) NOT NULL DEFAULT '0',
  `fechainiciocalidad` date DEFAULT NULL,
  `fechafincalidad` date DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) CHARACTER SET latin1 NOT NULL,
  `fehamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=swe7 AUTO_INCREMENT=9 ;

--
-- Volcado de datos para la tabla `establecimientos`
--

INSERT INTO `establecimientos` (`codigo`, `codigoprestador`, `nombre`, `domicilio`, `codlocali`, `codprovin`, `indpostal`, `numpostal`, `alfapostal`, `telefono1`, `ddn1`, `telefono2`, `ddn2`, `telefonofax`, `ddnfax`, `email`, `circulo`, `calidad`, `fechainiciocalidad`, `fechafincalidad`, `fecharegistro`, `usuarioregistro`, `fehamodificacion`, `usuariomodificacion`) VALUES
(1, 41, 'Establecimiento', 'No se', 4137, 4, 'X', 5000, '', 0, '', 0, '', 0, '', '', 0, 0, NULL, NULL, '2016-08-17 18:02:08', 'sistemas', '2016-08-18 15:09:41', 'sistemas'),
(2, 45, 'prueba', 'nose', 2, 1, 'C', 1002, '', 0, '', 0, '', 0, '', 'jose@dfs.com.ar', 0, 0, NULL, NULL, '2016-10-25 15:11:37', 'sistemas', '2016-10-25 15:33:58', 'sistemas'),
(3, 45, 'nuevo establecimiento', 'nose quepoern', 4121, 4, 'X', 5000, '', 0, '', 0, '', 0, '', 'email@gmail.com', 0, 0, NULL, NULL, '2016-10-25 15:30:35', 'sistemas', '2016-10-25 15:34:14', 'sistemas'),
(4, 1, 'Estable nombre', 'nose donde ponerlo', 13502, 13, 'S', 3580, '', 0, '', 0, '', 0, '', '', 0, 0, NULL, NULL, '2017-06-15 19:47:02', 'sistemas', '2017-06-15 19:47:02', 'sistemas'),
(5, 45, 'Ciruclo de Profesionales', 'MEDRANO 458', 21035, 1, 'C', 1000, '', 0, '', 0, '', 0, '', '', 1, 0, NULL, NULL, '2017-11-08 18:20:55', 'sistemas', '2017-11-08 18:26:53', 'sistemas'),
(6, 4323, 'Prueba con NULL', '', 0, 0, NULL, NULL, NULL, 488485, '011', NULL, NULL, NULL, NULL, 'prueba@hotmail.com', 0, 0, NULL, NULL, '2017-11-30 18:20:19', 'sistemas', '2017-11-30 18:27:39', 'sistemas'),
(7, 4323, 'Locura', 'SAN SEBASTIAN', 2873, 2, 'B', 6000, NULL, 1581881, '011', 161561651, '011', NULL, NULL, NULL, 0, 0, NULL, NULL, '2017-11-30 18:29:15', 'sistemas', '2017-11-30 18:33:31', 'sistemas'),
(8, 4323, 'No se que poner', 'SENILLOSA 430', 245, 1, 'C', 1424, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, '2017-11-30 18:33:48', 'sistemas', '2017-11-30 18:34:37', 'sistemas');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
