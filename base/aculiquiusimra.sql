-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-07-2015 a las 19:31:19
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
-- Estructura de tabla para la tabla `aculiquiusimra`
--

CREATE TABLE IF NOT EXISTS `aculiquiusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion OSPIM que da origen a la Liquidacion de Deuda',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro de Acuerdo Caido Incluido en la Liquidacion de Deuda',
  PRIMARY KEY (`nrorequerimiento`,`nroacuerdo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Acuerdos Caidos incluidos en Liquidacion de Deuda de OSPIM';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
