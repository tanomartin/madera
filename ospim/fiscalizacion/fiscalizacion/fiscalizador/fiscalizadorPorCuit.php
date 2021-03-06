<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function esMontoMenor($remuDDJJ, $importe) {
	$alicuota = 0.0765;
	$limiteDif = 0.01;
	$valor81 = (float)($remuDDJJ * $alicuota );
	$diferencia = $valor81 - $importe;
	if ($diferencia > $limiteDif) {
		return(1);
	}
	return(0);
}

function estaVencido($fechaPago, $me, $ano) {
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
		return(1);
	}
	return(0);
}

function encuentroPagos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and (concepto = '381' or concepto = '401' or concepto = '471') and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
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
		if ($arrayPagos != 0) {
			return($arrayPagos);
		} else {
			return(0);
		}
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
			$arrayAcuerdos[$id] = array('anio' => (int)$rowAcuerdos['anoacuerdo'], 'mes' => (int)$rowAcuerdos['mesacuerdo'], 'estado' => 'N');
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

function encuentroDdjj($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db, &$arrayPagos) {
	$sqlDdjj = "select anoddjj, mesddjj, sum(totalremundeclarada + totalremundecreto) as remu, totalpersonal from cabddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj <= $anofin) or (anoddjj = $anoinicio and mesddjj >= $mesinicio)) group by anoddjj, mesddjj order by anoddjj, mesddjj";
	//print($sqlDdjj);
	$resDdjj = mysql_query($sqlDdjj,$db);
	$canDdjj = mysql_num_rows($resDdjj); 
	if($canDdjj > 0) {
		while ($rowDdjj = mysql_fetch_assoc($resDdjj)) { 
			$id=$rowDdjj['anoddjj'].$rowDdjj['mesddjj'];	
			$arrayDdjj[$id] = array('anio' => (int)$rowDdjj['anoddjj'], 'mes' => (int)$rowDdjj['mesddjj'], 'remu' => (float)$rowDdjj['remu'], 'totper' => (int)$rowDdjj['totalpersonal'], 'estado' => 'A');
			if(array_key_exists($id, $arrayPagos)) {
				$arrayPagos[$id] = array('anio' => $arrayPagos[$id]['anio'], 'mes' => $arrayPagos[$id]['mes'], 'importe' => $arrayPagos[$id]['importe'], 'fechapago' => $arrayPagos[$id]['fechapago'], 'remu' => (float)$rowDdjj['remu'], 'totper' => (int)$rowDdjj['totalpersonal'], 'estado' => $arrayPagos[$id]['estado']);
 			}
		}
	} else {
		return 0;
	}
	return($arrayDdjj);
}

/****************************************************************************************/

$cuit = $_GET['cuit'];
$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresas.php");

//PAGOS (ESTADO P) --> Se tienen que fiscalizar para estado F o M
$arrayPagos = encuentroPagos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayPagos == 0){ 
	$arrayPagos = array();
} 

//FUERA DE FISCALIZACION (ESTADO F)
$arrayAcuerdos = encuentroAcuerdos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayAcuerdos == 0){ 
	$arrayAcuerdos = array();
} 
//var_dump($arrayAcuerdos);

$arrayJuicios = encuentroJuicios($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayJuicios == 0){ 
	$arrayJuicios = array();
} 
//var_dump($arrayJuicios);

$arrayRequerimientos = encuentroRequerimientos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayRequerimientos == 0){ 
	$arrayRequerimientos = array();
}
//var_dump($arrayRequerimientos);
 
//ADEUDADO (ESTADO A)
$arrayDdjj = encuentroDdjj($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db, $arrayPagos);
if ($arrayDdjj == 0){ 
	$arrayDdjj = array();
} 
//var_dump($arrayDdjj);
//var_dump($arrayPagos);

$arrayFinal = array();
$redirec = 1;
while($ano<=$anofin) {
	for ($i=1;$i<13;$i++){
		$doit = 1;
		if ($ano == $anoinicio) {
			if ($i < $mesinicio) {
				$doit = 0;
			}
		}
		if ($ano == $anofin) {
			if ($i > $mesfin) {
				$doit = 0;
			}
		}
		if ($doit == 1) {
			$idArray = $ano.$i;
			if (!array_key_exists($idArray, $arrayAcuerdos) && !array_key_exists($idArray, $arrayJuicios) && !array_key_exists($idArray, $arrayRequerimientos)) {
				if (array_key_exists($idArray, $arrayPagos)) {
					if (esMontoMenor((float)$arrayPagos[$idArray]['remu'], (float)$arrayPagos[$idArray]['importe'])) {
						$arrayFinal[$idArray] = array('anio' => (int)$arrayPagos[$idArray]['anio'], 'mes' => (int)$arrayPagos[$idArray]['mes'], 'remu' => (float)$arrayPagos[$idArray]['remu'], 'totper' => (int)$arrayPagos[$idArray]['totper'], 'importe' => (float)$arrayPagos[$idArray]['importe'], 'estado' => 'M');
						$redirec = 0;
					} else {
						if (estaVencido($arrayPagos[$idArray]['fechapago'], $arrayPagos[$idArray]['mes'], $arrayPagos[$idArray]['anio'])) {
							$arrayFinal[$idArray] = array('anio' => (int)$arrayPagos[$idArray]['anio'], 'mes' => (int)$arrayPagos[$idArray]['mes'], 'remu' => (float)$arrayPagos[$idArray]['remu'], 'totper' => (int)$arrayPagos[$idArray]['totper'], 'importe' => (float)$arrayPagos[$idArray]['importe'], 'estado' => 'F');
							$redirec = 0;
						} else {
							$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => 'P');
						}
					}
				} else {
					$redirec = 0;
					if (array_key_exists($idArray, $arrayDdjj)) {
						$arrayFinal[$idArray] =  $arrayDdjj[$idArray];
					} else {
						$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => 'S');
					}
				}
			} else {
				$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $i, 'estado' => 'P');
			}
		}
	}
$ano++;
}

//var_dump($arrayFinal);

$origen = $_GET['origen'];
$solicitante = $_GET['soli'];
$motivo = $_GET['motivo'];

if ($redire == 0) {
	$listadoFinal[0] = array('cuit' => $cuit, 'deudas' => $arrayFinal);
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

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$.blockUI({ message: "<h1>Grabando Requerimientos de Fiscalización... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("fiscalizador").submit();
	}
</script>
</head>
<body onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>"/>
</form> 
</body>
</html>