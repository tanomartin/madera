<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['pos'])) {
	$pos = $_POST['pos'];
	$respuesta='<option value="0">Seleccione Cara</option>';
	if ($pos != "") {
		$sqlCaras = "SELECT * FROM piezadentalcaras WHERE posicion = '$pos' OR posicion = 'ambas'";
		$resCaras = mysql_query($sqlCaras,$db);
		while($rowCara = mysql_fetch_assoc($resCaras)) {
			$respuesta .= "<option value='".$rowCara['id']."'>".$rowCara['codigo']."-".$rowCara['descripcion']."</option>";
		}
	}
	echo $respuesta;
}
?>