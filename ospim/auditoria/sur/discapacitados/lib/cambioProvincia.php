<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['locali']))
{
	$codlocali=$_POST['locali'];

	$sqlLocalidad="SELECT codlocali, codprovin FROM localidades WHERE codlocali = '$codlocali'";
	$resLocalidad=mysql_query($sqlLocalidad,$db);
	$rowLocalidad=mysql_fetch_array($resLocalidad);
	
	$codProvincia= $rowLocalidad['codprovin'];
	$sqlProvincia="SELECT indpostal, descrip FROM provincia WHERE codprovin = '$codProvincia'";
	$resProvincia=mysql_query($sqlProvincia,$db);
	$rowProvincia=mysql_fetch_array($resProvincia);
	$respuesta = array("indpostal" => $rowProvincia['indpostal'], "descrip" => $rowProvincia['descrip'], "codprovin" => $rowLocalidad['codprovin']);
	echo json_encode($respuesta);
}
?>