-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-05-2012 a las 20:01:51
-- Versión del servidor: 5.5.20
-- Versión de PHP: 5.3.9

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
-- Estructura de tabla para la tabla `validasospim`
--

CREATE TABLE IF NOT EXISTS `validasospim` (
  `idboleta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de Boleta',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. - En tabla Empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro. de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Nro. de Cuota',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de la Boleta',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control univoco para identificacion de la Boleta',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Validadas' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
