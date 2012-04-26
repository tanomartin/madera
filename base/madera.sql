-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-04-2012 a las 11:00:28
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
  PRIMARY KEY (`idboleta`,`cuit`,`nroacuerdo`,`nrocuota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Boletas Electronicas de OSPIM Generadas' AUTO_INCREMENT=160 ;

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
-- Estructura de tabla para la tabla `conceptosdeudas`
--

CREATE TABLE IF NOT EXISTS `conceptosdeudas` (
  `codigo` char(1) NOT NULL COMMENT 'Codigo para los conceptos de Deudas',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Conceptos  de Deudas',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para los Conceptos de Deudas';

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
  PRIMARY KEY (`cuit`,`codidelega`,`codiempresa`) USING BTREE,
  KEY `FK_DELEGAEMPRESA_CODIDELEGA` (`codidelega`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla transitoria para mantener los codigos de empresa';

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
  `ddn1` int(5) unsigned zerofill DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 1',
  `telefono1` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono 1',
  `contactel1` char(50) DEFAULT NULL COMMENT 'Contacto Telefonico 1',
  `ddn2` int(5) unsigned zerofill DEFAULT NULL COMMENT 'Discado Directo Nacional para el Telefono 2',
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
  PRIMARY KEY (`cuit`),
  KEY `FK_EMPRESAS_CODPROVIN` (`codprovin`),
  KEY `FK_EMPRESAS_INDPOSTAL` (`indpostal`),
  KEY `FK_EMPRESAS_NUMPOSTAL` (`numpostal`),
  KEY `FK_EMPRESAS_CODLOCALI` (`codlocali`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Empresas';

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
-- Estructura de tabla para la tabla `estadosdeacuerdos`
--

CREATE TABLE IF NOT EXISTS `estadosdeacuerdos` (
  `codigo` int(1) unsigned NOT NULL COMMENT 'Codigo para los Estados de Acuerdos',
  `descripcion` char(50) NOT NULL COMMENT 'Descripcion para los Estados de Acuerdos',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para los Estados de Acuerdos';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gestoresdeacuerdos`
--

CREATE TABLE IF NOT EXISTS `gestoresdeacuerdos` (
  `codigo` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo para Gestor de Acuerdo',
  `apeynombre` char(100) NOT NULL COMMENT 'Apellido y Nombre del Gestor',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Codificadora de Gestores de Acuerdos' AUTO_INCREMENT=2 ;

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
-- Estructura de tabla para la tabla `inspectores`
--

CREATE TABLE IF NOT EXISTS `inspectores` (
  `codigo` int(3) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Codigo de Inspector',
  `apeynombre` char(50) NOT NULL COMMENT 'Apellido y Nombre del Inspector',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Delegacion sobre la que posee jurisdiccion',
  PRIMARY KEY (`codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabla codificadora de Inspectores' AUTO_INCREMENT=41 ;

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
  `ddn` int(5) unsigned DEFAULT NULL COMMENT 'Discado Directo Nacional',
  `telefono` bigint(10) unsigned DEFAULT NULL COMMENT 'Telefono',
  `contactel` char(50) DEFAULT NULL COMMENT 'Contacto',
  `email` char(60) DEFAULT NULL COMMENT 'Direccion de Correo Electronico',
  `disgdinero` decimal(5,2) NOT NULL COMMENT 'Porcentaje de Disgregacion de Masas Dinerarias',
  PRIMARY KEY (`cuit`,`codidelega`) USING BTREE,
  KEY `FK_JURISDICCION_CODIDELEGA` (`codidelega`),
  KEY `FK_JURISDICCION_CODPROVIN` (`codprovin`),
  KEY `FK_JURISDICCION_INDPOSTAL` (`indpostal`),
  KEY `FK_JURISDICCION_NUMPOSTAL` (`numpostal`),
  KEY `FK_JURISDICCION_CODLOCALI` (`codlocali`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Jurisdicciones a las que pertenecen las empresas';

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
-- Estructura de tabla para la tabla `nacionalidad`
--

CREATE TABLE IF NOT EXISTS `nacionalidad` (
  `codnacion` int(3) unsigned zerofill NOT NULL COMMENT 'Codigo de Nacionalidad',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Codigo de Nacionalidad',
  PRIMARY KEY (`codnacion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Nacionalidades de la SSS';

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
-- Estructura de tabla para la tabla `padronsss`
--

CREATE TABLE IF NOT EXISTS `padronsss` (
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
  `fechanaci` date NOT NULL COMMENT 'Fecha de Nacimiento en Formato DDMMAAAA',
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
  `codtiptit` int(1) unsigned NOT NULL COMMENT 'Codigo de Tipo de Beneficiario Titular segun SSS',
  `fecaltaos` date NOT NULL COMMENT 'Fecha Alta en la O.S. con formato DDMMAAAA',
  `fecciepres` date NOT NULL COMMENT 'Fecha de Presentacion del registro en la SSS por la O.S. con formato DDMMAAAA',
  `verificuil` int(3) unsigned zerofill NOT NULL COMMENT 'Resultado de la verificacion de la SSS del cuil del beneficiario. Rango de Error: 200 a 400',
  `cuilinfos` char(11) DEFAULT NULL COMMENT 'C.U.I.L. informado por la O.S. para el caso en que la SSS lo modifico',
  `tiptitsijp` char(2) DEFAULT NULL COMMENT 'Tipo de Beneficiario segun SIJP que difiere del de la O.S.',
  `cuitsijp` char(11) DEFAULT NULL COMMENT 'C.U.I.T. del empleador que declara al beneficiario',
  `ossijp` int(6) unsigned DEFAULT NULL COMMENT 'O.S. declarada en el SIJP',
  `perisijp` char(6) DEFAULT NULL COMMENT 'Ultima declaracion jurada en SIJP con formato AAAAMM',
  `osopcion` int(6) unsigned DEFAULT NULL COMMENT 'La SSS informa el RNOS de la O.S. en caso de opcion vigente',
  `periopcion` char(6) DEFAULT NULL COMMENT 'Fecha desde la cual esta vigente la opcion con formato AAAAMM',
  `anopadron` int(4) unsigned NOT NULL COMMENT 'Año del Periodo al que corresponde el padron',
  `mespadron` int(2) unsigned NOT NULL COMMENT 'Mes del Periodo al que corresponde el padron',
  PRIMARY KEY (`cuit`,`cuiltitul`,`codparent`,`cuilfami`,`anopadron`,`mespadron`) USING BTREE,
  KEY `FK_PADRONSSS_TIPODOCU` (`codtipdoc`),
  KEY `FK_PADRONSSS_ESTADOCIVIL` (`codestciv`),
  KEY `FK_PADRONSSS_NACIONALIDAD` (`codnacion`),
  KEY `FK_PADRONSSS_PROVINCIA` (`codprovin`),
  KEY `FK_PADRONSSS_TIPOTITULAR` (`codtiptit`),
  KEY `FK_PADRONSSS_SITREVISTA` (`codsitrev`),
  KEY `FK_PADRONSSS_PARENTESCO` (`codparent`),
  KEY `FK_PADRONSSS_DOMICILIO` (`tipodomici`),
  KEY `FK_PADRONSSS_INCAPACIDAD` (`codincapa`),
  KEY `FK_PADRONSSS_VERIFICUIL` (`verificuil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Padron Consolidado de OSPIM segun la SSS';

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
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE IF NOT EXISTS `provincia` (
  `codprovin` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo de Provincia',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Codigo de Provincia',
  `indpostal` char(1) DEFAULT NULL COMMENT 'Indice de Provincia en el C.P.',
  PRIMARY KEY (`codprovin`),
  KEY `INDPOSTAL` (`indpostal`,`codprovin`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Provincias de la SSS';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reqfiscalizospim`
--

CREATE TABLE IF NOT EXISTS `reqfiscalizospim` (
  `fecharequerimiento` date NOT NULL COMMENT 'Fecha de Requerimiento',
  `nrorequerimiento` int(8) unsigned NOT NULL COMMENT 'Nro, de Requerimiento',
  `origenrequerimiento` int(1) unsigned NOT NULL COMMENT 'Origen del Requerimiento',
  `solicitarequerimiento` char(40) NOT NULL COMMENT 'Solicitante del Requerimiento',
  `motivorequerimiento` char(60) NOT NULL COMMENT 'Motivo del Requerimiento',
  `cuit` char(11) NOT NULL COMMENT 'CUIT de la Empresa - En tabla empresas',
  `codidelega` int(4) unsigned NOT NULL COMMENT 'Codigo de Delegacion',
  `codiempresa` int(6) unsigned NOT NULL COMMENT 'Codigo de Empresa',
  `incluyedetalle` char(1) NOT NULL COMMENT 'Detalles de la Fiscalizacion en Tabla detfiscalizospim',
  `procesoasignado` int(1) unsigned NOT NULL COMMENT 'Proceso Asignado al Requerimiento',
  PRIMARY KEY (`fecharequerimiento`,`nrorequerimiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Requerimientos de Fiscalizacion de OSPIM';

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
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE IF NOT EXISTS `tipodocumento` (
  `codtipdoc` char(2) NOT NULL COMMENT 'Codigo para Tipo de Documento',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Documento',
  PRIMARY KEY (`codtipdoc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora de Tipos de Documentos de la SSS';

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
-- Estructura de tabla para la tabla `tipotitular`
--

CREATE TABLE IF NOT EXISTS `tipotitular` (
  `codtiptit` int(2) unsigned zerofill NOT NULL COMMENT 'Codigo para Tipo de Beneficiario Titular',
  `descrip` char(50) NOT NULL COMMENT 'Descripcion para Tipo de Beneficiario Titular',
  PRIMARY KEY (`codtiptit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codificadora para Tipos de Beneficiarios Titulares de la SSS';

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
-- Estructura de tabla para la tabla `vericuiles`
--

CREATE TABLE IF NOT EXISTS `vericuiles` (
  `verificuil` int(3) unsigned zerofill NOT NULL COMMENT 'Codigos de resultados de la verificacion de cuil e identidad segun S.S.S.',
  `descrip` char(255) NOT NULL COMMENT 'Descripcion',
  `accion` char(100) NOT NULL COMMENT 'Accion a seguir por parte de la O.S.',
  PRIMARY KEY (`verificuil`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Codigos de validacion de cuil e identidad de la S.S.S.';

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `delegaciones`
--
ALTER TABLE `delegaciones`
  ADD CONSTRAINT `FK_DELEGACIONES_CODLOCALI` FOREIGN KEY (`codlocali`) REFERENCES `localidades` (`codlocali`),
  ADD CONSTRAINT `FK_DELEGACIONES_CODPROVIN` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_DELEGACIONES_INDPOSTAL` FOREIGN KEY (`indpostal`) REFERENCES `provincia` (`indpostal`),
  ADD CONSTRAINT `FK_DELEGACIONES_NUMPOSTAL` FOREIGN KEY (`numpostal`) REFERENCES `localidades` (`numpostal`);

--
-- Filtros para la tabla `delegaempresa`
--
ALTER TABLE `delegaempresa`
  ADD CONSTRAINT `FK_DELEGAEMPRESA_CODIDELEGA` FOREIGN KEY (`codidelega`) REFERENCES `delegaciones` (`codidelega`),
  ADD CONSTRAINT `FK_DELEGAEMPRESA_CUIT` FOREIGN KEY (`cuit`) REFERENCES `empresas` (`cuit`);

--
-- Filtros para la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD CONSTRAINT `FK_EMPRESAS_CODLOCALI` FOREIGN KEY (`codlocali`) REFERENCES `localidades` (`codlocali`),
  ADD CONSTRAINT `FK_EMPRESAS_CODPROVIN` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_EMPRESAS_INDPOSTAL` FOREIGN KEY (`indpostal`) REFERENCES `provincia` (`indpostal`),
  ADD CONSTRAINT `FK_EMPRESAS_NUMPOSTAL` FOREIGN KEY (`numpostal`) REFERENCES `localidades` (`numpostal`);

--
-- Filtros para la tabla `jurisdiccion`
--
ALTER TABLE `jurisdiccion`
  ADD CONSTRAINT `FK_JURISDICCION_CODIDELEGA` FOREIGN KEY (`codidelega`) REFERENCES `delegaciones` (`codidelega`),
  ADD CONSTRAINT `FK_JURISDICCION_CODLOCALI` FOREIGN KEY (`codlocali`) REFERENCES `localidades` (`codlocali`),
  ADD CONSTRAINT `FK_JURISDICCION_CODPROVIN` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_JURISDICCION_CUIT` FOREIGN KEY (`cuit`) REFERENCES `empresas` (`cuit`),
  ADD CONSTRAINT `FK_JURISDICCION_INDPOSTAL` FOREIGN KEY (`indpostal`) REFERENCES `provincia` (`indpostal`),
  ADD CONSTRAINT `FK_JURISDICCION_NUMPOSTAL` FOREIGN KEY (`numpostal`) REFERENCES `localidades` (`numpostal`);

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
-- Filtros para la tabla `padronsss`
--
ALTER TABLE `padronsss`
  ADD CONSTRAINT `FK_PADRONSSS_DOMICILIO` FOREIGN KEY (`tipodomici`) REFERENCES `tipodomicilio` (`codtipdom`),
  ADD CONSTRAINT `FK_PADRONSSS_ESTADOCIVIL` FOREIGN KEY (`codestciv`) REFERENCES `estadocivil` (`codestciv`),
  ADD CONSTRAINT `FK_PADRONSSS_INCAPACIDAD` FOREIGN KEY (`codincapa`) REFERENCES `incapacidad` (`codincapa`),
  ADD CONSTRAINT `FK_PADRONSSS_NACIONALIDAD` FOREIGN KEY (`codnacion`) REFERENCES `nacionalidad` (`codnacion`),
  ADD CONSTRAINT `FK_PADRONSSS_PARENTESCO` FOREIGN KEY (`codparent`) REFERENCES `parentesco` (`codparent`),
  ADD CONSTRAINT `FK_PADRONSSS_PROVINCIA` FOREIGN KEY (`codprovin`) REFERENCES `provincia` (`codprovin`),
  ADD CONSTRAINT `FK_PADRONSSS_SITREVISTA` FOREIGN KEY (`codsitrev`) REFERENCES `situacionrevista` (`codsitrev`),
  ADD CONSTRAINT `FK_PADRONSSS_TIPODOCU` FOREIGN KEY (`codtipdoc`) REFERENCES `tipodocumento` (`codtipdoc`),
  ADD CONSTRAINT `FK_PADRONSSS_TIPOTITULAR` FOREIGN KEY (`codtiptit`) REFERENCES `tipotitular` (`codtiptit`),
  ADD CONSTRAINT `FK_PADRONSSS_VERIFICUIL` FOREIGN KEY (`verificuil`) REFERENCES `vericuiles` (`verificuil`);
