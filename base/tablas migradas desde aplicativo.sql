-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2015 a las 19:43:12
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
-- Estructura de tabla para la tabla `aniosusimra`
--

CREATE TABLE IF NOT EXISTS `aniosusimra` (
  `anio` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`anio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para habilitacion de años en Aplicativo DDJJ';

--
-- Volcado de datos para la tabla `aniosusimra`
--

INSERT INTO `aniosusimra` (`anio`) VALUES
(2000),
(2001),
(2002),
(2003),
(2004),
(2005),
(2006),
(2007),
(2008),
(2009),
(2010),
(2011),
(2012),
(2013),
(2014),
(2015);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriasusimra`
--

CREATE TABLE IF NOT EXISTS `categoriasusimra` (
  `codram` int(3) NOT NULL DEFAULT '0',
  `codcat` int(3) NOT NULL,
  `descri` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`codram`,`codcat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Categorias de empleados segun rama del Aplicativo DDJJ';

--
-- Volcado de datos para la tabla `categoriasusimra`
--

INSERT INTO `categoriasusimra` (`codram`, `codcat`, `descri`) VALUES
(1, 1, 'VEHICULO 2'),
(1, 2, 'VEHICULO 3'),
(1, 3, 'VEHICULO 4'),
(1, 4, 'PRODUCCION 1'),
(1, 5, 'PRODUCCION 2'),
(1, 6, 'PRODUCCION 3'),
(1, 7, 'PRODUCCION 4'),
(1, 8, 'PRODUCCION 5'),
(1, 9, 'MANT. OF. ESP. 1'),
(1, 10, 'MANT. OF. ESP. 2'),
(1, 11, 'MANT. OF. 1'),
(1, 12, 'MANT. OF. 2'),
(1, 13, 'MANT. MEDIO OF. 1'),
(1, 14, 'SERVICIOS A'),
(1, 15, 'SERVICIOS B'),
(1, 16, 'SERVICIOS C'),
(1, 17, 'SERVICIOS D'),
(1, 18, 'MENORES DE 14 AÑOS'),
(1, 19, 'MENORES DE 15 AÑOS'),
(1, 20, 'MENORES DE 16 AÑOS'),
(1, 21, 'MENORES DE 17 AÑOS'),
(1, 22, 'OTROS'),
(2, 1, 'OFICIAL MULTIPLE'),
(2, 2, 'OFICIAL ESPECIALIZADO'),
(2, 3, 'OFICIAL GENERAL'),
(2, 4, 'OFICIAL ESTANDAR'),
(2, 5, 'MEDIO OFICIAL'),
(2, 6, 'AYUDANTE'),
(2, 7, 'PEON ACT. IND.'),
(2, 8, 'MENORES DE 14 AÑOS'),
(2, 9, 'MENORES DE 15 AÑOS'),
(2, 10, 'MENORES DE 16 AÑOS'),
(2, 11, 'MENORES DE 17 AÑOS'),
(2, 12, 'OTROS'),
(3, 1, 'OFICIAL MULTIPLE'),
(3, 2, 'OFICIAL ESPECIALIZADO'),
(3, 3, 'OFICIAL GENERAL'),
(3, 4, 'OFICIAL ESTANDAR'),
(3, 5, 'MEDIO OFICIAL'),
(3, 6, 'AYUDANTE'),
(3, 7, 'PEON ACT. IND.'),
(3, 8, 'MENORES DE 14 AÑOS'),
(3, 9, 'MENORES DE 15 AÑOS'),
(3, 10, 'MENORES DE 16 AÑOS'),
(3, 11, 'MENORES DE 17 AÑOS'),
(3, 12, 'OTROS'),
(4, 1, 'OFICIAL MULTIPLE'),
(4, 2, 'OFICIAL ESPECIALIZADO'),
(4, 3, 'OFICIAL GENERAL'),
(4, 4, 'MEDIO OFICIAL'),
(4, 5, 'AYUDANTE'),
(4, 6, 'PEON ACT. IND.'),
(4, 7, 'MENORES DE 14 AÑOS'),
(4, 8, 'MENORES DE 15 AÑOS'),
(4, 9, 'MENORES DE 16 AÑOS'),
(4, 10, 'MENORES DE 17 AÑOS'),
(4, 11, 'OTROS'),
(5, 1, 'NO CALIFICADOS'),
(5, 2, 'CALIFICADOS'),
(5, 3, 'REBANEADORES'),
(5, 4, 'MECANICOS Y CHOFERES'),
(5, 5, 'OTROS'),
(6, 1, 'OTROS'),
(13, 1, 'AGLOMERADOS VEHICULO 2'),
(13, 2, 'AGLOMERADOS VEHICULO 3'),
(13, 3, 'AGLOMERADOS VEHICULO 4'),
(13, 4, 'AGLOMERADOS PRODUCCION 1'),
(13, 5, 'AGLOMERADOS PRODUCCION 2'),
(13, 6, 'AGLOMERADOS PRODUCCION 3'),
(13, 7, 'AGLOMERADOS PRODUCCION 4'),
(13, 8, 'AGLOMERADOS PRODUCCION 5'),
(13, 9, 'AGLOMERADOS MANT. OF. ESP. 1'),
(13, 10, 'AGLOMERADOS MANT. OF. ESP. 2'),
(13, 11, 'AGLOMERADOS MANT. OF. 1'),
(13, 12, 'AGLOMERADOS MANT. OF. 2'),
(13, 13, 'AGLOMERADOS MANT. MEDIO OF. 1'),
(13, 14, 'AGLOMERADOS SERVICIOS A'),
(13, 15, 'AGLOMERADOS SERVICIOS B'),
(13, 16, 'AGLOMERADOS SERVICIOS C'),
(13, 17, 'AGLOMERADOS SERVICIOS D'),
(13, 18, 'AGLOMERADOS MENORES DE 14 AÑOS'),
(13, 19, 'AGLOMERADOS MENORES DE 15 AÑOS'),
(13, 20, 'AGLOMERADOS MENORES DE 16 AÑOS'),
(13, 21, 'AGLOMERADOS MENORES DE 17 AÑOS'),
(13, 22, 'ASERRADEROS OFICIAL MULTIPLE'),
(13, 23, 'ASERRADEROS OFICIAL ESPECIALIZADO'),
(13, 24, 'ASERRADEROS OFICIAL GENERAL'),
(13, 25, 'ASERRADEROS OFICIAL ESTANDAR'),
(13, 26, 'ASERRADEROS MEDIO OFICIAL'),
(13, 27, 'ASERRADEROS AYUDANTE'),
(13, 28, 'ASERRADEROS PEON ACT. IND.'),
(13, 29, 'ASERRADEROS MENORES DE 14 AÑOS'),
(13, 30, 'ASERRADEROS MENORES DE 15 AÑOS'),
(13, 31, 'ASERRADEROS MENORES DE 16 AÑOS'),
(13, 32, 'ASERRADEROS MENORES DE 17 AÑOS');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extraordinariosusimra`
--

CREATE TABLE IF NOT EXISTS `extraordinariosusimra` (
  `anio` int(4) unsigned NOT NULL DEFAULT '0',
  `mes` int(2) unsigned NOT NULL DEFAULT '0',
  `relacionmes` int(2) unsigned NOT NULL DEFAULT '0',
  `tipo` int(1) unsigned NOT NULL DEFAULT '0',
  `valor` decimal(7,3) unsigned NOT NULL DEFAULT '0.000',
  `retiene060` int(1) unsigned NOT NULL DEFAULT '0',
  `retiene100` int(1) unsigned NOT NULL DEFAULT '0',
  `retiene150` int(1) unsigned NOT NULL DEFAULT '0',
  `mensaje` text,
  PRIMARY KEY (`anio`,`mes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de periodos para pagos extraordinarios Aplicativo DDJJ';

--
-- Volcado de datos para la tabla `extraordinariosusimra`
--

INSERT INTO `extraordinariosusimra` (`anio`, `mes`, `relacionmes`, `tipo`, `valor`, `retiene060`, `retiene100`, `retiene150`, `mensaje`) VALUES
(2012, 13, 1, 0, '200.000', 0, 0, 1, ''),
(2012, 14, 2, 0, '200.000', 0, 0, 1, ''),
(2012, 15, 4, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 16, 5, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 17, 6, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 18, 7, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 19, 8, 1, '0.080', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 20, 9, 1, '0.080', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 21, 10, 1, '0.080', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 22, 11, 1, '0.080', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2012, 23, 12, 1, '0.075', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2013, 13, 1, 1, '0.075', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2013, 14, 2, 1, '0.075', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2013, 15, 3, 1, '0.075', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2012.'),
(2013, 16, 4, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 17, 5, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 18, 6, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 19, 7, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 20, 8, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 21, 9, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 22, 10, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 23, 11, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2013, 24, 12, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 13, 1, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 14, 2, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 15, 3, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 16, 4, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 17, 5, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Marzo de 2013.'),
(2014, 18, 6, 1, '0.125', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 19, 7, 1, '0.125', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 20, 8, 1, '0.125', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 21, 9, 1, '0.125', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 22, 10, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 23, 11, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 24, 12, 1, '0.100', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2014, 25, 11, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2014, 26, 12, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2014, 27, 12, 2, '100.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 13, 1, 1, '0.070', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2015, 14, 2, 1, '0.070', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2015, 15, 3, 1, '0.070', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2015, 16, 4, 1, '0.070', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2015, 17, 5, 1, '0.070', 1, 1, 1, 'Para el pago del no remunerativo seleccionado, en la columna donde se informa la remuneración para cada C.U.I.L., deberá consignarse como remuneración el resultado del calculo de Cantidad de Horas Trabajadas para el período seleccionado por el Valor Hora Total según la categoría al 31 de Mayo de 2014.'),
(2015, 25, 1, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 26, 2, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 27, 2, 2, '100.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 28, 3, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 29, 4, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 30, 4, 2, '100.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 31, 5, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 32, 6, 2, '50.000', 0, 0, 0, 'Contribucion Extraordinaria'),
(2015, 33, 6, 2, '100.000', 0, 0, 0, 'Contribucion Extraordinaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodosusimra`
--

CREATE TABLE IF NOT EXISTS `periodosusimra` (
  `anio` int(4) unsigned NOT NULL DEFAULT '0',
  `mes` int(2) unsigned NOT NULL DEFAULT '0',
  `descripcion` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`anio`,`mes`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para habilitacion de meses en Aplicativo DDJJ';

--
-- Volcado de datos para la tabla `periodosusimra`
--

INSERT INTO `periodosusimra` (`anio`, `mes`, `descripcion`) VALUES
(2002, 1, 'Enero'),
(2002, 2, 'Febrero'),
(2002, 3, 'Marzo'),
(2002, 4, 'Abril'),
(2002, 5, 'Mayo'),
(2002, 6, 'Junio'),
(2002, 7, 'Julio'),
(2002, 8, 'Agosto'),
(2002, 9, 'Setiembre'),
(2002, 10, 'Octubre'),
(2002, 11, 'Noviembre'),
(2002, 12, 'Diciembre'),
(2003, 1, 'Enero'),
(2003, 2, 'Febrero'),
(2003, 3, 'Marzo'),
(2003, 4, 'Abril'),
(2003, 5, 'Mayo'),
(2003, 6, 'Junio'),
(2003, 7, 'Julio'),
(2003, 8, 'Agosto'),
(2003, 9, 'Setiembre'),
(2003, 10, 'Octubre'),
(2003, 11, 'Noviembre'),
(2003, 12, 'Diciembre'),
(2004, 1, 'Enero'),
(2004, 2, 'Febrero'),
(2004, 3, 'Marzo'),
(2004, 4, 'Abril'),
(2004, 5, 'Mayo'),
(2004, 6, 'Junio'),
(2004, 7, 'Julio'),
(2004, 8, 'Agosto'),
(2004, 9, 'Setiembre'),
(2004, 10, 'Octubre'),
(2004, 11, 'Noviembre'),
(2004, 12, 'Diciembre'),
(2005, 1, 'Enero'),
(2005, 2, 'Febrero'),
(2005, 3, 'Marzo'),
(2005, 4, 'Abril'),
(2005, 5, 'Mayo'),
(2005, 6, 'Junio'),
(2005, 7, 'Julio'),
(2005, 8, 'Agosto'),
(2005, 9, 'Setiembre'),
(2005, 10, 'Octubre'),
(2005, 11, 'Noviembre'),
(2005, 12, 'Diciembre'),
(2006, 1, 'Enero'),
(2006, 2, 'Febrero'),
(2006, 3, 'Marzo'),
(2006, 4, 'Abril'),
(2006, 5, 'Mayo'),
(2006, 6, 'Junio'),
(2006, 7, 'Julio'),
(2006, 8, 'Agosto'),
(2006, 9, 'Setiembre'),
(2006, 10, 'Octubre'),
(2006, 11, 'Noviembre'),
(2006, 12, 'Diciembre'),
(2007, 1, 'Enero'),
(2007, 2, 'Febrero'),
(2007, 3, 'Marzo'),
(2007, 4, 'Abril'),
(2007, 5, 'Mayo'),
(2007, 6, 'Junio'),
(2007, 7, 'Julio'),
(2007, 8, 'Agosto'),
(2007, 9, 'Setiembre'),
(2007, 10, 'Octubre'),
(2007, 11, 'Noviembre'),
(2007, 12, 'Diciembre'),
(2008, 1, 'Enero'),
(2008, 2, 'Febrero'),
(2008, 3, 'Marzo'),
(2008, 4, 'Abril'),
(2008, 5, 'Mayo'),
(2008, 6, 'Junio'),
(2008, 7, 'Julio'),
(2008, 8, 'Agosto'),
(2008, 9, 'Setiembre'),
(2008, 10, 'Octubre'),
(2008, 11, 'Noviembre'),
(2008, 12, 'Diciembre'),
(2009, 1, 'Enero'),
(2009, 2, 'Febrero'),
(2009, 3, 'Marzo'),
(2009, 4, 'Abril'),
(2009, 5, 'Mayo'),
(2009, 6, 'Junio'),
(2009, 7, 'Julio'),
(2009, 8, 'Agosto'),
(2009, 9, 'Setiembre'),
(2009, 10, 'Octubre'),
(2009, 11, 'Noviembre'),
(2009, 12, 'Diciembre'),
(2010, 1, 'Enero'),
(2010, 2, 'Febrero'),
(2010, 3, 'Marzo'),
(2010, 4, 'Abril'),
(2010, 5, 'Mayo'),
(2010, 6, 'Junio'),
(2010, 7, 'Julio'),
(2010, 8, 'Agosto'),
(2010, 9, 'Setiembre'),
(2010, 10, 'Octubre'),
(2010, 11, 'Noviembre'),
(2010, 12, 'Diciembre'),
(2011, 1, 'Enero'),
(2011, 2, 'Febrero'),
(2011, 3, 'Marzo'),
(2011, 4, 'Abril'),
(2011, 5, 'Mayo'),
(2011, 6, 'Junio'),
(2011, 7, 'Julio'),
(2011, 8, 'Agosto'),
(2011, 9, 'Setiembre'),
(2011, 10, 'Octubre'),
(2011, 11, 'Noviembre'),
(2011, 12, 'Diciembre'),
(2012, 1, 'Enero'),
(2012, 2, 'Febrero'),
(2012, 3, 'Marzo'),
(2012, 4, 'Abril'),
(2012, 5, 'Mayo'),
(2012, 6, 'Junio'),
(2012, 7, 'Julio'),
(2012, 8, 'Agosto'),
(2012, 9, 'Setiembre'),
(2012, 10, 'Octubre'),
(2012, 11, 'Noviembre'),
(2012, 12, 'Diciembre'),
(2012, 13, 'No Remunerativo Enero'),
(2012, 14, 'No Remunerativo Febrero'),
(2012, 15, 'No Remunerativo Abril'),
(2012, 16, 'No Remunerativo Mayo'),
(2012, 17, 'No Remunerativo Junio'),
(2012, 18, 'No Remunerativo Julio'),
(2012, 19, 'No Remunerativo Agosto'),
(2012, 20, 'No Remunerativo Setiembre'),
(2012, 21, 'No Remunerativo Octubre'),
(2012, 22, 'No Remunerativo Noviembre'),
(2012, 23, 'No Remunerativo Diciembre'),
(2013, 1, 'Enero'),
(2013, 2, 'Febrero'),
(2013, 3, 'Marzo'),
(2013, 4, 'Abril'),
(2013, 5, 'Mayo'),
(2013, 6, 'Junio'),
(2013, 7, 'Julio'),
(2013, 8, 'Agosto'),
(2013, 9, 'Septiembre'),
(2013, 10, 'Octubre'),
(2013, 11, 'Noviembre'),
(2013, 12, 'Diciembre'),
(2013, 13, 'No Remunerativo Enero'),
(2013, 14, 'No Remunerativo Febrero'),
(2013, 15, 'No Remunerativo Marzo'),
(2013, 16, 'No Remunerativo Abril'),
(2013, 17, 'No Remunerativo Mayo'),
(2013, 18, 'No Remunerativo Junio'),
(2013, 19, 'No Remunerativo Julio'),
(2013, 20, 'No Remunerativo Agosto'),
(2013, 21, 'No Remunerativo Septiembre'),
(2013, 22, 'No Remunerativo Octubre'),
(2013, 23, 'No Remunerativo Noviembre'),
(2013, 24, 'No Remunerativo Diciembre'),
(2014, 1, 'Enero'),
(2014, 2, 'Febrero'),
(2014, 3, 'Marzo'),
(2014, 4, 'Abril'),
(2014, 5, 'Mayo'),
(2014, 6, 'Junio'),
(2014, 7, 'Julio'),
(2014, 8, 'Agosto'),
(2014, 9, 'Septiembre'),
(2014, 10, 'Octubre'),
(2014, 11, 'Noviembre'),
(2014, 12, 'Diciembre'),
(2014, 13, 'No Remunerativo Enero'),
(2014, 14, 'No Remunerativo Febrero'),
(2014, 15, 'No Remunerativo Marzo'),
(2014, 16, 'No Remunerativo Abril'),
(2014, 17, 'No Remunerativo Mayo'),
(2014, 18, 'No Remunerativo Junio'),
(2014, 19, 'No Remunerativo Julio'),
(2014, 20, 'No Remunerativo Agosto'),
(2014, 21, 'No Remunerativo Septiembre'),
(2014, 22, 'No Remunerativo Octubre'),
(2014, 23, 'No Remunerativo Noviembre'),
(2014, 24, 'No Remunerativo Diciembre'),
(2014, 25, 'Cuota Nro 1 Mensual (Nov)'),
(2014, 26, 'Cuota Nro 2 Mensual (Dic)'),
(2014, 27, 'Cuota Nro 1 Bimestral (Dic)'),
(2015, 1, 'Enero'),
(2015, 2, 'Febrero'),
(2015, 3, 'Marzo'),
(2015, 4, 'Abril'),
(2015, 5, 'Mayo'),
(2015, 13, 'No Remunerativo Enero'),
(2015, 14, 'No Remunerativo Febrero'),
(2015, 15, 'No Remunerativo Marzo'),
(2015, 16, 'No Remunerativo Abril'),
(2015, 17, 'No Remunerativo Mayo'),
(2015, 25, 'Cuota Nro 3 Mensual (Ene)'),
(2015, 26, 'Cuota Nro 4 Mensual (Feb)'),
(2015, 27, 'Cuota Nro 2 Bimestral (Feb)'),
(2015, 28, 'Cuota Nro 5 Mensual (Mar)'),
(2015, 29, 'Cuota Nro 6 Mensual (Abr)'),
(2015, 30, 'Cuota Nro 3 Bimestral (Abr)'),
(2015, 31, 'Cuota Nro 7 Mensual (May)'),
(2015, 32, 'Cuota Nro 8 Mensual (Jun)'),
(2015, 33, 'Cuota Nro 4 Bimestral (Jun)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ramausimra`
--

CREATE TABLE IF NOT EXISTS `ramausimra` (
  `id` int(2) unsigned NOT NULL,
  `descripcion` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Ramas de empresas del Aplicativo DDJJ';

--
-- Volcado de datos para la tabla `ramausimra`
--

INSERT INTO `ramausimra` (`id`, `descripcion`) VALUES
(1, 'AGLOMERADOS'),
(2, 'MADERAS TERCIADAS'),
(3, 'ASERRADEROS, ENVASES Y AFINES'),
(4, 'MUEBLES, ABERTURAS, CARPINTERIAS Y DEMAS MANUFACTURAS DE MADERAS Y AFINES'),
(5, 'CORCHO'),
(6, 'OTROS'),
(13, 'AGLOMERADOS / ASERRADEROS, ENVASES Y AFINES');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
