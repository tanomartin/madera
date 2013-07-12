<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemito=$_GET['ctaRemito'];
$fechaCargada=$_GET['fecRemito'];
$fechaRemito=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$numeroremito=0;

$sqlLeeRemitos = "SELECT * FROM  remitossueltosusimra WHERE codigocuenta = $cuentaRemito and fecharemito = $fechaRemito";
$resultLeeRemitos = mysql_query($sqlLeeRemitos,$db);
$totalLeeRemitos = mysql_num_rows($resultLeeRemitos);

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemito";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center><strong>Remitos Sueltos</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center>Remitos del ".$fechaCargada." para la cuenta ".$rowLeeCuenta['descripcioncuenta']."</div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeRemitos !=0) {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");

	print ("<tr>");
	print ("<td width=60><div align=center>Nro</div></td>");
	print ("<td width=125><div align=center>Importe Bruto</div></td>");
	print ("<td width=130><div align=center>Importe Comision</div></td>");
	print ("<td width=125><div align=center>Importe Faima</div></td>");
	print ("<td width=125><div align=center>Importe Neto</div></td>");
	print ("<td width=65><div align=center>Boletas</div></td>");
	print ("<td width=107><div align=center>Estado</div></td>");
	print ("<td width=60><div align=center>Accion</div></td>");
	print ("</tr>");

	$totalbruto=0.00;
	$totalcomis=0.00;
	$totalfaima=0.00;
	$totalnetos=0.00;
	while($rowLeeRemitos = mysql_fetch_array($resultLeeRemitos)) {
		$totalbruto=$totalbruto+$rowLeeRemitos['importebruto'];
		$totalcomis=$totalcomis+$rowLeeRemitos['importecomision'];
		$totalfaima=$totalfaima+$rowLeeRemitos['importefaima'];
		$totalnetos=$totalnetos+$rowLeeRemitos['importeneto'];
		print ("<tr>");
		print ("<td width=60><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['nroremito']."</font></div></td>");
		print ("<td width=125><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['importebruto']."</font></div></td>");
		print ("<td width=130><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['importecomision']."</font></div></td>");
		print ("<td width=125><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['importefaima']."</font></div></td>");
		print ("<td width=125><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['importeneto']."</font></div></td>");
		print ("<td width=65><div align=center><font size=1 face=Verdana>".$rowLeeRemitos['boletasremito']."</font></div></td>");
		if($rowLeeRemitos['estadoconciliacion']==0)
		{
			print ("<td width=107><div align=center><font size=1 face=Verdana>No Conciliado</font></div></td>");
			print ("<td width=60><div align=center><font size=1 face=Verdana><a href='modificaRemitoSuelto.php?ctaRemito=".$cuentaRemito."&fecRemito=".$fechaCargada."&ultRemito=".$rowLeeRemitos['nroremito']."'>".Modificar."</a></font></div></td>");
		}
		else
		{
			print ("<td width=107><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");
			print ("<td width=60><div align=center><font size=1 face=Verdana><a href='consultaRemitoSuelto.php?ctaRemito=".$cuentaRemito."&fecRemito=".$fechaCargada."&ultRemito=".$rowLeeRemitos['nroremito']."'>".Ver."</a></font></div></td>");
		}
		print ("</tr>");
		$ultimoremito=$rowLeeRemitos['nroremito'];
	}
	print ("</table>");
	print ("</div>");
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center><font size=1 face=Verdana>TOTALES</font></div></td>");
	print ("</tr>");
	print ("<tr>");
	print ("<td width=800><div align=left><font size=1 face=Verdana>BRUTO ----- ".number_format($totalbruto, 2, '.', '')."</font></div></td>");
	print ("</tr>");
	print ("<tr>");
	print ("<td width=800><div align=left><font size=1 face=Verdana>COMISION - ".number_format($totalcomis, 2, '.', '')."</font></div></td>");
	print ("</tr>");
	print ("<tr>");
	print ("<td width=800><div align=left><font size=1 face=Verdana>FAIMA ------ ".number_format($totalfaima, 2, '.', '')."</font></div></td>");
	print ("</tr>");
	print ("<tr>");
	print ("<td width=800><div align=left><font size=1 face=Verdana>NETO ------- ".number_format($totalnetos, 2, '.', '')."</font></div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center>No existen remitos sueltos para esa fecha.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
$numeroremito=$ultimoremito+1;
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
</head>
<body bgcolor="#B2A274">
<form id="listaRemitos" name="listaRemitos" method="POST" action="nuevoRemitoSuelto.php?ctaRemito=<?php echo $cuentaRemito?>&fecRemito=<?php echo $fechaCargada?>&ultRemito=<?php echo $numeroremito?>">
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="350">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'remitosSueltosBancarios.php'" align="left"/>
        </div>
      <td width="100">
        <div align="center">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/>
        </div>
      <td width="350">
        <div align="right">
          <input type="submit" name="nuevoremito" value="Nuevo Remito Suelto" align="right"/>
        </div></td>
    </tr>
  </table>
</div>
</form>
</body>
</html>