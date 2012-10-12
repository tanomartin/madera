-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 12-10-2012 a las 17:48:39
-- Versión del servidor: 5.1.41
-- Versión de PHP: 5.3.2-1ubuntu4.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `madera`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remitosremesasusimra`
--

CREATE TABLE IF NOT EXISTS `remitosremesasusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta Bancaria',
  `sistemaremesa` char(1) NOT NULL COMMENT 'Sistema de Origen de la Remesa / M Manual - E Electronico',
  `fecharemesa` date NOT NULL COMMENT 'Fecha de la Remesa',
  `nroremesa` int(4) unsigned NOT NULL COMMENT 'Numero de la Remesa',
  `nroremito` int(4) unsigned NOT NULL COMMENT 'Numero del Remito',
  `fecharemito` date NOT NULL COMMENT 'Fecha del Remito',
  `sucursalbanco` char(4) DEFAULT NULL COMMENT 'Sucursal del Banco Nacion',
  `importebruto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Bruto del Remito',
  `importecomision` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Comision Bancaria sobre el Remito',
  `importeneto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Neto del Remito',
  `boletasremito` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad Total de Boletas del Remito',
  `importeboletasaporte` decimal(9,2) DEFAULT NULL COMMENT 'Total de Aportes de las Boletas del Remito',
  `importeboletasrecargo` decimal(9,2) DEFAULT NULL COMMENT 'Total de Recargos de las Boletas del Remito',
  `importeboletasvarios` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos Varios de las Boletas del Remito',
  `importeboletaspagos` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos de las Boletas del Remito',
  `importeboletascuotas` decimal(9,2) DEFAULT NULL COMMENT 'Total de Cuotas de Acuerdos de las Boletas del Remito',
  `importeboletasbruto` decimal(9,2) DEFAULT NULL COMMENT 'Total Bruto de las Boletas del Remito',
  `cantidadboletas` int(5) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Boletas Conciliadas del Remito',
  `nrocontrol` char(14) DEFAULT NULL COMMENT 'Numero de Control univoco para identificacion de la Boleta de pago',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fechaacreditacion` date DEFAULT NULL COMMENT 'Fecha de Acreditacion en la Cuenta',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`codigocuenta`,`sistemaremesa`,`fecharemesa`,`nroremesa`,`nroremito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
