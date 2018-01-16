-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-01-2018 a las 18:08:58
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
-- Estructura de tabla para la tabla `detcontratoprestador`
--

CREATE TABLE IF NOT EXISTS `detcontratoprestador` (
  `idcontrato` int(4) NOT NULL,
  `idpractica` int(8) NOT NULL,
  `idcategoria` int(3) NOT NULL,
  `moduloconsultorio` decimal(8,2) NOT NULL,
  `modulourgencia` decimal(8,2) NOT NULL,
  `galenohonorario` decimal(8,2) NOT NULL,
  `galenohonorarioespecialista` decimal(8,2) NOT NULL,
  `galenohonorarioayudante` decimal(8,2) NOT NULL,
  `galenohonorarioanestesista` decimal(8,2) NOT NULL,
  `galenogastos` decimal(8,2) NOT NULL,
  `coseguro` decimal(8,2) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  PRIMARY KEY (`idcontrato`,`idpractica`,`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
