<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['personeria'])) {
	$respuesta = "";
	$personeria=$_POST['personeria'];
	if ($personeria == 0) {
		$respuesta = 0;
	} else { 
		if ($personeria == 6) {
			$respuesta = -1;
		} else {
			if ($personeria == 1) {
				$sqlServicio = "SELECT * FROM tiposervicio where profesional != 0";
			}
			if ($personeria != 1) {
				$sqlServicio = "SELECT * FROM tiposervicio where profesional != 1";
			}
			
			$resServicio = mysql_query($sqlServicio,$db);
			$canServicio = mysql_num_rows($resServicio);
			if ($canServicio == 0) {
				$respuesta = 0;
			} else {
				$i=0;
				while ($rowServicios=mysql_fetch_array($resServicio)) {
					$codigoServicio = $rowServicios['codigoservicio'];
					$descripcion = $rowServicios['descripcion'];
					$descripcion = utf8_encode($descripcion);
					$respuesta.="<input type='checkbox' id='servicios' name='servicios$i' value='$codigoServicio' />$descripcion<br />";
					$i++;
				}
			}
		}
	}
	echo $respuesta;
}
?>