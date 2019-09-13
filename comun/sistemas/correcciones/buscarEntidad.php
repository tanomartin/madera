<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
$modulo = $_POST['modulo'];
$valor = $_POST['valor'];
$arrayExistencia = array();
switch ($modulo) {
	case "ACUERDOS":
	case "APORTES":
	case "EMPRESAS": 
	case "JUICIOS":
		$arrayExistencia[0] = "SELECT nombre FROM empresas WHERE cuit = $valor";
		$arrayExistencia[1] = "SELECT nombre FROM empresasdebaja WHERE cuit = $valor";
		$entidad = "EMPRESA";
		break;
	case "AFILIADOS":
	case "AUDITORIA":
		$arrayExistencia[0] = "SELECT apellidoynombre as nombre FROM titulares WHERE cuil = $valor";
		$arrayExistencia[1] = "SELECT apellidoynombre as nombre FROM titularesdebaja WHERE cuil = $valor";
		$arrayExistencia[2] = "SELECT apellidoynombre as nombre FROM familiares WHERE cuil = $valor";
		$arrayExistencia[3] = "SELECT apellidoynombre as nombre FROM familiaresdebaja WHERE cuil = $valor";
		$entidad = "AFILIADO";
		break;
	case "FACTURACION":
		$arrayExistencia[0] = "SELECT nombre FROM prestadores WHERE cuit = $valor";
		$entidad = "PRESTADOR";
		break;
	case "AUTORIZACIONES":
		$arrayExistencia[0] = "SELECT d.nombre FROM autorizaciones a , delegaciones d 
								WHERE nrosolicitud = $valor and a.codidelega = d.codidelega";
		$entidad = "DELEGACION";
		break;
}
$numExistencia = 0;
foreach($arrayExistencia as $sqlExistencia) {
	$resExistencia = mysql_query($sqlExistencia,$db);
	$numExistencia += mysql_num_rows($resExistencia);
	if ($numExistencia > 0) {
		$nombreArray = mysql_fetch_assoc($resExistencia);
		$nombre = $entidad." - ".$nombreArray['nombre'];
	} 
}
if ($numExistencia == 0) {
	$nombre = $entidad." - SIN INFORMACION";
}
echo $nombre;
?>