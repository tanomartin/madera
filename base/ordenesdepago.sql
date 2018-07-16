-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-07-2018 a las 17:38:32
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
-- Estructura de tabla para la tabla `ordencabecera`
--

CREATE TABLE IF NOT EXISTS `ordencabecera` (
  `nroordenpago` int(8) NOT NULL AUTO_INCREMENT,
  `codigoprestador` int(4) NOT NULL,
  `fechaorden` date NOT NULL,
  `formapago` char(1) NOT NULL,
  `comprobantepago` char(30) DEFAULT NULL,
  `fechacomprobante` date NOT NULL,
  `retencion` float(8,2) NOT NULL,
  `importe` float(8,2) NOT NULL,
  `idemail` int(6) DEFAULT NULL,
  `fechapago` datetime DEFAULT NULL,
  `fechacancelacion` datetime DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` varchar(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nroordenpago`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Estructura de tabla para la tabla `ordendetalle`
--

CREATE TABLE IF NOT EXISTS `ordendetalle` (
  `nroordenpago` int(8) NOT NULL,
  `idfactura` int(8) NOT NULL,
  `tipocancelacion` char(1) NOT NULL,
  `importepago` float(8,2) NOT NULL,
  `recibo` varchar(11) DEFAULT NULL,
  `asiento` varchar(11) DEFAULT NULL,
  `folio` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`nroordenpago`,`idfactura`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoformadepago`
--

CREATE TABLE IF NOT EXISTS `tipoformadepago` (
  `id` char(2) NOT NULL,
  `descripcion` char(50) NOT NULL,
  `necnum` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipoformadepago`
--

INSERT INTO `tipoformadepago` (`id`, `descripcion`, `necnum`) VALUES
('C', 'Cheque', 1),
('E', 'Efectivo', 0),
('T', 'Transferencia', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
