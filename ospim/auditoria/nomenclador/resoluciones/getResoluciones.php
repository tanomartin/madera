<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['nomenclador'])) {
	$nomenclador=$_POST['nomenclador'];
	$sqlResol="SELECT nr.*, DATE_FORMAT(nr.fechainicio,'%d/%m/%Y') as fechainicio, DATE_FORMAT(nr.fechafin,'%d/%m/%Y') as fechafin FROM nomencladoresresolucion nr WHERE nr.idnomenclador = '$nomenclador'";
	$resResol=mysql_query($sqlResol,$db);
	$canResol = mysql_num_rows($resResol);
	if ($canResol == 0) {
		$respuesta = 0;
	} else {
		$respuesta='<option value="0">Seleccione Resoluciones</option>';
		while($rowResol=mysql_fetch_assoc($resResol)) {
			$value = $rowResol['id'];
			$fechafin = $rowResol['fechafin'];
			if ($rowResol['fechafin'] == NULL) { $fechafin = "actualidad"; }
				
			$descri = $rowResol['nombre']." (".$rowResol['fechainicio']." - ".$fechafin.")";
			$respuesta.="<option value='$value'>".$descri."</option>";
		}
	}
	echo $respuesta;
}
?>