-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-01-2018 a las 19:10:30
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
-- Estructura de tabla para la tabla `prestadores`
--

CREATE TABLE IF NOT EXISTS `prestadores` (
  `codigoprestador` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Código identificador de prestador',
  `nombre` text NOT NULL COMMENT 'Nombre o Razón Social del Prestador',
  `domicilio` char(100) NOT NULL,
  `codlocali` int(6) unsigned NOT NULL,
  `idBarrio` int(3) NOT NULL,
  `codprovin` int(2) unsigned NOT NULL,
  `indpostal` char(1) NOT NULL,
  `numpostal` int(4) unsigned NOT NULL,
  `alfapostal` char(3) DEFAULT NULL,
  `telefono1` bigint(10) DEFAULT NULL,
  `ddn1` char(5) DEFAULT NULL,
  `telefono2` bigint(10) DEFAULT NULL,
  `ddn2` char(5) DEFAULT NULL,
  `telefonofax` bigint(10) DEFAULT NULL,
  `ddnfax` char(5) DEFAULT NULL,
  `email1` char(60) DEFAULT NULL,
  `email2` char(60) DEFAULT NULL,
  `cuit` char(11) NOT NULL,
  `situacionfiscal` int(1) NOT NULL,
  `vtoexento` date DEFAULT NULL,
  `personeria` int(1) unsigned DEFAULT NULL COMMENT '1: Fisico - 2: Juridico',
  `tratamiento` int(2) unsigned DEFAULT NULL COMMENT 'Como se llamara al profesional para la cartas o ordenes de pago',
  `matriculanacional` char(10) DEFAULT NULL COMMENT 'Matricula Nacional',
  `matriculaprovincial` char(10) DEFAULT NULL COMMENT 'Matricula Provincial',
  `numeroregistrosss` int(10) DEFAULT NULL COMMENT 'Numero de registro en la Superintendencia de Servicio de Salud',
  `vtoregistrosss` date DEFAULT NULL,
  `numeroregistrosnr` int(10) DEFAULT NULL,
  `vtoregistrosnr` date DEFAULT NULL,
  `capitado` int(1) unsigned NOT NULL COMMENT '1: es capitado - 0: no es capitado',
  `montofijo` int(1) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fehamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`codigoprestador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4332 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
