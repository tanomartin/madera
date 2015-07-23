-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-07-2015 a las 19:31:51
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

--
-- Volcado de datos para la tabla `cabliquiusimra`
--

INSERT INTO `cabliquiusimra` (`nrorequerimiento`, `fechaliquidacion`, `horaliquidacion`, `liquidacionorigen`, `fechainspeccion`, `deudanominal`, `intereses`, `gtosadmin`, `totalliquidado`, `nroresolucioninspeccion`, `nrocertificadodeuda`, `operadorliquidador`, `liquidacionanulada`, `motivoanulacion`, `fechaanulacion`, `usuarioanulacion`) VALUES
(10, '2014-01-25', '12:15:58', '306834913290913O00019707.xls', NULL, '15859.25', '158.25', '88.28', '89489.25', NULL, NULL, 'Abogado', 0, NULL, NULL, NULL),
(21, '2015-07-23', '19:22:08', '305307051170615U00000021.xls', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(25, '2015-07-23', '19:22:31', '201691416070615U00000025.xls', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL),
(29, '2015-07-23', '19:22:34', '305307051171212U00000029.xls', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
