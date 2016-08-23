<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['codigopresta'])) {
	$codigopresta = $_POST['codigopresta'];
	$sqlPersoneria = "SELECT personeria FROM prestadores WHERE codigoprestador = $codigopresta";
	$resPersoneria = mysql_query($sqlPersoneria,$db);
	$canPersoneria = mysql_num_rows($resPersoneria);
	if ($canPersoneria == 0) {
		$respuesta = 0;
	} else {
		$rowPersoneria = mysql_fetch_assoc($resPersoneria);
		if ($rowPersoneria['personeria'] == 3) {
			$sqlCategoria = "select * from practicascategorias";
			$resCategoria = mysql_query($sqlCategoria,$db);
			while($rowCategoria = mysql_fetch_assoc($resCategoria)) { 
				$respuesta.="<option value='".$rowCategoria['id']."'>".$rowCategoria['descripcion']."</option>";
			}
		} else {
			$respuesta.="<option value='0'>Sin Categoria</option>";
		}
	}
	echo $respuesta;
}
?>