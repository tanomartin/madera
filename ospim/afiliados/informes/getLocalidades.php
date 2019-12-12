<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
if(isset($_POST['valor'])) {
	if ($_POST['valor'] == -1) {
		$respuesta='<option value="0">Seleccione Localidad</option>';
	} else {
		$codprovin=$_POST['valor'];
		$sqlLocali="SELECT * FROM localidades where codprovin = $codprovin group by nomlocali order  by nomlocali";
		$resLocali=mysql_query($sqlLocali,$db);
		$canLocali = mysql_num_rows($resLocali);
		if ($canLocali == 0) {
			$respuesta='<option value="0">Seleccione Localidad</option>';
		} else {
			$respuesta='<option value="0">Seleccione Localidad</option>';
			while($rowLocali=mysql_fetch_assoc($resLocali)) {
				$respuesta.="<option value='".utf8_encode($rowLocali['nomlocali'])."'>".utf8_encode($rowLocali['nomlocali'])."</option>";
			}
		}
	}
	echo $respuesta;
}
?>