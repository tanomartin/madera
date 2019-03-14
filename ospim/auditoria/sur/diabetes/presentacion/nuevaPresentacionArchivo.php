<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$whereIn = "(";
$arrayCuiles = array();
foreach ($_POST as $datos) {
	$arrayDatos = explode("-",$datos);
	$whereIn .= $arrayDatos[0].",";
	$arrayCuiles[$arrayDatos[0]] = $arrayDatos[1];
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

$fechaDesde = $_GET['desde'];
$fechaHasta = $_GET['hasta'];

$sqlListadoDiabetes = "SELECT d.id, d.nroafiliado, d.nroorden, d.tipodiabetes, d.fechadiagnostico, d.edaddiagnostico,
							  diabetescomorbilidad.dislipemia as dislipemia,
							  diabetescomorbilidad.obesidad as obesidad,
							  diabetescomorbilidad.tabaquismo as tabaquismo,
							  diabetescomplicaciones.hipertrofiaventricular as hipertrofiaventricular,
							  diabetescomplicaciones.infartomiocardio as infartomiocardio,
							  diabetescomplicaciones.insuficienciacardiaca as insuficienciacardiaca,
							  diabetescomplicaciones.accidentecerebrovascular as accidentecerebrovascular,
							  diabetescomplicaciones.retinopatia as retinopatia,
							  diabetescomplicaciones.ceguera as ceguera,
							  diabetescomplicaciones.neuropatiaperiferica as neuropatiaperiferica,
							  diabetescomplicaciones.vasculopatiaperiferica as vasculopatiaperiferica,
							  diabetescomplicaciones.amputacion as amputacion,
							  diabetescomplicaciones.dialisis as dialisis,
							  diabetescomplicaciones.transplanterenal as transplanterenal,
							  
							  diabetesestudios.idDiagnostico as estudios,
							  diabetestratamientos.idDiagnostico as tratamiento,
							  diabetesfarmacos.idDiagnostico as farmacos
					   FROM diabetesdiagnosticos d
					   LEFT JOIN diabetescomorbilidad on diabetescomorbilidad.idDiagnostico = d.id
					   LEFT JOIN diabetescomplicaciones on diabetescomplicaciones.idDiagnostico = d.id
					   LEFT JOIN diabetesestudios on diabetesestudios.idDiagnostico = d.id
					   LEFT JOIN diabetestratamientos on diabetestratamientos.idDiagnostico = d.id
					   LEFT JOIN diabetesfarmacos on diabetesfarmacos.idDiagnostico = d.id
					   WHERE d.id in $whereIn";
echo $sqlListadoDiabetes."<br><br>";
$resListadoDiabetes = mysql_query($sqlListadoDiabetes,$db);

$cantidadBene = sizeof($arrayCuiles);
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
$insertPresentacion = "INSERT INTO diabetespresentacion (DEFAULT, '$fechaDesde', '$fechaHasta', $cantidadBene, NULL,NULL,NULL,NULL,NULL,'$fecharegistro','$usuarioregistro',NULL,NULL)";
echo $insertPresentacion."<br>";

$id = 43;
$archivoImportacion = "archivos/presdiabid".$id.".csv";
//$file = fopen($archivoImportacion, "w");
while ($rowListadoDiabetes = mysql_fetch_assoc($resListadoDiabetes)) {
	$fechaRegistro = date_format(date_create($rowListadoDiabetes['fechadiagnostico']),"Ymd");
	$edadDiag = str_pad($rowListadoDiabetes['edaddiagnostico'],2,0,STR_PAD_LEFT);
	
	$linea = $arrayCuiles[$rowListadoDiabetes['id']]."|".$rowListadoDiabetes['tipodiabetes']."|".$fechaRegistro."|".$edadDiag."|".
			 $rowListadoDiabetes['dislipemia']."|".$rowListadoDiabetes['obesidad']."|".$rowListadoDiabetes['tabaquismo']."|".
			 $rowListadoDiabetes['hipertrofiaventricular']."|".$rowListadoDiabetes['infartomiocardio']."|".$rowListadoDiabetes['insuficienciacardiaca']."|".
			 $rowListadoDiabetes['accidentecerebrovascular']."|".$rowListadoDiabetes['retinopatia']."|".$rowListadoDiabetes['ceguera']."|".
			 $rowListadoDiabetes['neuropatiaperiferica']."|".$rowListadoDiabetes['vasculopatiaperiferica']."|".$rowListadoDiabetes['amputacion']."|".
			 $rowListadoDiabetes['dialisis']."|".$rowListadoDiabetes['transplanterenal'];
	echo $linea."<br>";
	//fwrite($file, $linea . PHP_EOL);
}
//fclose($file);

?>
