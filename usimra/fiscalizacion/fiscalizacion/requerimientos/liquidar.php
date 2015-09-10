<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php"); 
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = $_GET['fecha'];
/**********************************************************************************/

function agregaGuiones($cuit) {
	$primero = substr ($cuit,0,2);
	$segundo = substr ($cuit,2,8);
	$tercero = substr ($cuit,10,1);
	$conguiones = $primero."-".$segundo."-".$tercero;
	return $conguiones;
}

function encuentroPagos($cuit, $anoInicioActivida, $mesInicioActividad, $anoInicioDeuda, $mesInicioDeuda, $db) {
	if ($anoInicioActivida == $anoInicioDeuda) {
		$sqlPagos = "select anopago, mespago, fechapago, sum(montopagado) from seguvidausimra where cuit = $cuit and (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda and mespago >= $mesInicioActividad) group by anopago, mespago, fechapago order by anopago, mespago, fechapago";
	} else {
		$sqlPagos = "select anopago, mespago, fechapago, sum(montopagado) from seguvidausimra where cuit = $cuit and ((anopago > $anoInicioActivida and anopago < $anoInicioDeuda) or (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda) or (anopago = $anoInicioActivida and mespago >= $mesInicioActividad)) group by anopago, mespago, fechapago order by anopago, mespago, fechapago";
	}
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];		
			$arrayPagos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'estado' => 'PAGO');
		}
		return($arrayPagos);
	} else {
		return(0);
	}
}

function encuentroAcuerdos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlAcuerdos = "select anoacuerdo, mesacuerdo from detacuerdosusimra where cuit = $cuit and ((anoacuerdo > $anoinicio and anoacuerdo <= $anofin) or (anoacuerdo = $anoinicio and mesacuerdo >= $mesinicio)) group by anoacuerdo, mesacuerdo order by anoacuerdo, mesacuerdo";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db);
	$canAcuerdos = mysql_num_rows($resAcuerdos); 
	if($canAcuerdos > 0) {
		while ($rowAcuerdos = mysql_fetch_assoc($resAcuerdos)) { 
			$id=$rowAcuerdos['anoacuerdo'].$rowAcuerdos['mesacuerdo'];	
			$arrayAcuerdos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'estado' => 'N');
		}
	} else {
		return 0;
	}
	return($arrayAcuerdos);
}

function encuentroJuicios($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlJuicios = "select d.anojuicio, d.mesjuicio from cabjuiciosusimra c, detjuiciosusimra d  where c.cuit = $cuit and c.nroorden = d.nroorden and ((d.anojuicio > $anoinicio and d.anojuicio <= $anofin) or (d.anojuicio = $anoinicio and d.mesjuicio >= $mesinicio)) group by d.anojuicio, d.mesjuicio order by d.anojuicio, d.mesjuicio";
	$resJuicios = mysql_query($sqlJuicios,$db);
	$canJuicios = mysql_num_rows($resJuicios); 
	if($canJuicios > 0) {
		while ($rowJuicios = mysql_fetch_assoc($resJuicios)) { 
			$id=$rowJuicios['anojuicio'].$rowJuicios['mesjuicio'];	
			$arrayJuicios[$id] = array('anio' => (int)$rowJuicios['anojuicio'], 'mes' => (int)$rowJuicios['mesjuicio'], 'estado' => 'N');
		}
	} else {
		return 0;
	}
	return($arrayJuicios);
}

function encuentroRequerimientos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlRequerimientos = "select d.anofiscalizacion, d.mesfiscalizacion from reqfiscalizusimra r, detfiscalizusimra d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and ((d.anofiscalizacion > $anoinicio and d.anofiscalizacion <= $anofin) or (d.anofiscalizacion = $anoinicio and d.mesfiscalizacion >= $mesinicio)) group by d.anofiscalizacion, d.mesfiscalizacion order by d.anofiscalizacion, d.mesfiscalizacion";
	$resRequerimientos = mysql_query($sqlRequerimientos,$db);
	$canRequerimientos = mysql_num_rows($resRequerimientos); 
	if($canRequerimientos > 0) {
		while ($rowRequerimientos = mysql_fetch_assoc($resRequerimientos)) { 
			$id=$rowRequerimientos['anofiscalizacion'].$rowRequerimientos['mesfiscalizacion'];	
			$arrayRequerimientos[$id] = array('anio' => (int)$rowRequerimientos['anofiscalizacion'], 'mes' => (int)$rowRequerimientos['mesfiscalizacion'], 'estado' => 'N');
		}
	} else {
		return 0;
	}
	return($arrayRequerimientos);
}

