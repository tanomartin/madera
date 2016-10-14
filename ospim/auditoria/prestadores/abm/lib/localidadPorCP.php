<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['codigo'])) {
	$numpostal=$_POST['codigo'];
	$respuesta='<option title ="Seleccione un valor" value="">Seleccione una localidad</option>';
	$sqlLocalidad="SELECT codlocali, nomlocali FROM localidades WHERE numpostal = '$numpostal'";
	$resLocalidad=mysql_query($sqlLocalidad,$db);
	while($rowLocalidad=mysql_fetch_array($resLocalidad)) {
		$respuesta.="<option title ='$rowLocalidad[nomlocali]' value='$rowLocalidad[codlocali]'>".utf8_encode($rowLocalidad['nomlocali'])."</option>";
	}
	echo $respuesta;
}
?>