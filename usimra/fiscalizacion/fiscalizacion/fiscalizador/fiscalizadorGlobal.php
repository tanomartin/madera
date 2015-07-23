<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function esMontoMenor($remuDDJJ, $importe) {
	$alicuota = 0.031;
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
	$sqlPagos = "select anopago, mespago, fechapago, sum(remuneraciones) as remune, sum(montopagado) as importe, cantidadpersonal from seguvidausimra 
					where cuit = $cuit and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio)) and mespago < 13
					group by anopago, mespago order by anopago, mespago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		$idanterior = "";
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];		
			$arrayPagos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'remu' => (float)$rowPagos['remune'], 'importe' => $rowPagos['importe'], 'fechapago' => $rowPagos['fechapago'],  'totper' => $rowPagos['cantidadpersonal'] ,'estado' => 'P');
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

function encuentroPagosAnteriores($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlPagosAnteriores = "select anoanterior, mesanterior from periodosanterioresusimra where cuit = $cuit and ((anoanterior > $anoinicio and anoanterior <= $anofin) or (anoanterior = $anoinicio and mesanterior >= $mesinicio))";
	$resPagosAnteriores = mysql_query($sqlPagosAnteriores,$db);
	$canPagosAnteriores = mysql_num_rows($resPagosAnteriores);
	if($canPagosAnteriores > 0) {
		while ($rowPagosAnteriores = mysql_fetch_assoc($resPagosAnteriores)) {
			$id=$rowPagosAnteriores['anoanterior'].$rowPagosAnteriores['mesanterior'];
			$arrayPagosAnteriores[$id] = array('anio' => (int)$rowPagosAnteriores['anoanterior'], 'mes' => (int)$rowPagosAnteriores['mesanterior'], 'estado' => 'N');
		}
		if ($arrayPagosAnteriores != 0) {
			return($arrayPagosAnteriores);
		} else {
			return(0);
		}
	} else {
		return(0);
	}
}

function encuentroAcuerdos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlAcuerdos = "select anoacuerdo, mesacuerdo from detacuerdosusimra where cuit = $cuit and ((anoacuerdo > $anoinicio and anoacuerdo <= $anofin) or (anoacuerdo = $anoinicio and mesacuerdo >= $mesinicio)) group by anoacuerdo, mesacuerdo order by anoacuerdo, mesacuerdo";
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
	$sqlJuicios = "select d.anojuicio, d.mesjuicio from cabjuiciosusimra c, detjuiciosusimra d  where c.cuit = $cuit and c.nroorden = d.nroorden and ((d.anojuicio > $anoinicio and d.anojuicio <= $anofin) or (d.anojuicio = $anoinicio and d.mesjuicio >= $mesinicio)) group by d.anojuicio, d.mesjuicio order by d.anojuicio, d.mesjuicio";
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
	$sqlRequerimientos = "select d.anofiscalizacion, d.mesfiscalizacion from reqfiscalizusimra r, detfiscalizusimra d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and ((d.anofiscalizacion > $anoinicio and d.anofiscalizacion <= $anofin) or (d.anofiscalizacion = $anoinicio and d.mesfiscalizacion >= $mesinicio)) group by d.anofiscalizacion, d.mesfiscalizacion order by d.anofiscalizacion, d.mesfiscalizacion";
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
	$sqlDdjj = "select perano, permes, remune, totapo, recarg, nfilas from ddjjusimra where nrcuit = $cuit and nrcuil = 99999999999 and permes < 13 and ((perano > $anoinicio and perano <= $anofin) or (perano = $anoinicio and permes >= $mesinicio)) order by perano, permes, id ASC";
	//print($sqlDdjj);
	$resDdjj = mysql_query($sqlDdjj,$db);
	$canDdjj = mysql_num_rows($resDdjj); 
	if($canDdjj > 0) {
		while ($rowDdjj = mysql_fetch_assoc($resDdjj)) { 
			$id=$rowDdjj['perano'].$rowDdjj['permes'];	
			$montopagar = $rowDdjj['totapo'] + $rowDdjj['recarg'];	
			$arrayDdjj[$id] = array('anio' => (int)$rowDdjj['perano'], 'mes' => (int)$rowDdjj['permes'], 'remu' => (float)$rowDdjj['remune'], 'montopagar' => (float)$montopagar,'totper' => (int)$rowDdjj['nfilas'], 'estado' => 'A');
		}
	} else {
		return 0;
	}
	return($arrayDdjj);
}