function deudaAnterior($cuit, $db) {
	$tipo = 'activa';
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
	$anioInicioActi = substr($fechaInicio,0,4);
	$mesInicioActi = substr($fechaInicio,5,2);
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");
	
	if ($anioInicioActi == $anoinicio) {
		if ($mesInicioActi == $mesinicio) {
			return "N";
		} else { //ES MENOR EL MES DE INICIO DE ACTIVIDAD
			$pagos = encuentroPagos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
		}
	} else { //ES MENOR EL AÑO DE INICIO DE ACTIVIDAD Ó 0000
		$pagos = encuentroPagos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	} 

	if ($anioInicioActi == '0000' && $pagos == 0) {
		return "N";
	} else {
		if ($anioInicioActi == '0000') {
			foreach($pagos as $pago) {
				$anioInicioActi = $pago['anio'];
				$mesInicioActi = $pago['mes'];
				break;
			}
		}
	}
	
	$arrayAcuerdos = encuentroAcuerdos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayAcuerdos == 0){ 
		$arrayAcuerdos = array();
	} 
	
	$arrayJuicios = encuentroJuicios($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayJuicios == 0){ 
		$arrayJuicios = array();
	} 
	
	$arrayRequerimientos = encuentroRequerimientos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayRequerimientos == 0){ 
		$arrayRequerimientos = array();
	}
	
	$deuda = 0;
	if ($pagos != 0) {
		$ano = $anioInicioActi;
		while($ano<=$anoinicio && $deuda <= 2) {
			for ($i=1;$i<13;$i++){
				$idArray = $ano.$i;
				if (!array_key_exists($idArray, $pagos) && !array_key_exists($idArray, $arrayAcuerdos) && !array_key_exists($idArray, $arrayJuicios) && !array_key_exists($idArray, $arrayRequerimientos)) {
					$deuda = $deuda + 1;
				}
			}
			$ano++;
		}
	}
	if($deuda > 2) {
		return 'S';
	} else {
		return 'N';
	}
}

function creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $cuerpo, $nroreqArc) {	
	$tipo = 'activa';
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");
		
	//DDJJ VALIDAS
	$sqlDDJJ = "select anoddjj, mesddjj, cuil, remuneraciones from detddjjusimra 
					where cuit = $cuit and cuil != '99999999999' and
					((anoddjj > $anoinicio and anoddjj < $ultano) or 
	   				 (anoddjj = $ultano and mesddjj <= $ultmes) or 
	  				 (anoddjj = $anoinicio and mesddjj >= $mesinicio))";
	$arrayDDJJ = array();
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	while ($rowDDJJ = mysql_fetch_assoc($resDDJJ)) {
		$mes = str_pad($rowDDJJ['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJ['anoddjj'].$mes;
		$idArray = $rowDDJJ['anoddjj'].$mes.$rowDDJJ['cuil'];
		$arrayDDJJ[$idArray] = array ('origen' =>  1, 'datos' => $rowDDJJ, 'id' => $id);
	}
	
	//DDJJ TEMPORALES
	$sqlDDJJNroControl = "select perano, permes, nrctrl from ddjjusimra 
					   where nrcuit = $cuit and nrcuil = '99999999999' and
					   ((perano > $anoinicio and perano < $ultano) or 
	   				 	(perano = $ultano and permes <= $ultmes) or 
	  				 	(perano = $anoinicio and permes >= $mesinicio)) order by nrctrl, nrcuil DESC";
	$arrayNroControl = array();
	$resDDJJNroControl = mysql_query($sqlDDJJNroControl,$db);
	while ($rowDDJJNroControl = mysql_fetch_assoc($resDDJJNroControl)) {
		$idNrocontrol = $rowDDJJNroControl['perano'].$rowDDJJNroControl['permes'];
		$arrayNroControl[$idNrocontrol] =  $rowDDJJNroControl['nrctrl'];
	}
	foreach($arrayNroControl as $nrocontrol) {
		$wherein = $wherein."'".$nrocontrol."',";
	}
	$wherein = substr($wherein, 0, -1);
	$wherein = "(".$wherein.")";
	if ($wherein != "") {
		$sqlDDJJTemp = "select perano as anoddjj, permes as mesddjj, nrcuil as cuil, remune as remuneraciones from ddjjusimra 
							where nrcuit = $cuit and nrcuil != '99999999999' and nrctrl in $wherein";
		$resDDJJTemp = mysql_query($sqlDDJJTemp,$db);
		while ($rowDDJJTemp = mysql_fetch_assoc($resDDJJTemp)) {
			$mes = str_pad($rowDDJJTemp['mesddjj'],2,'0',STR_PAD_LEFT);
			$id = $rowDDJJTemp['anoddjj'].$mes;
			$idArray = $rowDDJJTemp['anoddjj'].$mes.$rowDDJJTemp['cuil'];
			if (!array_key_exists($idArray, $arrayDDJJ)) {
				$arrayDDJJ[$idArray] = array ('origen' =>  1, 'datos' => $rowDDJJTemp, 'id' => $id);
			}
		}
	}
	
	//DDJJ OSPIM			 	
	$sqlDDJJOspim = "select anoddjj, mesddjj, cuil, remundeclarada as remuneraciones from detddjjospim 
						where cuit = $cuit and cuil != '99999999999' and
							((anoddjj > $anoinicio and anoddjj < $ultano) or 
							 (anoddjj = $ultano and mesddjj <= $ultmes) or 
							 (anoddjj = $anoinicio and mesddjj >= $mesinicio))";
	$resDDJJOspim = mysql_query($sqlDDJJOspim,$db);
	while ($rowDDJJOspim = mysql_fetch_assoc($resDDJJOspim)) {
		$mes = str_pad($rowDDJJOspim['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJOspim['anoddjj'].$mes;
		$idArray = $rowDDJJOspim['anoddjj'].$mes.$rowDDJJOspim['cuil'];
		if (!array_key_exists($idArray, $arrayDDJJ)) {
			$arrayDDJJ[$idArray] = array ('origen' =>  2, 'datos' => $rowDDJJOspim, 'id' => $id);
		}
	}
	
	sort($arrayDDJJ);
	
	//NO REMUNERATIVO
	$sqlDDJJNR = "select d.anoddjj, d.mesddjj, e.relacionmes, d.cuil, d.remuneraciones 
						from detddjjusimra d, extraordinariosusimra e
						where d.cuit = $cuit and d.anoddjj > $anoinicio and d.anoddjj <= $ultano and d.mesddjj > 12 and d.anoddjj = e.anio and d.mesddjj = e.mes"; 
	$resDDJJNR = mysql_query($sqlDDJJNR,$db);
	$arrayNR = array();
	while ($rowDDJJNR = mysql_fetch_assoc($resDDJJNR)) {
		$mes = str_pad($rowDDJJNR['relacionmes'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJNR['cuil'].$rowDDJJNR['anoddjj'].$mes;
		$arrayNR[$id] =  array ('datos' => $rowDDJJNR);;
	}
	
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		$fecha = explode("|",$cuerpo[$i]);
		$fechaArray =  explode("/",$fecha[0]);
		$id = $fechaArray[2].$fechaArray[1];
		$idBuscar[$id] = $id;
	}
	
	$c = 0;
	foreach($arrayDDJJ as $ddjj) {
		$id = $ddjj['id'];
		if (array_key_exists($id, $idBuscar)) {
			$mes = str_pad($ddjj['datos']['mesddjj'],2,'0',STR_PAD_LEFT);
			$ano = $ddjj['datos']['anoddjj'];
			$idNR = $ddjj['datos']['cuil'].$ano.$mes;
			
			if (array_key_exists($idNR, $arrayNR)) {
				$norem = str_pad($arrayNR[$idNR]['datos']['remuneraciones'],12,'0',STR_PAD_LEFT);
			} else {
				$norem = str_pad('0',12,'0',STR_PAD_LEFT);
			}		
			$norem = number_format($norem,2,',','');
			$norem = str_pad($norem,12,'0',STR_PAD_LEFT);
			$remuDecl = number_format((float)$ddjj['datos']['remuneraciones'],2,',','');
			$remuDecl = str_pad($remuDecl,12,'0',STR_PAD_LEFT);
			$cuerpoCUIL[$c] = "01/".$mes."/".$ano."|".agregaGuiones($ddjj['datos']['cuil'])."|".$remuDecl."|".$norem."|".$ddjj['origen'];
			$c++;
		}
	}
	
	//CREAMOS EL ARCHIVO
	$ultanoArch = substr ($ultano,2,2);
	$nombreArcCUIL = $cuit.$ultmes.$ultanoArch.'S'.$nroreqArc.".txt";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArcCUIL;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArcCUIL;
	}

	//**********************************//
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de detalle de CUILES en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	for ($i=0; $i < sizeof($cuerpoCUIL); $i++) {
		fputs($ar,$cuerpoCUIL[$i]."\r\n");
	}
	fclose($ar);
	//**********************************//
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
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function estaVencida($fecha) {
	$today = date("Y-m-d"); 
	if ($today > $fecha) {
		return 1;
	}
	return 0;
}

function masTresVto($fecha) {
	$today = date("Y-m-d");
	$fechaVTO = strtotime ('-3 month',strtotime($today));
	$fechaVTO = date ('Y-m-j',$fechaVTO);
	if ($fechaVTO > $fecha) {
		return 1;
	}
	return 0;
}

function acuerdosCaidos($cuit, $db) {
	$sqlAcuerdo = "SELECT nroacuerdo, nroacta, fechaacuerdo, montoapagar, montopagadas FROM cabacuerdosusimra WHERE cuit = $cuit and estadoacuerdo = 1 and tipoacuerdo != 3";
	$resAcuerdo = mysql_query($sqlAcuerdo,$db);
	$cuerpo = array();
	$c = 0;
	while ($rowAcuerdo = mysql_fetch_assoc($resAcuerdo)) {
		$nro = $rowAcuerdo['nroacuerdo'];
		$sqlCuotas = "SELECT fechacuota FROM cuoacuerdosusimra WHERE cuit = $cuit and nroacuerdo = $nro and tipocancelacion = 8 order by fechacuota";
		$resCuotas = mysql_query($sqlCuotas,$db);
		$canCuotas = mysql_num_rows($resCuotas);
		$cantVto = 0;
		unset($fechasVencidas);
		while ($rowCuotas = mysql_fetch_assoc($resCuotas)) {
			if (estaVencida($rowCuotas['fechacuota'])) {
				$fechasVencidas[$cantVto] = $rowCuotas['fechacuota'];
				$cantVto++;
			}
		}

		//Colocolo los tamaños fijos.
		$nroacue = str_pad($rowAcuerdo['nroacuerdo'],3,'0',STR_PAD_LEFT);
		$acueNro = "ACUE".$nroacue;
		$acta = str_pad($rowAcuerdo['nroacta'],9,'0',STR_PAD_LEFT);
		$deuda = (float)($rowAcuerdo['montoapagar'] - $rowAcuerdo['montopagadas']);
		$deuda = number_format((float)$deuda,2,',','');
		$deuda = str_pad($deuda,12,'0',STR_PAD_LEFT);
		
		if ($cantVto < 3) {		
			$masTres = 0;
			for ($i=0; $i < sizeof($fechasVencidas); $i++) {
				if (masTresVto($fechasVencidas[$i])) {
					$masTres = 1;
					$i = sizeof($fechasVencidas);
				}
			}
			if ($masTres > 0) {
				$lineaAcuCaido = $acueNro."|".$acta."|".invertirFecha($fechasVencidas[0])."|".invertirFecha($rowAcuerdo['fechaacuerdo'])."|".$deuda;
				$cuerpo[$c] = str_pad($lineaAcuCaido,124,' ',STR_PAD_RIGHT);
				$c++;
			}
		} else {
			$lineaAcuCaido =  $acueNro."|".$acta."|".invertirFecha($fechasVencidas[0])."|".invertirFecha($rowAcuerdo['fechaacuerdo'])."|".$deuda;
			$cuerpo[$c] = str_pad($lineaAcuCaido,124,' ',STR_PAD_RIGHT);
			$c++;
		}
	}
	return($cuerpo);
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
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function liquidar($nroreq, $cuit, $codidelega, $db) {
	//CREAMOS PRIMERA LINEA DEL ARCHIVO
	$sqlJuris = "SELECT e.*, j.*, p.descrip as provincia, l.nomlocali as localidad from empresas e, jurisdiccion j, provincia p, localidades l where j.cuit = $cuit and j.cuit = e.cuit and j.codidelega = $codidelega and j.codprovin = p.codprovin and j.codlocali = l.codlocali";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$cuitconguiones = agregaGuiones($cuit);
	
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
	//DEUDA ANTERIOR
	//$deuda = deudaAnterior($cuit, $db);
	
	$primeraLinea = $delcod."|000000|".$nombre."|".$domireal."|".$locaDescr."|".$provDescr."|".$cuitconguiones."|".$numpostal."|".$telefono;
	
	//ACUERDOS CAIDOS
	//$cuerpoAcuerdoCaidos = acuerdosCaidos($cuit, $db);
	
	//CREAMOS EL CUERPO DEL ARCHIVO CON LA DEUDA
	$cuerpo = array();
	$pagos = array();
	$l = 0;
	$sqlRequeDet = "SELECT * from detfiscalizusimra where nrorequerimiento = $nroreq";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	while ($rowRequeDet = mysql_fetch_assoc($resRequeDet)) {
		if ($rowRequeDet['mesfiscalizacion'] < 10) {
			$mes = "0".$rowRequeDet['mesfiscalizacion'];
		} else {
			$mes = $rowRequeDet['mesfiscalizacion'];
		}
		if ($rowRequeDet['statusfiscalizacion'] == 'F' || $rowRequeDet['statusfiscalizacion'] == 'M') {
			//ACA PODEMOS PONER lA LINEA 0 ya que la data viene del detalle y de la consulta de agrupfisca linea de información.
			unset($pagos);		
			$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
			$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
			$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
			$pOrd = 0;
			//PAGOS ORDINARIOS//
			$sqlAfipProc = "select fechapago, sum(montopagado) from seguvidausimra where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." group by fechapago order by fechapago";
			$resAfipProc = mysql_query($sqlAfipProc,$db);
			while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
				$fechaOrdinario = $rowAfipProc['fechapago'];
				$importeOrdinario = $rowAfipProc['sum(montopagado)'];
				$importeOrdinario = number_format((float)$importeOrdinario,2,',','');
				$importeOrdinario = str_pad($importeOrdinario,12,'0',STR_PAD_LEFT);
				$pagos[$pOrd] = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|".invertirFecha($fechaOrdinario)."|".$importeOrdinario;
				$personal = "0000";
				$remunDec = "000000000,00";
				$pOrd++;
			} 
			//****************//
		} else {
			unset($pagos);	
			if ($rowRequeDet['statusfiscalizacion'] == 'A') {	
				$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
				$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
				$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|          |            ";
			} else {
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0000|000000000,00|          |            ";
			}
		}
		
		
		if (sizeof($pagos) > 0) {
			for ($n = 0; $n < sizeof($pagos); $n++) {
				$cuerpo[$l] = $pagos[$n];
				$l++;
			}
		} else  {
			$cuerpo[$l] = $linea;
			$l++;
			if (sizeof($pagosExtr) > 0) {
				for ($n = 0; $n < sizeof($pagosExtr); $n++) {
					$cuerpo[$l] = $pagosExtr[$n];
					$l++;
				}
			}
		}
		$ultmes = $mes;
		$ultano = $rowRequeDet['anofiscalizacion'];
	}

	//CREAMOS EL ARCHIVO DE DEUDA
	$ultanoArch = substr ($ultano,2,2);
	$nroreqCompleto = str_pad($nroreq,8,'0',STR_PAD_LEFT);
	$nombreArc = $cuit.$ultmes.$ultanoArch."U".$nroreqCompleto.".txt";
	$nombreArcExc = $cuit.$ultmes.$ultanoArch."U".$nroreqCompleto.".xls";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArc;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArc;
	}
	
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de Deuda en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\r\n");
	/*for ($i=0; $i < sizeof($cuerpoAcuerdoCaidos); $i++) {
		fputs($ar,$cuerpoAcuerdoCaidos[$i]."\r\n");
	}*/
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		fputs($ar,$cuerpo[$i]."\r\n");
	}
	fclose($ar);
	
	//**********************************
	creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $cuerpo, $nroreqCompleto);
	
	grabarCabLiquidacion($nroreq, $nombreArcExc, $db);
	
	//ACTULIZAMOS EL ESTADO DEL REQUERIMIENTO A 1.
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