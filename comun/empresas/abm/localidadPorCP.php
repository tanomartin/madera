<?php 
$origen = $_GET['origen'];
if ($origen == "ospim") {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
	$bgcolor="#CCCCCC";
} else {
	include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php");
	$bgcolor="#B2A274";
}
if(isset($_POST['codigo'])) {
	$numpostal=$_POST['codigo'];
	$respuesta='<option title ="Seleccione un valor" value="0">Seleccione un valor</option>';
	$sqlLocalidad="SELECT codlocali, nomlocali FROM localidades WHERE numpostal = '$numpostal'";
	$resLocalidad=mysql_query($sqlLocalidad,$db);
	while($rowLocalidad=mysql_fetch_array($resLocalidad)) {
		$respuesta.="<option title ='$rowLocalidad[nomlocali]' value='$rowLocalidad[codlocali]'>".$rowLocalidad['nomlocali']."</option>";
	}
	echo $respuesta;
}
?>