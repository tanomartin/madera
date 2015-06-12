<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaResumen=$_GET['ctaResumen'];
$fechaCargada=$_GET['fecEmision'];
$fechaEmision=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$numeroorden=0;

$sqlLeeResumen = "SELECT * FROM resumenusimra WHERE codigocuenta = $cuentaResumen and fechaemision = $fechaEmision";
$resultLeeResumen = mysql_query($sqlLeeResumen,$db);
$totalLeeResumen = mysql_num_rows($resultLeeResumen);

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaResumen";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: Módulo Banco USIMRA :.</title>
</head>
<body bgcolor="#B2A274">
<div align="center"><p><input type="reset" name="volver" value="Volver" onClick="location.href = 'resumenBancario.php'" align="left"/></p></div>
<p>
<?php
print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center><strong>Imputaciones</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center>Resumen del ".$fechaCargada." para la cuenta ".$rowLeeCuenta['descripcioncuenta']."</div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeResumen !=0) {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");

	print ("<tr>");
	print ("<td width=49><div align=center>Orden</div></td>");
	print ("<td width=74><div align=center>Fecha</div></td>");
	print ("<td width=81><div align=center>Importe</div></td>");
	print ("<td width=67><div align=center>Tipo</div></td>");
	print ("<td width=96><div align=center>Estado</div></td>");
	print ("<td width=301><div align=center>Comprobante</div></td>");
	print ("<td width=86><div align=center>Sistema</div></td>");
	print ("<td width=46><div align=center>Accion</div></td>");
	print ("</tr>");

	while($rowLeeResumen = mysql_fetch_array($resultLeeResumen)) {
		print ("<tr>");
		print ("<td width=49><div align=center><font size=1 face=Verdana>".$rowLeeResumen['nroordenimputacion']."</font></div></td>");
		print ("<td width=74><div align=center><font size=1 face=Verdana>".invertirFecha($rowLeeResumen['fechaimputacion'])."</font></div></td>");
		print ("<td width=81><div align=center><font size=1 face=Verdana>".$rowLeeResumen['importeimputado']."</font></div></td>");
		if($rowLeeResumen['tipoimputacion']=="C")
			print ("<td width=67><div align=center><font size=1 face=Verdana>Credito</font></div></td>");
		else
			print ("<td width=67><div align=center><font size=1 face=Verdana>Debito</font></div></td>");
		if($rowLeeResumen['estadoconciliacion']==0) {
			print ("<td width=96><div align=center><font size=1 face=Verdana>No Conciliado</font></div></td>");
			print ("<td width=301><div align=center><font size=1 face=Verdana>------------------------------------</font></div></td>");
			print ("<td width=86><div align=center><font size=1 face=Verdana>-----------</font></div></td>");
			print ("<td width=46><div align=center><font size=1 face=Verdana><a href='modificaImputacion.php?ctaResumen=".$cuentaResumen."&fecEmision=".$fechaCargada."&ultOrden=".$rowLeeResumen['nroordenimputacion']."'>".Modificar."</a></font></div></td>");
			print ("</tr>");
		}
		else {
			print ("<td width=96><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");

			$nroorden = $rowLeeResumen['nroordenimputacion'];
			$registroscomprobante = 0;
			$sqlLeeComprobante = "SELECT * FROM origencomprobanteusimra WHERE codigocuenta = $cuentaResumen and fechaemision = $fechaEmision and nroordenimputacion = $nroorden";
			$resultLeeComprobante = mysql_query($sqlLeeComprobante,$db);
			while($rowLeeComprobante = mysql_fetch_array($resultLeeComprobante)) {
				$registroscomprobante = $registroscomprobante+1;
				if($registroscomprobante==1)
				{
					print ("<td width=301><div align=center><font size=1 face=Verdana>".$rowLeeComprobante['comprobante']." Nro. ".$rowLeeComprobante['nrocomprobante']." del ".invertirFecha($rowLeeComprobante['fechacomprobante'])."</font></div></td>");

					if($rowLeeComprobante['sistemacomprobante']=="M")
						print ("<td width=86><div align=center><font size=1 face=Verdana>Manual</font></div></td>");
					else
						print ("<td width=86><div align=center><font size=1 face=Verdana>Electronico</font></div></td>");
					print ("<td width=46><div align=center><font size=1 face=Verdana>---------</font></div></td>");
					print ("</tr>");
				}
				else
				{
					print ("<td width=49><div align=center><font size=1 face=Verdana>-</font></div></td>");
					print ("<td width=74><div align=center><font size=1 face=Verdana>-</font></div></td>");
					print ("<td width=81><div align=center><font size=1 face=Verdana>-</font></div></td>");
					print ("<td width=67><div align=center><font size=1 face=Verdana>-</font></div></td>");
					print ("<td width=96><div align=center><font size=1 face=Verdana>-</font></div></td>");
					print ("<td width=301><div align=center><font size=1 face=Verdana>".$rowLeeComprobante['comprobante']." Nro. ".$rowLeeComprobante['nrocomprobante']." del ".invertirFecha($rowLeeComprobante['fechacomprobante'])."</font></div></td>");

					if($rowLeeComprobante['sistemacomprobante']=="M")
						print ("<td width=86><div align=center><font size=1 face=Verdana>Manual</font></div></td>");
					else
						print ("<td width=86><div align=center><font size=1 face=Verdana>Electronico</font></div></td>");
					print ("<td width=46><div align=center><font size=1 face=Verdana>---------</font></div></td>");
					print ("</tr>");
				}
			}
		}
		$ultimoorden=$rowLeeResumen['nroordenimputacion'];
	}
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center>No existen imputaciones para el resumen.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
$numeroorden=$ultimoorden+1;
?>
</p>
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="400">
          <div align="left"><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/></div></td>
      <td width="400">
        <div align="right"><input type="button" name="nuevaimputacion" value="Nueva Imputacion" align="right" onClick="location.href='nuevaImputacion.php?ctaResumen=<?php echo $cuentaResumen?>&fecEmision=<?php echo $fechaCargada?>&ultOrden=<?php echo $numeroorden?>'"/>
</div></td>
    </tr>
  </table>
</div>
</body>
</html>