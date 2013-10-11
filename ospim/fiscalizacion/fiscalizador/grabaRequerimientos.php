<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
set_time_limit(0);
//Para que se vea el blockUI
print("<br>");
//*************************

function calculoMenor($cuit, $anio, $mes, $db) {
	$sqlMenor = "SELECT count(*) as result FROM resdetddjj d where cuit = $cuit and anoddjj = $anio and mesddjj = $mes and remundeclarada < 240.00";
	$resMenor = mysql_query($sqlMenor,$db); 
	$rowMenor = mysql_fetch_assoc($resMenor);
	return ((int)$rowMenor['result']);
}

/****************************************************************************************/

$listadoSerializado=$_POST['empresas'];
$listadoEmpresas = unserialize(urldecode($listadoSerializado));

$datosSerializado=$_POST['datosReq'];
$listadoDatosReq = unserialize(urldecode($datosSerializado));


$solicitante=$listadoDatosReq['solicitante'];
$motivo = $listadoDatosReq['motivo'];
$origen = $listadoDatosReq['origen'];
print("DATOS FILSCALIZACION");
var_dump($listadoDatosReq);

print("DEUDA DE EMPRESAS FILSCALIZDAS<br><br>");
$empre = 0; 
$alicuota = 0.081;
$listadoFinal = array();
for($i=0; $i < sizeof($listadoEmpresas); $i++) {
	$deudaFinal = array();
	$cuit = $listadoEmpresas[$i]['cuit'];
	$deudas = $listadoEmpresas[$i]['deudas'];
	foreach ($deudas as $deuda){
		$estado = $deuda['estado'];
		if ($estado != 'P') {
			$anio = $deuda['anio'];
			$mes = $deuda['mes'];
			$id = $anio.$mes;
			if ($estado != 'S') {
				if ($estado == 'A') {
					$deudaNominal = (float)($deuda['remu'] * $alicuota);
					$deuda['deudaNominal'] = (float)number_format($deudaNominal,2,'.','');
					$deuda['menor240'] = "NUEVA TABLA";
				} else {
					$apagar = (float)($deuda['remu'] * $alicuota);
					$deudaNominal = (float)($apagar - $deuda['importe']);
					$deuda['deudaNominal'] = (float)number_format($deudaNominal,2,'.','');
					$deuda['menor240'] = "NUEVA TABLA";
				}
			} else {
				$deuda['remu'] = 0.00;
				$deuda['totper'] = 0;
				$deuda['deudaNominal'] = 0.00;
				$deuda['menor240'] = 0;
			}
			$deudaFinal[$id] = $deuda;
		}	
	}
	if (sizeof($deudaFinal) != 0) {
		$listadoFinal[$empre] = array('cuit' => $cuit, 'deuda' => $deudaFinal);
		$empre = $empre + 1;
	}
}


foreach ($listadoFinal as $lista){
	print("CUIT: ".$lista['cuit']);
	var_dump($lista['deuda']);
}

?>