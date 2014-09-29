<?php 
$origen = $_GET['origen'];
if ($origen == "ospim") {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
	$bgcolor="#CCCCCC";
} else {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");
	$bgcolor="#B2A274";
}
if(isset($_POST['locali'])){
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