<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuentaRemesa=$_GET['ctaRemesa'];
$fechaCargada=$_GET['fecRemesa'];
$fechaRemesa=substr($fechaCargada, 6, 4).substr($fechaCargada, 3, 2).substr($fechaCargada, 0, 2);
$numeroRemesa=$_GET['ultRemesa'];
$sistemaRemesa=$_GET['sisRemesa'];
$numeroRemito=0;

$sqlLeeRemito = "SELECT * FROM remitosremesasusimra WHERE codigocuenta = $cuentaRemesa and sistemaremesa = '$sistemaRemesa' and fecharemesa = $fechaRemesa and nroremesa = $numeroRemesa";
$resultLeeRemito = mysql_query($sqlLeeRemito,$db);
$totalLeeRemito = mysql_num_rows($resultLeeRemito);
$sqlLeeCuenta="SELECT * FROM cuentasusimra where codigocuenta = $cuentaRemesa";
$resultLeeCuenta=mysql_query($sqlLeeCuenta,$db);
$rowLeeCuenta=mysql_fetch_array($resultLeeCuenta);

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center><strong>Remitos</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

print ("<div align=center>");
print ("<table width=800 border=1>");
print ("<tr>");
print ("<td width=800><div align=center>Remesa Nro. ".$numeroRemesa." del ".$fechaCargada." para la cuenta ".$rowLeeCuenta['descripcioncuenta']."</div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeRemito !=0) {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");

	print ("<tr>");
	print ("<td width=49><div align=center>Nro</div></td>");
	print ("<td width=80><div align=center>Fecha</div></td>");
	print ("<td width=115><div align=center>Importe Bruto</div></td>");
	print ("<td width=120><div align=center>Importe Comision</div></td>");
	print ("<td width=115><div align=center>Importe Neto</div></td>");
	print ("<td width=95><div align=center>Boletas</div></td>");
	print ("<td width=96><div align=center>Estado</div></td>");
	print ("<td width=86><div align=center>Sistema</div></td>");
	print ("<td width=46><div align=center>Accion</div></td>");
	print ("</tr>");

	while($rowLeeRemito = mysql_fetch_array($resultLeeRemito)) {
		print ("<tr>");
		print ("<td width=49><div align=center><font size=1 face=Verdana>".$rowLeeRemito['nroremito']."</font></div></td>");
		print ("<td width=80><div align=center><font size=1 face=Verdana>".invertirFecha($rowLeeRemito['fecharemito'])."</font></div></td>");
		print ("<td width=115><div align=center><font size=1 face=Verdana>".$rowLeeRemito['importebruto']."</font></div></td>");
		print ("<td width=120><div align=center><font size=1 face=Verdana>".$rowLeeRemito['importecomision']."</font></div></td>");
		print ("<td width=115><div align=center><font size=1 face=Verdana>".$rowLeeRemito['importeneto']."</font></div></td>");
		print ("<td width=95><div align=center><font size=1 face=Verdana>".$rowLeeRemito['boletasremito']."</font></div></td>");
		if($rowLeeRemito['estadoconciliacion']==0)
			print ("<td width=96><div align=center><font size=1 face=Verdana>No Conciliado</font></div></td>");
		else
			print ("<td width=96><div align=center><font size=1 face=Verdana>Conciliado</font></div></td>");
		if($rowLeeRemito['sistemaremesa']=="M")
			print ("<td width=86><div align=center><font size=1 face=Verdana>Manual</font></div></td>");
		else
			print ("<td width=86><div align=center><font size=1 face=Verdana>Electronico</font></div></td>");
		if($rowLeeRemito['estadoconciliacion']==0) {
			if($rowLeeRemito['sistemaremesa']=="M")
				print ("<td width=46><div align=center><font size=1 face=Verdana><a href='modificaRemito.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemito['nroremesa']."&ultRemito=".$rowLeeRemito['nroremito']."'>".Modificar."</a></font></div></td>");
			else
				print ("<td width=46><div align=center><font size=1 face=Verdana><a href='consultaRemito.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemito['nroremesa']."&sisRemesa=".$rowLeeRemito['sistemaremesa']."&ultRemito=".$rowLeeRemito['nroremito']."'>".Ver."</a></font></div></td>");
		}
		else
			print ("<td width=46><div align=center><font size=1 face=Verdana><a href='consultaRemito.php?ctaRemesa=".$cuentaRemesa."&fecRemesa=".$fechaCargada."&ultRemesa=".$rowLeeRemito['nroremesa']."&sisRemesa=".$rowLeeRemito['sistemaremesa']."&ultRemito=".$rowLeeRemito['nroremito']."'>".Ver."</a></font></div></td>");
		print ("</tr>");
		$ultimoremito=$rowLeeRemito['nroremito'];
	}
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=800 border=1 align=center>");
	print ("<tr>");
	print ("<td width=800><div align=center>No existen remitos para esa remesa.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
$numeroRemito=$ultimoremito+1;
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
<form id="listaRemitos" name="listaRemitos" method="POST" action="nuevoRemito.php?ctaRemesa=<?php echo $cuentaRemesa?>&fecRemesa=<?php echo $fechaCargada?>&ultRemesa=<?php echo $numeroRemesa?>&ultRemito=<?php echo $numeroRemito?>">
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="350">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'listarRemesas.php?ctaRemesa=<?php echo $cuentaRemesa?>&fecRemesa=<?php echo $fechaCargada?>'" align="left"/>
        </div>
      <td width="100">
        <div align="center">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/>
        </div>
      <td width="350">
        <div align="right">
		  <?php if($sistemaRemesa=='M') {?>
	          <input type="submit" name="nuevoremito" value="Nuevo Remito" align="right"/>
    	  <?php }
    	  		else {?>
	          <input type="submit" name="nuevoremito" value="Nuevo Remito" align="right"  disabled="disabled" sub/>
    	  <?php }?>
	    </div></td>
    </tr>
  </table>
</div>
</form>
</body>
</html>