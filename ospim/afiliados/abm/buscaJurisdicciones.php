<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
if(isset($_POST['cuit']))
{
	$cuit=$_POST['cuit'];
	$respuesta='<option title ="Seleccione un valor" value="">Seleccione un valor</option>';
	$sqlLeeJurisdi="SELECT cuit, codidelega FROM jurisdiccion WHERE cuit = '$cuit'";
	$resLeeJurisdi=mysql_query($sqlLeeJurisdi,$db);
	while($rowLeeJurisdi=mysql_fetch_array($resLeeJurisdi)) {
		$coddelega = $rowLeeJurisdi['codidelega'];
		$sqlLeeDelega = "SELECT codidelega, nombre FROM delegaciones WHERE codidelega = '$coddelega'";
		$resLeeDelega = mysql_query($sqlLeeDelega,$db);
		$rowLeeDelega = mysql_fetch_array($resLeeDelega);
		$respuesta.="<option title ='$rowLeeDelega[nombre]' value='$rowLeeJurisdi[codidelega]'>".$rowLeeDelega['nombre']."</option>";
	}
	echo $respuesta;
}
?>