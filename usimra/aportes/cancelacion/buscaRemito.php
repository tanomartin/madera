<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['fecharemesa']) && isset($_POST['cuentaremesa']) && isset($_POST['nroremesa'])) {
	$cuentaRemesa=$_POST['cuentaremesa'];
	$fechaInvertida=fechaParaGuardar($_POST['fecharemesa']);
	$nroremesa=$_POST['nroremesa'];
	$respuesta="<option title='Seleccione un valor' value='0'>Seleccione un valor</option>";
	$sqlRemito="SELECT nroremito FROM remitosremesasusimra WHERE codigocuenta = $cuentaRemesa AND sistemaremesa = 'M' AND fecharemesa = '$fechaInvertida' AND nroremesa = $nroremesa";
	$resRemito=mysql_query($sqlRemito,$db);
	while($rowRemito=mysql_fetch_array($resRemito)) {
		$respuesta.="<option title ='$rowRemito[nroremito]' value='$rowRemito[nroremito]'>".$rowRemito['nroremito']."</option>";
	}
	echo $respuesta;
}
?>