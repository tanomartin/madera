<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionUsimra.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function consultaPeriodos($db) {
	$sqlPerExtra = "SELECT p.anio,p.mes,e.relacionmes FROM periodosusimra p, extraordinariosusimra e where p.mes = e.mes and p.anio = e.anio and e.tipo != 2";
	$resPerExtra = mysql_query($sqlPerExtra,$db);
	while ($rowPerExtra = mysql_fetch_assoc($resPerExtra)) {
		$id=$rowPerExtra['anio'].$rowPerExtra['mes'];
		$arrayPeriodos[$rowPerExtra['anio']][$id] = array('anio' => $rowPerExtra['anio'], 'mes' => $rowPerExtra['mes'], 'relacionmes' => $rowPerExtra['relacionmes']);
	}
	
	$sqlPerComun = "SELECT anio,mes FROM periodosusimra p where mes < 13";
	$resPerComun = mysql_query($sqlPerComun,$db);
	while ($rowPerComun = mysql_fetch_assoc($resPerComun)) {
		$id=$rowPerComun['anio'].$rowPerComun['mes'];
		$arrayPeriodos[$rowPerComun['anio']][$id] = array('anio' => $rowPerComun['anio'], 'mes' => $rowPerComun['mes']);
	}
	return($arrayPeriodos);
}

function consultaExtra($db) {
	$sqlExtra = "SELECT relacionmes, anio, mes, tipo, valor, retiene060*0.06 + retiene100*0.1 + retiene150*0.15 as porcentaje FROM extraordinariosusimra WHERE tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	while ($rowExtra = mysql_fetch_assoc($resExtra)) {
		$id=$rowExtra['anio'].$rowExtra['mes'];
		$arrayExtra[$id] = array('anio' => (int)$rowExtra['anio'], 'mes' => (int)$rowExtra['mes'], 'tipo' => (int)$rowExtra['tipo'], 'valor' => (float)$rowExtra['valor'], 'porcentaje' => (float)$rowExtra['porcentaje']);
	}
	return $arrayExtra;
}

function esMontoMenorNoRemu($remu, $importe, $personal, $extra) {
	$apagar = 0;
	$limiteDif = 0.01;
	if ($extra['tipo'] == 0) {
		$apagar = $extra['valor'] * $extra['porcentaje'] * $personal;
	}
	if ($extra['tipo'] == 1) {
		$apagar = $remu * $extra['valor'] * $extra['porcentaje'];
	}
	if ($extra['tipo'] == 2) {
		$apagar =  $extra['valor'] * $personal;
	}
	$diferencia = $apagar - $importe;
	if ($diferencia > $limiteDif) {
		return($diferencia);
	}
	return(0);
}

