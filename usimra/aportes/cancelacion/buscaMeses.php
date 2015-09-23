<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST) && !empty($_POST) && isset($_POST['anio']))
{
	$anio=$_POST['anio'];
	$respuesta='<option title ="Seleccione un valor" value="">Seleccione un valor</option>';
	$sqlLeeMeses="SELECT mes, descripcion FROM periodosusimra WHERE anio = '$anio'";
	$resLeeMeses=mysql_query($sqlLeeMeses,$db);
	while($rowLeeMeses=mysql_fetch_array($resLeeMeses)) {
		$respuesta.="<option title ='$rowLeeMeses[descripcion]' value='$rowLeeMeses[mes]'>".$rowLeeMeses['descripcion']."</option>";
	}
	echo $respuesta;
}
?>