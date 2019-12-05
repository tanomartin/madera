<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$capitulo=$_POST['valor'];
	$idsubcapitulos = $_POST['subcapitulos'];
	$listadoidSubCapitulos = unserialize(urldecode($idsubcapitulos));
	if (sizeof($listadoidSubCapitulos) > 0) {
		$whereIn = "(";
		foreach ($listadoidSubCapitulos as $subcapcontrato) {
			$whereIn .= $subcapcontrato.",";
		}
		$whereIn = substr($whereIn, 0, -1);
		$whereIn .= ")";
		
		$sqlSubCapitulo="SELECT * FROM subcapitulosdepracticas 
							WHERE idcapitulo = $capitulo and id in $whereIn
							ORDER BY codigo";
		$resSubCapitulo=mysql_query($sqlSubCapitulo,$db);
		$canSubCapitulo = mysql_num_rows($resSubCapitulo);
		if ($canSubCapitulo == 0) {
			$respuesta = 0;
		} else {
			$respuesta='<option value="0">Seleccione SubCapitulo</option>';
			$resSubCapitulo=mysql_query($sqlSubCapitulo,$db);
			while($rowSubCapitulo=mysql_fetch_assoc($resSubCapitulo)) {
				$value = $rowSubCapitulo['id']."-".$rowSubCapitulo['codigo'];
				$descri = substr($rowSubCapitulo['descripcion'],0,90);
				$respuesta.="<option value='$value'>".$rowSubCapitulo['codigo']."-".$descri."</option>";
			}
		}
		echo $respuesta;
	} else {
		echo 0;
	}
}
?>