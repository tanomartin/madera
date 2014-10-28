<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['delegacion']))
{
	$codidelega = $_POST['delegacion'];
	$sqlDelegacion="SELECT nombre FROM delegaciones WHERE codidelega = '$codidelega'";
	$resDelegacion=mysql_query($sqlDelegacion,$db);
	$rowDelegacion=mysql_fetch_array($resDelegacion);
	$respuesta = $rowDelegacion['nombre'];
	echo json_encode($respuesta);
}
?>