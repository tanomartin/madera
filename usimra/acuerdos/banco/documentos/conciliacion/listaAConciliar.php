<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$sqlLeeResumen = "SELECT fechaemision, count(*) as registros FROM resumenusimra where estadoconciliacion = 0 group by fechaemision";
$resultLeeResumen = mysql_query($sqlLeeResumen,$db);
$totalLeeResumen = mysql_num_rows($resultLeeResumen);
//echo $totalLeeResumen;
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: Módulo Banco USIMRA :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function MsgWait(formulario) {
	$.blockUI({ message: "<h1>Conciliando Imputaciones. Aguarde por favor...</h1>" });
	return true;
}
</script>
</head>
<body bgcolor="#B2A274">
<div align="center"><input type="reset" name="volver" value="Volver" onClick="location.href = '../documentosBancarios.php'" align="center"/> </div>

<p align="center">
  <?php
print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center><strong>Items a Conciliar</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeResumen !=0) {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");

	print ("<tr>");
	print ("<td width=250><div align=center>Fecha Emision Resumen</div></td>");
	print ("<td width=300><div align=center>Imputaciones a Conciliar</div></td>");
	print ("<td width=250><div align=center>Accion</div></td>");
	print ("</tr>");

	while($rowLeeResumen = mysql_fetch_array($resultLeeResumen))
	{
		print ("<tr>");
		print ("<td width=250><div align=center><font size=1 face=Verdana>".invertirFecha($rowLeeResumen['fechaemision'])."</font></div></td>");
		print ("<td width=300><div align=center><font size=1 face=Verdana>".$rowLeeResumen['registros']."</font></div></td>");
		print ("<td width=250><div align=center><font size=1 face=Verdana><a onclick='return MsgWait(this)' href='conciliaNuevos.php?fecEmision=".$rowLeeResumen['fechaemision']."'>".Conciliar."</a></font></div></td>");
		print ("</tr>");
	}
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center>No existen Items a conciliar.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}

?>
</p>
<p>
  
  </div>
</p>
</body>
</html>