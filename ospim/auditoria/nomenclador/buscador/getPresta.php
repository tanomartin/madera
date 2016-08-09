<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['codigopresta'])) {
	$codigopresta = $_POST['codigopresta'];
	$codigonomenclador = $_POST['codigonomenclador'];
	if (!is_numeric($codigopresta)) {
		$respuesta = 0;
		echo $respuesta; 
	} else {
		$sqlPresta = "SELECT nombre FROM prestadores p, prestadornomenclador pr WHERE p.codigoprestador = $codigopresta and p.codigoprestador = pr.codigoprestador and pr.codigonomenclador = $codigonomenclador";
		$resPresta = mysql_query($sqlPresta,$db);
		$canPresta = mysql_num_rows($resPresta);
		if ($canPresta == 0) {
			//$respuesta = "<font color='red'><b>El prestador no existe o <br> El prestador no tiene asociado el nomenclador</b></font>";
			$respuesta = 0;
		} else {
			$rowPresta = mysql_fetch_assoc($resPresta);
			$respuesta = "<b>Nombre - <font color='blue'>".$rowPresta['nombre']."</font></b>";
		}
		echo $respuesta;
	}
}
?>