function esMontoMenor($remuDDJJ, $importe) {
	$alicuota = 0.031;
	$limiteDif = 0.01;
	$valor81 = (float)($remuDDJJ * $alicuota );
	$diferencia = $valor81 - $importe;
	if ($diferencia > $limiteDif) {
		return($diferencia);
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
					where cuit = $cuit and ((anopago > $anoinicio and anopago <= $anofin) or (anopago = $anoinicio and mespago >= $mesinicio))
					group by anopago, mespago order by anopago, mespago";
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
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

function encuentroDdjj($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
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

function encuentroDdjjOspim($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$sqlDdjjOspim = "select anoddjj, mesddjj, sum(totalremundeclarada + totalremundecreto) as remu, totalpersonal from cabddjjospim where cuit = $cuit and ((anoddjj > $anoinicio and anoddjj <= $anofin) or (anoddjj = $anoinicio and mesddjj >= $mesinicio)) group by anoddjj, mesddjj order by anoddjj, mesddjj";
	$resDdjjOspim = mysql_query($sqlDdjjOspim,$db);
	$canDdjjOspim = mysql_num_rows($resDdjjOspim);
	if($canDdjjOspim > 0) {
		while ($rowDdjjOspim = mysql_fetch_assoc($resDdjjOspim)) {
			$id=$rowDdjjOspim['anoddjj'].$rowDdjjOspim['mesddjj'];
			$arrayDdjjOspim[$id] = array('anio' => (int)$rowDdjjOspim['anoddjj'], 'mes' => (int)$rowDdjjOspim['mesddjj'], 'remu' => (float)$rowDdjjOspim['remu'], 'totper' => (int)$rowDdjjOspim['totalpersonal'], 'estado' => 'O');
		}
	} else {
		return 0;
	}
	return($arrayDdjjOspim);
}

/****************************************************************************************/

//$cuit = $_GET['cuit'];
$cuit = "30530705117";
$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");

//PAGOS (ESTADO P) --> Se tienen que fiscalizar para estado F o M
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
$arrayDdjj = encuentroDdjj($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayDdjj == 0){ 
	$arrayDdjj = array();
} 
//var_dump($arrayDdjj);
//var_dump($arrayPagos);

//ADEUDADO (ESTADO O)
$arrayDdjjOspim = encuentroDdjjOspim($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
if ($arrayDdjjOspim == 0){
	$arrayDdjjOspim = array();
}
//var_dump($arrayDdjj);
//var_dump($arrayPagos);

$arrayExtraordinarios = consultaExtra($db);

$arrayPerdiosXAnio = consultaPeriodos($db);

$arrayFinal = array();
$redirec = 1;
while($ano<=$anofin) {
	$arrayPerdiosAFiscali = $arrayPerdiosXAnio[$ano];
	foreach ($arrayPerdiosAFiscali as $perido){
		if ($perido['mes'] > 12) {
			$mes = $perido['relacionmes'];
		} else {
			$mes = $perido['mes'];
		}
		$doit = 1;
		if ($ano == $anoinicio) {
			if ($mes < $mesinicio) {
				$doit = 0;
			}
		}
		if ($ano == $anofin) {
			if ($mes > $mesfin) {
				$doit = 0;
			}
		}
		if ($doit == 1) {
			$idArray = $ano.$perido['mes'];
			if (!array_key_exists($idArray, $arrayAcuerdos) && !array_key_exists($idArray, $arrayJuicios) && !array_key_exists($idArray, $arrayRequerimientos) && !array_key_exists($idArray, $arrayPagosAnteriores)) {
				if (array_key_exists($idArray, $arrayPagos)) {
					//PAGO MENOR
					$esMenor = 0;
					if ($arrayPagos[$idArray]['mes'] > 12) {
						$diferencia = esMontoMenorNoRemu((float)$arrayPagos[$idArray]['remu'], (float)$arrayPagos[$idArray]['importe'], (int)$arrayPagos[$idArray]['totper'], $arrayExtraordinarios[$idArray]);
						if ($diferencia > 0) {
							$arrayFinal[$idArray] = array('anio' => (int)$arrayPagos[$idArray]['anio'], 'mes' => (int)$arrayPagos[$idArray]['mes'], 'remu' => (float)$arrayPagos[$idArray]['remu'], 'totper' => (int)$arrayPagos[$idArray]['totper'], 'importe' => (float)$arrayPagos[$idArray]['importe'], 'deuda' => $diferencia, 'estado' => 'M');
							$redirec = 0;
							$esMenor = 1;
						}
					} else {
						$diferencia = esMontoMenor((float)$arrayPagos[$idArray]['remu'], (float)$arrayPagos[$idArray]['importe']);
						if ($diferencia > 0) {
							$arrayFinal[$idArray] = array('anio' => (int)$arrayPagos[$idArray]['anio'], 'mes' => (int)$arrayPagos[$idArray]['mes'], 'remu' => (float)$arrayPagos[$idArray]['remu'], 'totper' => (int)$arrayPagos[$idArray]['totper'], 'importe' => (float)$arrayPagos[$idArray]['importe'], 'deuda' => $diferencia, 'estado' => 'M');
							$redirec = 0;
							$esMenor = 1;
						}
					}
					if($esMenor == 0) {
						//PAGO VENCIDO
						if (estaVencido($arrayPagos[$idArray]['fechapago'], $arrayPagos[$idArray]['mes'], $arrayPagos[$idArray]['anio'])) {
							$arrayFinal[$idArray] = array('anio' => (int)$arrayPagos[$idArray]['anio'], 'mes' => (int)$arrayPagos[$idArray]['mes'], 'remu' => (float)$arrayPagos[$idArray]['remu'], 'totper' => (int)$arrayPagos[$idArray]['totper'], 'importe' => (float)$arrayPagos[$idArray]['importe'], 'estado' => 'F');
							$redirec = 0;
						} else {
							$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $perido['mes'], 'estado' => 'P');
						}
					}
				} else {
					$redirec = 0;
					if (array_key_exists($idArray, $arrayDdjj)) {
						$arrayFinal[$idArray] =  $arrayDdjj[$idArray];
					} else {
						if (array_key_exists($idArray, $arrayDdjjOspim)) {
							$arrayFinal[$idArray] =  $arrayDdjjOspim[$idArray];
						} else {
							$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $perido['mes'], 'estado' => 'S');
						}
					}
				}
			} else {
				$arrayFinal[$idArray] =  array('anio' => $ano, 'mes' => $perido['mes'], 'estado' => 'P');
			}
		}
	}
$ano++;
}

//var_dump($arrayFinal);

$origen = $_GET['origen'];
$solicitante = $_GET['soli'];
$motivo = $_GET['motivo'];

if ($redirec == 0) {
	$listadoFinal[0] = array('cuit' => $cuit, 'deudas' => $arrayFinal);
} else {
	header ("Location: fiscalizador.php?err=5");
	exit(0);
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
<body bgcolor="#B2A274" onload="formSubmit();">
<form action="grabaRequerimientos.php" id="fiscalizador" method="post"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>"/>
   <input name="datosReq" type="hidden" value="<?php echo $listadoDatosReq ?>"/>
</form> 
</body>
</html>