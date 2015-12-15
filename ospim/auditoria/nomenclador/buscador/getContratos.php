<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['codigopresta'])) {
	$codigopresta = $_POST['codigopresta'];
	$sqlContratos = "SELECT * FROM cabcontratoprestador WHERE codigoprestador = $codigopresta";
	$resContratos = mysql_query($sqlContratos,$db);
	$canContratos = mysql_num_rows($resContratos);
	if ($canContratos == 0) {
		$respuesta = 0;
	} else {
		$respuesta='<option value="0">Seleccione Contrato</option>';
		while($rowContratos = mysql_fetch_assoc($resContratos)) {
			$value = $rowContratos['idcontrato'];
			$respuesta.="<option value='$value'>".$rowContratos['idcontrato']." (".$rowContratos['fechainicio']." - ".$rowContratos['fechafin'].")</option>";
		}
	}
	echo $respuesta;
}
?>