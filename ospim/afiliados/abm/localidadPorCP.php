<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['codigo']))
{
	$numpostal=$_POST['codigo'];
	$respuesta='<option title ="Seleccione un valor" value="">Seleccione un valor</option>';
	$sqlLocalidad="select codlocali, nomlocali from localidades where numpostal = $numpostal";
	$resLocalidad=mysql_query($sqlLocalidad,$db);
	while($rowLocalidad=mysql_fetch_array($resLocalidad)) {
		$respuesta.="<option title ='$rowLocalidad[nomlocali]' value='$rowLocalidad[codlocali]'>".$rowLocalidad['nomlocali']."</option>";
	}
	echo $respuesta;
}
?>