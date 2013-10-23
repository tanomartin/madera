<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function reverificaFueraTerminoYMenor($ano, $me, $cuit, $db) {
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosospim c, detacuerdosospim d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
	$CantAcuerdos = mysql_num_rows($resAcuerdos); 
	if($CantAcuerdos > 0) {
		return('ACUERDO');
	} else {
		//VEO LOS JUICIOS
		$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio > 0) {
			return('JUICIO');
		} else {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq > 0) {
				return('REQ');
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return ('ENTRA');
}

function esMontoMenor($cuit, $importe, $me, $ano, $db) {
	$alicuota = 0.081;
	$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	$CanDDJJ = mysql_num_rows($resDDJJ); 
	if ($CanDDJJ != 0) {
		$rowDDJJ = mysql_fetch_assoc($resDDJJ);
		$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
		$valor81 = (float)($remuDDJJ * $alicuota );
		if ($importe < $valor81) {
			$resultadoMenor = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal']);
			return($resultadoMenor);
		}
	}
	return(0);
}

function estaVencido($cuit, $fechaPago, $me, $ano, $db) {
	if ($me == 12) {
		$mesvto = 1;
		$anovto = $ano + 1;
	} else {
		$mesvto = $me + 1;
		$anovto = $ano;
	}
	if ($mesvto < 10) {
		$mesvto = "0".$mesvto;
	}
	$diavto = 15;
	$fechaStr = $anovto.'-'.$mesvto.'-'.$diavto;
	if (strcmp($fechaPago,$fechaStr) > 0) {
		$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
		$resDDJJ = mysql_query($sqlDDJJ,$db);
		$CanDDJJ = mysql_num_rows($resDDJJ); 
		if ($CanDDJJ != 0) {
			$rowDDJJ = mysql_fetch_assoc($resDDJJ);
			$remuDDJJ = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
			$resultadoVto = array('remu' => $remuDDJJ, 'totper' => $rowDDJJ['totalpersonal']);
		} else {
			$resultadoVto = array('remu' => 0.00, 'totper' => 0);
		}
		return($resultadoVto);
	}
	return(0);
}

function fiscalizoPagos($cuit, $arrayPagos, $db) {
	$resultado = array();
	foreach ($arrayPagos as $pago){
		$anio = $pago['anio'];
		$mes = $pago['mes'];
		$id=$anio.$mes; 
		$importe = $pago['importe'];
		$fechaPago = $pago['fechapago'];
		$resMenor = esMontoMenor($cuit, $importe, $mes, $anio, $db);
		if ($resMenor != 0) {
			$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'remu' => $resMenor['remu'], 'totper' => (int)$resMenor['totper'], 'importe' => $importe, 'estado' => 'M');
		} else {
			$resVto = estaVencido($cuit, $fechaPago, $mes, $anio, $db);
			if ($resVto != 0) {
				$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'remu' => $resVto['remu'], 'totper' => (int)$resVto['totper'], 'importe' => $importe, 'estado' => 'F');
			} else {
				$resultado[$id] = array('anio' => (int)$anio, 'mes' => (int)$mes, 'importe' => $importe, 'estado' => 'P');
			}
		}
	}
	if (sizeof($resultado) != 0) {
		return($resultado);
	} else {
		return(0);
	}
}

function encuentroPagosMyF($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		$idanterior = "";
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];		
			if ($rowPagos['debitocredito'] == 'C') {
				$importe = (float)$rowPagos['sum(importe)'];
			} else {
				$importe = (float)("-".$rowPagos['sum(importe)']);
			}
			if ($id == $idanterior) {
				$importeArray = $arrayPagos[$id]['importe'];
				$importe = $importeArray + $importe;
			} else {
				$idanterior = $id;
			}	
			$arrayPagos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'importe' => $importe, 'fechapago' => $rowPagos['fechapago'] ,'estado' => 'P');
		}	
		
		$arrayPagos = fiscalizoPagos($cuit, $arrayPagos, $db);
		if ($arrayPagos != 0) {
			return($arrayPagos);
		} else {
			return(0);
		}
	} else {
		return(0);
	}
}

function estado($ano, $me, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	if ($ano == $anoinicio) {
		if ($me < $mesinicio) {
			return(0);
		}
	}
	if ($ano == $anofin) {
		if ($me > $mesfin) {
			return(0);
		}
	}
	
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select cuit from detacuerdosospim where cuit = $cuit and anoacuerdo = $ano and mesacuerdo = $me";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
	$CantAcuerdos = mysql_num_rows($resAcuerdos); 		
	if($CantAcuerdos == 0) {
		//VEO LOS JUICIOS
		$sqlJuicio = "select c.nroorden from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio == 0) {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq == 0) {
				// VEO LAS DDJJ REALIZADAS SIN PAGOS
				$sqlDDJJ = "select totalremundeclarada, totalremundecreto, totalpersonal from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
				$resDDJJ = mysql_query($sqlDDJJ,$db); 
				$CantDDJJ = mysql_num_rows($resDDJJ); 
				if($CantDDJJ > 0) {
					$rowDDJJ = mysql_fetch_assoc($resDDJJ);
					$totalRemu = $rowDDJJ['totalremundeclarada'] + $rowDDJJ['totalremundecreto'];
					$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'A', 'remu' => $totalRemu, 'totper' => (int)$rowDDJJ['totalpersonal'] );
					return($res);
				} else {
					// NO HAY DDJJ SIN PAGOS
					$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'S');
					return($res);
				} //else DDJJ
			}//if REQ
		} //if JUICIOS
	}//if ACUERDOS
	return(0);
} //function


/****************************************************************************************/

$cuit = $_GET['cuit'];
$tipo = $_GET['tipo'];
if (tipo == "activa") {
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
} else {
	$sqlEmpresasInicioActividad = "select iniobliosp, fechabaja from empresasdebaja where cuit = $cuit ";
}	
$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
if ($tipo == "baja") {
	$fechaBaja = $rowEmpresasInicioActividad['fechabaja'];
}
include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");

$arrayPagos = encuentroPagosMyF($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayPagos==0){ 
	$arrayPagos = array();
} 

while($ano<=$anofin) {
	for ($i=1;$i<13;$i++){
		$idArray = $ano.$i;
		if (!array_key_exists($idArray, $arrayPagos)) {
			$resultado = estado($ano, $i, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
			if ($resultado != 0) {
				$arrayPagos[$idArray] =  $resultado;
			}
		} else {
			$estado = $arrayPagos[$idArray]['estado'];
			if($estado != 'P') {
				$resultado = reverificaFueraTerminoYMenor($ano, $i, $cuit, $db);
				if ($resultado != 'ENTRA') {
					$arrayPagos[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => 'P');
				}
			}
		}
	}
$ano++;
}
$origen = $_GET['origen'];
$solicitante = $_GET['soli'];
$motivo = $_GET['motivo'];

//var_dump($arrayPagos);
if (sizeof($arrayPagos) != 0) {
	$listadoFinal[0] = array('cuit' => $cuit, 'deudas' => $arrayPagos);
} else {
	header ("Location: fiscalizador.php?err=5");
}

$datosReque['origen'] = $origen;
$datosReque['motivo'] = $motivo;
$datosReque['solicitante'] = $solicitante;
	
$listadoSerializado = serialize($listadoFinal);
$listadoSerializado = urlencode($listadoSerializado);
	
$listadoDatosReq = serialize($datosReque);
$listadoDatosReq = urlencode($listadoDatosReq);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Grabando Requerimientos de Fiscalización... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("fiscalizador").submit();
	}
</script>

<body onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>">
</form> 
</body>