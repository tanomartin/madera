<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['origen']))
{
	if($_POST['origen']==1) {
		$respuesta='<option title ="Seleccione una Cuenta" value="0">Seleccione una Cuenta</option>';
	}
	if($_POST['origen']==2) {
		$respuesta='<option title ="Seleccione una Cuenta" value="0">Seleccione Cuenta de Remesa</option>';
	}
	if($_POST['origen']==3) {
		$respuesta='<option title ="Seleccione una Cuenta" value="0">Seleccione Cuenta de Remito</option>';
	}
	$sqlLeeCuentas="SELECT * FROM cuentasusimra";
	$resLeeCuentas=mysql_query($sqlLeeCuentas,$db);
	while($rowLeeCuentas=mysql_fetch_array($resLeeCuentas)) {
		$respuesta.="<option title ='$rowLeeCuentas[descripcioncuenta]' value='$rowLeeCuentas[codigocuenta]'>".$rowLeeCuentas['descripcioncuenta']."</option>";
	}
	echo $respuesta;
}
?>