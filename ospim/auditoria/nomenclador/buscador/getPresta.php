<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['codigopresta'])) {
	$codigopresta = $_POST['codigopresta'];
	if (!is_numeric($codigopresta)) {
		$respuesta = 0;
		echo $respuesta; 
	} else {
		$sqlPresta = "SELECT nombre FROM prestadores WHERE codigoprestador = $codigopresta";
		$resPresta = mysql_query($sqlPresta,$db);
		$canPresta = mysql_num_rows($resPresta);
		if ($canPresta == 0) {
			$respuesta = 0;
		} else {
			$rowPresta = mysql_fetch_assoc($resPresta);
			$respuesta = $rowPresta['nombre'];
		}
		echo $respuesta;
	}
}
?>