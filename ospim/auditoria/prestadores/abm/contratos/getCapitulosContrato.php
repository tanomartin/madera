<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	$tipo = $_POST['valor'];
	$idcapitulos = $_POST['idcapitulos'];
	$listadoidCapitulos = unserialize(urldecode($idcapitulos));
	if (sizeof($listadoidCapitulos) > 0) {
		$whereIn = "(";
		foreach ($listadoidCapitulos as $capcontrato) {
			$whereIn .= $capcontrato.",";
		}
		$whereIn = substr($whereIn, 0, -1);
		$whereIn .= ")"; 
		
		$sqlCapitulo="SELECT * FROM capitulosdepracticas
						WHERE idtipopractica = '$tipo' and id in $whereIn
						ORDER BY codigo";
		$resCapitulo=mysql_query($sqlCapitulo,$db);
		$canCapitulo = mysql_num_rows($resCapitulo);
		if ($canCapitulo == 0) {
			$respuesta = 0;
		} else {
			$respuesta='<option value="0">Seleccione Capitulo</option>';
			while($rowCapitulo=mysql_fetch_assoc($resCapitulo)) {
				$value = $rowCapitulo['id']."-".$rowCapitulo['codigo'];
				$descri = substr($rowCapitulo['descripcion'],0,90);
				$respuesta.="<option value='$value'>".$rowCapitulo['codigo']."-".$descri."</option>";
			}
		}
		echo $respuesta;
	} else {
		echo 0;
	}
}
?>