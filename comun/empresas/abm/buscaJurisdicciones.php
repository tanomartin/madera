<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['codigo'])) {
	$codigo=$_POST['codigo'];
	$respuesta='<option title ="Seleccione un valor" value="0">Seleccione un valor</option>';
	$sqlDelega="select nombre, codidelega from delegaciones where codprovin = $codigo";
	$resDelega=mysql_query($sqlDelega,$db);
	while($rowDelega=mysql_fetch_array($resDelega)) {
		$respuesta.="<option title ='$rowDelega[nombre]' value='$rowDelega[codidelega]'>".$rowDelega['nombre']."</option>";
	}
	if ($codigo == 2) {
		$respuesta.="<option title ='CAPITAL FEDERAL' value='1002'>CAPITAL FEDERAL</option>";
	}
	echo $respuesta;
}
?>