function deudaNominal($arrayFinal) {
	$totalDeuda = 0;
	$alicuota = 0.031;
	foreach ($arrayFinal as $perido){
		$valor31 = (float)($perido['remu'] * $alicuota );
		if($perido['estado'] == "A") {
			$totalDeuda = $totalDeuda + $valor31;
		}
		if ($perido['estado'] == "M") {
			$diferencia = $valor31 - $perido['importe'];
			$totalDeuda = $totalDeuda + $diferencia;
		}
	}
	return($totalDeuda);
}

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));
$filtrosSerializado=$_POST['filtros'];
$filtros = unserialize(urldecode($filtrosSerializado));

//var_dump($listadoEmpresas);
//var_dump($filtros);

$empre = 0;
for ($e=0; $e < sizeof($listadoEmpresas); $e++) {
	if ($empre < $filtros['empresas']) {
		$cuit = $listadoEmpresas[$e]['cuit'];
		$fechaInicio = $listadoEmpresas[$e]['iniobliosp'];
		include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");
		
		$arrayPagos = encuentroPagos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayPagos == 0){ 
			$arrayPagos = array();
		}
		$arrayPagosAnteriores = encuentroPagosAnteriores($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayPagosAnteriores == 0){
			$arrayPagosAnteriores = array();
		}
		$arrayAcuerdos = encuentroAcuerdos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayAcuerdos == 0){ 
			$arrayAcuerdos = array();
		} 
		$arrayJuicios = encuentroJuicios($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayJuicios == 0){ 
			$arrayJuicios = array();
		} 
		$arrayRequerimientos = encuentroRequerimientos($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		if ($arrayRequerimientos == 0){ 
			$arrayRequerimientos = array();
		}
		$arrayDdjj = encuentroDdjj($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db, $arrayPagos);
		if ($arrayDdjj == 0){ 
			$arrayDdjj = array();
		} 
		
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
					if (!array_key_exists($idArray, $arrayAcuerdos) && !array_key_exists($idArray, $arrayJuicios) && !array_key_exists($idArray, $arrayRequerimientos) && !array_key_exists($idArray, $arrayPagosAnteriores)) {
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
		
		$deudaNominal = deudaNominal($arrayFinal);
		//print("CUIT: ".$cuit." - DEUDA: ".$deudaNominal."<br>");
		if($deudaNominal > $filtros['deuda']) {
			$empresasDefinitivas[$empre] = array('cuit' => $cuit, 'deudas' => $arrayFinal);
			$empre = $empre + 1;
		}
	} else {
		$e = sizeof($listadoEmpresas);
	}
}

//var_dump($empresasDefinitivas);

if(sizeof($empresasDefinitivas) == 0) {
	header ("Location: fiscalizador.php?err=5");
	exit(0);
} else {
	$datosReque['origen'] = 1;
	$datosReque['motivo'] = "Selecci�n Autom�tica";
	$datosReque['solicitante'] = $filtros['solicitante'];
	
	$listadoSerializado = serialize($empresasDefinitivas);
	$listadoSerializado = urlencode($listadoSerializado);
	
	$listadoDatosReq = serialize($datosReque);
	$listadoDatosReq = urlencode($listadoDatosReq);
}

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$.blockUI({ message: "<h1>Grabando Requerimientos de Fiscalizaci�n... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("fiscalizador").submit();
	}
</script>
</head>
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>"/>
</form> 
</body>
</html>