-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-11-2016 a las 15:36:07
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
-- Estructura de tabla para la tabla `aculiquiospim`
--

CREATE TABLE IF NOT EXISTS `aculiquiospim` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion OSPIM que da origen a la Liquidacion de Deuda',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro de Acuerdo Caido Incluido en la Liquidacion de Deuda',
  PRIMARY KEY (`nrorequerimiento`,`nroacuerdo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Acuerdos Caidos incluidos en Liquidacion de Deuda de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aculiquiusimra`
--

CREATE TABLE IF NOT EXISTS `aculiquiusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion OSPIM que da origen a la Liquidacion de Deuda',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro de Acuerdo Caido Incluido en la Liquidacion de Deuda',
  PRIMARY KEY (`nrorequerimiento`,`nroacuerdo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Acuerdos Caidos incluidos en Liquidacion de Deuda de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afipconceptos`
--

CREATE TABLE IF NOT EXISTS `afipconceptos` (
  `concepto` char(6) NOT NULL COMMENT 'Codigo de Concepto',
  `descripcion` text NOT NULL COMMENT 'Descripcion',
  `contraconcepto` char(6) DEFAULT NULL COMMENT 'Codigo de Contraconcepto',
  `debitocredito` char(1) NOT NULL COMMENT 'Indicador Débito / Crédito',
  PRIMARY KEY (`concepto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='AFIP - Códigos de Conceptos de Transferencias a Obras Sociales';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afipddjj`
--

CREATE TABLE IF NOT EXISTS `afipddjj` (
  `nrodisco` int(5) unsigned NOT NULL,
  `nroregistro` int(6) unsigned NOT NULL,
  `origeninfo` char(1) DEFAULT NULL,
  `codobrasocial` char(6) DEFAULT NULL,
  `anoddjj` int(4) unsigned NOT NULL,
  `mesddjj` int(2) unsigned NOT NULL,
  `cuit` char(11) NOT NULL,
  `cuil` char(11) NOT NULL,
  `remundeclarada` decimal(12,2) NOT NULL,
  `importeosadicional` decimal(12,2) NOT NULL,
  `familiares` int(2) unsigned DEFAULT NULL,
  `adherentes` int(2) unsigned DEFAULT NULL,
  `secuenciapresentacion` int(3) unsigned DEFAULT NULL COMMENT 'Secuencia de Presentacion de DDJJ',
  `aporteosadicional` decimal(12,2) DEFAULT NULL,
  `remundecreto` decimal(12,2) DEFAULT NULL,
  `unificaesposa` int(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`nrodisco`,`nroregistro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Informacion original de AFIP de declaraciones juradas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afipmensajes`
--

CREATE TABLE IF NOT EXISTS `afipmensajes` (
  `nromensaje` int(9) unsigned NOT NULL COMMENT 'Nro de mensaje en el buzon de cuenta POP3',
  `fechaemailafip` datetime NOT NULL COMMENT 'Fecha de recepcion del email en la cuenta',
  `cuentaderecepcion` char(150) NOT NULL COMMENT 'Cuenta POP3 asociada para la recepcion del email',
  `tipoarchivo` char(20) NOT NULL COMMENT 'Tipo de archivo indicador del proceso efectuado',
  `nrodisco` int(6) unsigned NOT NULL COMMENT 'Nro de Disco/Archivo Asignado por el Procesamiento en OSPIM',
  PRIMARY KEY (`nromensaje`,`fechaemailafip`,`cuentaderecepcion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Identificacion de Mensajes de AFIP para Procesamiento de Archivos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afippadrones`
--

CREATE TABLE IF NOT EXISTS `afippadrones` (
  `nrodisco` int(6) unsigned NOT NULL COMMENT 'Nro. de Disco',
  `nroregistro` int(6) unsigned NOT NULL COMMENT 'Nro. de Registro',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `nombre` char(50) NOT NULL COMMENT 'Apellido y Nombre o Razon Social',
  `calle` char(20) NOT NULL COMMENT 'Domicilio - Calle',
  `numero` int(6) unsigned NOT NULL COMMENT 'Domicilio - Nro.',
  `piso` char(3) DEFAULT NULL COMMENT 'Domicilio - Piso',
  `depto` char(3) DEFAULT NULL COMMENT 'Domicilio - Departamento',
  `localidad` char(20) NOT NULL COMMENT 'Localidad',
  `provincia` int(3) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `codigopostal` int(4) unsigned NOT NULL COMMENT 'Codigo Postal Numerico',
  `vacios1` char(4) DEFAULT NULL COMMENT 'Campo Vacio no Identificado',
  `codobrasocial` char(6) DEFAULT NULL COMMENT 'Nro. de R.N.O.S de la Obra Social',
  PRIMARY KEY (`nrodisco`,`nroregistro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Informacion Original de AFIP - Padron de CUITs de DDJJ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afipprocesadas`
--

CREATE TABLE IF NOT EXISTS `afipprocesadas` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Año del Pago',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `concepto` char(6) NOT NULL COMMENT 'Concepto de la Transferencia',
  `fechapago` date NOT NULL COMMENT 'Fecha del Pago',
  `debitocredito` char(1) NOT NULL COMMENT 'Indicador de Debito o Credito',
  `fechaprocesoafip` date NOT NULL COMMENT 'Fecha de Procesamiento AFIP',
  `importe` decimal(15,2) unsigned NOT NULL COMMENT 'Importe Del Pago',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`concepto`,`fechapago`,`debitocredito`,`fechaprocesoafip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Proceso Agrupador de AFIP Transferencias por Pagos de Empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afiptransferencias`
--

CREATE TABLE IF NOT EXISTS `afiptransferencias` (
  `nrodisco` int(6) unsigned NOT NULL COMMENT 'Nro. de Disco',
  `nroregistro` int(6) unsigned NOT NULL COMMENT 'Nro. de Registro',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Año del Pago',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `concepto` char(6) NOT NULL COMMENT 'Concepto de la Transferencia',
  `fechapago` date NOT NULL COMMENT 'Fecha del Pago',
  `importe` decimal(15,2) unsigned NOT NULL COMMENT 'Importe Del Pago',
  `debitocredito` char(1) NOT NULL COMMENT 'Indicador de Debito o Credito',
  `porcenreduccion` decimal(5,2) unsigned NOT NULL COMMENT 'Porcentaje de Reduccion para Zonas Especiales',
  `cuil` char(11) NOT NULL COMMENT 'C.U.I.L. del Empleado',
  `familiares` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Familiares de los Empleados',
  `adherentes` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Adherentes de los Empleados',
  `numeroobligacion` char(12) DEFAULT NULL COMMENT 'Numero de Obligacion de la Presentacion ante AFIP',
  `secuenciapresentacion` char(3) NOT NULL COMMENT 'Secuencia de la Presentacion ante AFIP',
  `codigobanco` char(3) NOT NULL COMMENT 'Codigo del Banco de la Presentacion',
  `codigosucursal` char(3) NOT NULL COMMENT 'Codigo de Sucursal del Banco de la Presentacion',
  `codigozona` char(2) DEFAULT NULL COMMENT 'Codigo de Zonas Especiales',
  `fechaprocesoafip` date NOT NULL COMMENT 'Fecha de Procesamiento AFIP',
  PRIMARY KEY (`nrodisco`,`nroregistro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Informacion original de AFIP de Transferencias por Pagos de Empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agrufiscalizospim`
--

CREATE TABLE IF NOT EXISTS `agrufiscalizospim` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `anoddjj` int(4) unsigned NOT NULL COMMENT 'Año de la DDJJ',
  `mesddjj` int(4) unsigned NOT NULL COMMENT 'Mes de la DDJJ',
  `cantcuilmenor240` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion menor a 240 pesos',
  `remucuilmenor1001` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones Menores a 1001 pesos',
  `cantcuilmenor1001` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion menor a 1001 pesos',
  `remuadhemenor1001` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones menores a 1001 pesos con Adherentes',
  `cantadhemenor1001` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Adherentes de Cuiles con Remuneracion menor a 1001 pesos',
  `remucuilmayor1000` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones Mayores a 1000 pesos',
  `cantcuilmayor1000` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion mayor a 1000 pesos',
  `remuadhemayor1000` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones mayores a 1000 pesos con Adherentes',
  `cantadhemayor1000` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Adherentes de Cuiles con Remuneracion mayor a 1000 pesos',
  PRIMARY KEY (`cuit`,`anoddjj`,`mesddjj`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Agrupamiento de Valores para el Detalle de Fiscalizacion de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agrufiscalizusimra`
--

CREATE TABLE IF NOT EXISTS `agrufiscalizusimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `anoddjj` int(4) unsigned NOT NULL COMMENT 'Año de la DDJJ',
  `mesddjj` int(4) unsigned NOT NULL COMMENT 'Mes de la DDJJ',
  `cantcuilmenor240` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion menor a 240 pesos',
  `remucuilmenor1001` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones Menores a 1001 pesos',
  `cantcuilmenor1001` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion menor a 1001 pesos',
  `remuadhemenor1001` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones menores a 1001 pesos con Adherentes',
  `cantadhemenor1001` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Adherentes de Cuiles con Remuneracion menor a 1001 pesos',
  `remucuilmayor1000` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones Mayores a 1000 pesos',
  `cantcuilmayor1000` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Cuiles con Remumeracion mayor a 1000 pesos',
  `remuadhemayor1000` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Totalizador de Remuneraciones mayores a 1000 pesos con Adherentes',
  `cantadhemayor1000` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad de Adherentes de Cuiles con Remuneracion mayor a 1000 pesos',
  PRIMARY KEY (`cuit`,`anoddjj`,`mesddjj`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Agrupamiento de Valores para el Detalle de Fiscalizacion de USIMRA';

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
  `documentoenmano` int(1) unsigned NOT NULL COMMENT 'Boleta en posesion de OSPIM al momento de anularla',
  `motivoanulacion` text NOT NULL COMMENT 'Descripcion del motivo de la anulacion',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuladasusimra`
--

CREATE TABLE IF NOT EXISTS `anuladasusimra` (
  `idboleta` int(10) unsigned NOT NULL,
  `cuit` char(11) NOT NULL,
  `nroacuerdo` int(3) unsigned NOT NULL,
  `nrocuota` int(3) unsigned NOT NULL,
  `importe` decimal(9,2) NOT NULL,
  `nrocontrol` char(14) NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechaanulacion` datetime NOT NULL,
  `usuarioanulacion` char(50) NOT NULL,
  `documentoenmano` int(1) unsigned NOT NULL COMMENT 'Boleta en posesion de USIMRA al momento de anularla',
  `motivoanulacion` text NOT NULL COMMENT 'Descripcion del motivo de la anulacion',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apor060usimra`
--

CREATE TABLE IF NOT EXISTS `apor060usimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Total del Art. 32 Aporte 0.60%',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle del Pago de Seguro de Vida y Sepelio USIMRA - Articulo 32 Aporte 0.60%';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apor100usimra`
--

CREATE TABLE IF NOT EXISTS `apor100usimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Total del Art. 32 Bis Contribucion 1.00%',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle del Pago de Seguro de Vida y Sepelio USIMRA - Articulo 32 Bis Contr. 1.00%';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apor150usimra`
--

CREATE TABLE IF NOT EXISTS `apor150usimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Total del Art. 32 Bis Aporte 1.50%',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle del Pago de Seguro de Vida y Sepelio USIMRA - Articulo 32 Bis Aporte 1.50%';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aporcontroldescarga`
--

CREATE TABLE IF NOT EXISTS `aporcontroldescarga` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `usuariodescarga` char(50) NOT NULL,
  `fechadescarga` datetime NOT NULL,
  `cantidadddjj` int(4) unsigned DEFAULT NULL,
  `cantidadactivos` int(4) DEFAULT NULL,
  `cantidadinactivos` int(4) DEFAULT NULL,
  `nrocontrol` char(14) DEFAULT NULL,
  `cantidadempresas` int(4) unsigned DEFAULT NULL,
  `cantidadtitulares` int(4) unsigned DEFAULT NULL,
  `cantidadfamiliares` int(4) unsigned DEFAULT NULL,
  `cantidadtitularesbaja` int(4) unsigned DEFAULT NULL,
  `cantidadfamiliaresbaja` int(4) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=123 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asesoreslegales`
--

CREATE TABLE IF NOT EXISTS `asesoreslegales` (
  `codigo` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo Para el Asesor Legal',
  `apeynombre` char(100) NOT NULL COMMENT 'Apellido y Nombre del Asesor Legal',
  `codidelega` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`codigo`,`codidelega`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Codificadora de Asesores Legales' AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizaciondocumento`
--

CREATE TABLE IF NOT EXISTS `autorizaciondocumento` (
  `nrosolicitud` int(9) unsigned NOT NULL,
  `documentofinal` mediumblob NOT NULL,
  PRIMARY KEY (`nrosolicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Almacena el documento final de solicitudes autorizadas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autorizaciones`
--

CREATE TABLE IF NOT EXISTS `autorizaciones` (
  `nrosolicitud` int(9) unsigned NOT NULL,
  `codidelega` int(4) unsigned NOT NULL DEFAULT '0',
  `fechasolicitud` date NOT NULL DEFAULT '0000-00-00',
  `cuil` varchar(11) NOT NULL DEFAULT '',
  `nroafiliado` int(7) unsigned DEFAULT NULL,
  `codiparentesco` int(2) DEFAULT NULL,
  `apellidoynombre` varchar(60) NOT NULL DEFAULT '',
  `practica` int(1) unsigned DEFAULT NULL,
  `material` int(1) unsigned DEFAULT NULL,
  `tipomaterial` int(2) unsigned DEFAULT '0',
  `medicamento` int(1) unsigned DEFAULT NULL,
  `pedidomedico` mediumblob,
  `resumenhc` mediumblob,
  `avalsolicitud` mediumblob,
  `presupuesto1` mediumblob,
  `aprobado1` int(1) unsigned DEFAULT '0' COMMENT 'Presupuesto Aprobado en la Autorizacion',
  `presupuesto2` mediumblob,
  `aprobado2` int(1) unsigned DEFAULT '0' COMMENT 'Presupuesto Aprobado en la Autorizacion',
  `presupuesto3` mediumblob,
  `aprobado3` int(1) unsigned DEFAULT '0' COMMENT 'Presupuesto Aprobado en la Autorizacion',
  `presupuesto4` mediumblob,
  `aprobado4` int(1) unsigned DEFAULT '0' COMMENT 'Presupuesto Aprobado en la Autorizacion',
  `presupuesto5` mediumblob,
  `aprobado5` int(1) unsigned DEFAULT '0' COMMENT 'Presupuesto Aprobado en la Autorizacion',
  `statusverificacion` int(1) DEFAULT NULL,
  `fechaverificacion` datetime DEFAULT NULL,
  `usuarioverificacion` char(50) DEFAULT NULL,
  `consultasssverificacion` mediumblob,
  `rechazoverificacion` text,
  `fechaemailautoriza` datetime DEFAULT NULL,
  `statusautorizacion` int(1) DEFAULT NULL,
  `fechapidereverificacion` datetime DEFAULT NULL,
  `usuariopidereverificacion` char(50) DEFAULT NULL,
  `motivopidereverificacion` text,
  `fechaemailreverificacion` datetime DEFAULT NULL,
  `fechaautorizacion` datetime DEFAULT NULL,
  `usuarioautorizacion` char(50) DEFAULT NULL,
  `clasificacionape` int(1) DEFAULT NULL,
  `fechaemailape` datetime DEFAULT NULL,
  `rechazoautorizacion` text,
  `fechaemaildelega` datetime DEFAULT NULL,
  `emailprestador` char(100) DEFAULT NULL,
  `fechaemailprestador` datetime DEFAULT NULL,
  `montoautorizacion` decimal(9,2) unsigned DEFAULT '0.00' COMMENT 'Monto Autorizado para la Solicitud',
  `usuariodescarga` char(50) DEFAULT NULL,
  `fechadescarga` datetime DEFAULT NULL,
  PRIMARY KEY (`nrosolicitud`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Solicitudes de autorizacion de las delegaciones';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banacuerdosospim`
--

CREATE TABLE IF NOT EXISTS `banacuerdosospim` (
  `nromovimiento` int(6) unsigned NOT NULL COMMENT 'Numero de Movimiento en el Banco',
  `sucursalorigen` char(4) NOT NULL COMMENT 'Sucursal de Origen del Movimiento',
  `fecharecaudacion` date NOT NULL COMMENT 'Fecha en que se Deposita',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que Acredita el Banco',
  `estadomovimiento` char(1) NOT NULL COMMENT 'Tipo/Status del Movimiento',
  `sucursalbcra` char(4) NOT NULL COMMENT 'Sucursal del Banco Central',
  `codigomovimiento` int(2) unsigned NOT NULL COMMENT 'Codigo de Movimiento',
  `importe` decimal(15,2) NOT NULL COMMENT 'Importe Acreditado',
  `moneda` int(1) unsigned NOT NULL COMMENT 'Moneda del Deposito',
  `codigobarra` char(80) NOT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En Tabla Empresas',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Univoco para Identificacion de la Boleta',
  `chequebanco` int(4) unsigned DEFAULT NULL COMMENT 'Codigo del Banco del Cheque',
  `chequesucursal` int(4) unsigned DEFAULT NULL COMMENT 'Codigo de Sucursal del Banco del Cheque',
  `chequenro` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Cheque',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha en que se carga el Registro en la Tabla',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que carga el Registro en la Tabla',
  `fechavalidacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Validacion de la Boleta',
  `usuariovalidacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Validacion de la Boleta',
  `fechaimputacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Imputacion del Pago en la Tabla cuoacuerdosospim',
  `usuarioimputacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Imputacion del Pago en la Tabla cuoacuerdosospim',
  PRIMARY KEY (`nromovimiento`,`sucursalorigen`,`fecharecaudacion`,`fechaacreditacion`,`estadomovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banacuerdosusimra`
--

CREATE TABLE IF NOT EXISTS `banacuerdosusimra` (
  `nromovimiento` int(6) unsigned NOT NULL COMMENT 'Numero de Movimiento en el Banco',
  `sucursalorigen` char(4) NOT NULL COMMENT 'Sucursal de Origen del Movimiento',
  `fecharecaudacion` date NOT NULL COMMENT 'Fecha en que se Deposita',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que Acredita el Banco',
  `estadomovimiento` char(1) NOT NULL COMMENT 'Tipo/Status del Movimiento',
  `sucursalbcra` char(4) NOT NULL COMMENT 'Sucursal del Banco Central',
  `codigomovimiento` int(2) unsigned NOT NULL COMMENT 'Codigo de Movimiento',
  `importe` decimal(15,2) NOT NULL COMMENT 'Importe Acreditado',
  `moneda` int(1) unsigned NOT NULL COMMENT 'Moneda del Deposito',
  `codigobarra` char(80) NOT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En Tabla Empresas',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Univoco para Identificacion de la Boleta',
  `chequebanco` int(4) unsigned DEFAULT NULL COMMENT 'Codigo del Banco del Cheque',
  `chequesucursal` int(4) unsigned DEFAULT NULL COMMENT 'Codigo de Sucursal del Banco del Cheque',
  `chequenro` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Cheque',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha en que se carga el Registro en la Tabla',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que carga el Registro en la Tabla',
  `fechavalidacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Validacion de la Boleta',
  `usuariovalidacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Validacion de la Boleta',
  `fechaimputacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Imputacion del Pago en la Tabla cuoacuerdosusimra',
  `usuarioimputacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Imputacion del Pago en la Tabla cuoacuerdosusimra',
  PRIMARY KEY (`nromovimiento`,`sucursalorigen`,`fecharecaudacion`,`fechaacreditacion`,`estadomovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banaportesusimra`
--

CREATE TABLE IF NOT EXISTS `banaportesusimra` (
  `nromovimiento` int(6) unsigned NOT NULL COMMENT 'Numero de Movimiento en el Banco',
  `sucursalorigen` char(4) NOT NULL COMMENT 'Sucursal de Origen del Movimiento',
  `fecharecaudacion` date NOT NULL COMMENT 'Fecha en que se Deposita',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que Acredita el Banco',
  `estadomovimiento` char(1) NOT NULL COMMENT 'Tipo/Status del Movimiento',
  `sucursalbcra` char(4) NOT NULL COMMENT 'Sucursal del Banco Central',
  `codigomovimiento` int(2) unsigned NOT NULL COMMENT 'Codigo de Movimiento',
  `importe` decimal(15,2) NOT NULL COMMENT 'Importe Acreditado',
  `moneda` int(1) unsigned NOT NULL COMMENT 'Moneda del Deposito',
  `codigobarra` char(80) NOT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En Tabla Empresas',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Univoco para Identificacion de la Boleta',
  `chequebanco` int(4) unsigned DEFAULT NULL COMMENT 'Codigo del Banco del Cheque',
  `chequesucursal` int(4) unsigned DEFAULT NULL COMMENT 'Codigo de Sucursal del Banco del Cheque',
  `chequenro` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Cheque',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha en que se carga el Registro en la Tabla',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que carga el Registro en la Tabla',
  `fechavalidacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Validacion de la Boleta',
  `usuariovalidacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Validacion de la Boleta',
  `fechaimputacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Imputacion del Pago en la Tabla seguvidausimra',
  `usuarioimputacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Imputacion del Pago en la Tabla seguvidausimra',
  PRIMARY KEY (`nromovimiento`,`sucursalorigen`,`fecharecaudacion`,`fechaacreditacion`,`estadomovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banextraordinariausimra`
--

CREATE TABLE IF NOT EXISTS `banextraordinariausimra` (
  `nromovimiento` int(6) unsigned NOT NULL COMMENT 'Numero de Movimiento en el Banco',
  `sucursalorigen` char(4) NOT NULL COMMENT 'Sucursal de Origen del Movimiento',
  `fecharecaudacion` date NOT NULL COMMENT 'Fecha en que se Deposita',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que Acredita el Banco',
  `estadomovimiento` char(1) NOT NULL COMMENT 'Tipo/Status del Movimiento',
  `sucursalbcra` char(4) NOT NULL COMMENT 'Sucursal del Banco Central',
  `codigomovimiento` int(2) unsigned NOT NULL COMMENT 'Codigo de Movimiento',
  `importe` decimal(15,2) NOT NULL COMMENT 'Importe Acreditado',
  `moneda` int(1) unsigned NOT NULL COMMENT 'Moneda del Deposito',
  `codigobarra` char(80) NOT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En Tabla Empresas',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Univoco para Identificacion de la Boleta',
  `chequebanco` int(4) unsigned DEFAULT NULL COMMENT 'Codigo del Banco del Cheque',
  `chequesucursal` int(4) unsigned DEFAULT NULL COMMENT 'Codigo de Sucursal del Banco del Cheque',
  `chequenro` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Cheque',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha en que se carga el Registro en la Tabla',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que carga el Registro en la Tabla',
  `fechavalidacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Validacion de la Boleta',
  `usuariovalidacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Validacion de la Boleta',
  `fechaimputacion` datetime DEFAULT NULL COMMENT 'Fecha del Proceso de Imputacion del Pago en la Tabla seguvidausimra',
  `usuarioimputacion` char(50) DEFAULT NULL COMMENT 'Usuario que Genera el Proceso de Imputacion del Pago en la Tabla seguvidausimra',
  PRIMARY KEY (`nromovimiento`,`sucursalorigen`,`fecharecaudacion`,`fechaacreditacion`,`estadomovimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `barrios`
--

CREATE TABLE IF NOT EXISTS `barrios` (
  `id` int(3) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bkuptransferencias`
--

CREATE TABLE IF NOT EXISTS `bkuptransferencias` (
  `nrodisco` int(6) unsigned NOT NULL COMMENT 'Nro. de Disco',
  `nroregistro` int(6) unsigned NOT NULL COMMENT 'Nro. de Registro',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Año del Pago',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `concepto` char(6) NOT NULL COMMENT 'Concepto de la Transferencia',
  `fechapago` date NOT NULL COMMENT 'Fecha del Pago',
  `importe` decimal(15,2) unsigned NOT NULL COMMENT 'Importe Del Pago',
  `debitocredito` char(1) NOT NULL COMMENT 'Indicador de Debito o Credito',
  `porcenreduccion` decimal(5,2) unsigned NOT NULL COMMENT 'Porcentaje de Reduccion para Zonas Especiales',
  `cuil` char(11) NOT NULL COMMENT 'C.U.I.L. del Empleado',
  `familiares` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Familiares de los Empleados',
  `adherentes` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Adherentes de los Empleados',
  `numeroobligacion` char(12) DEFAULT NULL COMMENT 'Numero de Obligacion de la Presentacion ante AFIP',
  `secuenciapresentacion` char(3) NOT NULL COMMENT 'Secuencia de la Presentacion ante AFIP',
  `codigobanco` char(3) NOT NULL COMMENT 'Codigo del Banco de la Presentacion',
  `codigosucursal` char(3) NOT NULL COMMENT 'Codigo de Sucursal del Banco de la Presentacion',
  `codigozona` char(2) DEFAULT NULL COMMENT 'Codigo de Zonas Especiales',
  `fechaprocesoafip` date NOT NULL COMMENT 'Fecha de Procesamiento AFIP',
  PRIMARY KEY (`nrodisco`,`nroregistro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Historico de Informacion original de AFIP de Transferencias por Pagos de Empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletasospim`
--

CREATE TABLE IF NOT EXISTS `boletasospim` (
  `idboleta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de Boleta',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. - En tabla Empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro. de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Nro. de Cuota',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de la Boleta',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control univoco para identificacion de la Boleta',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Generadas' AUTO_INCREMENT=5038 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `boletasusimra`
--

CREATE TABLE IF NOT EXISTS `boletasusimra` (
  `idboleta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de Boleta',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. - En tabla Empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro. de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Nro. de Cuota',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de la Boleta',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control univoco para identificacion de la Boleta',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Generadas' AUTO_INCREMENT=31865 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabacuerdosospim`
--

CREATE TABLE IF NOT EXISTS `cabacuerdosospim` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `tipoacuerdo` int(1) unsigned NOT NULL COMMENT 'Tipo de Acuerdo - En tabla tiposdeacuerdos',
  `fechaacuerdo` date NOT NULL COMMENT 'Fecha de Entrada en Vigencia del Acuerdo',
  `nroacta` int(9) unsigned DEFAULT NULL COMMENT 'Numero de Acta de Acuerdo',
  `gestoracuerdo` int(3) unsigned NOT NULL COMMENT 'Gestor del Acuerdo - En tabla gestoresdeacuerdos',
  `porcengastoadmin` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Porcentaje de Gto. Administrativo al momento de Celebrar el Acuerdo',
  `inspectorinterviene` int(3) unsigned NOT NULL COMMENT 'Inspector Interviniente - En tabla inspectores',
  `requerimientoorigen` int(8) unsigned DEFAULT NULL COMMENT 'Requerimiento que da origen al acuerdo - En tabla requerimientosospim',
  `liquidacionorigen` char(100) DEFAULT NULL COMMENT 'Plantilla de Liquidacion que da origen al acuerdo - Excel en unidad compartida de Fiscalizacion',
  `montoacuerdo` decimal(9,2) NOT NULL COMMENT 'Monto Total Acordado',
  `observaciones` text COMMENT 'Observaciones del Acuerdo',
  `estadoacuerdo` int(1) unsigned NOT NULL COMMENT 'Estado del Acuerdo - En tabla estadosdeacuerdos',
  `cuotasapagar` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Cuotas a Pagar',
  `montoapagar` decimal(9,2) DEFAULT NULL COMMENT 'Monto Total de Cuotas a Pagar',
  `cuotaspagadas` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Cuotas Pagadas a una Fecha',
  `montopagadas` decimal(9,2) DEFAULT NULL COMMENT 'Monto de Cuotas Pagadas a una Fecha',
  `fechapagadas` date DEFAULT NULL COMMENT 'Fecha de Ultima Cuota Pagada',
  `saldoacuerdo` decimal(9,2) DEFAULT NULL COMMENT 'Saldo del Acuerdo una vez Cancelado',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`nroacuerdo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Acuerdos de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabacuerdosusimra`
--

CREATE TABLE IF NOT EXISTS `cabacuerdosusimra` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `tipoacuerdo` int(1) unsigned NOT NULL COMMENT 'Tipo de Acuerdo - En tabla tiposdeacuerdos',
  `fechaacuerdo` date NOT NULL COMMENT 'Fecha de Entrada en Vigencia del Acuerdo',
  `nroacta` int(9) unsigned DEFAULT NULL COMMENT 'Numero de Acta de Acuerdo',
  `gestoracuerdo` int(3) unsigned NOT NULL COMMENT 'Gestor del Acuerdo - En tabla gestoresdeacuerdos',
  `porcengastoadmin` decimal(5,2) unsigned NOT NULL DEFAULT '0.00' COMMENT 'Porcentaje de Gto. Administrativo al momento de Celebrar el Acuerdo',
  `inspectorinterviene` int(3) unsigned NOT NULL COMMENT 'Inspector Interviniente - En tabla inspectores',
  `requerimientoorigen` int(8) unsigned DEFAULT NULL COMMENT 'Requerimiento que da origen al acuerdo - En tabla requerimientosospim',
  `liquidacionorigen` char(100) DEFAULT NULL COMMENT 'Plantilla de Liquidacion que da origen al acuerdo - Excel en unidad compartida de Fiscalizacion',
  `montoacuerdo` decimal(9,2) NOT NULL COMMENT 'Monto Total Acordado',
  `observaciones` text COMMENT 'Observaciones del Acuerdo',
  `estadoacuerdo` int(1) unsigned NOT NULL COMMENT 'Estado del Acuerdo - En tabla estadosdeacuerdos',
  `cuotasapagar` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Cuotas a Pagar',
  `montoapagar` decimal(9,2) DEFAULT NULL COMMENT 'Monto Total de Cuotas a Pagar',
  `cuotaspagadas` int(3) unsigned DEFAULT NULL COMMENT 'Cantidad de Cuotas Pagadas a una Fecha',
  `montopagadas` decimal(9,2) DEFAULT NULL COMMENT 'Monto de Cuotas Pagadas a una Fecha',
  `fechapagadas` date DEFAULT NULL COMMENT 'Fecha de Ultima Cuota Pagada',
  `saldoacuerdo` decimal(9,2) DEFAULT NULL COMMENT 'Saldo del Acuerdo una vez Cancelado',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`nroacuerdo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Acuerdos de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabcontratoprestador`
--

CREATE TABLE IF NOT EXISTS `cabcontratoprestador` (
  `idcontrato` int(4) NOT NULL AUTO_INCREMENT,
  `codigoprestador` int(4) NOT NULL,
  `fechainicio` date NOT NULL,
  `fechafin` date NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) NOT NULL,
  PRIMARY KEY (`idcontrato`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabddjjospim`
--

CREATE TABLE IF NOT EXISTS `cabddjjospim` (
  `cuit` char(11) NOT NULL,
  `anoddjj` int(4) unsigned NOT NULL,
  `mesddjj` int(2) unsigned NOT NULL,
  `totalpersonal` int(5) unsigned NOT NULL,
  `totalremundeclarada` decimal(12,2) NOT NULL,
  `totalremundecreto` decimal(12,2) NOT NULL,
  PRIMARY KEY (`cuit`,`anoddjj`,`mesddjj`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabddjjusimra`
--

CREATE TABLE IF NOT EXISTS `cabddjjusimra` (
  `id` int(9) unsigned NOT NULL COMMENT 'ID de Registro',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `cuil` char(11) NOT NULL COMMENT 'C.U.I.L. del Empleado',
  `mesddjj` int(2) unsigned NOT NULL COMMENT 'Mes de la DDJJ',
  `anoddjj` int(4) unsigned NOT NULL COMMENT 'Anio de la DDJJ',
  `remuneraciones` decimal(9,2) unsigned NOT NULL COMMENT 'Remuneraciones Declaradas',
  `apor060` decimal(9,2) unsigned NOT NULL COMMENT 'Total Aporte 0.60%',
  `apor100` decimal(9,2) unsigned NOT NULL COMMENT 'Total Contribucion 1.00%',
  `apor150` decimal(9,2) unsigned NOT NULL COMMENT 'Total Aporte 1.50%',
  `totalaporte` decimal(9,2) unsigned NOT NULL COMMENT 'Total de Aportes Declarados',
  `recargo` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de Recargo',
  `cantidadpersonal` int(5) unsigned NOT NULL COMMENT 'Cantidad de Personal Declarado',
  `instrumentodepago` char(1) NOT NULL COMMENT 'Instrumento con el que se efectua el Pago',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Para Identificacion de la Boleta/Ticket de Pago',
  `observaciones` char(230) NOT NULL COMMENT 'Observaciones',
  `fechasubida` date NOT NULL COMMENT 'Fecha de Subida de la DDJJ desde Internet',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla Cabecera Totalizadora de DDJJ USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabjuiciosospim`
--

CREATE TABLE IF NOT EXISTS `cabjuiciosospim` (
  `nroorden` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro de Orden',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `nrocertificado` int(8) unsigned DEFAULT NULL COMMENT 'Nro de Certificado de Deuda',
  `statusdeuda` int(1) unsigned NOT NULL COMMENT 'Status de la Deuda',
  `fechaexpedicion` date DEFAULT NULL COMMENT 'Fecha de Expedicion del Certificado',
  `acuerdorelacionado` int(1) unsigned NOT NULL COMMENT 'Incluye Acuerdo Caido',
  `nroacuerdo` int(3) unsigned DEFAULT NULL COMMENT 'Nro de Acuerdo Incluido',
  `deudahistorica` decimal(9,2) unsigned NOT NULL COMMENT 'Deuda Historica',
  `intereses` decimal(9,2) unsigned NOT NULL COMMENT 'Monto de Intereses',
  `deudaactualizada` decimal(9,2) unsigned NOT NULL COMMENT 'Deuda Actualizada',
  `codasesorlegal` int(3) unsigned NOT NULL COMMENT 'Asesor Legal Interviniente',
  `codinspector` int(3) unsigned NOT NULL COMMENT 'Inspector Interviniente',
  `usuarioejecutor` char(50) NOT NULL COMMENT 'Usuario Ejecutor',
  `tramitejudicial` int(1) unsigned NOT NULL COMMENT 'Informacion de Tramite Judicial',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroorden`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Cabecera de Juicios de OSPIM' AUTO_INCREMENT=4336 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabjuiciosusimra`
--

CREATE TABLE IF NOT EXISTS `cabjuiciosusimra` (
  `nroorden` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro de Orden',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `nrocertificado` int(8) unsigned DEFAULT NULL COMMENT 'Nro de Certificado de Deuda',
  `statusdeuda` int(1) unsigned NOT NULL COMMENT 'Status de la Deuda',
  `fechaexpedicion` date DEFAULT NULL COMMENT 'Fecha de Expedicion del Certificado',
  `acuerdorelacionado` int(1) unsigned NOT NULL COMMENT 'Incluye Acuerdo Caido',
  `nroacuerdo` int(3) unsigned DEFAULT NULL COMMENT 'Nro de Acuerdo Incluido',
  `deudahistorica` decimal(9,2) unsigned NOT NULL COMMENT 'Deuda Historica',
  `intereses` decimal(9,2) unsigned NOT NULL COMMENT 'Monto de Intereses',
  `deudaactualizada` decimal(9,2) unsigned NOT NULL COMMENT 'Deuda Actualizada',
  `codasesorlegal` int(3) unsigned NOT NULL COMMENT 'Asesor Legal Interviniente',
  `codinspector` int(3) unsigned NOT NULL COMMENT 'Inspector Interviniente',
  `usuarioejecutor` char(50) NOT NULL COMMENT 'Usuario Ejecutor',
  `tramitejudicial` int(1) unsigned NOT NULL COMMENT 'Informacion de Tramite Judicial',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroorden`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Cabecera de Juicios de USIMRA' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabliquiospim`
--

CREATE TABLE IF NOT EXISTS `cabliquiospim` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion OSPIM que da origen a la Liquidacion de Deuda',
  `fechaliquidacion` date NOT NULL COMMENT 'Fecha de la Liquidacion',
  `horaliquidacion` time NOT NULL COMMENT 'Hora de la Liquidacion',
  `liquidacionorigen` char(100) DEFAULT NULL COMMENT 'Nombre de la Plantilla de Liquidacion',
  `fechainspeccion` date DEFAULT NULL COMMENT 'Fecha del Procedimiento de Inspeccion',
  `deudanominal` decimal(12,2) DEFAULT NULL COMMENT 'Deuda Nominal',
  `intereses` decimal(12,2) DEFAULT NULL COMMENT 'Intereses',
  `gtosadmin` decimal(12,2) DEFAULT NULL COMMENT 'Gastos Administrativos',
  `totalliquidado` decimal(12,2) DEFAULT NULL COMMENT 'Monto Total Liquidado',
  `nroresolucioninspeccion` char(20) DEFAULT NULL COMMENT 'Nro. de Resolucion de Inspeccion',
  `nrocertificadodeuda` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Certificado de Deuda',
  `operadorliquidador` char(100) DEFAULT NULL COMMENT 'Operador que Efectua la Liquidacion',
  `liquidacionanulada` int(1) NOT NULL DEFAULT '0',
  `motivoanulacion` text,
  `fechaanulacion` datetime DEFAULT NULL,
  `usuarioanulacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Liquidaciones de Deuda OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabliquiusimra`
--

CREATE TABLE IF NOT EXISTS `cabliquiusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion USIMRA que da origen a la Liquidacion de Deuda',
  `fechaliquidacion` date NOT NULL COMMENT 'Fecha de la Liquidacion',
  `horaliquidacion` time NOT NULL COMMENT 'Hora de la Liquidacion',
  `liquidacionorigen` char(100) DEFAULT NULL COMMENT 'Nombre de la Plantilla de Liquidacion',
  `fechainspeccion` date DEFAULT NULL COMMENT 'Fecha del Procedimiento de Inspeccion',
  `deudanominal` decimal(12,2) DEFAULT NULL COMMENT 'Deuda Nominal',
  `intereses` decimal(12,2) DEFAULT NULL COMMENT 'Intereses',
  `gtosadmin` decimal(12,2) DEFAULT NULL COMMENT 'Gastos Administrativos',
  `totalliquidado` decimal(12,2) DEFAULT NULL COMMENT 'Monto Total Liquidado',
  `nroresolucioninspeccion` char(20) DEFAULT NULL COMMENT 'Nro. de Resolucion de Inspeccion',
  `nrocertificadodeuda` int(8) unsigned DEFAULT NULL COMMENT 'Nro. de Certificado de Deuda',
  `operadorliquidador` char(100) DEFAULT NULL COMMENT 'Operador que Efectua la Liquidacion',
  `liquidacionanulada` int(1) NOT NULL DEFAULT '0',
  `motivoanulacion` text,
  `fechaanulacion` datetime DEFAULT NULL,
  `usuarioanulacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Liquidaciones de Deuda USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cabpedidos`
--

CREATE TABLE IF NOT EXISTS `cabpedidos` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `fechasolicitud` date NOT NULL,
  `descripcion` text,
  `costototal` float(9,2) NOT NULL,
  `idproveedor` int(2) unsigned NOT NULL,
  `fechacierre` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idproveedor` (`idproveedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cancermama`
--

CREATE TABLE IF NOT EXISTS `cancermama` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `canceruterino`
--

CREATE TABLE IF NOT EXISTS `canceruterino` (
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
-- Estructura de tabla para la tabla `capitados`
--

CREATE TABLE IF NOT EXISTS `capitados` (
  `codigo` int(3) unsigned zerofill NOT NULL,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capitadosdelega`
--

CREATE TABLE IF NOT EXISTS `capitadosdelega` (
  `codigopresta` int(3) unsigned zerofill NOT NULL,
  `codidelega` int(3) unsigned zerofill NOT NULL,
  PRIMARY KEY (`codigopresta`,`codidelega`),
  KEY `codidelega` (`codidelega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capitadosformatopadron`
--

CREATE TABLE IF NOT EXISTS `capitadosformatopadron` (
  `codigopresta` int(3) NOT NULL,
  `tipopadron` varchar(1) NOT NULL,
  `consulta` text NOT NULL,
  PRIMARY KEY (`codigopresta`,`tipopadron`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `capitulosdepracticas`
--

CREATE TABLE IF NOT EXISTS `capitulosdepracticas` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` char(2) NOT NULL,
  `idtipopractica` int(2) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=58 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cie10capitulos`
--

CREATE TABLE IF NOT EXISTS `cie10capitulos` (
  `idcapitulo` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `numerocapitulo` char(10) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`idcapitulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cie10categorias`
--

CREATE TABLE IF NOT EXISTS `cie10categorias` (
  `idcategoria` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `idgrupo` int(4) unsigned NOT NULL,
  `letracodigo` char(1) NOT NULL,
  `numerocodigo` char(2) NOT NULL,
  `simbolocodigo` char(1) DEFAULT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2044 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cie10grupos`
--

CREATE TABLE IF NOT EXISTS `cie10grupos` (
  `idgrupo` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `idcapitulo` int(3) unsigned NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`idgrupo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=264 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cie10subcategorias`
--

CREATE TABLE IF NOT EXISTS `cie10subcategorias` (
  `idsubcategoria` int(6) unsigned NOT NULL AUTO_INCREMENT,
  `idcategoria` int(5) unsigned NOT NULL,
  `numerosubcodigo` char(1) NOT NULL,
  `simbolosubcodigo` char(1) DEFAULT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`idsubcategoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12183 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clasificamaterial`
--

CREATE TABLE IF NOT EXISTS `clasificamaterial` (
  `codigo` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` char(100) NOT NULL DEFAULT '',
  `presuminimo` int(1) unsigned NOT NULL DEFAULT '0',
  `presumaximo` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Clasifica materiales para establecer puntos minimos y maximo' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conceptosdeudas`
--

CREATE TABLE IF NOT EXISTS `conceptosdeudas` (
  `codigo` char(1) NOT NULL COMMENT 'Codigo para los conceptos de Deudas',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Conceptos  de Deudas',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para los Conceptos de Deudas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conciliacuotasusimra`
--

CREATE TABLE IF NOT EXISTS `conciliacuotasusimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Numero de Cuota',
  `cuentaboleta` int(2) unsigned NOT NULL COMMENT 'Cuenta en que se Acredita la Boleta',
  `cuentaremesa` int(2) unsigned DEFAULT NULL COMMENT 'Cuenta a la que Pertenece la Remesa Bancaria',
  `fecharemesa` date DEFAULT NULL COMMENT 'Fecha de la Remesa Bancaria',
  `nroremesa` int(4) unsigned DEFAULT NULL COMMENT 'Numero de la Remesa Bancaria',
  `nroremitoremesa` int(4) unsigned DEFAULT NULL COMMENT 'Numero de Remito que compone la Remesa Bancaria',
  `cuentaremitosuelto` int(2) unsigned DEFAULT NULL COMMENT 'Cuenta a la que Pertenece el Remito Suelto Bancario',
  `fecharemitosuelto` date DEFAULT NULL COMMENT 'Fecha del Remito Suelto Bancario',
  `nroremitosuelto` int(4) unsigned DEFAULT NULL COMMENT 'Numero del Remito Suelto Bancario',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Conciliacion Bancaria de Cuotas de Acuerdos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conciliapagosusimra`
--

CREATE TABLE IF NOT EXISTS `conciliapagosusimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `cuentaboleta` int(2) unsigned NOT NULL COMMENT 'Cuenta en que se Acredita la Boleta',
  `cuentaremesa` int(2) unsigned DEFAULT NULL COMMENT 'Cuenta a la que Pertenece la Remesa Bancaria',
  `fecharemesa` date DEFAULT NULL COMMENT 'Fecha de la Remesa Bancaria',
  `nroremesa` int(4) unsigned DEFAULT NULL COMMENT 'Numero de la Remesa Bancaria',
  `nroremitoremesa` int(4) unsigned DEFAULT NULL COMMENT 'Numero de Remito que compone la Remesa Bancaria',
  `cuentaremitosuelto` int(2) unsigned DEFAULT NULL COMMENT 'Cuenta a la que Pertenece el Remito Suelto Bancario',
  `fecharemitosuelto` date DEFAULT NULL COMMENT 'Fecha del Remito Suelto Bancario',
  `nroremitosuelto` int(4) unsigned DEFAULT NULL COMMENT 'Numero del Remito Suelto Bancario',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Conciliacion Bancaria de Pagos de Empresas por Aportes';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consumoinsumo`
--

CREATE TABLE IF NOT EXISTS `consumoinsumo` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `idinsumo` int(3) unsigned NOT NULL,
  `idusuario` int(3) NOT NULL,
  `fechaconsumo` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idinsumo` (`idinsumo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conveniosbanco`
--

CREATE TABLE IF NOT EXISTS `conveniosbanco` (
  `nrocovenio` int(10) unsigned NOT NULL COMMENT 'Nro. de Convenio',
  `descripcion` text NOT NULL COMMENT 'Descripcion del Convenio',
  `cuentaasociada` int(2) DEFAULT NULL COMMENT 'Nro. de Cuenta Bancaria Asociada (Tabla: cuentasospim)',
  PRIMARY KEY (`nrocovenio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Convenios de Boletas Electronicas OSPIM-Bco. Nacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conveniosbancousimra`
--

CREATE TABLE IF NOT EXISTS `conveniosbancousimra` (
  `nrocovenio` int(10) unsigned NOT NULL COMMENT 'Nro. de Convenio',
  `descripcion` text NOT NULL COMMENT 'Descripcion del Convenio',
  `cuentaasociada` int(2) DEFAULT NULL COMMENT 'Nro. de Cuenta Bancaria Asociada (Tabla: cuentasusimra)',
  PRIMARY KEY (`nrocovenio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Convenios de Boletas Electronicas USIMRA-Bco. Nacion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentasusimra`
--

CREATE TABLE IF NOT EXISTS `cuentasusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta',
  `descripcioncuenta` char(100) NOT NULL COMMENT 'Descripcion de Cuenta',
  PRIMARY KEY (`codigocuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cuentas Bancarias de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuoacuerdosospim`
--

CREATE TABLE IF NOT EXISTS `cuoacuerdosospim` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Numero de Cuota',
  `montocuota` decimal(9,2) NOT NULL COMMENT 'Monto de la Cuota',
  `fechacuota` date NOT NULL COMMENT 'Fecha de Vencimiento de la Cuota',
  `tipocancelacion` int(2) unsigned NOT NULL COMMENT 'Instrumento de Cancelacion de la Cuota - En tabla tiposcancelaciones',
  `chequenro` char(20) DEFAULT NULL COMMENT 'Nro de Cheque con que se Cancela',
  `chequebanco` char(20) DEFAULT NULL COMMENT 'Banco Cheque a Cargo',
  `chequefecha` date DEFAULT NULL COMMENT 'Fecha del Cheque con que se cancela',
  `observaciones` char(60) DEFAULT NULL COMMENT 'Observaciones Generales para la Cuota',
  `boletaimpresa` int(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Contador de Boletas Electronicas Impresas para la cuota',
  `montopagada` decimal(9,2) DEFAULT NULL COMMENT 'Monto Pagado de la Cuota',
  `fechapagada` date DEFAULT NULL COMMENT 'Fecha de Pago de la Cuota',
  `fechacancelacion` date DEFAULT NULL COMMENT 'Fecha en que se Procesa la Cancelacion',
  `sistemacancelacion` char(1) DEFAULT NULL COMMENT 'Sistema de Cancelacion de la Cuota - En tabla sistemascancelacion',
  `codigobarra` char(30) DEFAULT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `fechaacreditacion` date DEFAULT NULL COMMENT 'Fecha en que se acredita el Pago',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cuotas de Acuerdos de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuoacuerdosusimra`
--

CREATE TABLE IF NOT EXISTS `cuoacuerdosusimra` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Numero de Cuota',
  `montocuota` decimal(9,2) NOT NULL COMMENT 'Monto de la Cuota',
  `fechacuota` date NOT NULL COMMENT 'Fecha de Vencimiento de la Cuota',
  `tipocancelacion` int(2) unsigned NOT NULL COMMENT 'Instrumento de Cancelacion de la Cuota - En tabla tiposcancelaciones',
  `chequenro` char(20) DEFAULT NULL COMMENT 'Nro de Cheque con que se Cancela',
  `chequebanco` char(20) DEFAULT NULL COMMENT 'Banco Cheque a Cargo',
  `chequefecha` date DEFAULT NULL COMMENT 'Fecha del Cheque con que se cancela',
  `observaciones` char(60) DEFAULT NULL COMMENT 'Observaciones Generales para la Cuota',
  `boletaimpresa` int(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Contador de Boletas Electronicas Impresas para la cuota',
  `montopagada` decimal(9,2) DEFAULT NULL COMMENT 'Monto Pagado de la Cuota',
  `fechapagada` date DEFAULT NULL COMMENT 'Fecha de Pago de la Cuota',
  `fechacancelacion` date DEFAULT NULL COMMENT 'Fecha en que se Procesa la Cancelacion',
  `sistemacancelacion` char(1) DEFAULT NULL COMMENT 'Sistema de Cancelacion de la Cuota - En tabla sistemascancelacion',
  `codigobarra` char(30) DEFAULT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `fechaacreditacion` date DEFAULT NULL COMMENT 'Fecha en que se acredita el Pago',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cuotas de Acuerdos de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuotaextraordinariausimra`
--

CREATE TABLE IF NOT EXISTS `cuotaextraordinariausimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `fechapago` date NOT NULL COMMENT 'Fecha de Pago',
  `cantidadaportantes` int(5) unsigned NOT NULL COMMENT 'Cantidad de Aportantes',
  `totalaporte` decimal(12,2) unsigned NOT NULL COMMENT 'Total de Aporte',
  `montorecargo` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de Recargo Incluido',
  `montopagado` decimal(9,2) unsigned NOT NULL COMMENT 'Total Pagado',
  `observaciones` text COMMENT 'Observaciones',
  `sistemacancelacion` char(1) NOT NULL COMMENT 'Sistema de Cancelacion del Pago - En tabla sistemascancelacion',
  `codigobarra` char(39) DEFAULT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que se acredita el Pago',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de ultima modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de ultima modificacion del Registro',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Pagos Para Cuota Excepcional y Extraordinaria USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ddjjinactivosusimra`
--

CREATE TABLE IF NOT EXISTS `ddjjinactivosusimra` (
  `id` int(6) NOT NULL,
  `nrcuit` varchar(11) NOT NULL DEFAULT '',
  `nrcuil` varchar(11) NOT NULL DEFAULT '',
  `permes` int(2) NOT NULL DEFAULT '0',
  `perano` int(4) NOT NULL DEFAULT '0',
  `motivo` varchar(100) DEFAULT NULL,
  `nrctrl` varchar(14) NOT NULL DEFAULT '',
  `idControl` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ddjjusimra`
--

CREATE TABLE IF NOT EXISTS `ddjjusimra` (
  `id` int(9) unsigned NOT NULL,
  `nrcuit` varchar(11) NOT NULL,
  `nrcuil` varchar(11) NOT NULL,
  `permes` int(2) NOT NULL,
  `perano` int(4) NOT NULL,
  `remune` decimal(9,2) NOT NULL,
  `apo060` decimal(9,2) NOT NULL,
  `apo100` decimal(9,2) NOT NULL,
  `apo150` decimal(9,2) NOT NULL,
  `totapo` decimal(9,2) NOT NULL,
  `recarg` decimal(9,2) NOT NULL,
  `nfilas` int(5) NOT NULL,
  `instrumento` varchar(1) DEFAULT NULL,
  `nrctrl` varchar(14) NOT NULL,
  `observ` varchar(230) NOT NULL,
  `idControl` int(11) NOT NULL,
  PRIMARY KEY (`id`,`nrcuit`,`nrcuil`,`permes`,`perano`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delegaciones`
--

CREATE TABLE IF NOT EXISTS `delegaciones` (
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Codigo de Delegacion',
  `nombre` char(100) NOT NULL COMMENT 'Denominacion',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `indpostal` char(1) NOT NULL COMMENT 'Indice de Provincia en C.P.',
  `numpostal` int(4) unsigned NOT NULL COMMENT 'Componente numerico en C.P.',
  `alfapostal` char(3) DEFAULT NULL COMMENT 'Componente alfabetico en C.P.',
  `codlocali` int(6) unsigned NOT NULL COMMENT 'Codigo de Localidad',
  `domicilio` char(50) NOT NULL COMMENT 'Domicilio',
  `ddn1` int(5) unsigned zerofill DEFAULT NULL COMMENT 'Discado Directo Nacional 1',
  `telefono1` int(8) unsigned DEFAULT NULL COMMENT 'Telefono 1',
  `ddn2` int(5) unsigned DEFAULT NULL COMMENT 'Discado Directo Nacional 2',
  `telefono2` int(8) unsigned DEFAULT NULL COMMENT 'Telefono 2',
  `email` char(60) DEFAULT NULL COMMENT 'Direccion de Corrreo Electronico',
  `autoridad` char(80) NOT NULL COMMENT 'Nombre de la Autoridad Principal',
  `cargo` char(50) NOT NULL COMMENT 'Cargo de la Autoridad Principal',
  `porreinteg` decimal(5,2) NOT NULL COMMENT 'Porcentaje de Reintegro sobre la Recaudacion',
  PRIMARY KEY (`codidelega`),
  KEY `FK_DELEGACIONES_CODPROVIN` (`codprovin`),
  KEY `FK_DELEGACIONES_INDPOSTAL` (`indpostal`),
  KEY `FK_DELEGACIONES_NUMPOSTAL` (`numpostal`),
  KEY `FK_DELEGACIONES_CODLOCALI` (`codlocali`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Delegaciones de OSPIM/USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `delegaempresa`
--

CREATE TABLE IF NOT EXISTS `delegaempresa` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Codigo de Delegacion',
  `codiempresa` int(6) unsigned NOT NULL COMMENT 'Codigo de Empresa',
  PRIMARY KEY (`cuit`,`codidelega`,`codiempresa`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla transitoria para mantener los codigos de empresa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE IF NOT EXISTS `departamentos` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desempleosss`
--

CREATE TABLE IF NOT EXISTS `desempleosss` (
  `anodesempleo` int(4) unsigned NOT NULL COMMENT 'Año del Periodo de Desempleo',
  `mesdesempleo` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo de Desempleo',
  `nroderegistro` int(5) unsigned NOT NULL COMMENT 'Nro. de Registro Generado Automaticamente',
  `clave` int(13) unsigned NOT NULL COMMENT 'Clave de Identificacion del Subsidio por Desempleo',
  `controlpago` char(1) DEFAULT NULL COMMENT 'Control del Pago del Subsidio por Desempleo',
  `parentesco` int(2) unsigned NOT NULL COMMENT 'Codigo de Tipo de Beneficiario (Titular/Familiar)',
  `tipodocumento` int(2) unsigned NOT NULL COMMENT 'Tipo de Documento (Codigo SSS)',
  `nrodocumento` int(10) unsigned NOT NULL COMMENT 'Nro. de Documento',
  `provinciacedula` char(3) NOT NULL COMMENT 'Provincia cuando el Documento es Cedula (Codigo SSS)',
  `cuilbeneficiario` char(11) NOT NULL COMMENT 'CUIL del Beneficiario',
  `fechanacimiento` date NOT NULL COMMENT 'Fecha de Nacimiento',
  `apellidoynombre` char(100) NOT NULL COMMENT 'Apellido y Nombre',
  `fechacobro` date NOT NULL COMMENT 'Fecha de Cobro de Subsidio',
  `sexo` char(1) NOT NULL COMMENT 'Sexo del Beneficiario',
  `periodoanses` char(4) NOT NULL COMMENT 'Periodo Relacionado Establecido por ANSES (AAMM)',
  `mesfinrelacion` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo de Fin de Relacion Laboral',
  `anofinrelacion` int(4) unsigned NOT NULL COMMENT 'Año del Periodo de Fin de Relacion Laboral',
  `codigoobrasocial` int(6) unsigned NOT NULL COMMENT 'Codigo de Obra Social - RNOS',
  `fechainformesss` date NOT NULL COMMENT 'Fecha en que informa la SSS',
  `cuiltitular` char(11) NOT NULL COMMENT 'CUIL del Titular del Subsidio',
  PRIMARY KEY (`anodesempleo`,`mesdesempleo`,`nroderegistro`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Padron de Beneficiario del Fondo de Desempleo Segun la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detacuerdosospim`
--

CREATE TABLE IF NOT EXISTS `detacuerdosospim` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `idperiodo` int(3) unsigned NOT NULL COMMENT 'Identificador de Periodo',
  `mesacuerdo` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo Incluido en el Acuerdo',
  `anoacuerdo` int(4) unsigned NOT NULL COMMENT 'Año del Periodo Incluido en el Acuerdo',
  `conceptodeuda` char(1) NOT NULL COMMENT 'Concepto de la Deuda - En tabla conceptosdeudas',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`idperiodo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle y Periodos de Acuerdos de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detacuerdosusimra`
--

CREATE TABLE IF NOT EXISTS `detacuerdosusimra` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `idperiodo` int(3) unsigned NOT NULL COMMENT 'Identificador de Periodo',
  `mesacuerdo` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo Incluido en el Acuerdo',
  `anoacuerdo` int(4) unsigned NOT NULL COMMENT 'Año del Periodo Incluido en el Acuerdo',
  `conceptodeuda` char(1) NOT NULL COMMENT 'Concepto de la Deuda - En tabla conceptosdeudas',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`idperiodo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle y Periodos de Acuerdos de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepadroncapitados`
--

CREATE TABLE IF NOT EXISTS `detallepadroncapitados` (
  `codigoprestador` char(3) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT '',
  `mespadron` int(2) NOT NULL DEFAULT '0',
  `anopadron` int(4) NOT NULL DEFAULT '0',
  `codidelega` int(4) NOT NULL DEFAULT '0',
  `totaltitulares` int(6) NOT NULL DEFAULT '0',
  `totalfamiliares` int(6) NOT NULL DEFAULT '0',
  `totalbeneficiarios` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigoprestador`,`mespadron`,`anopadron`,`codidelega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detcontratoprestador`
--

CREATE TABLE IF NOT EXISTS `detcontratoprestador` (
  `idcontrato` int(4) NOT NULL,
  `idpractica` int(8) NOT NULL,
  `idcategoria` int(3) NOT NULL,
  `moduloconsultorio` decimal(10,2) DEFAULT NULL,
  `modulourgencia` decimal(10,2) DEFAULT NULL,
  `galenohonorario` decimal(6,2) DEFAULT NULL,
  `galenohonorarioespecialista` decimal(6,2) DEFAULT NULL,
  `galenohonorarioayudante` decimal(6,2) DEFAULT NULL,
  `galenohonorarioanestesista` decimal(6,2) DEFAULT NULL,
  `galenogastos` decimal(6,2) DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  PRIMARY KEY (`idcontrato`,`idpractica`,`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detddjjospim`
--

CREATE TABLE IF NOT EXISTS `detddjjospim` (
  `cuit` char(11) NOT NULL,
  `anoddjj` int(4) unsigned NOT NULL,
  `mesddjj` int(2) unsigned NOT NULL,
  `cuil` char(11) NOT NULL,
  `remundeclarada` decimal(12,2) NOT NULL,
  `adherentes` int(2) unsigned NOT NULL,
  PRIMARY KEY (`cuit`,`anoddjj`,`mesddjj`,`cuil`),
  KEY `BUSQUEDA` (`cuit`,`anoddjj`,`mesddjj`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle de las declaraciones juradas de OSPIM. Disgregramiento por CUIL.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detddjjusimra`
--

CREATE TABLE IF NOT EXISTS `detddjjusimra` (
  `id` int(9) unsigned NOT NULL COMMENT 'ID de Registro',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `cuil` char(11) NOT NULL COMMENT 'C.U.I.L. del Empleado',
  `mesddjj` int(2) unsigned NOT NULL COMMENT 'Mes de la DDJJ',
  `anoddjj` int(4) unsigned NOT NULL COMMENT 'Anio de la DDJJ',
  `remuneraciones` decimal(9,2) unsigned NOT NULL COMMENT 'Remuneracion Declarada',
  `apor060` decimal(9,2) unsigned NOT NULL COMMENT 'Total Aporte 0.60%',
  `apor100` decimal(9,2) unsigned NOT NULL COMMENT 'Total Contribucion 1.00%',
  `apor150` decimal(9,2) unsigned NOT NULL COMMENT 'Total Aporte 1.50%',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control Para Identificacion de la Boleta/Ticket de Pago',
  `fechasubida` date NOT NULL COMMENT 'Fecha de Subida de la DDJJ desde Internet',
  PRIMARY KEY (`id`),
  KEY `BUSQUEDA` (`cuit`,`anoddjj`,`mesddjj`,`nrocontrol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla Detalle por CUIL de DDJJ USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detfiscalizospim`
--

CREATE TABLE IF NOT EXISTS `detfiscalizospim` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro. Requerimiento',
  `anofiscalizacion` int(4) unsigned NOT NULL COMMENT 'Año Fiscalizado',
  `mesfiscalizacion` int(2) unsigned NOT NULL COMMENT 'Mes Fiscalizado',
  `statusfiscalizacion` char(1) NOT NULL COMMENT 'Status de Periodo',
  `remundeclarada` decimal(12,2) NOT NULL COMMENT 'Remuneracion Declarada',
  `cantidadpersonal` int(4) unsigned NOT NULL COMMENT 'Cantidad Personal',
  `deudanominal` decimal(12,2) NOT NULL COMMENT 'Deuda Nominal',
  PRIMARY KEY (`nrorequerimiento`,`anofiscalizacion`,`mesfiscalizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle de Requerimientos de Fiscalizacion de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detfiscalizusimra`
--

CREATE TABLE IF NOT EXISTS `detfiscalizusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro. Requerimiento',
  `anofiscalizacion` int(4) unsigned NOT NULL COMMENT 'Año Fiscalizado',
  `mesfiscalizacion` int(2) unsigned NOT NULL COMMENT 'Mes Fiscalizado',
  `statusfiscalizacion` char(1) NOT NULL COMMENT 'Status de Periodo',
  `remundeclarada` decimal(12,2) NOT NULL COMMENT 'Remuneracion Declarada',
  `cantidadpersonal` int(4) unsigned NOT NULL COMMENT 'Cantidad Personal',
  `deudanominal` decimal(12,2) NOT NULL COMMENT 'Deuda Nominal',
  PRIMARY KEY (`nrorequerimiento`,`anofiscalizacion`,`mesfiscalizacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Detalle de Requerimientos de Fiscalizacion de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detjuiciosospim`
--

CREATE TABLE IF NOT EXISTS `detjuiciosospim` (
  `nroorden` int(8) unsigned NOT NULL COMMENT 'Nro. de Orden en Cabecera',
  `idperiodo` int(3) unsigned NOT NULL COMMENT 'Identificador de Periodo',
  `anojuicio` int(4) unsigned NOT NULL COMMENT 'Año del Juicio',
  `mesjuicio` int(2) unsigned NOT NULL COMMENT 'Mes del Juicio',
  `nroacuerdo` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Nro de acuerdo del cual fue absorvido el periodo',
  `conceptodeuda` char(1) NOT NULL COMMENT 'Concepto de deuda que viene del acuerdo absorvido',
  PRIMARY KEY (`nroorden`,`anojuicio`,`mesjuicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Periodos en Juicio de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detjuiciosusimra`
--

CREATE TABLE IF NOT EXISTS `detjuiciosusimra` (
  `nroorden` int(8) unsigned NOT NULL COMMENT 'Nro. de Orden en Cabecera',
  `idperiodo` int(3) unsigned NOT NULL COMMENT 'Identificador de Periodo',
  `anojuicio` int(4) unsigned NOT NULL COMMENT 'Año del Juicio',
  `mesjuicio` int(2) unsigned NOT NULL COMMENT 'Mes del Juicio',
  `nroacuerdo` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Nro de acuerdo del cual fue absorvido el periodo',
  `conceptodeuda` char(1) NOT NULL COMMENT 'Concepto de deuda que viene del acuerdo absorvido',
  PRIMARY KEY (`nroorden`,`anojuicio`,`mesjuicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Periodos en Juicio de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detpedidos`
--

CREATE TABLE IF NOT EXISTS `detpedidos` (
  `idpedido` int(3) unsigned NOT NULL,
  `idinsumo` int(3) unsigned NOT NULL,
  `descripcion` text,
  `cantidadpedido` int(4) unsigned NOT NULL,
  `costounitario` float(9,2) DEFAULT NULL,
  `cantidadentregada` int(4) NOT NULL DEFAULT '0',
  `fechacierre` date DEFAULT NULL,
  PRIMARY KEY (`idpedido`,`idinsumo`),
  KEY `idinsumo` (`idinsumo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `devolucionsss`
--

CREATE TABLE IF NOT EXISTS `devolucionsss` (
  `codigornos` int(6) unsigned NOT NULL COMMENT 'Codigo de Obra Social segun R.N.O.S.',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. del Empleador',
  `cuiltitul` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Titular',
  `codparent` int(2) unsigned NOT NULL COMMENT 'Codigo de Parentesco segun SSS',
  `cuilfami` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Familiar',
  `codtipdoc` char(2) NOT NULL COMMENT 'Codigo de Tipo de Documento segun SSS',
  `nrodocum` int(8) unsigned NOT NULL COMMENT 'Numero de Documento del Beneficiario',
  `apeynom` char(30) NOT NULL COMMENT 'Apellido y Nombre del Beneficiario',
  `sexo` char(1) NOT NULL COMMENT 'Sexo del Beneficiario',
  `codestciv` int(2) unsigned NOT NULL COMMENT 'Codigo de Estado Civil segun SSS',
  `fechanaci` date NOT NULL COMMENT 'Fecha de Nacimiento en Formato AAAAMMDD',
  `codnacion` int(3) unsigned NOT NULL COMMENT 'Codigo de Nacionalidad segun SSS',
  `calledomi` char(20) NOT NULL COMMENT 'Calle del Domicilio del Beneficiario',
  `puertadomi` char(5) NOT NULL COMMENT 'Nro. de Puerta del Domicilio del Beneficiario',
  `pisodomi` char(4) NOT NULL COMMENT 'Piso del Domicilio del Beneficiario',
  `deptodomi` char(4) NOT NULL COMMENT 'Depto. del Domicilio del Beneficiario',
  `localidad` char(20) NOT NULL COMMENT 'Localidad en la que vive el Beneficiario',
  `codpostal` char(8) NOT NULL COMMENT 'Codigo Postal que corresponde al Beneficiario',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia en la que vive el Beneficiario',
  `tipodomici` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Tipo de Domicilio segun SSS',
  `telefono` char(20) DEFAULT NULL COMMENT 'Telefono del Beneficiario',
  `codsitrev` int(2) unsigned NOT NULL COMMENT 'Codigo de Situacion de Revista segun SSS',
  `codincapa` int(2) unsigned NOT NULL COMMENT 'Codigo de Incapacidad segun SSS',
  `codtiptit` int(2) unsigned NOT NULL COMMENT 'Codigo de Tipo de Beneficiario Titular segun SSS',
  `fecaltaos` date NOT NULL COMMENT 'Fecha Alta en la O.S. con formato AAAAMMDD',
  `fecciepres` date NOT NULL COMMENT 'Fecha de Presentacion del registro en la SSS por la O.S. con formato AAAAMMDD',
  `codmovios` char(1) NOT NULL COMMENT 'Codigo de Movimiento informado por la O.S. en el envio a la SSS',
  `coderrores` int(12) unsigned NOT NULL COMMENT 'Codigo de error detectado en las validaciones hechas por la SSS',
  `verificuil` int(3) unsigned zerofill NOT NULL COMMENT 'Resultado de la verificacion de la SSS del cuil del beneficiario.',
  `cuildevolu` char(11) DEFAULT NULL COMMENT 'Solo para de apropiacion de cuil distinto al informado por la O.S.',
  `anodevolu` int(4) unsigned NOT NULL COMMENT 'Año del periodo al que corresponde la devolucion',
  `mesdevolu` int(2) unsigned NOT NULL COMMENT 'Mes del periodo al que corresponde la devolucion',
  PRIMARY KEY (`cuit`,`cuiltitul`,`codparent`,`cuilfami`,`anodevolu`,`mesdevolu`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Devolucion generada por errores hacia el padron de la O.S. p';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diabetes`
--

CREATE TABLE IF NOT EXISTS `diabetes` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diasbanco`
--

CREATE TABLE IF NOT EXISTS `diasbanco` (
  `ano` int(4) NOT NULL,
  `mes` int(2) NOT NULL,
  `dia` int(2) NOT NULL,
  `nroconvenio` int(10) unsigned NOT NULL,
  `procesado` int(1) NOT NULL DEFAULT '0',
  `exceptuado` int(1) NOT NULL DEFAULT '0',
  `observacion` char(100) DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`ano`,`mes`,`dia`,`nroconvenio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla donde se cargan los días a procesar del banco en OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diasbancousimra`
--

CREATE TABLE IF NOT EXISTS `diasbancousimra` (
  `ano` int(4) NOT NULL,
  `mes` int(2) NOT NULL,
  `dia` int(2) NOT NULL,
  `nroconvenio` int(10) unsigned NOT NULL,
  `procesado` int(1) NOT NULL DEFAULT '0',
  `exceptuado` int(1) NOT NULL DEFAULT '0',
  `observacion` char(100) DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`ano`,`mes`,`dia`,`nroconvenio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla donde se cargan los días a procesar del banco en USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacidadbeneficiario`
--

CREATE TABLE IF NOT EXISTS `discapacidadbeneficiario` (
  `nroafiliado` int(9) NOT NULL,
  `nroorden` int(3) NOT NULL,
  `iddiscapacidad` int(2) NOT NULL,
  PRIMARY KEY (`nroafiliado`,`nroorden`,`iddiscapacidad`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacitadoexpendiente`
--

CREATE TABLE IF NOT EXISTS `discapacitadoexpendiente` (
  `idexpediente` int(5) NOT NULL AUTO_INCREMENT,
  `nroafiliado` int(9) NOT NULL,
  `nroorden` int(3) NOT NULL,
  `pedidomedico` int(1) NOT NULL,
  `presupuesto` int(1) NOT NULL,
  `presupuestotransporte` int(1) NOT NULL,
  `registrosss` int(1) NOT NULL,
  `resolucionsnr` int(1) NOT NULL,
  `titulo` int(1) NOT NULL,
  `plantratamiento` int(1) NOT NULL,
  `informeevolutivo` int(1) NOT NULL,
  `resumenhistoria` int(1) NOT NULL,
  `planillafim` int(1) NOT NULL,
  `consentimientotratamiento` int(1) NOT NULL,
  `consentimientotransporte` int(1) NOT NULL,
  `constanciaalumno` int(1) NOT NULL,
  `adaptaciones` int(1) NOT NULL,
  `actaacuerdo` int(1) NOT NULL,
  `certificadodiscapacidad` int(1) NOT NULL,
  `dependencia` int(1) NOT NULL,
  `recibosueldo` int(1) NOT NULL,
  `segurodesempleo` int(1) NOT NULL,
  `evolutivoprimer` int(1) NOT NULL,
  `evolutivosegundo` int(1) NOT NULL,
  `admision` int(1) NOT NULL,
  `observaciones` text,
  `completo` int(1) NOT NULL,
  `fechacierre` datetime DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) NOT NULL,
  PRIMARY KEY (`idexpediente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=536 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacitados`
--

CREATE TABLE IF NOT EXISTS `discapacitados` (
  `nroafiliado` int(9) unsigned NOT NULL COMMENT 'Nro. de Afiliado',
  `nroorden` int(3) unsigned NOT NULL COMMENT 'Nro. Orden Interno (0 = Titular / Resto = Familiar)',
  `certificadodiscapacidad` int(1) unsigned NOT NULL COMMENT 'Posee Certificado de Discapacidad (0 = No / 1 = Si)',
  `emisioncertificado` date DEFAULT NULL COMMENT 'Fecha de Emision del Certificado de Discapacidad',
  `vencimientocertificado` date DEFAULT NULL COMMENT 'Fecha de Vencimiento del Certificado de Discapacidad',
  `documentocertificado` mediumblob COMMENT 'Scaneo JPG del Certificado de Discapacidad',
  PRIMARY KEY (`nroafiliado`,`nroorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Beneficiarios con Discapacidad';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `idusuario` int(3) NOT NULL,
  `fechamodificacion` datetime NOT NULL,
  `usuariomodificacion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleadosbanco`
--

CREATE TABLE IF NOT EXISTS `empleadosbanco` (
  `nrolegajo` int(9) unsigned zerofill NOT NULL,
  `apellidoynombre` char(100) NOT NULL,
  `nrocuenta` char(10) NOT NULL,
  `nrosucursal` char(4) NOT NULL,
  `iddocumento` int(3) unsigned NOT NULL,
  `nrodocumento` char(20) NOT NULL,
  `cuil` char(11) NOT NULL,
  `pertenencia` char(1) NOT NULL,
  PRIMARY KEY (`nrolegajo`,`pertenencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleadosdebajausimra`
--

CREATE TABLE IF NOT EXISTS `empleadosdebajausimra` (
  `id` int(5) NOT NULL,
  `nrcuit` varchar(11) NOT NULL,
  `nrcuil` varchar(11) NOT NULL,
  `apelli` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecing` date NOT NULL,
  `tipdoc` varchar(5) NOT NULL,
  `nrodoc` varchar(9) NOT NULL,
  `ssexxo` varchar(10) NOT NULL,
  `fecnac` date NOT NULL,
  `estciv` varchar(10) NOT NULL,
  `direcc` varchar(50) NOT NULL,
  `locale` varchar(50) NOT NULL,
  `copole` varchar(12) NOT NULL,
  `provin` varchar(20) NOT NULL,
  `nacion` varchar(20) NOT NULL,
  `rramaa` int(2) NOT NULL,
  `catego` varchar(20) NOT NULL,
  `activo` char(2) NOT NULL,
  `bajada` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleadosusimra`
--

CREATE TABLE IF NOT EXISTS `empleadosusimra` (
  `nrcuit` varchar(11) NOT NULL DEFAULT '',
  `nrcuil` varchar(11) NOT NULL DEFAULT '',
  `apelli` varchar(50) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `fecing` date NOT NULL DEFAULT '0000-00-00',
  `tipdoc` varchar(5) NOT NULL DEFAULT '',
  `nrodoc` varchar(9) NOT NULL DEFAULT '',
  `ssexxo` varchar(10) NOT NULL DEFAULT '',
  `fecnac` date NOT NULL DEFAULT '0000-00-00',
  `estciv` varchar(10) NOT NULL DEFAULT '',
  `direcc` varchar(50) NOT NULL DEFAULT '',
  `locale` varchar(50) NOT NULL DEFAULT '',
  `copole` varchar(12) NOT NULL DEFAULT '',
  `provin` varchar(20) NOT NULL DEFAULT '',
  `nacion` varchar(20) NOT NULL DEFAULT '',
  `rramaa` int(2) NOT NULL,
  `catego` varchar(20) NOT NULL DEFAULT '',
  `activo` char(2) NOT NULL DEFAULT '',
  `bajada` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`nrcuit`,`nrcuil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE IF NOT EXISTS `empresas` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `nombre` char(100) NOT NULL COMMENT 'Razon Social, Denominacion o Nombre',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `indpostal` char(1) NOT NULL COMMENT 'Indice de Provincia en el C.P.',
  `numpostal` int(4) unsigned NOT NULL COMMENT 'Componente Numerico del C.P.',
  `alfapostal` char(3) DEFAULT NULL COMMENT 'Componente Alfabetico del C.P.',
  `codlocali` int(6) unsigned NOT NULL COMMENT 'Codigo de Localidad',
  `domilegal` char(50) NOT NULL COMMENT 'Domicilio Legal del la Empresa o Persona',
  `ddn1` char(5) DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 1',
  `telefono1` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono 1',
  `contactel1` char(50) DEFAULT NULL COMMENT 'Contacto Telefonico 1',
  `ddn2` char(5) DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 2',
  `telefono2` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono 2',
  `contactel2` char(50) DEFAULT NULL COMMENT 'Contacto Telefonico 2',
  `codigotipo` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Tipo de Empresa',
  `codpertene` int(1) unsigned DEFAULT NULL COMMENT 'Codigo de Pertenencia Organizacional: USIMRA-OSPIM',
  `actividad` char(80) DEFAULT NULL COMMENT 'Actividad Desarrollada por la Empresa',
  `obsospim` char(120) DEFAULT NULL COMMENT 'Observaciones relacionadas con OSPIM',
  `obsusimra` char(120) DEFAULT NULL COMMENT 'Observaciones relacionadas con USIMRA',
  `iniobliosp` date DEFAULT NULL COMMENT 'Fecha en que comienza obligaciones con OSPIM',
  `iniobliusi` date DEFAULT NULL COMMENT 'Fecha en que comienza obligaciones con USIMRA',
  `email` char(60) DEFAULT NULL COMMENT 'Direccion de Correo Electronico',
  `carpetaenarchivo` char(10) DEFAULT NULL COMMENT 'Indice de Carpeta en Archivo',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Incilializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime NOT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) NOT NULL COMMENT 'Ultimo Usuario que Modifica el Registro',
  `mirroring` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`cuit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresasdebaja`
--

CREATE TABLE IF NOT EXISTS `empresasdebaja` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T.',
  `nombre` char(100) NOT NULL COMMENT 'Razon Social, Denominacion o Nombre',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `indpostal` char(1) NOT NULL COMMENT 'Indice de Provincia en el C.P.',
  `numpostal` int(4) unsigned NOT NULL COMMENT 'Componente Numerico del C.P.',
  `alfapostal` char(3) DEFAULT NULL COMMENT 'Componente Alfabetico del C.P.',
  `codlocali` int(6) unsigned NOT NULL COMMENT 'Codigo de Localidad',
  `domilegal` char(50) NOT NULL COMMENT 'Domicilio Legal del la Empresa o Persona',
  `ddn1` char(5) DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 1',
  `telefono1` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono 1',
  `contactel1` char(50) DEFAULT NULL COMMENT 'Contacto Telefonico 1',
  `ddn2` char(5) DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 2',
  `telefono2` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono 2',
  `contactel2` char(50) DEFAULT NULL COMMENT 'Contacto Telefonico 2',
  `codigotipo` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Tipo de Empresa',
  `codpertene` int(1) unsigned DEFAULT NULL COMMENT 'Codigo de Pertenencia Organizacional: USIMRA-OSPIM',
  `actividad` char(80) DEFAULT NULL COMMENT 'Actividad Desarrollada por la Empresa',
  `obsospim` char(120) DEFAULT NULL COMMENT 'Observaciones relacionadas con OSPIM',
  `obsusimra` char(120) DEFAULT NULL COMMENT 'Observaciones relacionadas con USIMRA',
  `iniobliosp` date DEFAULT NULL COMMENT 'Fecha en que comienza obligaciones con OSPIM',
  `iniobliusi` date DEFAULT NULL COMMENT 'Fecha en que comienza obligaciones con USIMRA',
  `email` char(60) DEFAULT NULL COMMENT 'Direccion de Correo Electronico',
  `carpetaenarchivo` char(10) DEFAULT NULL COMMENT 'Indice de Carpeta en Archivo',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Incilializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime NOT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) NOT NULL COMMENT 'Ultimo Usuario que Modifica el Registro',
  `mirroring` char(1) NOT NULL DEFAULT 'N',
  `fechabaja` date NOT NULL COMMENT 'Fecha en que deja de tener actividad',
  `motivobaja` text COMMENT 'Motivo por el que deja de tener actividad',
  `fechaefectivizacion` datetime DEFAULT NULL,
  `usuarioefectivizacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`cuit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Empresas Inactivas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `establecimientos`
--

CREATE TABLE IF NOT EXISTS `establecimientos` (
  `codigo` int(4) NOT NULL AUTO_INCREMENT,
  `codigoprestador` int(4) NOT NULL,
  `nombre` char(100) CHARACTER SET latin1 NOT NULL,
  `domicilio` char(50) CHARACTER SET latin1 NOT NULL,
  `codlocali` int(6) NOT NULL,
  `codprovin` int(2) NOT NULL,
  `indpostal` char(1) CHARACTER SET latin1 DEFAULT NULL,
  `numpostal` int(4) DEFAULT NULL,
  `alfapostal` char(3) CHARACTER SET latin1 DEFAULT NULL,
  `telefono1` bigint(10) DEFAULT NULL,
  `ddn1` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `telefono2` bigint(10) DEFAULT NULL,
  `ddn2` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `telefonofax` bigint(10) DEFAULT NULL,
  `ddnfax` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `email` char(60) CHARACTER SET latin1 DEFAULT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) CHARACTER SET latin1 NOT NULL,
  `fehamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=swe7 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadocivil`
--

CREATE TABLE IF NOT EXISTS `estadocivil` (
  `codestciv` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo de Estado Civil',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion del Estado Civil',
  PRIMARY KEY (`codestciv`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Estado Civil de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadocontablecontrol`
--

CREATE TABLE IF NOT EXISTS `estadocontablecontrol` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `anio` int(4) NOT NULL,
  `mes` int(2) NOT NULL,
  `remuneracion` decimal(20,2) NOT NULL,
  `obligacion` decimal(20,2) NOT NULL,
  `pagos` decimal(20,2) NOT NULL,
  `incobrables` decimal(20,2) NOT NULL,
  `diferencia` decimal(20,2) NOT NULL,
  `patharchivo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `discoinicio` int(4) NOT NULL,
  `discofin` int(4) NOT NULL,
  `fechadesde` date NOT NULL,
  `fechahasta` date NOT NULL,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadosdeacuerdos`
--

CREATE TABLE IF NOT EXISTS `estadosdeacuerdos` (
  `codigo` int(1) unsigned NOT NULL COMMENT 'Codigo para los Estados de Acuerdos',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Estados de Acuerdos',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para los Estados de Acuerdos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadosprocesales`
--

CREATE TABLE IF NOT EXISTS `estadosprocesales` (
  `codigo` int(2) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Estado Procesal',
  `descripcion` char(100) NOT NULL COMMENT 'Descripcion',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Codificadora de Estados Procesales' AUTO_INCREMENT=11 ;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiadebajausimra`
--

CREATE TABLE IF NOT EXISTS `familiadebajausimra` (
  `id` int(7) NOT NULL,
  `nrcuit` varchar(11) DEFAULT NULL,
  `nrcuil` varchar(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apelli` varchar(50) DEFAULT NULL,
  `codpar` varchar(16) NOT NULL,
  `ssexxo` varchar(10) DEFAULT NULL,
  `fecnac` date DEFAULT NULL,
  `fecing` date DEFAULT NULL,
  `tipdoc` varchar(5) DEFAULT NULL,
  `nrodoc` varchar(11) DEFAULT NULL,
  `benefi` char(2) DEFAULT NULL,
  `bajada` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiares`
--

CREATE TABLE IF NOT EXISTS `familiares` (
  `nroafiliado` int(9) unsigned NOT NULL,
  `nroorden` int(3) unsigned NOT NULL,
  `tipoparentesco` int(2) unsigned NOT NULL,
  `apellidoynombre` char(100) NOT NULL,
  `tipodocumento` char(2) NOT NULL,
  `nrodocumento` int(10) unsigned NOT NULL,
  `fechanacimiento` date NOT NULL,
  `nacionalidad` int(3) unsigned NOT NULL,
  `sexo` char(1) NOT NULL,
  `ddn` char(5) DEFAULT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `fechaobrasocial` date NOT NULL,
  `discapacidad` int(2) unsigned NOT NULL,
  `certificadodiscapacidad` int(1) unsigned DEFAULT NULL,
  `estudia` int(1) unsigned NOT NULL,
  `certificadoestudio` int(1) unsigned DEFAULT NULL,
  `emisioncertificadoestudio` date DEFAULT NULL COMMENT 'Fecha de Emision del Certificado de Estudio',
  `vencimientocertificadoestudio` date DEFAULT NULL COMMENT 'Fecha de Vto del Certificado de Estudio',
  `cuil` char(11) NOT NULL,
  `emitecarnet` int(1) unsigned NOT NULL DEFAULT '0',
  `cantidadcarnet` int(4) unsigned NOT NULL DEFAULT '0',
  `fechacarnet` date DEFAULT NULL,
  `lote` char(14) DEFAULT NULL COMMENT 'Lote de impresion en que fue impreso el carnet',
  `tipocarnet` char(1) DEFAULT NULL,
  `vencimientocarnet` date DEFAULT NULL,
  `informesss` int(1) unsigned NOT NULL,
  `tipoinformesss` char(1) DEFAULT NULL,
  `fechainformesss` datetime DEFAULT NULL,
  `usuarioinformesss` char(50) DEFAULT NULL,
  `foto` mediumblob,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  `mirroring` char(1) NOT NULL,
  PRIMARY KEY (`nroafiliado`,`nroorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Beneficiarios Familiares';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiaresdebaja`
--

CREATE TABLE IF NOT EXISTS `familiaresdebaja` (
  `nroafiliado` int(9) unsigned NOT NULL,
  `nroorden` int(3) unsigned NOT NULL,
  `tipoparentesco` int(2) unsigned NOT NULL,
  `apellidoynombre` char(100) NOT NULL,
  `tipodocumento` char(2) NOT NULL,
  `nrodocumento` int(10) unsigned NOT NULL,
  `fechanacimiento` date NOT NULL,
  `nacionalidad` int(3) unsigned NOT NULL,
  `sexo` char(1) NOT NULL,
  `ddn` char(5) DEFAULT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `fechaobrasocial` date NOT NULL,
  `discapacidad` int(2) unsigned NOT NULL,
  `certificadodiscapacidad` int(1) unsigned DEFAULT NULL,
  `estudia` int(1) unsigned NOT NULL,
  `certificadoestudio` int(1) unsigned DEFAULT NULL,
  `emisioncertificadoestudio` date DEFAULT NULL COMMENT 'Fecha de Emision del Certificado de Estudio',
  `vencimientocertificadoestudio` date DEFAULT NULL COMMENT 'Fecha de Vto del Certificado de Estudio',
  `cuil` char(11) NOT NULL,
  `emitecarnet` int(1) unsigned NOT NULL DEFAULT '0',
  `cantidadcarnet` int(4) unsigned NOT NULL DEFAULT '0',
  `fechacarnet` date DEFAULT NULL,
  `lote` char(14) DEFAULT NULL COMMENT 'Lote de impresion en que fue impreso el carnet',
  `tipocarnet` char(1) DEFAULT NULL,
  `vencimientocarnet` date DEFAULT NULL,
  `informesss` int(1) unsigned NOT NULL,
  `tipoinformesss` char(1) DEFAULT NULL,
  `fechainformesss` datetime DEFAULT NULL,
  `usuarioinformesss` char(50) DEFAULT NULL,
  `foto` mediumblob,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  `mirroring` char(1) NOT NULL,
  `fechabaja` date NOT NULL,
  `motivobaja` text,
  `fechaefectivizacion` datetime DEFAULT NULL,
  `usuarioefectivizacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroafiliado`,`nroorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Beneficiarios Familiares de Baja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familiausimra`
--

CREATE TABLE IF NOT EXISTS `familiausimra` (
  `id` int(7) NOT NULL,
  `nrcuit` varchar(11) NOT NULL DEFAULT '',
  `nrcuil` varchar(11) NOT NULL DEFAULT '',
  `nombre` varchar(50) NOT NULL DEFAULT '',
  `apelli` varchar(50) NOT NULL DEFAULT '',
  `codpar` varchar(16) NOT NULL DEFAULT '',
  `ssexxo` varchar(10) NOT NULL DEFAULT '',
  `fecnac` date NOT NULL DEFAULT '0000-00-00',
  `fecing` date NOT NULL DEFAULT '0000-00-00',
  `tipdoc` varchar(5) NOT NULL DEFAULT '',
  `nrodoc` varchar(11) NOT NULL DEFAULT '',
  `benefi` char(2) NOT NULL DEFAULT '',
  `bajada` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestoresdeacuerdos`
--

CREATE TABLE IF NOT EXISTS `gestoresdeacuerdos` (
  `codigo` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo para Gestor de Acuerdo',
  `apeynombre` char(100) NOT NULL COMMENT 'Apellido y Nombre del Gestor',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Codificadora de Gestores de Acuerdos' AUTO_INCREMENT=114 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hipertension`
--

CREATE TABLE IF NOT EXISTS `hipertension` (
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
-- Estructura de tabla para la tabla `impresioncarnets`
--

CREATE TABLE IF NOT EXISTS `impresioncarnets` (
  `lote` char(14) NOT NULL COMMENT 'Lote de impresion',
  `usuarioemision` char(50) NOT NULL COMMENT 'Usuario que emite el lote',
  `fechaemision` datetime NOT NULL COMMENT 'Fecha de emision del lote',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Delegacion a la que pertenece el lote',
  `totaltitulares` int(4) unsigned NOT NULL COMMENT 'Cantidad total de titulares en el lote',
  `totalcarnetsazul` int(4) unsigned NOT NULL COMMENT 'Cantidad total de carnets ospim (azul) en el lote',
  `totalhojasazul` int(4) unsigned NOT NULL COMMENT 'Cantidad total de hojas para carnets ospim (azul) en el lote',
  `marcaimpresionazul` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para carnets ospim (azul) impreso (0 - No / 1 - Si)',
  `totalcarnetsbordo` int(4) unsigned NOT NULL COMMENT 'Cantidad total de carnets solo ospim (bordo) en el lote',
  `totalhojasbordo` int(4) unsigned NOT NULL COMMENT 'Cantidad total de hojas para carnets solo ospim (bordo) en el lote',
  `marcaimpresionbordo` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para carnets solo ospim (bordo) impreso (0 - No / 1 - Si)',
  `totalcarnetsrojo` int(4) unsigned NOT NULL COMMENT 'Cantidad total de carnets por opcion (rojo) en el lote',
  `totalhojasrojo` int(4) unsigned NOT NULL COMMENT 'Cantidad total de hojas para carnets por opcion (rojo) en el lote',
  `marcaimpresionrojo` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para carnets por opcion (rojo) impreso (0 - No / 1 - Si)',
  `totalcarnetsverde` int(4) unsigned NOT NULL COMMENT 'Cantidad total de carnets usimra (verde) en el lote',
  `totalhojasverde` int(4) unsigned NOT NULL COMMENT 'Cantidad total de hojas para carnets usimra (verde) en el lote',
  `marcaimpresionverde` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para carnets usimra (verde) impreso (0 - No / 1 - Si)',
  `marcaimpresionlistado` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para listado impreso (0 - No / 1 - Si)',
  `marcaimpresionnota` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para nota impresa (0 - No / 1 - Si)',
  `marcacierreimpresion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Marca para lote impreso (0 - No / 1 - Si)',
  `usuarioimpresion` char(50) DEFAULT NULL COMMENT 'Usuario que imprime el lote',
  `fechaimpresion` datetime DEFAULT NULL COMMENT 'Fecha de impresion del lote',
  PRIMARY KEY (`lote`,`usuarioemision`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Lotes de Impresion de Carnets';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incapacidad`
--

CREATE TABLE IF NOT EXISTS `incapacidad` (
  `codincapa` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo de Incapacidad',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Codigo de Incapacidad',
  PRIMARY KEY (`codincapa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Incapacidad de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspecfiscalizospim`
--

CREATE TABLE IF NOT EXISTS `inspecfiscalizospim` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion OSPIM',
  `inspectorasignado` int(3) unsigned NOT NULL COMMENT 'Inspector Asignado al Procedimiento',
  `fechaasignado` date NOT NULL COMMENT 'Fecha en que se Asigna a Procedimiento',
  `diasefectivizacion` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Maximo de dias en que debe Efectivizarse el Procedimiento',
  `adjuntadocumentos` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicador de Documentacion Adjunta para Realizar el Procedimiento ( 0 / No - 1 / Si)',
  `detalledocumentos` text COMMENT 'Descripcion en Detalle de los Documentos que se adjuntan',
  `formaenviodocumentos` int(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Forma de Envio de la Documentacion',
  `fecharecibodocumentos` date DEFAULT NULL COMMENT 'Fecha en que Recepciona la Documentacion el Inspector a Cargo del Procedimiento',
  `fechadevoluciondocumentos` date DEFAULT NULL COMMENT 'Fecha de Devolucion de Documentacion Enviada al Inspector',
  `inspeccionefectuada` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicador de Inspeccion Efectuada ( 0 / No - 1 / Si)',
  `fechainspeccion` date DEFAULT NULL COMMENT 'Fecha en que se realiza la Inspeccion / Cierre del Procedimiento',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Requerimientos de Fiscalizacion de OSPIM con Procedimiento de Inspeccion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspecfiscalizusimra`
--

CREATE TABLE IF NOT EXISTS `inspecfiscalizusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro de Requerimiento de Fiscalizacion USIMRA',
  `inspectorasignado` int(3) unsigned NOT NULL COMMENT 'Inspector Asignado al Procedimiento',
  `fechaasignado` date NOT NULL COMMENT 'Fecha en que se Asigna a Procedimiento',
  `diasefectivizacion` int(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Maximo de dias en que debe Efectivizarse el Procedimiento',
  `adjuntadocumentos` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicador de Documentacion Adjunta para Realizar el Procedimiento ( 0 / No - 1 / Si)',
  `detalledocumentos` text COMMENT 'Descripcion en Detalle de los Documentos que se adjuntan',
  `formaenviodocumentos` int(2) unsigned NOT NULL DEFAULT '0' COMMENT 'Forma de Envio de la Documentacion',
  `fecharecibodocumentos` date DEFAULT NULL COMMENT 'Fecha en que Recepciona la Documentacion el Inspector a Cargo del Procedimiento',
  `fechadevoluciondocumentos` date DEFAULT NULL COMMENT 'Fecha de Devolucion de Documentacion Enviada al Inspector',
  `inspeccionefectuada` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicador de Inspeccion Efectuada ( 0 / No - 1 / Si)',
  `fechainspeccion` date DEFAULT NULL COMMENT 'Fecha en que se realiza la Inspeccion / Cierre del Procedimiento',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Requerimientos de Fiscalizacion de USIMRA con Procedimiento de Inspeccion';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inspectores`
--

CREATE TABLE IF NOT EXISTS `inspectores` (
  `codigo` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Inspector',
  `apeynombre` char(50) NOT NULL COMMENT 'Apellido y Nombre del Inspector',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Delegacion sobre la que posee jurisdiccion',
  PRIMARY KEY (`codigo`,`codidelega`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla codificadora de Inspectores' AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumo`
--

CREATE TABLE IF NOT EXISTS `insumo` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` char(50) NOT NULL,
  `numeroserie` char(50) NOT NULL,
  `descripcion` text NOT NULL,
  `puntopedido` int(3) unsigned NOT NULL,
  `stockminimo` int(3) unsigned NOT NULL,
  `puntopromedio` int(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `insumoproducto`
--

CREATE TABLE IF NOT EXISTS `insumoproducto` (
  `idinsumo` int(3) unsigned NOT NULL,
  `idproducto` int(3) unsigned NOT NULL,
  PRIMARY KEY (`idinsumo`,`idproducto`),
  KEY `idproducto` (`idproducto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jurisdiccion`
--

CREATE TABLE IF NOT EXISTS `jurisdiccion` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Codigo de Delegacion a la que pertenece',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `indpostal` char(1) NOT NULL COMMENT 'Indice de Provincia en el C.P.',
  `numpostal` int(4) unsigned NOT NULL COMMENT 'Componente Numerico en el C.P.',
  `alfapostal` char(3) DEFAULT NULL COMMENT 'Componente Alfabetico en el C.P.',
  `codlocali` int(6) unsigned NOT NULL COMMENT 'Codigo de Localidad',
  `domireal` char(50) NOT NULL COMMENT 'Domicilio Real de la Empresa',
  `ddn` char(5) DEFAULT NULL COMMENT 'Discado Directo Nacional',
  `telefono` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono',
  `contactel` char(50) DEFAULT NULL COMMENT 'Contacto',
  `email` char(60) DEFAULT NULL COMMENT 'Direccion de Correo Electronico',
  `disgdinero` decimal(5,2) NOT NULL COMMENT 'Porcentaje de Disgregacion de Masas Dinerarias',
  PRIMARY KEY (`cuit`,`codidelega`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Jurisdicciones a las que pertenecen las empresas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `juzgados`
--

CREATE TABLE IF NOT EXISTS `juzgados` (
  `codigojuzgado` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Juzgado',
  `denominacion` char(100) NOT NULL COMMENT 'Denominacion del Juzgado',
  `fueros` char(100) NOT NULL COMMENT 'Fueros del Juzgado',
  PRIMARY KEY (`codigojuzgado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Codificadora de Juzgados' AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidades`
--

CREATE TABLE IF NOT EXISTS `localidades` (
  `codlocali` int(6) unsigned NOT NULL COMMENT 'Codigo de Localidad',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia',
  `numpostal` int(4) unsigned NOT NULL COMMENT 'Componente Numerico en el C.P.',
  `nomlocali` char(50) NOT NULL COMMENT 'Nombre de la Localidad',
  PRIMARY KEY (`codlocali`),
  KEY `FK_LOCALIDADES_CODPROVIN` (`codprovin`),
  KEY `Index_NUMPOSTAL` (`numpostal`,`codlocali`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Localidades';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maternoinfantil`
--

CREATE TABLE IF NOT EXISTS `maternoinfantil` (
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
-- Estructura de tabla para la tabla `nacionalidad`
--

CREATE TABLE IF NOT EXISTS `nacionalidad` (
  `codnacion` int(3) unsigned zerofill NOT NULL COMMENT 'Codigo de Nacionalidad',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Codigo de Nacionalidad',
  PRIMARY KEY (`codnacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Nacionalidades de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomencladores`
--

CREATE TABLE IF NOT EXISTS `nomencladores` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nominasddjj`
--

CREATE TABLE IF NOT EXISTS `nominasddjj` (
  `nrodisco` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro de Disco/Archivo Asignado por el Procesamiento en OSPIM',
  `fechaarchivoafip` date NOT NULL COMMENT 'Fecha de Generacion del Archivo en AFIP',
  `fechaemailafip` datetime NOT NULL COMMENT 'Fecha del Email de AFIP que Comunica la generacion del Archivo',
  `registrosafip` int(6) unsigned NOT NULL COMMENT 'Cantidad Total de Registros en el Archivo Informado por AFIP',
  `fechaprocesoospim` datetime DEFAULT NULL COMMENT 'Fecha en que se procesa el Archivo en OSPIM',
  `usuarioprocesoospim` char(100) DEFAULT NULL COMMENT 'Usuario que procesa el Archivo en OSPIM',
  `registrosprocesoospim` int(6) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Registros Resultantes del proceso del Archivo en OSPIM',
  `carpetaarchivoospim` text COMMENT 'Carpeta en el Servidor donde se almacena el Archivo Procesado en OSPIM',
  PRIMARY KEY (`nrodisco`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Identificacion en OSPIM Archivos de Nominas AFIP por DDJJ' AUTO_INCREMENT=358 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedadessss`
--

CREATE TABLE IF NOT EXISTS `novedadessss` (
  `codigornos` int(6) unsigned NOT NULL COMMENT 'Codigo de Obra Social segun R.N.O.S.',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. del Empleador',
  `cuiltitul` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Titular',
  `codparent` int(2) unsigned NOT NULL COMMENT 'Codigo de Parentesco segun SSS',
  `cuilfami` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Familiar',
  `codtipdoc` char(2) NOT NULL COMMENT 'Codigo de Tipo de Documento segun SSS',
  `nrodocum` int(8) unsigned NOT NULL COMMENT 'Numero de Documento del Beneficiario',
  `apeynom` char(30) NOT NULL COMMENT 'Apellido y Nombre del Beneficiario',
  `sexo` char(1) NOT NULL COMMENT 'Sexo del Beneficiario',
  `codestciv` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Estado Civil segun SSS',
  `fechanaci` date NOT NULL COMMENT 'Fecha de Nacimiento en Formato DDMMAAAA',
  `codnacion` int(3) unsigned DEFAULT NULL COMMENT 'Codigo de Nacionalidad segun SSS',
  `calledomi` char(20) DEFAULT NULL COMMENT 'Calle del Domicilio del Beneficiario',
  `puertadomi` char(5) DEFAULT NULL COMMENT 'Nro. de Puerta del Domicilio del Beneficiario',
  `pisodomi` char(4) DEFAULT NULL COMMENT 'Piso del Domicilio del Beneficiario',
  `deptodomi` char(4) DEFAULT NULL COMMENT 'Depto. del Domicilio del Beneficiario',
  `localidad` char(20) DEFAULT NULL COMMENT 'Localidad en la que vive el Beneficiario',
  `codpostal` char(8) DEFAULT NULL COMMENT 'Codigo Postal que corresponde al Beneficiario',
  `codprovin` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Provincia en la que vive el Beneficiario',
  `tipodomici` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Tipo de Domicilio segun SSS',
  `telefono` char(20) DEFAULT NULL COMMENT 'Telefono del Beneficiario',
  `codsitrev` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Situacion de Revista segun SSS',
  `codincapa` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Incapacidad segun SSS',
  `codtiptit` int(2) unsigned NOT NULL COMMENT 'Codigo de Tipo de Beneficiario Titular segun SSS',
  `fecaltaos` date DEFAULT NULL COMMENT 'Fecha Alta en la O.S. con formato DDMMAAAA',
  `fecciepres` date NOT NULL COMMENT 'Fecha de Presentacion del registro en la SSS por la O.S. con formato DDMMAAAA',
  `movimiento` char(2) NOT NULL COMMENT 'Codigo de definiciones y acciones a seguir por parte de la O.S.',
  `detallenov` char(11) DEFAULT NULL COMMENT 'Para cada movimiento contiene el dato de la accion a seguir',
  `anonovedad` int(4) unsigned NOT NULL COMMENT 'Año del Periodo al que corresponden las novedades',
  `mesnovedad` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo al que corresponden las novedades',
  PRIMARY KEY (`cuit`,`cuiltitul`,`codparent`,`cuilfami`,`anonovedad`,`mesnovedad`) USING BTREE,
  KEY `FK_NOVEDADESSSS_PARENTESCO` (`codparent`),
  KEY `FK_NOVEDADESSSS_TIPODOCU` (`codtipdoc`),
  KEY `FK_NOVEDADESSSS_ESTADOCIVIL` (`codestciv`),
  KEY `FK_NOVEDADESSSS_NACIONALIDAD` (`codnacion`),
  KEY `FK_NOVEDADESSSS_PROVINCIA` (`codprovin`),
  KEY `FK_NOVEDADESSSS_DOMICILIO` (`tipodomici`),
  KEY `FK_NOVEDADESSSS_SITREVISTA` (`codsitrev`),
  KEY `FK_NOVEDADESSSS_INCAPACIDAD` (`codincapa`),
  KEY `FK_NOVEDADESSSS_TIPOTITULAR` (`codtiptit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Novedades generadas hacia el padron de la O.S. por la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `odontologica`
--

CREATE TABLE IF NOT EXISTS `odontologica` (
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
-- Estructura de tabla para la tabla `origencomprobanteusimra`
--

CREATE TABLE IF NOT EXISTS `origencomprobanteusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta Bancaria',
  `fechaemision` date NOT NULL COMMENT 'Fecha de Emision del Resumen Bancario',
  `nroordenimputacion` int(4) unsigned NOT NULL COMMENT 'Numero de Orden de la Imputacion',
  `sistemacomprobante` char(1) NOT NULL COMMENT 'Sistema del Comprobante que da Origen a la Imputacion / M Manual - E Electronico',
  `fechacomprobante` date NOT NULL COMMENT 'Fecha del Comprobante que Origina la Imputacion',
  `nrocomprobante` int(4) unsigned NOT NULL COMMENT 'Nro de Comprobante que Origina la Imputacion',
  `comprobante` char(100) NOT NULL COMMENT 'Comprobante que Origina la Imputacion',
  PRIMARY KEY (`codigocuenta`,`fechaemision`,`nroordenimputacion`,`sistemacomprobante`,`fechacomprobante`,`nrocomprobante`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Origen de los Comprobantes que concilian las imputaciones de';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padronesddjj`
--

CREATE TABLE IF NOT EXISTS `padronesddjj` (
  `nrodisco` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro de Disco/Archivo Asignado por el Procesamiento en OSPIM',
  `fechaarchivoafip` date NOT NULL COMMENT 'Fecha de Generacion del Archivo en AFIP',
  `fechaemailafip` datetime NOT NULL COMMENT 'Fecha del Email de AFIP que Comunica la generacion del Archivo',
  `registrosafip` int(6) unsigned NOT NULL COMMENT 'Cantidad Total de Registros en el Archivo Informado por AFIP',
  `fechaprocesoospim` datetime DEFAULT NULL COMMENT 'Fecha en que se procesa el Archivo en OSPIM',
  `usuarioprocesoospim` char(100) DEFAULT NULL COMMENT 'Usuario que procesa el Archivo en OSPIM',
  `registrosprocesoospim` int(6) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Registros Resultantes del proceso del Archivo en OSPIM',
  `carpetaarchivoospim` text COMMENT 'Carpeta en el Servidor donde se almacena el Archivo Procesado en OSPIM',
  `verificaempresasospim` int(1) unsigned DEFAULT '0' COMMENT 'Indicador de busqueda de nuevas empresas para apertura',
  `fechaverificaempresasospim` datetime DEFAULT NULL COMMENT 'Fecha en que se procesa la busqueda de nuevas empresas para apertura',
  `altasempresasospim` int(4) unsigned DEFAULT '0' COMMENT 'Cantidad total de alta de empresas desde el proceso verificador',
  PRIMARY KEY (`nrodisco`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Identificacion en OSPIM Archivos de Padron de CUITs de AFIP por DDJJ' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padronsss`
--

CREATE TABLE IF NOT EXISTS `padronsss` (
  `codigornos` int(6) unsigned NOT NULL COMMENT 'Codigo de Obra Social segun R.N.O.S.',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. del Empleador',
  `cuiltitular` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Titular',
  `parentesco` int(2) unsigned NOT NULL COMMENT 'Codigo de Parentesco segun SSS',
  `cuilfamiliar` char(11) NOT NULL COMMENT 'C.U.I.L. del Beneficiario Familiar',
  `tipodocumento` char(2) NOT NULL COMMENT 'Codigo de Tipo de Documento segun SSS',
  `nrodocumento` int(8) unsigned NOT NULL COMMENT 'Numero de Documento del Beneficiario',
  `apellidoynombre` char(30) NOT NULL COMMENT 'Apellido y Nombre del Beneficiario',
  `sexo` char(1) NOT NULL COMMENT 'Sexo del Beneficiario',
  `estadocivil` int(2) unsigned NOT NULL COMMENT 'Codigo de Estado Civil segun SSS',
  `fechanacimiento` date NOT NULL COMMENT 'Fecha de Nacimiento en Formato DDMMAAAA',
  `nacionalidad` int(3) unsigned NOT NULL COMMENT 'Codigo de Nacionalidad segun SSS',
  `calledomicilio` char(20) NOT NULL COMMENT 'Calle del Domicilio del Beneficiario',
  `puertadomicilio` char(5) NOT NULL COMMENT 'Nro. de Puerta del Domicilio del Beneficiario',
  `pisodomicilio` char(4) NOT NULL COMMENT 'Piso del Domicilio del Beneficiario',
  `deptodomicilio` char(4) NOT NULL COMMENT 'Depto. del Domicilio del Beneficiario',
  `localidad` char(20) NOT NULL COMMENT 'Localidad en la que vive el Beneficiario',
  `codigopostal` char(8) NOT NULL COMMENT 'Codigo Postal que corresponde al Beneficiario',
  `codprovin` int(2) unsigned NOT NULL COMMENT 'Codigo de Provincia en la que vive el Beneficiario',
  `tipodomicilio` int(2) unsigned DEFAULT NULL COMMENT 'Codigo de Tipo de Domicilio segun SSS',
  `telefono` char(20) DEFAULT NULL COMMENT 'Telefono del Beneficiario',
  `situacionrevista` int(2) unsigned NOT NULL COMMENT 'Codigo de Situacion de Revista segun SSS',
  `incapacidad` int(2) unsigned NOT NULL COMMENT 'Codigo de Incapacidad segun SSS',
  `tipotitular` int(1) unsigned NOT NULL COMMENT 'Codigo de Tipo de Beneficiario Titular segun SSS',
  `fechaaltaos` date NOT NULL COMMENT 'Fecha Alta en la O.S. con formato DDMMAAAA',
  `fechapresentacion` date NOT NULL COMMENT 'Fecha de Presentacion del registro en la SSS por la O.S. con formato DDMMAAAA',
  `verificacioncuil` int(3) unsigned zerofill NOT NULL COMMENT 'Resultado de la verificacion de la SSS del cuil del beneficiario. Rango de Error: 200 a 400',
  `cuilinformadoos` char(11) DEFAULT NULL COMMENT 'C.U.I.L. informado por la O.S. para el caso en que la SSS lo modifico',
  `tipotitularsijp` char(2) DEFAULT NULL COMMENT 'Tipo de Beneficiario segun SIJP que difiere del de la O.S.',
  `cuitsijp` char(11) DEFAULT NULL COMMENT 'C.U.I.T. del empleador que declara al beneficiario',
  `ossijp` int(6) unsigned DEFAULT NULL COMMENT 'O.S. declarada en el SIJP',
  `periodosijp` char(6) DEFAULT NULL COMMENT 'Ultima declaracion jurada en SIJP con formato AAAAMM',
  `osopcion` int(6) unsigned DEFAULT NULL COMMENT 'La SSS informa el RNOS de la O.S. en caso de opcion vigente',
  `periodoopcion` char(6) DEFAULT NULL COMMENT 'Fecha desde la cual esta vigente la opcion con formato AAAAMM',
  PRIMARY KEY (`cuit`,`cuiltitular`,`parentesco`,`cuilfamiliar`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Padron Consolidado de OSPIM segun la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagosbanco`
--

CREATE TABLE IF NOT EXISTS `pagosbanco` (
  `anopago` int(4) NOT NULL,
  `mespago` int(2) NOT NULL,
  `fechapago` date NOT NULL,
  `importe` decimal(13,2) unsigned zerofill NOT NULL,
  `nrolegajo` int(9) unsigned zerofill NOT NULL,
  `pertenencia` char(1) NOT NULL,
  PRIMARY KEY (`anopago`,`mespago`,`nrolegajo`,`fechapago`,`pertenencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parametros`
--

CREATE TABLE IF NOT EXISTS `parametros` (
  `id` int(4) unsigned NOT NULL COMMENT 'Indice de Acceso',
  `valorgastoadmin` decimal(5,2) NOT NULL COMMENT 'Valor porcentual para Gastos Administrativos',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Campos para parametrizacion del sistema';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parentesco`
--

CREATE TABLE IF NOT EXISTS `parentesco` (
  `codparent` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo de Parentesco',
  `descrip` char(100) NOT NULL COMMENT 'Descripcion para Codigo de Parentesco',
  PRIMARY KEY (`codparent`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Parentesco de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patologiasautorizaciones`
--

CREATE TABLE IF NOT EXISTS `patologiasautorizaciones` (
  `codigo` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Patologia',
  `descripcion` char(150) NOT NULL COMMENT 'Descripcion de la Patologia',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Clasificacion de Patologias para el modulo de Autorizaciones' AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodosanterioresusimra`
--

CREATE TABLE IF NOT EXISTS `periodosanterioresusimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago Origen',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago Origen',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago Origen',
  `mesanterior` int(2) unsigned NOT NULL COMMENT 'Mes del Pago Incluido en el Pago Origen',
  `anoanterior` int(4) unsigned NOT NULL COMMENT 'Anio del Pago Incluido en el Pago Origen',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`,`anoanterior`,`mesanterior`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Periodos Cancelados por Intermedio de un Pago de Seguro de Vida y Sepelio USIMRA';

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas`
--

CREATE TABLE IF NOT EXISTS `practicas` (
  `idpractica` int(8) NOT NULL AUTO_INCREMENT,
  `codigopractica` char(8) NOT NULL,
  `tipopractica` int(2) NOT NULL,
  `codigocomplejidad` int(2) NOT NULL,
  `nomenclador` int(1) NOT NULL,
  `descripcion` text NOT NULL,
  `unihonorario` decimal(4,2) DEFAULT NULL,
  `unihonorarioespecialista` decimal(4,2) DEFAULT NULL,
  `unihonorarioayudante` decimal(4,2) DEFAULT NULL,
  `unihonorarioanestesista` decimal(4,2) DEFAULT NULL,
  `unigastos` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`idpractica`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2422 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicascategorias`
--

CREATE TABLE IF NOT EXISTS `practicascategorias` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `tipoprestador` int(3) unsigned NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prenatal`
--

CREATE TABLE IF NOT EXISTS `prenatal` (
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
-- Estructura de tabla para la tabla `prestadores`
--

CREATE TABLE IF NOT EXISTS `prestadores` (
  `codigoprestador` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Código identificador de prestador',
  `nombre` char(100) NOT NULL COMMENT 'Nombre o Razón Social del Prestador',
  `domicilio` char(50) NOT NULL,
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
  `personeria` int(1) unsigned DEFAULT NULL COMMENT '1: Fisico - 2: Juridico',
  `tratamiento` int(2) unsigned DEFAULT NULL COMMENT 'Como se llamara al profesional para la cartas o ordenes de pago',
  `matriculanacional` char(10) DEFAULT NULL COMMENT 'Matricula Nacional',
  `matriculaprovincial` char(10) DEFAULT NULL COMMENT 'Matricula Provincial',
  `numeroregistrosss` varchar(10) DEFAULT NULL COMMENT 'Numero de registro en la Superintendencia de Servicio de Salud',
  `vtoregistrosss` date DEFAULT NULL,
  `numeroregistrosnr` varchar(10) DEFAULT NULL,
  `vtoregistrosnr` date DEFAULT NULL,
  `capitado` int(1) unsigned NOT NULL COMMENT '1: es capitado - 0: no es capitado',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fehamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) NOT NULL,
  PRIMARY KEY (`codigoprestador`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestadorjurisdiccion`
--

CREATE TABLE IF NOT EXISTS `prestadorjurisdiccion` (
  `codigoprestador` int(4) unsigned NOT NULL DEFAULT '0',
  `codidelega` int(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigoprestador`,`codidelega`),
  KEY `codidelega` (`codidelega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestadornomenclador`
--

CREATE TABLE IF NOT EXISTS `prestadornomenclador` (
  `codigoprestador` int(4) NOT NULL,
  `codigonomenclador` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestadorservicio`
--

CREATE TABLE IF NOT EXISTS `prestadorservicio` (
  `codigoprestador` int(4) unsigned NOT NULL DEFAULT '0',
  `codigoservicio` int(2) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigoprestador`,`codigoservicio`),
  KEY `codigoservicio` (`codigoservicio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE IF NOT EXISTS `producto` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` char(50) NOT NULL,
  `numeroserie` char(50) DEFAULT NULL,
  `valororiginal` decimal(9,2) NOT NULL,
  `numeropoliza` char(50) DEFAULT NULL,
  `activo` int(1) unsigned NOT NULL,
  `descripcion` text,
  `sistemaoperativo` varchar(150) DEFAULT NULL,
  `idso` varchar(150) DEFAULT NULL,
  `office` varchar(150) DEFAULT NULL,
  `idoffice` varchar(150) DEFAULT NULL,
  `fechainicio` date DEFAULT NULL,
  `fechabaja` date DEFAULT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionales`
--

CREATE TABLE IF NOT EXISTS `profesionales` (
  `codigoprofesional` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Código identificador del profesional',
  `codigoprestador` int(4) unsigned NOT NULL COMMENT 'Codigo de prestador que corresponde el profesional',
  `nombre` char(100) NOT NULL COMMENT 'Nombre o Razón Social del Profesional',
  `idcategoria` int(3) NOT NULL,
  `domicilio` char(50) NOT NULL,
  `codlocali` int(6) unsigned NOT NULL,
  `codprovin` int(2) unsigned NOT NULL,
  `indpostal` char(1) DEFAULT NULL,
  `numpostal` int(4) unsigned DEFAULT NULL,
  `alfapostal` char(3) DEFAULT NULL,
  `telefono1` bigint(10) DEFAULT NULL,
  `ddn1` char(5) DEFAULT NULL,
  `telefono2` bigint(10) DEFAULT NULL,
  `ddn2` char(5) DEFAULT NULL,
  `telefonofax` bigint(10) DEFAULT NULL,
  `ddnfax` char(5) DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `cuit` char(11) NOT NULL,
  `tratamiento` int(2) unsigned NOT NULL COMMENT 'Como se llamara al profesional para la cartas o ordenes de pago',
  `matriculanacional` char(20) NOT NULL COMMENT 'Matricula Nacional',
  `matriculaprovincial` char(20) NOT NULL COMMENT 'Matricula Provincial',
  `numeroregistrosss` int(20) DEFAULT NULL COMMENT 'Numero de registro en la Superintendencia de Servicio de Salud',
  `activo` int(1) NOT NULL DEFAULT '1',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fehamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) NOT NULL,
  PRIMARY KEY (`codigoprofesional`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE IF NOT EXISTS `proveedor` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(100) NOT NULL,
  `telefono` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE IF NOT EXISTS `provincia` (
  `codprovin` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo de Provincia',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Codigo de Provincia',
  `indpostal` char(1) DEFAULT NULL COMMENT 'Indice de Provincia en el C.P.',
  `codafip` int(2) unsigned DEFAULT NULL,
  `codzeus` int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`codprovin`),
  KEY `INDPOSTAL` (`indpostal`,`codprovin`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Provincias de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ramausimra`
--

CREATE TABLE IF NOT EXISTS `ramausimra` (
  `id` int(2) unsigned NOT NULL,
  `descripcion` char(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Ramas de empresas del Aplicativo DDJJ';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remesasusimra`
--

CREATE TABLE IF NOT EXISTS `remesasusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta Bancaria',
  `sistemaremesa` char(1) NOT NULL COMMENT 'Sistema de Origen de la Remesa / M Manual - E Electronico',
  `fecharemesa` date NOT NULL COMMENT 'Fecha de la Remesa',
  `nroremesa` int(4) unsigned NOT NULL COMMENT 'Numero de la Remesa',
  `importebruto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Bruto de la Remesa',
  `importecomision` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Comision Bancaria sobre la Remesa',
  `importeneto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Neto de la Remesa',
  `importefaima` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe que le corresponde a FAIMA de la Remesa',
  `importebrutoremitos` decimal(9,2) DEFAULT NULL COMMENT 'Total Bruto de los Remitos de la Remesa',
  `importecomisionesremitos` decimal(9,2) DEFAULT NULL COMMENT 'Total Comision Bancaria sobre los Remitos de la Remesa',
  `importenetoremitos` decimal(9,2) DEFAULT NULL COMMENT 'Total Neto de los Remitos de la Remesa',
  `importeboletasaporte` decimal(9,2) DEFAULT NULL COMMENT 'Total de Aportes de las Boletas de la Remesa',
  `importeboletasrecargo` decimal(9,2) DEFAULT NULL COMMENT 'Total de Recargos de las Boletas de la Remesa',
  `importeboletasvarios` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos Varios de las Boletas de la Remesa',
  `importeboletaspagos` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos de las Boletas de la Remesa',
  `importeboletascuotas` decimal(9,2) DEFAULT NULL COMMENT 'Total de Cuotas de Acuerdos de las Boletas de la Remesa',
  `importeboletasbruto` decimal(9,2) DEFAULT NULL COMMENT 'Total Bruto de las Boletas de la Remesa',
  `cantidadboletas` int(5) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Boletas Conciliadas de la Remesa',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fechaacreditacion` date DEFAULT NULL COMMENT 'Fecha de Acreditacion en la Cuenta',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`codigocuenta`,`sistemaremesa`,`fecharemesa`,`nroremesa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `remitossueltosusimra`
--

CREATE TABLE IF NOT EXISTS `remitossueltosusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta Bancaria',
  `sistemaremito` char(1) NOT NULL COMMENT 'Sistema de Origen del Remito / M Manual - E Electronico',
  `fecharemito` date NOT NULL COMMENT 'Fecha del Remito',
  `nroremito` int(4) unsigned NOT NULL COMMENT 'Numero del Remito',
  `sucursalbanco` char(4) DEFAULT NULL COMMENT 'Sucursal del Banco Nacion',
  `importebruto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Bruto del Remito',
  `importecomision` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Comision Bancaria sobre el Remito',
  `importeneto` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe Neto del Remito',
  `importefaima` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT 'Importe que le corresponde a FAIMA del Remito',
  `boletasremito` int(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Cantidad Total de Boletas del Remito',
  `importeboletasaporte` decimal(9,2) DEFAULT NULL COMMENT 'Total de Aportes de las Boletas del Remito',
  `importeboletasrecargo` decimal(9,2) DEFAULT NULL COMMENT 'Total de Recargos de las Boletas del Remito',
  `importeboletasvarios` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos Varios de las Boletas del Remito',
  `importeboletaspagos` decimal(9,2) DEFAULT NULL COMMENT 'Total de Pagos de las Boletas del Remito',
  `importeboletascuotas` decimal(9,2) DEFAULT NULL COMMENT 'Total de Cuotas de Acuerdos de las Boletas del Remito',
  `importeboletasbruto` decimal(9,2) DEFAULT NULL COMMENT 'Total Bruto de las Boletas del Remito',
  `cantidadboletas` int(5) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Boletas Conciliadas del Remito',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fechaacreditacion` date DEFAULT NULL COMMENT 'Fecha de Acreditacion en la Cuenta',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`codigocuenta`,`sistemaremito`,`fecharemito`,`nroremito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reqfiscalizospim`
--

CREATE TABLE IF NOT EXISTS `reqfiscalizospim` (
  `nrorequerimiento` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro, de Requerimiento',
  `fecharequerimiento` date NOT NULL COMMENT 'Fecha de Requerimiento',
  `origenrequerimiento` int(1) unsigned NOT NULL COMMENT 'Origen del Requerimiento',
  `solicitarequerimiento` char(60) NOT NULL COMMENT 'Solicitante del Requerimiento',
  `motivorequerimiento` char(100) NOT NULL COMMENT 'Motivo del Requerimiento',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `codidelega` int(4) unsigned NOT NULL,
  `procesoasignado` int(1) unsigned NOT NULL COMMENT 'Proceso Asignado al Requerimiento',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  `requerimientoanulado` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Requerimiento Anulado (0 = No / 1 = SI))',
  `motivoanulacion` text COMMENT 'Motivo de Anulacion del Requerimiento',
  `fechaanulacion` datetime DEFAULT NULL COMMENT 'Fecha de Anulacion del Requerimiento',
  `usuarioanulacion` char(50) DEFAULT NULL COMMENT 'Usuario que Anula el Requerimiento',
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Requerimientos de Fiscalizacion de OSPIM' AUTO_INCREMENT=19912 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reqfiscalizusimra`
--

CREATE TABLE IF NOT EXISTS `reqfiscalizusimra` (
  `nrorequerimiento` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro, de Requerimiento',
  `fecharequerimiento` date NOT NULL COMMENT 'Fecha de Requerimiento',
  `origenrequerimiento` int(1) unsigned NOT NULL COMMENT 'Origen del Requerimiento',
  `solicitarequerimiento` char(60) NOT NULL COMMENT 'Solicitante del Requerimiento',
  `motivorequerimiento` char(100) NOT NULL COMMENT 'Motivo del Requerimiento',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `codidelega` int(4) unsigned NOT NULL,
  `procesoasignado` int(1) unsigned NOT NULL COMMENT 'Proceso Asignado al Requerimiento',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  `requerimientoanulado` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Requerimiento Anulado (0 = No / 1 = SI))',
  `motivoanulacion` text COMMENT 'Motivo de Anulacion del Requerimiento',
  `fechaanulacion` datetime DEFAULT NULL COMMENT 'Fecha de Anulacion del Requerimiento',
  `usuarioanulacion` char(50) DEFAULT NULL COMMENT 'Usuario que Anula el Requerimiento',
  PRIMARY KEY (`nrorequerimiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Requerimientos de Fiscalizacion de USIMRA' AUTO_INCREMENT=29663 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resumenusimra`
--

CREATE TABLE IF NOT EXISTS `resumenusimra` (
  `codigocuenta` int(2) unsigned NOT NULL COMMENT 'Codigo de Cuenta Bancaria',
  `fechaemision` date NOT NULL COMMENT 'Fecha de Emision del Resumen Bancario',
  `nroordenimputacion` int(4) unsigned NOT NULL COMMENT 'Numero de Orden de la Imputacion',
  `fechaimputacion` date NOT NULL COMMENT 'Fecha de la Imputacion',
  `importeimputado` decimal(9,2) NOT NULL COMMENT 'Importe Imputado',
  `tipoimputacion` char(1) NOT NULL COMMENT 'Tipo de Imputacion / C Credito - D Debito',
  `estadoconciliacion` int(1) unsigned NOT NULL DEFAULT '0' COMMENT 'Estado de Conciliacion / 0 No Conciliado - 1 Conciliado',
  `fechaconciliacion` datetime DEFAULT NULL COMMENT 'Fecha de Conciliacion',
  `usuarioconciliacion` char(50) DEFAULT NULL COMMENT 'Usuario de Conciliacion',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de Ultima Modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de Ultima Modificacion del Registro',
  PRIMARY KEY (`codigocuenta`,`fechaemision`,`nroordenimputacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Resumenes Bancarios de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `saludsexual`
--

CREATE TABLE IF NOT EXISTS `saludsexual` (
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretarias`
--

CREATE TABLE IF NOT EXISTS `secretarias` (
  `codigojuzgado` int(3) unsigned NOT NULL COMMENT 'Codigo de Juzgado',
  `codigosecretaria` int(3) unsigned NOT NULL COMMENT 'Codigo de Secretaria',
  `denominacion` char(100) NOT NULL COMMENT 'Denominacion de la Secretaria',
  PRIMARY KEY (`codigojuzgado`,`codigosecretaria`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Secretarias por Juzgado';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguvidausimra`
--

CREATE TABLE IF NOT EXISTS `seguvidausimra` (
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. de la Empresa',
  `mespago` int(2) unsigned NOT NULL COMMENT 'Mes del Pago',
  `anopago` int(4) unsigned NOT NULL COMMENT 'Anio del Pago',
  `nropago` int(3) unsigned NOT NULL COMMENT 'Nro de Pago',
  `periodoanterior` int(1) unsigned NOT NULL COMMENT 'Cancela Periodos Anteriores',
  `fechapago` date NOT NULL COMMENT 'Fecha de Pago',
  `cantidadpersonal` int(5) unsigned NOT NULL COMMENT 'Cantidad de Personal',
  `remuneraciones` decimal(12,2) unsigned NOT NULL COMMENT 'Total de Remuneraciones',
  `montorecargo` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de Recargo Incluido',
  `montopagado` decimal(9,2) unsigned NOT NULL COMMENT 'Total Pagado',
  `observaciones` text COMMENT 'Observaciones',
  `sistemacancelacion` char(1) NOT NULL COMMENT 'Sistema de Cancelacion del Pago - En tabla sistemascancelacion',
  `codigobarra` char(39) DEFAULT NULL COMMENT 'Codigo de Barra de la Boleta de Pago',
  `fechaacreditacion` date NOT NULL COMMENT 'Fecha en que se acredita el Pago',
  `fecharegistro` datetime NOT NULL COMMENT 'Fecha de Inicializacion del Registro',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que inicializa el Registro',
  `fechamodificacion` datetime DEFAULT NULL COMMENT 'Fecha de ultima modificacion del Registro',
  `usuariomodificacion` char(50) DEFAULT NULL COMMENT 'Usuario de ultima modificacion del Registro',
  PRIMARY KEY (`cuit`,`anopago`,`mespago`,`nropago`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Cabecera de Pagos de Seguro de Vida y Sepelio USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sistemascancelacion`
--

CREATE TABLE IF NOT EXISTS `sistemascancelacion` (
  `codigo` char(1) NOT NULL COMMENT 'Codigo para los Sistemas de Cancelacion de Cuotas de Acuerdos',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Sistemas de Cancelacion de Cuotas de Acuerdos',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para los Sistemas de Cancelacion de Cuotas de A';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `situacionrevista`
--

CREATE TABLE IF NOT EXISTS `situacionrevista` (
  `codsitrev` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo para Situacion de Revista',
  `descrip` char(100) NOT NULL COMMENT 'Descripcion para Situacion de Revista',
  PRIMARY KEY (`codsitrev`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codific. p/ Situacion de Revista(status laboral) de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
  `id` int(3) unsigned NOT NULL,
  `cantidad` int(4) unsigned NOT NULL,
  `fechamodificacion` datetime NOT NULL,
  `usuariomodificacion` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcapitulosdepracticas`
--

CREATE TABLE IF NOT EXISTS `subcapitulosdepracticas` (
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `codigo` char(5) NOT NULL,
  `idcapitulo` int(3) NOT NULL,
  `descripcion` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=187 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subidapadroncapitados`
--

CREATE TABLE IF NOT EXISTS `subidapadroncapitados` (
  `codigoprestador` char(3) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL DEFAULT '',
  `mespadron` int(2) NOT NULL DEFAULT '0',
  `anopadron` int(4) NOT NULL DEFAULT '0',
  `fechasubida` date NOT NULL DEFAULT '0000-00-00',
  `horasubida` time NOT NULL DEFAULT '00:00:00',
  `totaltitulares` int(6) NOT NULL DEFAULT '0',
  `totalfamiliares` int(6) NOT NULL DEFAULT '0',
  `totalbeneficiarios` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigoprestador`,`mespadron`,`anopadron`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocomplejidad`
--

CREATE TABLE IF NOT EXISTS `tipocomplejidad` (
  `codigocomplejidad` int(2) NOT NULL AUTO_INCREMENT,
  `descripcion` char(20) NOT NULL,
  PRIMARY KEY (`codigocomplejidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodiscapacidad`
--

CREATE TABLE IF NOT EXISTS `tipodiscapacidad` (
  `iddiscapacidad` int(2) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(25) NOT NULL,
  PRIMARY KEY (`iddiscapacidad`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE IF NOT EXISTS `tipodocumento` (
  `codtipdoc` char(2) NOT NULL COMMENT 'Codigo para Tipo de Documento',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Documento',
  PRIMARY KEY (`codtipdoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Tipos de Documentos de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumentobanco`
--

CREATE TABLE IF NOT EXISTS `tipodocumentobanco` (
  `id` int(3) unsigned NOT NULL,
  `descripcion` char(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodomicilio`
--

CREATE TABLE IF NOT EXISTS `tipodomicilio` (
  `codtipdom` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo para Tipo de Domicilio',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Domicilio',
  PRIMARY KEY (`codtipdom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Tipos de Domicilios de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopracticas`
--

CREATE TABLE IF NOT EXISTS `tipopracticas` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `codigonomenclador` int(4) NOT NULL,
  `descripcion` char(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoprestador`
--

CREATE TABLE IF NOT EXISTS `tipoprestador` (
  `id` int(3) NOT NULL,
  `descripcion` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposcancelaciones`
--

CREATE TABLE IF NOT EXISTS `tiposcancelaciones` (
  `codigo` int(2) unsigned NOT NULL COMMENT 'Codigo de Tipo de Cancelacion',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Cancelacion',
  `imprimible` int(1) unsigned NOT NULL COMMENT 'Status de para impresion de Boleta',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Tipos de Cancelaciones de Cuotas';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposdeacuerdos`
--

CREATE TABLE IF NOT EXISTS `tiposdeacuerdos` (
  `codigo` int(1) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Tipos de Acuerdos',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Tipos de Acuerdos',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla codificadora de Tipos de Acuerdos' AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposervicio`
--

CREATE TABLE IF NOT EXISTS `tiposervicio` (
  `codigoservicio` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` char(100) NOT NULL,
  `profesional` int(1) unsigned NOT NULL COMMENT 'Servicio que presta un profesional (0: No - 1:Si - 2: Ambos)',
  PRIMARY KEY (`codigoservicio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipotitular`
--

CREATE TABLE IF NOT EXISTS `tipotitular` (
  `codtiptit` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo para Tipo de Beneficiario Titular',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Beneficiario Titular',
  PRIMARY KEY (`codtiptit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Tipos de Beneficiarios Titulares de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipotratamiento`
--

CREATE TABLE IF NOT EXISTS `tipotratamiento` (
  `codigotratamiento` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` char(10) NOT NULL,
  `matriculado` int(1) unsigned DEFAULT NULL COMMENT 'Es matriculado (1:Si - 0:No)',
  PRIMARY KEY (`codigotratamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulares`
--

CREATE TABLE IF NOT EXISTS `titulares` (
  `nroafiliado` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `apellidoynombre` char(100) NOT NULL,
  `tipodocumento` char(2) NOT NULL,
  `nrodocumento` int(10) unsigned NOT NULL,
  `fechanacimiento` date NOT NULL,
  `nacionalidad` int(3) unsigned NOT NULL,
  `sexo` char(1) NOT NULL,
  `estadocivil` int(2) unsigned NOT NULL,
  `codprovin` int(2) unsigned NOT NULL,
  `indpostal` char(1) NOT NULL,
  `numpostal` int(4) unsigned NOT NULL,
  `alfapostal` char(3) DEFAULT NULL,
  `codlocali` int(6) unsigned NOT NULL,
  `domicilio` char(50) NOT NULL,
  `ddn` char(5) DEFAULT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `fechaobrasocial` date NOT NULL,
  `tipoafiliado` char(1) NOT NULL,
  `solicitudopcion` int(8) unsigned DEFAULT NULL,
  `situaciontitularidad` int(2) unsigned NOT NULL,
  `discapacidad` int(2) unsigned NOT NULL,
  `certificadodiscapacidad` int(1) unsigned DEFAULT NULL,
  `cuil` char(11) NOT NULL,
  `cuitempresa` char(11) NOT NULL,
  `fechaempresa` date NOT NULL,
  `codidelega` int(4) unsigned NOT NULL,
  `categoria` char(100) DEFAULT NULL,
  `emitecarnet` int(1) unsigned NOT NULL DEFAULT '0',
  `cantidadcarnet` int(4) unsigned NOT NULL DEFAULT '0',
  `fechacarnet` date DEFAULT NULL,
  `lote` char(14) DEFAULT NULL COMMENT 'Lote de impresion en que fue impreso el carnet',
  `tipocarnet` char(1) DEFAULT NULL,
  `vencimientocarnet` date DEFAULT NULL,
  `informesss` int(1) unsigned NOT NULL,
  `tipoinformesss` char(1) DEFAULT NULL,
  `fechainformesss` datetime DEFAULT NULL,
  `usuarioinformesss` char(50) DEFAULT NULL,
  `foto` mediumblob,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  `mirroring` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`nroafiliado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Beneficiarios Titulares' AUTO_INCREMENT=161063 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titularesdebaja`
--

CREATE TABLE IF NOT EXISTS `titularesdebaja` (
  `nroafiliado` int(9) unsigned NOT NULL,
  `apellidoynombre` char(100) NOT NULL,
  `tipodocumento` char(2) NOT NULL,
  `nrodocumento` int(10) unsigned NOT NULL,
  `fechanacimiento` date NOT NULL,
  `nacionalidad` int(3) unsigned NOT NULL,
  `sexo` char(1) NOT NULL,
  `estadocivil` int(2) unsigned NOT NULL,
  `codprovin` int(2) unsigned NOT NULL,
  `indpostal` char(1) NOT NULL,
  `numpostal` int(4) unsigned NOT NULL,
  `alfapostal` char(3) DEFAULT NULL,
  `codlocali` int(6) unsigned NOT NULL,
  `domicilio` char(50) NOT NULL,
  `ddn` char(5) DEFAULT NULL,
  `telefono` int(10) unsigned DEFAULT NULL,
  `email` char(60) DEFAULT NULL,
  `fechaobrasocial` date NOT NULL,
  `tipoafiliado` char(1) NOT NULL,
  `solicitudopcion` int(8) unsigned DEFAULT NULL,
  `situaciontitularidad` int(2) unsigned NOT NULL,
  `discapacidad` int(2) unsigned NOT NULL,
  `certificadodiscapacidad` int(1) unsigned DEFAULT NULL,
  `cuil` char(11) NOT NULL,
  `cuitempresa` char(11) NOT NULL,
  `fechaempresa` date NOT NULL,
  `codidelega` int(4) unsigned NOT NULL,
  `categoria` char(100) DEFAULT NULL,
  `emitecarnet` int(1) unsigned NOT NULL DEFAULT '0',
  `cantidadcarnet` int(4) unsigned NOT NULL DEFAULT '0',
  `fechacarnet` date DEFAULT NULL,
  `lote` char(14) DEFAULT NULL COMMENT 'Lote de impresion en que fue impreso el carnet',
  `tipocarnet` char(1) DEFAULT NULL,
  `vencimientocarnet` date DEFAULT NULL,
  `informesss` int(1) unsigned NOT NULL,
  `tipoinformesss` char(1) DEFAULT NULL,
  `fechainformesss` datetime DEFAULT NULL,
  `usuarioinformesss` char(50) DEFAULT NULL,
  `foto` mediumblob,
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  `mirroring` char(1) NOT NULL DEFAULT 'N',
  `fechabaja` date NOT NULL,
  `motivobaja` text,
  `fechaefectivizacion` datetime DEFAULT NULL,
  `usuarioefectivizacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroafiliado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Beneficiarios Titulares de Baja';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trajuiciosospim`
--

CREATE TABLE IF NOT EXISTS `trajuiciosospim` (
  `nroorden` int(8) unsigned NOT NULL COMMENT 'Nro. de Orden en Cabecera',
  `fechainicio` date NOT NULL COMMENT 'Fecha de Inicio del Juicio',
  `autoscaso` text NOT NULL COMMENT 'Autos del Caso Judicial',
  `codigojuzgado` int(3) unsigned NOT NULL COMMENT 'Codigo de Juzgado',
  `codigosecretaria` int(3) unsigned NOT NULL COMMENT 'Codigo de Secretaria del Juzgado',
  `nroexpediente` char(30) NOT NULL COMMENT 'Nro. de Expediente Judicial',
  `bienesembargados` text COMMENT 'Bienes Embargados',
  `observacion` text,
  `estadoprocesal` int(2) unsigned NOT NULL COMMENT 'Estado Procesal del Expediente',
  `fechafinalizacion` date NOT NULL COMMENT 'Fecha de Finalizacion del Juicio',
  `montocobrado` decimal(9,2) unsigned NOT NULL COMMENT 'Monto Cobrado',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tramite Judicial de Jucios de OSPIM';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trajuiciosusimra`
--

CREATE TABLE IF NOT EXISTS `trajuiciosusimra` (
  `nroorden` int(8) unsigned NOT NULL COMMENT 'Nro. de Orden en Cabecera',
  `fechainicio` date NOT NULL COMMENT 'Fecha de Inicio del Juicio',
  `autoscaso` text NOT NULL COMMENT 'Autos del Caso Judicial',
  `codigojuzgado` int(3) unsigned NOT NULL COMMENT 'Codigo de Juzgado',
  `codigosecretaria` int(3) unsigned NOT NULL COMMENT 'Codigo de Secretaria del Juzgado',
  `nroexpediente` char(30) NOT NULL COMMENT 'Nro. de Expediente Judicial',
  `bienesembargados` text COMMENT 'Bienes Embargados',
  `observacion` text,
  `estadoprocesal` int(2) unsigned NOT NULL COMMENT 'Estado Procesal del Expediente',
  `fechafinalizacion` date NOT NULL COMMENT 'Fecha de Finalizacion del Juicio',
  `montocobrado` decimal(9,2) unsigned NOT NULL COMMENT 'Monto Cobrado',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`nroorden`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tramite Judicial de Jucios de USIMRA';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferenciasaportes`
--

CREATE TABLE IF NOT EXISTS `transferenciasaportes` (
  `nrodisco` int(6) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Nro de Disco/Archivo Asignado por el Procesamiento en OSPIM',
  `fechaarchivoafip` datetime NOT NULL COMMENT 'Fecha de Generacion del Archivo en AFIP',
  `fechaemailafip` datetime NOT NULL COMMENT 'Fecha del Email de AFIP que Comunica la generacion del Archivo',
  `registrosafip` int(6) unsigned NOT NULL COMMENT 'Cantidad Total de Registros en el Archivo Informado por AFIP',
  `importeafip` decimal(15,2) unsigned NOT NULL COMMENT 'Importe Total Transferido en el Archivo Informado por AFIP',
  `fechaprocesoospim` datetime DEFAULT NULL COMMENT 'Fecha en que se procesa el Archivo en OSPIM',
  `usuarioprocesoospim` char(100) DEFAULT NULL COMMENT 'Usuario que procesa el Archivo en OSPIM',
  `registrosprocesoospim` int(6) unsigned DEFAULT NULL COMMENT 'Cantidad Total de Registros Resultantes del proceso del Archivo en OSPIM',
  `creditoprocesoospim` decimal(15,2) unsigned DEFAULT '0.00' COMMENT 'Importe Total de Creditos Resultantes del proceso del Archivo en OSPIM',
  `debitoprocesoospim` decimal(15,2) unsigned DEFAULT '0.00' COMMENT 'Importe Total de Debitos Resultantes del proceso del Archivo en OSPIM',
  `importeprocesoospim` decimal(15,2) unsigned DEFAULT '0.00' COMMENT 'Importe Total Transferido Resultante del proceso del Archivo en OSPIM',
  `carpetaarchivoospim` text COMMENT 'Carpeta en el Servidor donde se almacena el Archivo Procesado en OSPIM',
  PRIMARY KEY (`nrodisco`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Identificacion en OSPIM Archivos de Transferencias AFIP por Aportes de Empresas' AUTO_INCREMENT=469 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transferenciasusimra`
--

CREATE TABLE IF NOT EXISTS `transferenciasusimra` (
  `idtransferencia` int(9) NOT NULL AUTO_INCREMENT,
  `banco` char(100) NOT NULL COMMENT 'Nombre del banco del cual nace la transferencia',
  `sucursal` int(4) unsigned zerofill NOT NULL COMMENT 'Código de la sucursal del banco del cual nace la transferencia',
  `numerocuenta` char(15) NOT NULL COMMENT 'Número de cuenta del banco del cual nace la transferencia',
  `cuit` char(11) NOT NULL COMMENT 'Cuit de la empresa que realiza la transferencia',
  `monto` decimal(15,2) NOT NULL COMMENT 'Monto transferido',
  `numeroorden` char(12) NOT NULL COMMENT 'Nro de orden de la transferencia',
  `fecha` date NOT NULL COMMENT 'Fecha que informa el banco que se realizo la transferencia',
  `importecomision` decimal(15,2) NOT NULL COMMENT 'Importe de la comisión por la transferencia',
  `ivacomision` decimal(15,2) NOT NULL COMMENT 'Importe del IVA por comisión',
  `fecharegistro` datetime NOT NULL,
  `usuarioregistro` char(50) NOT NULL,
  `fechamodificacion` datetime DEFAULT NULL,
  `usuariomodificacion` char(50) DEFAULT NULL,
  PRIMARY KEY (`idtransferencia`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacionproducto`
--

CREATE TABLE IF NOT EXISTS `ubicacionproducto` (
  `id` int(3) unsigned NOT NULL,
  `departamento` int(2) unsigned NOT NULL,
  `pertenencia` char(1) NOT NULL,
  `idusuario` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`,`departamento`),
  KEY `departamento` (`departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `departamento` int(2) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombrepc` varchar(100) NOT NULL,
  `usuariowin` varchar(50) NOT NULL,
  `passwin` varchar(50) NOT NULL,
  `usuariosistema` varchar(50) NOT NULL,
  `passsistema` varchar(50) NOT NULL,
  `puerto` varchar(5) NOT NULL,
  `conector` varchar(5) NOT NULL,
  `fechamodificacion` datetime NOT NULL,
  `usuariomodificacion` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Validadas' AUTO_INCREMENT=4975 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validasusimra`
--

CREATE TABLE IF NOT EXISTS `validasusimra` (
  `idboleta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador de Boleta',
  `cuit` char(11) NOT NULL COMMENT 'C.U.I.T. - En tabla Empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Nro. de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Nro. de Cuota',
  `importe` decimal(9,2) unsigned NOT NULL COMMENT 'Importe de la Boleta',
  `nrocontrol` char(14) NOT NULL COMMENT 'Nro. de Control univoco para identificacion de la Boleta',
  `usuarioregistro` char(50) NOT NULL COMMENT 'Usuario que Inicializa el Registro',
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Validadas' AUTO_INCREMENT=31691 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoresalcobro`
--

CREATE TABLE IF NOT EXISTS `valoresalcobro` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Numero de Cuota',
  `chequenro` char(20) NOT NULL COMMENT 'Nro de Cheque con que se Cancela',
  `chequebanco` char(20) NOT NULL COMMENT 'Banco Cheque a Cargo',
  `chequefecha` date NOT NULL COMMENT 'Fecha del Cheque con que se cancela',
  `idresumenbancario` char(50) DEFAULT NULL COMMENT 'Identificacion del Resumen Bancario en que se Acredita el Valor al Cobro',
  `fecharesumenbancario` date DEFAULT NULL COMMENT 'Feha del Resumen Bancario en que se Acredita el Valor al Cobro',
  `chequenroospim` char(20) DEFAULT NULL COMMENT 'Nro de Cheque que se deposita en Cuenta Recaudadora',
  `chequebancoospim` char(20) DEFAULT 'Nacion' COMMENT 'Banco del Cheque que se deposita en Cuenta Recaudadora',
  `chequefechaospim` date DEFAULT NULL COMMENT 'Fecha del Cheque que se deposita en Cuenta Recaudadora',
  `usuariodepositoospim` char(20) DEFAULT NULL COMMENT 'Usuario que registra el deposito en Cuenta Recaudadora',
  `fechadepositoospim` datetime DEFAULT NULL COMMENT 'Fecha en que se registra el deposito en Cuenta Recaudadora',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoresalcobrousimra`
--

CREATE TABLE IF NOT EXISTS `valoresalcobrousimra` (
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `nroacuerdo` int(3) unsigned NOT NULL COMMENT 'Numero de Acuerdo',
  `nrocuota` int(3) unsigned NOT NULL COMMENT 'Numero de Cuota',
  `chequenro` char(20) NOT NULL COMMENT 'Nro de Cheque con que se Cancela',
  `chequebanco` char(20) NOT NULL COMMENT 'Banco Cheque a Cargo',
  `chequefecha` date NOT NULL COMMENT 'Fecha del Cheque con que se cancela',
  `idresumenbancario` char(50) DEFAULT NULL COMMENT 'Identificacion del Resumen Bancario en que se Acredita el Valor al Cobro',
  `fecharesumenbancario` date DEFAULT NULL COMMENT 'Feha del Resumen Bancario en que se Acredita el Valor al Cobro',
  `chequenrousimra` char(20) DEFAULT NULL COMMENT 'Nro de Cheque que se deposita en Cuenta Recaudadora',
  `chequebancousimra` char(20) DEFAULT 'Nacion' COMMENT 'Banco del Cheque que se deposita en Cuenta Recaudadora',
  `chequefechausimra` date DEFAULT NULL COMMENT 'Fecha del Cheque que se deposita en Cuenta Recaudadora',
  `usuariodepositousimra` char(20) DEFAULT NULL COMMENT 'Usuario que registra el deposito en Cuenta Recaudadora',
  `fechadepositousimra` datetime DEFAULT NULL COMMENT 'Fecha en que se registra el deposito en Cuenta Recaudadora',
  PRIMARY KEY (`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vericuiles`
--

CREATE TABLE IF NOT EXISTS `vericuiles` (
  `verificuil` int(3) unsigned zerofill NOT NULL COMMENT 'Codigos de resultados de la verificacion de cuil e identidad segun S.S.S.',
  `descrip` char(255) NOT NULL COMMENT 'Descripcion',
  `accion` char(100) NOT NULL COMMENT 'Accion a seguir por parte de la O.S.',
  PRIMARY KEY (`verificuil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codigos de validacion de cuil e identidad de la S.S.S.';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vinculadocuusimra`
--

CREATE TABLE IF NOT EXISTS `vinculadocuusimra` (
  `nrcuit` char(11) NOT NULL,
  `referencia` char(12) NOT NULL,
  `nrctrl` char(14) NOT NULL,
  PRIMARY KEY (`referencia`,`nrctrl`,`nrcuit`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cabpedidos`
--
ALTER TABLE `cabpedidos`
  ADD CONSTRAINT `cabpedidos_ibfk_1` FOREIGN KEY (`idproveedor`) REFERENCES `proveedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `consumoinsumo`
--
ALTER TABLE `consumoinsumo`
  ADD CONSTRAINT `consumoinsumo_ibfk_1` FOREIGN KEY (`idinsumo`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `delegaciones`
--
ALTER TABLE `delegaciones`
  ADD CONSTRAINT `FK_DELEGACIONES_CODLOCALI` FOREIGN KEY (`codlocali`) REFERENCES `localidades` (`codlocali`),
  ADD CONSTRAINT `FK_DELEGACIONES_CODPROVIN` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_DELEGACIONES_INDPOSTAL` FOREIGN KEY (`indpostal`) REFERENCES `provincia` (`indpostal`),
  ADD CONSTRAINT `FK_DELEGACIONES_NUMPOSTAL` FOREIGN KEY (`numpostal`) REFERENCES `localidades` (`numpostal`);

--
-- Filtros para la tabla `detpedidos`
--
ALTER TABLE `detpedidos`
  ADD CONSTRAINT `detpedidos_ibfk_1` FOREIGN KEY (`idpedido`) REFERENCES `cabpedidos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `detpedidos_ibfk_2` FOREIGN KEY (`idinsumo`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `insumoproducto`
--
ALTER TABLE `insumoproducto`
  ADD CONSTRAINT `insumoproducto_ibfk_1` FOREIGN KEY (`idinsumo`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `insumoproducto_ibfk_2` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `localidades`
--
ALTER TABLE `localidades`
  ADD CONSTRAINT `FK_LOCALIDADES_CODPROVIN` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`);

--
-- Filtros para la tabla `novedadessss`
--
ALTER TABLE `novedadessss`
  ADD CONSTRAINT `FK_NOVEDADESSSS_DOMICILIO` FOREIGN KEY (`tipodomici`) REFERENCES `tipodomicilio` (`codtipdom`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_ESTADOCIVIL` FOREIGN KEY (`codestciv`) REFERENCES `estadocivil` (`codestciv`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_INCAPACIDAD` FOREIGN KEY (`codincapa`) REFERENCES `incapacidad` (`codincapa`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_NACIONALIDAD` FOREIGN KEY (`codnacion`) REFERENCES `nacionalidad` (`codnacion`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_PARENTESCO` FOREIGN KEY (`codparent`) REFERENCES `parentesco` (`codparent`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_PROVINCIA` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_SITREVISTA` FOREIGN KEY (`codsitrev`) REFERENCES `situacionrevista` (`codsitrev`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_TIPODOCU` FOREIGN KEY (`codtipdoc`) REFERENCES `tipodocumento` (`codtipdoc`),
  ADD CONSTRAINT `FK_NOVEDADESSSS_TIPOTITULAR` FOREIGN KEY (`codtiptit`) REFERENCES `tipotitular` (`codtiptit`);

--
-- Filtros para la tabla `prestadorjurisdiccion`
--
ALTER TABLE `prestadorjurisdiccion`
  ADD CONSTRAINT `prestadorjurisdiccion_ibfk_3` FOREIGN KEY (`codigoprestador`) REFERENCES `prestadores` (`codigoprestador`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `prestadorjurisdiccion_ibfk_4` FOREIGN KEY (`codidelega`) REFERENCES `delegaciones` (`codidelega`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `prestadorservicio`
--
ALTER TABLE `prestadorservicio`
  ADD CONSTRAINT `prestadorservicio_ibfk_1` FOREIGN KEY (`codigoprestador`) REFERENCES `prestadores` (`codigoprestador`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `prestadorservicio_ibfk_2` FOREIGN KEY (`codigoservicio`) REFERENCES `tiposervicio` (`codigoservicio`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`id`) REFERENCES `insumo` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `ubicacionproducto`
--
ALTER TABLE `ubicacionproducto`
  ADD CONSTRAINT `ubicacionproducto_ibfk_1` FOREIGN KEY (`id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `ubicacionproducto_ibfk_2` FOREIGN KEY (`departamento`) REFERENCES `departamentos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
