<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['cuit']))
{
	$cuit=$_POST['cuit'];
	$sqlLeeEmpresa="SELECT cuit, nombre FROM empresas WHERE cuit = '$cuit'";
	$resLeeEmpresa=mysql_query($sqlLeeEmpresa,$db);
	$rowLeeEmpresa=mysql_fetch_array($resLeeEmpresa);
	$respuesta=$rowLeeEmpresa['nombre'];

	echo $respuesta;
}
?>