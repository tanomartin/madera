<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$numpostal=$_POST['codigo'];
$respuesta='<option title ="Seleccione un valor" value="0">Seleccione un barrio</option>';
$sqlBarrios="SELECT * FROM barrios WHERE id != 0";
$resBarrios=mysql_query($sqlBarrios,$db);
while($rowBarrios=mysql_fetch_array($resBarrios)) {
	$respuesta.="<option title ='$rowBarrios[descripcion]' value='$rowBarrios[id]'>".utf8_encode($rowBarrios[descripcion])."</option>";
}
echo $respuesta;
?>
