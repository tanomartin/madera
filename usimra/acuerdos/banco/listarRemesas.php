<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/usimra/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$numeroremesa=0;

$sqlLeeRemesa = "SELECT * FROM remesasusimra WHERE codigocuenta = $cuentaRemesa and fecharemesa = $fechaRemesa";
$resultLeeRemesa = mysql_query($sqlLeeRemesa,$db);
$totalLeeRemesa = mysql_num_rows($resultLeeRemesa);

$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center><strong>Remesas</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center>Remesas del ".$fechaCargada." para la cuenta ".$rowLeeCuenta['descripcioncuenta']."</div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeRemesa !=0) {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");

	print ("<tr>");
	print ("<td width=49><div align=center>Nro</div></td>");
	print ("<td width=115><div align=center>Importe Bruto</div></td>");
	print ("<td width=120><div align=center>Importe Comision</div></td>");
	print ("<td width=115><div align=center>Importe Faima</div></td>");
	print ("<td width=115><div align=center>Importe Neto</div></td>");
	print ("<td width=55><div align=center>Remitos</div></td>");
	print ("<td width=96><div align=center>Estado</div></td>");
	print ("<td width=86><div align=center>Sistema</div></td>");
	print ("<td width=46><div align=center>Accion</div></td>");
	print ("</tr>");

	while($rowLeeRemesa = mysql_fetch_array($resultLeeRemesa)) {
		print ("<tr>");
		print ("<td width=49><div align=center><font size=1 face=Verdana>".$rowLeeRemesa['nroremesa']."</font></div></td>");
		print ("<td width=115><div align=center><font size=1 face=Verdana>".$rowLeeRemesa['importebruto']."</font></div></td>");
		print ("<td width=120><div align=center><font size=1 face=Verdana>".$rowLeeRemesa['importecomision']."</font></div></td>");
		print ("<td width=115><div align=center><font size=1 face=Verdana>".$rowLeeRemesa['importefaima']."</font></div></td>");
		print ("<td width=115><div align=center><font size=1 face=Verdana>".$rowLeeRemesa['importeneto']."</font></div></td>");
		print ("<td width=55><div align=center><font size=1 face=Verdana><a href='listarRemitos.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemesa['nroremesa']."&sisRemesa=".$rowLeeRemesa['sistemaremesa']."'>".Listar."</a></font></div></td>");
		if($rowLeeRemesa['estadoconciliacion']==0)
			print ("<td width=96><div align=center><font size=1 face=Verdana>No Conciliado</font></div></td>");
		else
			print ("<td width=96><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");
		if($rowLeeRemesa['sistemaremesa']=="M")
			print ("<td width=86><div align=center><font size=1 face=Verdana>Manual</font></div></td>");
		else
			print ("<td width=86><div align=center><font size=1 face=Verdana>Electronico</font></div></td>");
		if($rowLeeRemesa['estadoconciliacion']==0) {
			if($rowLeeRemesa['sistemaremesa']=="M")
				print ("<td width=46><div align=center><font size=1 face=Verdana><a href='modificaRemesa.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemesa['nroremesa']."'>".Modificar."</a></font></div></td>");
			else
				print ("<td width=46><div align=center><font size=1 face=Verdana><a href='consultaRemesa.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemesa['nroremesa']."&sisRemesa=".$rowLeeRemesa['sistemaremesa']."'>".Ver."</a></font></div></td>");
		}
		else
			print ("<td width=46><div align=center><font size=1 face=Verdana><a href='consultaRemesa.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemesa['nroremesa']."&sisRemesa=".$rowLeeRemesa['sistemaremesa']."'>".Ver."</a></font></div></td>");
		print ("</tr>");
		$ultimaremesa=$rowLeeRemesa['nroremesa'];
	}
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center>No existen remesas para esa fecha.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
$numeroremesa=$ultimaremesa+1;
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: M�dulo Banco USIMRA :.</title>
</head>
<body bgcolor="#B2A274">
<form id="listaRemesas" name="listaRemesas" method="POST" action="nuevaRemesa.php?ctaRemesa=<?php echo $cuentaRemesa?>&fecRemesa=<?php echo $fechaCargada?>&ultRemesa=<?php echo $numeroremesa?>">
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="350">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'remesasBancarias.php'" align="left"/>
        </div>
      <td width="100">
        <div align="center">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/>
        </div>
      <td width="350">
        <div align="right">
          <input type="submit" name="nuevaremesa" value="Nueva Remesa" align="right"/>
        </div></td>
    </tr>
  </table>
</div>
</form>
</body>
</html>