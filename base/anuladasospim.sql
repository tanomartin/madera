-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-05-2012 a las 18:09:45
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
-- Estructura de tabla para la tabla `anuladasospim`
--

CREATE TABLE IF NOT EXISTS `anuladasospim` (
  `idboleta` int(10) unsigned NOT NULL,
  `cuit` char(11) NOT NULL,
  `nroacuerdo` int(3) unsigned NOT NULL,
  `nrocuota` int(3) unsigned NOT NULL,
  `importe` decimal(9,2) NOT NULL,
  `nrocontrol` char(14) NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechaanulacion` datetime NOT NULL,
  `usuarioanulacion` char(50) NOT NULL,
  `documentoenmano` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Boleta en posesion de OSPIM al momento de anularla',
  `motivoanulacion` text NOT NULL COMMENT 'Descripcion del motivo de la anulacion',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
