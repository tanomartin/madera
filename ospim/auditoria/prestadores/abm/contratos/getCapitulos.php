<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$tipo=$_POST['valor'];
	$sqlCapitulo="SELECT * FROM capitulosdepracticas WHERE idtipopractica = '$tipo'";
	$resCapitulo=mysql_query($sqlCapitulo,$db);
	$canCapitulo = mysql_num_rows($resCapitulo);
	if ($canCapitulo == 0) {
		$respuesta = 0;
	} else {
		$respuesta='<option value="0">Seleccione Capitulo</option>';
		while($rowCapitulo=mysql_fetch_assoc($resCapitulo)) {
			$value = $rowCapitulo['id']."-".$rowCapitulo['codigo'];
			$respuesta.="<option value='$value'>".$rowCapitulo['codigo']."-".$rowCapitulo['descripcion']."</option>";
		}
	}
	echo $respuesta;
}
?>