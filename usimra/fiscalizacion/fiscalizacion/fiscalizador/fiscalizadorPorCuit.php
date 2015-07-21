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
			$id=$rowDdjj['anoddjj'].$rowDdjj['mesddjj'];	
			$montopagar = $rowDdjj['totapo'] + $rowDdjj['recarg'];	
			$arrayDdjj[$id] = array('anio' => (int)$rowDdjj['anoddjj'], 'mes' => (int)$rowDdjj['mesddjj'], 'remu' => (float)$rowDdjj['remune'], 'montopagar' => (float)$montopagar,'totper' => (int)$rowDdjj['nfilas'], 'estado' => 'A');
		}
	} else {
		return 0;
	}
	return($arrayDdjj);
}

/****************************************************************************************/

$cuit = $_GET['cuit'];
$cuit = '30530705117';
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
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