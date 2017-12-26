<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_GET['tipoEscuela']))
{
	$respuesta='<option title ="Seleccione CUE" value="">Seleccione CUE</option>';
	$sqlLeeEscuelas="SELECT id, nombre, cue FROM escuelas ORDER BY cue ASC";
	$resLeeEscuelas=mysql_query($sqlLeeEscuelas,$db);
	while($rowLeeEscuelas=mysql_fetch_array($resLeeEscuelas)) {
		$respuesta.="<option title ='$rowLeeEscuelas[cue]' value='$rowLeeEscuelas[id]'>".$rowLeeEscuelas['cue']." - ".$rowLeeEscuelas['nombre']."</option>";
	}
	echo $respuesta;
}
?>