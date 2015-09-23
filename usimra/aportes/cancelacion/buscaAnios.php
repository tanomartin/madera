<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
if(isset($_POST))
{
	$respuesta='<option title ="Seleccione un valor" value="">Seleccione un valor</option>';
	$sqlLeeAnios="SELECT anio FROM periodosusimra WHERE anio > 2009 GROUP BY anio ORDER BY anio DESC";
	$resLeeAnios=mysql_query($sqlLeeAnios,$db);
	while($rowLeeAnios=mysql_fetch_array($resLeeAnios)) {
		$respuesta.="<option title ='$rowLeeAnios[anio]' value='$rowLeeAnios[anio]'>".$rowLeeAnios['anio']."</option>";
	}
	echo $respuesta;
}
?>