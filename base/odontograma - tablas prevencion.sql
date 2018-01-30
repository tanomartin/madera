-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-01-2018 a las 20:56:29
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
-- Estructura de tabla para la tabla `odontograma`
--

CREATE TABLE IF NOT EXISTS `odontograma` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nroafiliado` int(9) NOT NULL,
  `nroorden` int(3) NOT NULL,
  `idpractica` int(8) NOT NULL,
  `fecha` date NOT NULL,
  `codigopieza` int(2) NOT NULL,
  `idcara` int(1) DEFAULT NULL,
  `codigoprestador` int(4) NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevcancermama`
--

CREATE TABLE IF NOT EXISTS `prevcancermama` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned DEFAULT NULL,
  `codpar` int(2) unsigned DEFAULT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `antecedentes` int(1) unsigned NOT NULL,
  `personaantecedente` int(1) unsigned DEFAULT NULL,
  `examenmamario` int(1) unsigned NOT NULL,
  `ultimoexamenmamario` date DEFAULT NULL,
  `mamografia` int(1) unsigned NOT NULL,
  `ultimamamografia` date DEFAULT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevcanceruterino`
--

CREATE TABLE IF NOT EXISTS `prevcanceruterino` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `antecedentes` int(1) unsigned NOT NULL,
  `personaantecedente` int(1) unsigned DEFAULT NULL,
  `pap` int(1) unsigned NOT NULL,
  `ultimopap` date DEFAULT NULL,
  `colpo` int(1) unsigned NOT NULL,
  `ultimacolpo` date DEFAULT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevdiabetes`
--

CREATE TABLE IF NOT EXISTS `prevdiabetes` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `talla` decimal(3,2) unsigned NOT NULL,
  `peso` decimal(6,3) unsigned NOT NULL,
  `presion` char(7) NOT NULL,
  `cinturaabdominal` decimal(5,2) unsigned NOT NULL,
  `masamuscular` decimal(5,2) unsigned NOT NULL,
  `masacorporal` decimal(5,2) unsigned NOT NULL,
  `antecedentes` int(1) unsigned NOT NULL,
  `personaantecedente` int(1) unsigned DEFAULT NULL,
  `nivelglucemia` int(3) unsigned NOT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevhipertension`
--

CREATE TABLE IF NOT EXISTS `prevhipertension` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `talla` decimal(3,2) unsigned NOT NULL,
  `peso` decimal(6,3) unsigned NOT NULL,
  `presion` char(7) NOT NULL,
  `antecedenteshipertension` int(1) unsigned NOT NULL,
  `personaantecedentehipertension` int(1) unsigned DEFAULT NULL,
  `antecedentescardiacos` int(1) unsigned NOT NULL,
  `personaantecedentecardiaco` int(1) unsigned DEFAULT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevmaternoinfantil`
--

CREATE TABLE IF NOT EXISTS `prevmaternoinfantil` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `nombrepaciente` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `talla` decimal(3,2) unsigned NOT NULL,
  `peso` decimal(6,3) unsigned NOT NULL,
  `perimetrocefalico` decimal(4,2) unsigned NOT NULL,
  `estudiofei` int(1) unsigned DEFAULT NULL,
  `otoemisionesacusticas` int(1) unsigned DEFAULT NULL,
  `fondodeojo` int(1) unsigned DEFAULT NULL,
  `ecocadera` int(1) unsigned DEFAULT NULL,
  `controlnro` int(2) unsigned NOT NULL,
  `vacunasaldia` int(1) unsigned NOT NULL,
  `vacunasfaltantes` text,
  `lactanciamaterna` int(1) unsigned NOT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevodontologica`
--

CREATE TABLE IF NOT EXISTS `prevodontologica` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `fluor` int(1) unsigned NOT NULL,
  `cepillado` int(1) unsigned NOT NULL,
  `fosasyfisuras` text,
  `pasta` text,
  `embarazo` int(1) unsigned NOT NULL,
  `semanaembarazo` int(2) unsigned DEFAULT NULL,
  `consultanro` int(2) unsigned NOT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevprenatal`
--

CREATE TABLE IF NOT EXISTS `prevprenatal` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `talla` decimal(3,2) unsigned NOT NULL,
  `peso` decimal(6,3) unsigned NOT NULL,
  `presion` char(7) NOT NULL,
  `serologia` int(10) unsigned NOT NULL,
  `fum` date NOT NULL,
  `edadgestacional` int(2) unsigned NOT NULL,
  `alturauterina` decimal(4,2) unsigned NOT NULL,
  `fpp` date NOT NULL,
  `gestas` int(2) unsigned DEFAULT NULL,
  `vivos` int(2) unsigned DEFAULT NULL,
  `abortos` int(2) unsigned DEFAULT NULL,
  `controlnro` int(2) unsigned NOT NULL,
  `cantidadecografias` int(2) unsigned NOT NULL,
  `toxoplasmosis` int(1) unsigned NOT NULL,
  `chagas` int(1) unsigned NOT NULL,
  `vdrl` int(1) unsigned NOT NULL,
  `hepatitis` int(1) unsigned NOT NULL,
  `hiv` int(1) unsigned NOT NULL,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prevsaludsexual`
--

CREATE TABLE IF NOT EXISTS `prevsaludsexual` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `delcod` int(4) unsigned NOT NULL,
  `profesional` char(100) NOT NULL,
  `fechaatencion` date NOT NULL,
  `nrcuil` char(11) NOT NULL,
  `nrafil` int(7) unsigned NOT NULL,
  `codpar` int(2) unsigned NOT NULL,
  `nombre` char(60) NOT NULL,
  `ddntelefono` int(5) unsigned DEFAULT NULL,
  `nrotelefono` int(10) unsigned DEFAULT NULL,
  `edad` int(3) unsigned NOT NULL,
  `informacion` int(1) unsigned NOT NULL,
  `metodoanticonceptivo` int(1) unsigned NOT NULL,
  `preservativos` int(1) unsigned DEFAULT NULL,
  `diu` int(1) unsigned DEFAULT NULL,
  `anticonceptivos` int(1) unsigned DEFAULT NULL,
  `motivonoentrega` text,
  `emitediagnostico` int(1) unsigned NOT NULL,
  `diagnostico` text,
  `subdiagnostico` text,
  `observaciones` text,
  `fecharegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fechamodificacion` datetime DEFAULT NULL,
  `descargado` int(1) unsigned NOT NULL DEFAULT '0',
  `eliminado` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
