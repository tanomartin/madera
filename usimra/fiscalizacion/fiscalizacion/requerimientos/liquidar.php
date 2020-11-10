<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = $_GET['fecha'];

/***********************************************************************************/

function calculoDeudaNr($remu, $personal, $tipo, $valor, $porcentaje) {
	$apagar = 0;
	if ($tipo == 0) {
		$apagar = $valor * $porcentaje * $personal;
	}
	if ($tipo == 1) {
		$apagar = $remu * $valor * $porcentaje;
	}
	return $apagar;
}

function calculoBaseCalculoNR($remu, $mes, $anio, $db) {
	$sqlExtra = "SELECT tipo, valor FROM extraordinariosusimra WHERE anio = $anio and mes = $mes";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	$baseCalculoNR = 0;
	if ($rowExtra['tipo'] == 0) {
		$baseCalculoNR = $rowExtra['valor'];
	}
	if ($rowExtra['tipo'] == 1) {
		$baseCalculoNR = $remu * $rowExtra['valor'];
	}
	return $baseCalculoNR;
}

function obtenerMesNoRem($mesrelacion, $anio, $tipo, $db) {
	$sqlExtra = "SELECT mes FROM extraordinariosusimra WHERE anio = $anio and relacionmes = $mesrelacion and tipo = $tipo";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	return $rowExtra['mes'];
}

function obtenerMesRelacion($mes, $anio, $db) {
	$sqlExtra = "SELECT relacionmes FROM extraordinariosusimra WHERE anio = $anio and mes = $mes";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	return $rowExtra['relacionmes'];
}

function agregaGuiones($cuit) {
	$primero = substr ($cuit,0,2);
	$segundo = substr ($cuit,2,8);
	$tercero = substr ($cuit,10,1);
	$conguiones = $primero."-".$segundo."-".$tercero;
	return $conguiones;
}

function obtenerPagos($cuit, $db) {
	$sqlPagos = "SELECT anopago, mespago, relacionmes, fechapago, sum(montopagado) as pago FROM seguvidausimra
						LEFT JOIN extraordinariosusimra ON anopago = anio and mespago = mes
						where cuit = '$cuit'
						group by anopago, mespago, fechapago order by fechapago";
	$resPagos = mysql_query($sqlPagos,$db);
	$canPagos = mysql_num_rows($resPagos);
	$arrayPagos = array();
	if ($canPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) {
			$mes = str_pad($rowPagos['mespago'],2,'0',STR_PAD_LEFT);
			$index = $rowPagos['anopago'].$mes;
			if (isset($arrayPagos[$index][$rowPagos['fechapago']])) {
				$arrayPagos[$index][$rowPagos['fechapago']] +=  $rowPagos['pago'];
			} else {
				$arrayPagos[$index][$rowPagos['fechapago']] =  $rowPagos['pago'];
			}
		}
	}
	return $arrayPagos;
}

function grabarCabLiquidacion($nroreq, $nombreArcExc, $db) {
	$fechaLiqui = date("Ymd");
	$horaLiqui = date("His");
	$sqlCabeLiqui = "INSERT INTO cabliquiusimra VALUE($nroreq, $fechaLiqui, $horaLiqui, '$nombreArcExc', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlCabeLiqui);
		$dbh->commit();
	}catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}

function cambioEstadoReq($nroreq) {
	global $fechamodif, $usuariomodif;
	$sqlUpdateReque = "UPDATE reqfiscalizusimra SET procesoasignado = 1, fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlUpdateReque);
		$dbh->commit();
	}catch (PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}

function creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $tiporegistro, $nroreqArc) {
	$sqlEmpresasInicioActividad = "select iniobliusi from empresas where cuit = $cuit";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliusi'];
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");

	$arrayDDJJ = array();
	$arrayPeriodos = array();
	//DDJJ VALIDAS
	//$sqlDDJJ = "select anoddjj, mesddjj, cuil, sum(remuneraciones) as remuneraciones, count(cuil) as ddjjcant from detddjjusimra where cuit = $cuit and cuil != '99999999999' and anoddjj >= $anoinicio and anoddjj <= $ultano group by anoddjj, mesddjj, cuil";
	
	$sqlDDJJ = "SELECT anoddjj, mesddjj, cuil, sum(remuneraciones) as remuneraciones, count(cuil) as ddjjcant,
	                   if(empleadosusimra.nombre is null, empleadosdebajausimra.nombre, empleadosusimra.nombre) as nombre
                FROM detddjjusimra
                LEFT JOIN empleadosusimra ON empleadosusimra.nrcuil = detddjjusimra.cuil
                LEFT JOIN empleadosdebajausimra ON empleadosdebajausimra.nrcuil = detddjjusimra.cuil
                WHERE cuit = $cuit and cuil != '99999999999' and anoddjj >= $anoinicio and anoddjj <= $ultano 
                GROUP BY anoddjj, mesddjj, cuil";
	
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	while ($rowDDJJ = mysql_fetch_assoc($resDDJJ)) {
		$mes = str_pad($rowDDJJ['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJ['anoddjj'].$mes;
		$arrayDDJJ[$id][$rowDDJJ['cuil']] = array ('origen' =>  1, 'datos' => $rowDDJJ);
		
		//controlo para no mezclar ddjj
		$idUsimra = $id."U";
		$arrayPeriodos[$idUsimra] = $id;
	}
	
	//DDJJ TEMPORALES
	$sqlDDJJNroControl = "select perano, permes, nrctrl
	from ddjjusimra
	where nrcuit = $cuit and nrcuil = '99999999999' and perano >= $anoinicio and perano <= $ultano order by nrctrl, nrcuil DESC";
	$arrayNroControl = array();
	$resDDJJNroControl = mysql_query($sqlDDJJNroControl,$db);
	while ($rowDDJJNroControl = mysql_fetch_assoc($resDDJJNroControl)) {
		$idNrocontrol = $rowDDJJNroControl['perano'].$rowDDJJNroControl['permes'];
		$arrayNroControl[$idNrocontrol] =  $rowDDJJNroControl['nrctrl'];
	}
	$wherein = '';
	foreach($arrayNroControl as $nrocontrol) {
		$wherein = $wherein."'".$nrocontrol."',";
	}
	$wherein = substr($wherein, 0, -1);
	$wherein = "(".$wherein.")";
	if ($wherein != "()") {
		//$sqlDDJJTemp = "select perano as anoddjj, permes as mesddjj, nrcuil as cuil, remune as remuneraciones,  '1' as ddjjcant from ddjjusimra where nrcuit = $cuit and nrcuil != '99999999999' and nrctrl in $wherein";
	    $sqlDDJJTemp = "SELECT perano as anoddjj, permes as mesddjj, ddjjusimra.nrcuil as cuil, remune as remuneraciones,  '1' as ddjjcant,
                        	   if(empleadosusimra.nombre is null, empleadosdebajausimra.nombre, empleadosusimra.nombre) as nombre
                        FROM ddjjusimra
                        LEFT JOIN empleadosusimra ON empleadosusimra.nrcuil = ddjjusimra.nrcuil
                        LEFT JOIN empleadosdebajausimra ON empleadosdebajausimra.nrcuil = ddjjusimra.nrcuil
                        WHERE ddjjusimra.nrcuit = $cuit and ddjjusimra.nrcuil != '99999999999' and nrctrl in $wherein";
	    echo $sqlDDJJTemp."<br><br>";
	    
	    $resDDJJTemp = mysql_query($sqlDDJJTemp,$db);
		$canDDJJTemp = mysql_num_rows($resDDJJTemp);
		if ($canDDJJTemp != 0) {
			while ($rowDDJJTemp = mysql_fetch_assoc($resDDJJTemp)) {
				$mes = str_pad($rowDDJJTemp['mesddjj'],2,'0',STR_PAD_LEFT);
				$id = $rowDDJJTemp['anoddjj'].$mes;
				$idUsimra = $id."U";
				$idTemp = $id."T";
				if (!array_key_exists($idUsimra, $arrayPeriodos)) {
					$arrayDDJJ[$id][$rowDDJJTemp['cuil']] = array ('origen' =>  1, 'datos' => $rowDDJJTemp);
					$arrayPeriodos[$idTemp] = $id;
				}
			}
		}
	}
	
	//DDJJ OSPIM
	//$sqlDDJJOspim = "select anoddjj, mesddjj, cuil, sum(remundeclarada) as remuneraciones, count(cuil) as ddjjcant from detddjjospim where cuit = $cuit and cuil != '99999999999' and ((anoddjj > $anoinicio and anoddjj < $ultano) or (anoddjj = $ultano and mesddjj <= $ultmes) or (anoddjj = $anoinicio and mesddjj >= $mesinicio)) group by anoddjj, mesddjj, cuil";
	$sqlDDJJOspim = "SELECT anoddjj, mesddjj, detddjjospim.cuil, sum(remundeclarada) as remuneraciones, count(detddjjospim.cuil) as ddjjcant,
                            if(titulares.apellidoynombre is null, titularesdebaja.apellidoynombre, titulares.apellidoynombre) as nombre
                     FROM detddjjospim
                     LEFT JOIN titulares ON titulares.cuil = detddjjospim.cuil
                     LEFT JOIN titularesdebaja ON titularesdebaja.cuil = detddjjospim.cuil
                     WHERE cuit = $cuit and detddjjospim.cuil != '99999999999' and
                    	  ((anoddjj > $anoinicio and anoddjj < $ultano) or (anoddjj = $ultano and mesddjj <= $ultmes) or (anoddjj = $anoinicio and mesddjj >= $mesinicio))
                     GROUP BY anoddjj, mesddjj, detddjjospim.cuil";
	echo $sqlDDJJOspim."<br><br>";
	
	$resDDJJOspim = mysql_query($sqlDDJJOspim,$db);
	while ($rowDDJJOspim = mysql_fetch_assoc($resDDJJOspim)) {
		$mes = str_pad($rowDDJJOspim['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJOspim['anoddjj'].$mes;
		$idUsimra = $id."U";
		$idTemp = $id."T";
		if (!array_key_exists($idUsimra, $arrayPeriodos) && !array_key_exists($idTemp, $arrayPeriodos)) {
			$arrayDDJJ[$id][$rowDDJJOspim['cuil']] = array ('origen' =>  2, 'datos' => $rowDDJJOspim);
		}
	}
	
	//ksort($arrayDDJJ);
	
	/*echo "<br><br>DDJJ TODAS<br><br>";
	foreach($arrayDDJJ as $key => $ddjj) {
		echo $key."->";var_dump($ddjj);echo "<br>";
	}
	echo "<br>--------------------<br>";*/
	
	$sqlRequeDet = "SELECT d.*, relacionmes, tipo, valor, retiene060*0.006 + retiene100*0.01 + retiene150*0.015 as porcentaje
					FROM detfiscalizusimra d
					LEFT JOIN extraordinariosusimra ON anofiscalizacion = anio and mesfiscalizacion = mes
					WHERE nrorequerimiento = $nroreqArc";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	while ($rowRequeDet = mysql_fetch_assoc($resRequeDet)) {
		$mes = $rowRequeDet['mesfiscalizacion'];
		if ($mes > 12) {
			$mes = $rowRequeDet['relacionmes'];
		} 
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		$idBuscadorTipo = $rowRequeDet['anofiscalizacion'].$mes;
		$idBuscadorDDJJ = $rowRequeDet['anofiscalizacion'].str_pad($rowRequeDet['mesfiscalizacion'],2,'0',STR_PAD_LEFT);
		
		$tipolinea = $tiporegistro[$idBuscadorTipo];
		$remu = 0;
		$noremok = 0;
		$fijook = 0;
		$cuotaok = 0;
		if (substr($tipolinea,0,1) == 1) {
			$remu = 1;
		}
		if (substr($tipolinea,1,1) == 1) {
			$noremok = 1;
		}
		if (substr($tipolinea,2,1) == 1) {
			$fijook = 1;
		}
		$cuerpoCUIL[$idBuscadorTipo]['tipo'] = $tipolinea;
		
		if (array_key_exists($idBuscadorDDJJ, $arrayDDJJ)) {
			$ddjjarray = $arrayDDJJ[$idBuscadorDDJJ];
			foreach($ddjjarray as $ddjj) {
				$cuil = $ddjj['datos']['cuil'];
				if (isset($cuerpoCUIL[$idBuscadorTipo][$cuil])) {
					$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('fecha' => "01/".$mes."/".$rowRequeDet['anofiscalizacion']);
				} else {
					$cuerpoCUIL[$idBuscadorTipo][$cuil] = array('fecha' => "01/".$mes."/".$rowRequeDet['anofiscalizacion']);
				}
				if($remu == 1 and $rowRequeDet['tipo'] == null) {
					$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('remu' => $ddjj['datos']['remuneraciones']);
				}
				if($noremok == 1 and $rowRequeDet['tipo'] == 1) {
					$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('norem' => $ddjj['datos']['remuneraciones']);
				}
				if($fijook == 1 and $rowRequeDet['tipo'] != null and $rowRequeDet['tipo'] == 0) {
					$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('fijo' => $ddjj['datos']['remuneraciones']);
				}
				$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('origen' =>  $ddjj['origen']);
				$cuerpoCUIL[$idBuscadorTipo][$cuil] += array('nombre' =>  $ddjj['nombre']);
			}
		}
	}

	$arrayCuerpoArchivo = array();
	$i=0;
	foreach ($cuerpoCUIL as $per => $cuiles) {
		foreach($cuiles as $cuil => $datos) {
		    
		    var_dump($datos);echo"<br><br>";
			
			if ($cuil == 'tipo') {
				$tipolinea = $datos;
			} else {
				$remu = 0;
				if (isset($datos['remu'])) {
					$remu = $datos['remu'];
				}
				$norem = 0;
				if (isset($datos['norem'])) {
					$norem = $datos['norem'];
				}
				$fijo = 0;
				if (isset($datos['fijo'])) {
					$fijo = $datos['fijo'];
				}
				$arrayCuerpoArchivo[$per][$i] = $datos['fecha']."|".$cuil."|".str_pad(number_format($remu,2,',',''),12,'0',STR_PAD_LEFT)."|".str_pad(number_format($norem,2,',',''),12,'0',STR_PAD_LEFT)."|".str_pad(number_format($fijo,2,',',''),12,'0',STR_PAD_LEFT)."|".$datos['origen']."|".$tipolinea."|".$datos['nombre'];
				$i++;
			}
		}
	}
	
	//CREAMOS EL ARCHIVO
	$ultanoArch = substr ($ultano,2,2);
	$ultmesArch = str_pad($ultmes,2,'0',STR_PAD_LEFT);
	$nombreArcCUIL = $cuit.$ultmesArch.$ultanoArch.'S'.$nroreqArc.".txt";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArcCUIL;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArcCUIL;
	}
	
	ksort($arrayCuerpoArchivo);
	
	//echo "<BR><BR>DETALLE<BR><BR>";
	$ar=fopen($direArc,"x");
	if ($ar===false) {
		$error = "Hubo un error al generar el archivo de liquidación de detalle de CUILES en $direArc. Por favor cuminiquese con el dpto. de Sistemas";
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
	foreach ($arrayCuerpoArchivo as $key=>$lineasCuil) {
		foreach ($lineasCuil as $lineaCuil) {
			//echo $key."->".$lineaCuil."<br>";
	 		fputs($ar,$lineaCuil."\r\n");
		}
	}
	fclose($ar);
}

function liquidar($nroreq, $cuit, $codidelega, $db) {
	//CREAMOS PRIMERA LINEA DEL ARCHIVO
	$sqlJuris = "SELECT e.*, j.*, p.descrip as provincia, l.nomlocali as localidad from empresas e, jurisdiccion j, provincia p, localidades l where j.cuit = $cuit and j.cuit = e.cuit and j.codidelega = $codidelega and j.codprovin = p.codprovin and j.codlocali = l.codlocali";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);

	//Coloco los tamaños fijos.
	$delcod = str_pad($rowJuris['codidelega'],4,'0',STR_PAD_RIGHT);
	$nombre = str_pad($rowJuris['nombre'],30,' ',STR_PAD_RIGHT);
	$domireal = str_pad($rowJuris['domireal'],30,' ',STR_PAD_RIGHT);
	$locaDescr = str_pad($rowJuris['localidad'],30,' ',STR_PAD_RIGHT);
	$provDescr = str_pad($rowJuris['provincia'],20,' ',STR_PAD_RIGHT);
	$numpostal = str_pad($rowJuris['numpostal'],4,'0',STR_PAD_RIGHT);

	if ($rowJuris['telefono'] == "" or $rowJuris['telefono'] == 0) {
		$telefono = $rowJuris['ddn1'].$rowJuris['telefono1'];
		$telefono = str_pad($telefono,14,' ',STR_PAD_RIGHT);
	} else {
		$telefono = $rowJuris['ddn'].$rowJuris['telefono'];
		$telefono = str_pad($telefono,14,' ',STR_PAD_RIGHT);
	}

	$primeraLinea = $delcod."|".$nombre."|".$domireal."|".$locaDescr."|".$provDescr."|".$cuit."|".$numpostal."|".$telefono;

	$arrayPagos = obtenerPagos($cuit, $db);
	$sqlRequeDet = "SELECT d.*, relacionmes, tipo, valor, retiene060*0.006 + retiene100*0.01 + retiene150*0.015 as porcentaje
						FROM detfiscalizusimra d
						LEFT JOIN extraordinariosusimra ON anofiscalizacion = anio and mesfiscalizacion = mes
						WHERE nrorequerimiento = $nroreq";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	$arrayReque = array();
	$arrayTipoLinea = array();
	$arrayRemu = array();
	$arrayCanPer = array();
	while ($rowRequeDet = mysql_fetch_assoc($resRequeDet)) {
		$mes = $rowRequeDet['mesfiscalizacion'];
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		if ($mes > 12) {
			$obligacion = calculoDeudaNr($rowRequeDet['remundeclarada'], $rowRequeDet['cantidadpersonal'], $rowRequeDet['tipo'], $rowRequeDet['valor'], $rowRequeDet['porcentaje']);
			$mesTipo = obtenerMesRelacion($mes,$rowRequeDet['anofiscalizacion'],$db);
			$mesTipo = str_pad($mesTipo,2,'0',STR_PAD_LEFT);
			if ( $rowRequeDet['tipo'] == 0) {
				$arrayTipoLinea[$rowRequeDet['anofiscalizacion'].$mesTipo][2] = 1;
			}
			if ( $rowRequeDet['tipo'] == 1) {
				$arrayTipoLinea[$rowRequeDet['anofiscalizacion'].$mesTipo][1] = 1;
			}
		} else {
			$arrayTipoLinea[$rowRequeDet['anofiscalizacion'].$mes][0] = 1;
			$obligacion = $rowRequeDet['remundeclarada'] * 0.031;
		}
		$index = $rowRequeDet['anofiscalizacion'].$mes;
		if (isset($arrayReque[$index])) {
			$arrayReque[$index] += round($obligacion, 2);
		} else {
			$arrayReque[$index] = round($obligacion, 2);
		}
		if (isset($arrayRemu[$index])) {
			$arrayRemu[$index] += round($rowRequeDet['remundeclarada'], 2);
		} else {
			$arrayRemu[$index] = round($rowRequeDet['remundeclarada'], 2);
		}
		if (isset($arrayCanPer[$index])) {
			if ($arrayCanPer[$index] < $rowRequeDet['cantidadpersonal']) {
				$arrayCanPer[$index] = $rowRequeDet['cantidadpersonal'];
			}
		} else {
			$arrayCanPer[$index] = $rowRequeDet['cantidadpersonal'];
		}
	} 
	
	//echo "<br><br>REQUERIMIENTOS -> AGRUPACION DE OBLIGACION<br><br>";
	$obligacion= array();
	foreach($arrayReque as $key => $obliga) {
		$anio = substr($key, 0, 4);
		$mes = substr($key, -2);
		if ($mes > 12) {
			$mes = obtenerMesRelacion($mes,$anio,$db);
		}
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		$index = $anio.$mes;
		if (isset($obligacion[$index])) {
			$obligacion[$index] += round($obliga,2);
		} else {
			$obligacion[$index] = round($obliga,2);
		}
		//echo $index." -> ".$obliga."<br>";
	}	
	
	//echo "<br><br>REQUERIMIENTOS -> AGRUPACION DE PAGOS y REMUNERACIONES<br><br>";
	$pagos = array();
	$remun = array();
	$perso = array();
	foreach($arrayReque as $key => $obliga) {
		$anio = substr($key, 0, 4);
		$mes = substr($key, -2);
		if ($mes > 12) {
			$mes = obtenerMesRelacion($mes,$anio,$db);
		}
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		$index = $anio.$mes;
	
		//agrupo pagos
		if (isset($arrayPagos[$key])) {
			foreach ($arrayPagos[$key] as $fechapago => $importe) {
				if (isset($pagos[$index][$fechapago])) {
					$pagos[$index][$fechapago] += round($importe,2);
				} else {
					$pagos[$index][$fechapago] = round($importe,2);
				}
				//echo $index." -> ".round($importe,2)."<br>";
			} 
		}
		
		//agrupo remuneraciones
		if (isset($arrayRemu[$key])) {
			if (isset($remun[$index])) {
				$remun[$index] += round($arrayRemu[$key],2);
			} else {
				$remun[$index] = round($arrayRemu[$key],2);
			}
		}
		
		//agrupo cantidad personal
		if (isset($arrayCanPer[$key])) {
			if (isset($arrayCanPer[$index])) {
				 if ($arrayCanPer[$index] > $arrayCanPer[$key]) {
				 	$perso[$index] = $arrayCanPer[$index];
				 } else {
				 	$perso[$index] = $arrayCanPer[$key];
				 }
			} else {
				$perso[$index] = $arrayCanPer[$key];
			}
		}
	}
	
	
	$cab = 0;
	foreach($obligacion as $key => $obliga) {
		$obliga = str_pad(number_format((float)$obliga,2,',',''),12,'0',STR_PAD_LEFT);
		$anio = substr($key, 0, 4);
		$mes = substr($key, -2);
		if ($mes > 12) {
			$mes = obtenerMesRelacion($mes,$anio,$db);
		}
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		$index = $anio.$mes;
		
		if (!isset($arrayTipoLinea[$index][0])) {
			$arrayTipoLinea[$index][0] = 0;
		}
		if (!isset($arrayTipoLinea[$index][1])) {
			$arrayTipoLinea[$index][1] = 0;
		}
		if (!isset($arrayTipoLinea[$index][2])) {
			$arrayTipoLinea[$index][2] = 0;
		}
		
		$remuneracion = 0;
		if (isset($remun[$index])) {
			$remuneracion = $remun[$index];
		}
		$remuneracion = number_format((float)$remuneracion,2,',','');
		$remuneracion = str_pad($remuneracion,12,'0',STR_PAD_LEFT);
		
		$personal = 0;
		if (isset($perso[$index])) {
			$personal = $perso[$index];
		}
		$personal = number_format((float)$personal,0,',','');
		$personal = str_pad($personal,4,'0',STR_PAD_LEFT);
		
		if (isset($pagos[$index])) {
			$nropago = 0;	
			foreach ($pagos[$index] as $fechapago => $importe) {
				$importe = number_format((float)$importe,2,',','');
				$importe = str_pad($importe,12,'0',STR_PAD_LEFT);	
				if ($nropago == 0) {
					$cabecera[$index][$cab] = "01/".$mes."/".$anio."|".$personal."|".$remuneracion."|".$obliga."|".invertirFecha($fechapago)."|".$importe."|".$arrayTipoLinea[$index][0].$arrayTipoLinea[$index][1].$arrayTipoLinea[$index][2];
				} else {
					$cabecera[$index][$cab] = "01/".$mes."/".$anio."|0000|000000000,00|000000000,00|".invertirFecha($fechapago)."|".$importe."|".$arrayTipoLinea[$index][0].$arrayTipoLinea[$index][1].$arrayTipoLinea[$index][2];
				}
				$nropago++;
				$cab++;
			}
		} else {
			$cabecera[$index][$cab] = "01/".$mes."/".$anio."|".$personal."|".$remuneracion."|".$obliga."|          |000000000,00|".$arrayTipoLinea[$index][0].$arrayTipoLinea[$index][1].$arrayTipoLinea[$index][2];
			$cab++;
		}
		
	}
	
	ksort($cabecera);
	
	$sqlUltimoMes = "SELECT d.anofiscalizacion,  IFNULL(e.relacionmes,d.mesfiscalizacion) as mesorden
	FROM detfiscalizusimra d LEFT JOIN extraordinariosusimra e on d.mesfiscalizacion = e.mes and d.anofiscalizacion = e.anio
	where d.nrorequerimiento = $nroreq
	order by d.anofiscalizacion DESC, mesorden DESC limit 1";
	$resUltimoMes = mysql_query($sqlUltimoMes,$db);
	$rowUltimoMes = mysql_fetch_assoc($resUltimoMes);
	$ultano = $rowUltimoMes['anofiscalizacion'];
	$ultmes = $rowUltimoMes['mesorden'];
	
	//CREAMOS EL ARCHIVO DE DEUDA
	$ultanoArch = substr ($ultano,2,2);
	$ultmesArch = str_pad($ultmes,2,'0',STR_PAD_LEFT);
	$nroreqCompleto = str_pad($nroreq,8,'0',STR_PAD_LEFT);
	$nombreArc = $cuit.$ultmesArch.$ultanoArch."U".$nroreqCompleto.".txt";
	$nombreArcExc = $cuit.$ultmesArch.$ultanoArch."U".$nroreqCompleto.".xls";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArc;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArc;
	}
	
	
	
	$ar=fopen($direArc,"x");

	if ($ar===false) {
		$error = "Hubo un error al generar el archivo de liquidación de Deuda en $direArc. Por favor cuminiquese con el dpto. de Sistemas";	
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
	
	//echo "<br><br>CABECERA<br><br>";
	fputs($ar,$primeraLinea."\r\n");
	$arrayTipoLinea = array();
	foreach ($cabecera as $key => $lineas) {
		foreach($lineas as $linea) {
			//echo $key."=>".$linea."<br>";
			fputs($ar,$linea."\r\n");
			
			$lineaExp = explode("|",$linea);
			$arrayTipoLinea[$key] = $lineaExp[6];
		}
	}
	fclose($ar);
	
	//**********************************
	creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $arrayTipoLinea, $nroreqCompleto);
	
	grabarCabLiquidacion($nroreq, $nombreArcExc, $db);
	
	cambioEstadoReq($nroreq);
	//**********************************
	
	return $nombreArc;
}

/***********************************************************************************/

$datos = array_values($_POST);
$reqALiquidar = array();
$resultado = array();
$req = 0;
$resul = 0;

for ($i=0; $i < sizeof($datos); $i++) {
	$nroreq = $datos[$i];
	$sqlRequeCab = "SELECT * from reqfiscalizusimra where nrorequerimiento = $nroreq";
	$resRequeCab = mysql_query($sqlRequeCab,$db);
	$rowRequeCab = mysql_fetch_assoc($resRequeCab);
	if ($rowRequeCab['procesoasignado'] == 0) {
		$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit'], 'codidelega' => $rowRequeCab['codidelega']);
		$req++;
	} else {
		$sqlRequeInsp = "SELECT * from inspecfiscalizusimra where nrorequerimiento = $nroreq";
		$resRequeInsp = mysql_query($sqlRequeInsp,$db);
		$rowRequeInsp= mysql_fetch_assoc($resRequeInsp);
		if ($rowRequeInsp['inspeccionefectuada'] == 1) {
			$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit'], 'codidelega' => $rowRequeCab['codidelega']);
			$req++;
		} else {
			$resultado[$resul] = array('nroreq' => $nroreq, 'estado' => "Se encuentra asociada a una inspección que nos se ha cerrado. No se liquidará", 'liquidado' => 0);
			$resul++;
		}
	}
}

if (sizeof($reqALiquidar) != 0) {
	for ($i=0; $i < sizeof($reqALiquidar); $i++) {
		$nombreArc = liquidar($reqALiquidar[$i]['req'],$reqALiquidar[$i]['cuit'], $reqALiquidar[$i]['codidelega'], $db);
		$resultado[$resul] =  array('nroreq' => $reqALiquidar[$i]['req'], 'estado' => "Se ha liquidado en el archivo con nombre '".$nombreArc."'", 'liquidado' => 1);
		$resul++;
	}
}

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s");
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos Liquidados:.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script>
function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
			"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=400, top=10, left=10");
}

</script>
</head>
<body bgcolor="#B2A274">
<div align="center">
<p><span style="text-align:center">
<input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" />
</span></p>
<p class="Estilo2">Resultado del proceso de liquidación los los requerimientos del d&iacute;a <?php echo $fecha ?>  </p>
	  <table style="width: 800; text-align: center;" border=1>
        <tr>
          <th>Req Nro.</th>
          <th>Resolución</th>
		  <th>Acción</th>
        </tr>
<?php 	for ($i=0; $i < sizeof($resultado); $i++) { ?>
			<tr align='center'>
			<td><?php echo $resultado[$i]['nroreq'] ?></td>
			<td><?php echo $resultado[$i]['estado'] ?></td>  
<?php		if ($resultado[$i]['liquidado'] == 0) {
				$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq']; ?>
				<td><a href="javascript:abrirInfo('<?php echo $dire ?>')">Ver Datos Inspección</a></td>
<?php		} else {  ?>
				<td>-</td>   
<?php		} ?>
			</tr>
<?php	} ?>
      </table>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
  </p>
</div>
</body>
</html>