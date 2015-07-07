<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
include($libPath."controlSessionOspim.php"); 
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

function compeltarNroReq($nroreq) {
	if ($nroreq<10) {
		$nrocompleto = "0000000".$nroreq;
	} else {
		if ($nroreq<100) {
			$nrocompleto = "000000".$nroreq;
		} else {
			if ($nroreq<1000) {
				$nrocompleto = "00000".$nroreq;
			} else {
				if ($nroreq<10000) {
					$nrocompleto = "0000".$nroreq;
				} else {
					if ($nroreq<100000) {
						$nrocompleto = "000".$nroreq;
					} else {
						if ($nroreq<1000000) {
							$nrocompleto = "00".$nroreq;
						} else {
							if ($nroreq<10000000) {
								$nrocompleto = "0".$nroreq;
							} else {
								$nrocompleto = $nroreq;
							}
						}
					}
				}
			}
		} 
	} 
	return($nrocompleto);
}

function encuentroPagos($cuit, $anoInicioActivida, $mesInicioActividad, $anoInicioDeuda, $mesInicioDeuda, $db) {
	if ($anoInicioActivida == $anoInicioDeuda) {
		$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda and mespago >= $mesInicioActividad) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
	} else {
		$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and ((anopago > $anoInicioActivida and anopago < $anoInicioDeuda) or (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda) or (anopago = $anoInicioActivida and mespago >= $mesInicioActividad)) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
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
	$sqlAcuerdos = "select anoacuerdo, mesacuerdo from detacuerdosospim where cuit = $cuit and ((anoacuerdo > $anoinicio and anoacuerdo <= $anofin) or (anoacuerdo = $anoinicio and mesacuerdo >= $mesinicio)) group by anoacuerdo, mesacuerdo order by anoacuerdo, mesacuerdo";
	//print($sqlAcuerdos);
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
	$sqlJuicios = "select d.anojuicio, d.mesjuicio from cabjuiciosospim c, detjuiciosospim d  where c.cuit = $cuit and c.nroorden = d.nroorden and ((d.anojuicio > $anoinicio and d.anojuicio <= $anofin) or (d.anojuicio = $anoinicio and d.mesjuicio >= $mesinicio)) group by d.anojuicio, d.mesjuicio order by d.anojuicio, d.mesjuicio";
	//print($sqlJuicios);
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
	$sqlRequerimientos = "select d.anofiscalizacion, d.mesfiscalizacion from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and ((d.anofiscalizacion > $anoinicio and d.anofiscalizacion <= $anofin) or (d.anofiscalizacion = $anoinicio and d.mesfiscalizacion >= $mesinicio)) group by d.anofiscalizacion, d.mesfiscalizacion order by d.anofiscalizacion, d.mesfiscalizacion";
	//print($sqlRequerimientos);
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
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresas.php");
	
	//print("ANO INICIO ACTIVIDAD: ".$anioInicioActi."<br>");
	//print("MES INICIO ACTIVIDAD: ".$mesInicioActi."<br>");
	//print("ANO INICIO CALCULO DEUDA: ".$anoinicio."<br>");
	//print("MES INICIO CALCULO DEUDA: ".$mesinicio."<br>");
	
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
				//print("ANO INICIO ACTIVIDAD: ".$anioInicioActi."<br>");
				//print("MES INICIO ACTIVIDAD: ".$mesInicioActi."<br>");
				break;
			}
		}
	}
	//var_dump($pagos);
	
	$arrayAcuerdos = encuentroAcuerdos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayAcuerdos == 0){ 
		$arrayAcuerdos = array();
	} 
	//var_dump($arrayAcuerdos);
	
	$arrayJuicios = encuentroJuicios($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayJuicios == 0){ 
		$arrayJuicios = array();
	} 
	//var_dump($arrayJuicios);
	
	$arrayRequerimientos = encuentroRequerimientos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	if ($arrayRequerimientos == 0){ 
		$arrayRequerimientos = array();
	}
	//var_dump($arrayRequerimientos);
	
	$deuda = 0;
	if ($pagos != 0) {
		$ano = $anioInicioActi;
		while($ano<=$anoinicio && $deuda <= 2) {
			for ($i=1;$i<13;$i++){
				$idArray = $ano.$i;
				if (!array_key_exists($idArray, $pagos) && !array_key_exists($idArray, $arrayAcuerdos) && !array_key_exists($idArray, $arrayJuicios) && !array_key_exists($idArray, $arrayRequerimientos)) {
					$deuda = $deuda + 1;
					//print("DEUDA ACUMULA: ".$deuda."<br>");
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
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresas.php");
	
	/*print("CUIT: ".$cuit."<br>");
	print("INICIO MES: ".$mesinicio."<br>");
	print("INICIO ANO: ".$anoinicio."<br>");
	print("FIN MES: ".$ultmes."<br>");
	print("FIN ANO: ".$ultano."<br>");*/
	
	if ($anoinicio == $mesinicio) {
		$sqlDDJJ = "select anoddjj, mesddjj, cuil, remundeclarada, adherentes from detddjjospim where cuit = $cuit and (anoddjj = $anoInicioDeuda and mesddjj <= $ultmes and mesddjj >= $mesinicio)";
	} else {
		$sqlDDJJ = "select anoddjj, mesddjj, cuil, remundeclarada, adherentes from detddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj < $ultano) or (anoddjj = $ultano and mesddjj <= $ultmes) or (anoddjj = $anoinicio and mesddjj >= $mesinicio))";
	}
	
	//print($sqlDDJJ."<br>");
	$arrayDDJJ = array();
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	$b = 0;
	while ($rowDDJJ = mysql_fetch_assoc($resDDJJ)) {
		if ($rowDDJJ['mesddjj'] < 10) {
			$mes = "0".$rowDDJJ['mesddjj'];
		} else {
			$mes = $rowDDJJ['mesddjj'];
		}
		$id = $rowDDJJ['anoddjj'].$mes;
		$arrayDDJJ[$b] = array ('id' =>  $id, 'datos' => $rowDDJJ);
		$b++;
	}
	//var_dump($arrayDDJJ);
	
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		$fecha = explode("|",$cuerpo[$i]);
		$fechaArray =  explode("/",$fecha[0]);
		$id = $fechaArray[2].$fechaArray[1];
		$idBuscar[$id] = $id;
	}
	//var_dump($idBuscar);
	
	$c = 0;
	for ($i=0; $i < sizeof($arrayDDJJ); $i++) {
		$id = $arrayDDJJ[$i]['id'];
		if (array_key_exists($id, $idBuscar)) {
			if ($arrayDDJJ[$i]['datos']['mesddjj'] < 10) {
				$mes = "0".$arrayDDJJ[$i]['datos']['mesddjj'];
			} else {
				$mes = $arrayDDJJ[$i]['datos']['mesddjj'];
			}
			$remuDecl = number_format((float)$arrayDDJJ[$i]['datos']['remundeclarada'],2,',','');
			$remuDecl = str_pad($remuDecl,12,'0',STR_PAD_LEFT);
			$cantAdhe = str_pad($arrayDDJJ[$i]['datos']['adherentes'],4,'0',STR_PAD_LEFT);
			$cuerpoCUIL[$c] = "01/".$mes."/".$arrayDDJJ[$i]['datos']['anoddjj']."|".agregaGuiones($arrayDDJJ[$i]['datos']['cuil'])."|".$remuDecl."|".$cantAdhe;
			$c++;
		}
	}
	
	//CREAMOS EL ARCHIVO
	$ultanoArch = substr ($ultano,2,2);
	$nombreArcCUIL = $cuit.$ultmes.$ultanoArch.'D'.$nroreqArc.".txt";
	//print("ARCHIVO: ".$nombreArc."<br><br>");
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/fiscalizacion/fiscalizacion/requerimientos/liqui/".$nombreArcCUIL;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArcCUIL;
	}
	//print($primeraLinea."<br>");
	//solo para probar y eliminar lo recien hecho... en producción NO...
	//unlink($direArc);
	//****************
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de detalle de CUILES en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	for ($i=0; $i < sizeof($cuerpoCUIL); $i++) {
		fputs($ar,$cuerpoCUIL[$i]."\r\n");
	}
	fclose($ar);
	//**********************************
}

function cambioEstadoReq($nroreq) {
	global $fechamodif, $usuariomodif;
	$sqlUpdateReque = "UPDATE reqfiscalizospim SET procesoasignado = 1, fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
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
	$sqlAcuerdo = "SELECT nroacuerdo, nroacta, fechaacuerdo, montoapagar, montopagadas FROM cabacuerdosospim WHERE cuit = $cuit and estadoacuerdo = 1 and tipoacuerdo != 3";
	$resAcuerdo = mysql_query($sqlAcuerdo,$db);
	$cuerpo = array();
	$c = 0;
	while ($rowAcuerdo = mysql_fetch_assoc($resAcuerdo)) {
		$nro = $rowAcuerdo['nroacuerdo'];
		$sqlCuotas = "SELECT fechacuota FROM cuoacuerdosospim WHERE cuit = $cuit and nroacuerdo = $nro and tipocancelacion = 8 order by fechacuota";
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
	$sqlCabeLiqui = "INSERT INTO cabliquiospim VALUE($nroreq, $fechaLiqui, $horaLiqui, '$nombreArcExc', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
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
	$deuda = deudaAnterior($cuit, $db);
	
	$primeraLinea = $delcod."|000000|".$nombre."|".$domireal."|".$locaDescr."|".$provDescr."|".$cuitconguiones."|".$numpostal."|".$telefono."|".$deuda;
	//**********************************************************************************************************
	
	//ACUERDOS CAIDOS
	//$cuerpoAcuerdoCaidos = acuerdosCaidos($cuit, $db);
	$cuerpoAcuerdoCaidos = array();
	//CREAMOS EL CUERPO DEL ARCHIVO CON LA DEUDA ************************************************************************
	
	$cuerpo = array();
	$pagos = array();
	$pagosOrdi = array();
	$pagosExtr = array();
	$l = 0;
	$sqlRequeDet = "SELECT * from detfiscalizospim where nrorequerimiento = $nroreq";
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
			unset($pagosOrdi);	
			unset($pagosExtr);		
			$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
			$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
			$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
			
			$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =".$rowRequeDet['anofiscalizacion']." and mesddjj =".$rowRequeDet['mesfiscalizacion'];
			$resAgrup = mysql_query($sqlAgrup,$db);
			$rowAgrup = mysql_fetch_assoc($resAgrup);
					
			$cantm1000 = str_pad($rowAgrup['cantcuilmenor1001'],4,'0',STR_PAD_LEFT);
			$remum1000 = number_format((float)$rowAgrup['remucuilmenor1001'],2,',','');
			$remum1000 = str_pad($remum1000,12,'0',STR_PAD_LEFT);
			$adehm1000 = str_pad($rowAgrup['cantadhemenor1001'],4,'0',STR_PAD_LEFT);
			$remam1000 = number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
			$remam1000 = str_pad($remam1000,12,'0',STR_PAD_LEFT);
					
			$cantM1000 = str_pad($rowAgrup['cantcuilmayor1000'],4,'0',STR_PAD_LEFT);
			$remuM1000 = number_format((float)$rowAgrup['remucuilmayor1000'],2,',','');
			$remuM1000 = str_pad($remuM1000,12,'0',STR_PAD_LEFT);
			$adehM1000 = str_pad($rowAgrup['cantadhemayor1000'],4,'0',STR_PAD_LEFT);
			$remaM1000 = number_format((float)$rowAgrup['remuadhemayor1000'],2,',','');
			$remaM1000 = str_pad($remaM1000,12,'0',STR_PAD_LEFT);
			
			$pExt = 0;
			$pOrd = 0;
			
			//PAGOS ORDINARIOS//
			$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and (concepto = '381' or concepto = '401') group by fechapago, debitocredito order by fechapago, debitocredito";
			$resAfipProc = mysql_query($sqlAfipProc,$db);
			while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
				$fechaOrdinario = $rowAfipProc['fechapago'];
				$importeOrdinario = $rowAfipProc['sum(importe)'];
				$importeOrdinario = number_format((float)$importeOrdinario,2,',','');
				if ($rowAfipProc['debitocredito'] == 'D') {
					$importeOrdinario = str_pad($importeOrdinario,11,'0',STR_PAD_LEFT);
					$importeOrdinario = "-".$importeOrdinario;
				} else {
					$importeOrdinario = str_pad($importeOrdinario,12,'0',STR_PAD_LEFT);
				}
				$pagosOrdi[$pOrd] = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|".invertirFecha($fechaOrdinario)."|".$importeOrdinario."|".$cantm1000."|".$remum1000."|".$adehm1000."|".$remam1000."|".$cantM1000."|".$remuM1000."|".$adehM1000."|".$remaM1000;
				$personal = "0000";
				$remunDec = "000000000,00";
				$pOrd++;
			} 
			//********************//
			
			//PAGOS EXTRAORDINARIOS//
			$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and concepto != 'REM' and concepto != '381' and concepto != '401' group by fechapago, debitocredito order by fechapago, debitocredito";
			$resAfipProc = mysql_query($sqlAfipProc,$db);
			while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
				$fechaExtra = $rowAfipProc['fechapago'];
				$importe = $rowAfipProc['sum(importe)'];
				$imporDep = number_format((float)$importe,2,',','');	
				if ($rowAfipProc['debitocredito'] == 'D') {
					$imporDep = str_pad($imporDep,11,'0',STR_PAD_LEFT);
					$imporDep = "-".$imporDep;
				} else {
					$imporDep = str_pad($imporDep,12,'0',STR_PAD_LEFT);
				}
				$lineaDeuda =  "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0000|000000000,00|".invertirFecha($fechaExtra)."|".$imporDep;
				$pagosExtr[$pExt] = str_pad($lineaDeuda,124,' ',STR_PAD_RIGHT);
				$pExt++;
			}
			//********************//		
			$pagos = array_merge((array)$pagosOrdi, (array)$pagosExtr);	
		} else {
			unset($pagos);	
			unset($pagosExtr);	
			if ($rowRequeDet['statusfiscalizacion'] == 'A') {
				//ESTO NO SE USA MAS Y HACE QUE TARDE MUCHO Y ESTA REPETIDO
				$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =". $rowRequeDet['anofiscalizacion']." and mesddjj = ".$rowRequeDet['mesfiscalizacion'];
				$resAgrup = mysql_query($sqlAgrup,$db);
				$rowAgrup = mysql_fetch_assoc($resAgrup);
				
				$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
				$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
				$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
				
				$cantm1000 = str_pad($rowAgrup['cantcuilmenor1001'],4,'0',STR_PAD_LEFT);
				$remum1000 = number_format((float)$rowAgrup['remucuilmenor1001'],2,',','');
				$remum1000 = str_pad($remum1000,12,'0',STR_PAD_LEFT);
				$adehm1000 = str_pad($rowAgrup['cantadhemenor1001'],4,'0',STR_PAD_LEFT);
				$remam1000 = number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
				$remam1000 = str_pad($remam1000,12,'0',STR_PAD_LEFT);
					
				$cantM1000 = str_pad($rowAgrup['cantcuilmayor1000'],4,'0',STR_PAD_LEFT);
				$remuM1000 = number_format((float)$rowAgrup['remucuilmayor1000'],2,',','');
				$remuM1000 = str_pad($remuM1000,12,'0',STR_PAD_LEFT);
				$adehM1000 = str_pad($rowAgrup['cantadhemayor1000'],4,'0',STR_PAD_LEFT);
				$remaM1000 = number_format((float)$rowAgrup['remuadhemayor1000'],2,',','');
				$remaM1000 = str_pad($remaM1000,12,'0',STR_PAD_LEFT);
				
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|          |            |".$cantm1000."|".$remum1000."|".$adehm1000."|".$remam1000."|".$cantM1000."|".$remuM1000."|".$adehM1000."|".$remaM1000;
				
				$pExt = 0;
				//PAGOS EXTRAORDINARIOS//
				$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and concepto != 'REM' and concepto != '381' and concepto != '401' group by fechapago, debitocredito order by fechapago, debitocredito";
				$resAfipProc = mysql_query($sqlAfipProc,$db);
				while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
					$fechaExtra = $rowAfipProc['fechapago'];
					$importe = $rowAfipProc['sum(importe)'];
					$imporDep = number_format((float)$importe,2,',','');	
					if ($rowAfipProc['debitocredito'] == 'D') {
						$imporDep = str_pad($imporDep,11,'0',STR_PAD_LEFT);
						$imporDep = "-".$imporDep;
					} else {
						$imporDep = str_pad($imporDep,12,'0',STR_PAD_LEFT);
					}
					$lineaDeuda =  "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0000|000000000,00|".invertirFecha($fechaExtra)."|".$imporDep;
					$pagosExtr[$pExt] = str_pad($lineaDeuda,124,' ',STR_PAD_RIGHT);
					$pExt++;
				}
				//********************//	
			} else {
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0000|000000000,00|          |            |0000|000000000,00|0000|000000000,00|0000|000000000,00|0000|000000000,00";
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
	//************************************************************************************************************************
	
	//CREAMOS EL ARCHIVO DE DEUDA
	$ultanoArch = substr ($ultano,2,2);
	$nroreqCompleto = compeltarNroReq($nroreq); 
	$nombreArc = $cuit.$ultmes.$ultanoArch."O".$nroreqCompleto.".txt";
	$nombreArcExc = $cuit.$ultmes.$ultanoArch."O".$nroreqCompleto.".xls";
	//print("ARCHIVO: ".$nombreArc."<br><br>");
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/fiscalizacion/fiscalizacion/requerimientos/liqui/".$nombreArc;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArc;
	}
	//print($primeraLinea."<br>");
	//solo por ahora...
	//unlink($direArc);
	//****************
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de Deuda en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\r\n");
	for ($i=0; $i < sizeof($cuerpoAcuerdoCaidos); $i++) {
		//print($cuerpoAcuerdoCaidos[$i]."<br>");
		fputs($ar,$cuerpoAcuerdoCaidos[$i]."\r\n");
	}
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		//print($cuerpo[$i]."<br>");
		fputs($ar,$cuerpo[$i]."\r\n");
	}
	fclose($ar);
	
	//**********************************
	creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $cuerpo, $nroreqCompleto);
	
	//Grabamos cabecera de liquidación
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
	$sqlRequeCab = "SELECT * from reqfiscalizospim where nrorequerimiento = $nroreq";
	$resRequeCab = mysql_query($sqlRequeCab,$db);
	$rowRequeCab = mysql_fetch_assoc($resRequeCab);
	if ($rowRequeCab['procesoasignado'] == 0) {
		$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit'], 'codidelega' => $rowRequeCab['codidelega']);
		$req++;
	} else {
		$sqlRequeInsp = "SELECT * from inspecfiscalizospim where nrorequerimiento = $nroreq";
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


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=400, top=10, left=10");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" />
  </span></p>
  	<p class="Estilo2">Resultado del proceso de liquidación los los requerimientos del d&iacute;a <?php echo $fecha ?>  </p>
	  <table width="800" border="1" align="center">
        <tr>
          <th>Req Nro.</th>
          <th>Resolución</th>
		  <th>Acción</th>
        </tr>
  <?php for ($i=0; $i < sizeof($resultado); $i++) {
			print("<tr align='center'>");
			print("<td>".$resultado[$i]['nroreq']."</td>");
			print("<td>".$resultado[$i]['estado']."</td>");   
			if ($resultado[$i]['liquidado'] == 0) {
				$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq'];
				print ("<td><a href=javascript:abrirInfo('".$dire."')>Ver Datos Inspección</a></td>");
			} else {
				print("<td>-</td>");   
			}
			print("</tr>");
		} ?>
      </table>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
  </p>
</div>
</body>
</html>