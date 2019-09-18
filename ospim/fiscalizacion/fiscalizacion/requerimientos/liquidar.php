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

function tieneDiscapacitados($cuit, $db) {
	$disca = "N";
	$sqlAfiliados = "SELECT nroafiliado, discapacidad FROM titulares WHERE cuitempresa = $cuit";
	$resAfiliados = mysql_query($sqlAfiliados,$db);
	$canAfiliados = mysql_num_rows($resAfiliados);
	if ($canAfiliados != 0) {
		$whereIn = "(";
		while($rowAfiliados = mysql_fetch_assoc($resAfiliados)) {
			$whereIn .= $rowAfiliados['nroafiliado'].",";
			if ($rowAfiliados['discapacidad'] == 1) {
				$disca = "S";
			}
		}
		$whereIn = substr($whereIn, 0, -1);
		$whereIn .= ")";
		if ($disca == "N") {
			$sqlAfiliadosFami = "SELECT discapacidad FROM familiares WHERE nroafiliado in $whereIn and discapacidad = 1";
			$resAfiliadosFami = mysql_query($sqlAfiliadosFami,$db);
			$canAfiliadosFami = mysql_num_rows($resAfiliadosFami);
			if ($canAfiliadosFami > 0) {
				$disca = "S";
			}
		}
	}
	return $disca;
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

	$sqlDDJJ = "select anoddjj, mesddjj, cuil, remundeclarada, adherentes from detddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj < $ultano) or (anoddjj = $ultano and mesddjj <= $ultmes) or (anoddjj = $anoinicio and mesddjj >= $mesinicio))";
	
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
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
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
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
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
	
	$disca = tieneDiscapacitados($cuit, $db);
	
	$primeraLinea = $delcod."|000000|".$nombre."|".$domireal."|".$locaDescr."|".$provDescr."|".$cuitconguiones."|".$numpostal."|".$telefono."|".$disca;
	//*******************************************************************************************************************
	
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
			
			//PAGOS ORDINARIOS 381 y 401//
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
			
			//PAGOS ORDINARIOS 471//
			$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and concepto = '471' group by fechapago, debitocredito order by fechapago, debitocredito";
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
			$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and concepto != 'REM' and concepto != '381' and concepto != '401' and concepto != '471' group by fechapago, debitocredito order by fechapago, debitocredito";
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
	$nroreqCompleto = str_pad($nroreq,8,'0',STR_PAD_LEFT);
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
	//****************
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de Deuda en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\r\n");
	
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
<script language="javascript">

function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=400, top=10, left=10");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" /></p>
  	<h3>Resultado del proceso de liquidación los los requerimientos del d&iacute;a <?php echo $fecha ?>  </h3>
	<table width="800" border="1" align="center">
        <tr>
          <th>Req Nro.</th>
          <th>Resolución</th>
		  <th>Acción</th>
        </tr>
  <?php for ($i=0; $i < sizeof($resultado); $i++) { ?>
			<tr align="center">
				<td><?php echo $resultado[$i]['nroreq'] ?></td>
			 	<td><?php echo $resultado[$i]['estado'] ?></td>
			<?php if ($resultado[$i]['liquidado'] == 0) {
					$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq']; ?>
					<td><a href="javascript:abrirInfo('<?php echo $dire ?>')">Ver Datos Inspección</a></td>
			<?php } else { ?>
					<td>-</td>   
			<?php } ?>
			</tr>
   <?php } ?>
      </table>
      <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>