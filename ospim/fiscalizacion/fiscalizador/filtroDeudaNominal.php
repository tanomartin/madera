<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
set_time_limit(0);
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
	
	//VEO LOS PAGOS DE AFIP
	$sqlPagos = "select cuit, importe from afipprocesadas where cuit = $cuit and anopago = $ano and mespago = $me";
	$resPagos = mysql_query($sqlPagos,$db); 
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos == 0) {
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
				$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
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
						$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'A', 'remu' => $totalRemu, 'totper' => $rowDDJJ['totalpersonal'] );
						return($res);
					} else {
						// NO HAY DDJJ SIN PAGOS
						$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'S');
						return($res);
					} //else DDJJ
				}//if REQ
			} //if JUICIOS
		}//if ACUERDOS
	} else {//if PAGOS 
		$res = array('mes' => (int)$me, 'anio' => (int)$ano, 'estado' => 'P');
		return($res);
	}
	return(0);
} //function

function fiscalizadorDeudaNominal($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	$ano = $anoinicio;
	$n = 0;
	while($ano<=$anofin) {
		for ($i=1;$i<13;$i++){
			$res = estado($ano, $i, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
			if ($res != 0) {
				$datosDeuda[$n] = $res;
				$n = $n + 1;
			}
		}
		$ano++;
	}
	if (sizeof($datosDeuda) > 0) {
		return $datosDeuda;
	} else {
		return(0);
	}	
}

function deudaNominal($resultado) {
	$totalDeuda = 0;
	$alicuota = 0.081;
	for($i=0; $i < sizeof($resultado); $i++) {
		if($resultado[$i]['estado'] == "A") {
			$totalDeuda = $totalDeuda + $resultado[$i]['remu'];
		}
	}
	return ($totalDeuda * $alicuota);
}


//Para que se vea el blockUI
print("-");
//*************************
$listadoSerializado=$_POST['empresas'];
$filtrosSerializado=$_POST['filtros'];

$listadoEmpresas = unserialize(urldecode($listadoSerializado));
$filtros = unserialize(urldecode($filtrosSerializado));

//var_dump($listadoEmpresas);
//var_dump($filtros);


$n = 0;
for ($i=0; $i < sizeof($listadoEmpresas); $i++) {
	if ($n < $filtros['empresas']) {
		$cuit = $listadoEmpresas[$i]['cuit'];
		$fechaInicio = $listadoEmpresas[$i]['iniobliosp'];
		include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
		$resultado =  fiscalizadorDeudaNominal($cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db);
		//var_dump($resultado);
		if ($resultado!=0) {
			$deudaNominal = deudaNominal($resultado);
			if($deudaNominal > $filtros['deuda'] && $n <= $filtros['empresas']) {
				print("CUIT: ". $cuit." - DEUDA: ".$deudaNominal."<br>");
				$empresasDefinitivas[$n] = array('cuit' => $cuit, 'deudas' => $resultado);
				$n = $n + 1;
			}
		}
	} else {
		$i = sizeof($listadoEmpresas);
	}
}


if (sizeof($empresasDefinitivas) == 0) {
	header ("Location: menuFiscalizador.php?err=4");
} else {
	$listadoSerializado = serialize($empresasDefinitivas);
	$listadoSerializado = urlencode($listadoSerializado);
}
//var_dump($empresasDefinitivas);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Fiscalizando empresas Filtradas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("filtroDeudaNominal").submit();
	}
</script>

<body onload="formSubmit();">
<form action="fiscalizador.php" id="filtroDeudaNominal" method="POST"> 
   <input name="empresas" type="hidden" value="<?php echo $listadoSerializado ?>">
   <input name="solicitante" type="hidden" value="<?php echo $filtros['solicitante'] ?>">
</form> 
</body>