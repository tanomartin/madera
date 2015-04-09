<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT a.nrosolicitud,a.fechasolicitud,a.codidelega,d.nombre as delegacion,a.cuil,a.nroafiliado,a.codiparentesco,a.apellidoynombre,a.statusverificacion
						FROM autorizaciones a, delegaciones d WHERE a.statusautorizacion = 0 and a.codidelega = d.codidelega ORDER BY nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);

print ("<div align=center>");
print ("<table width=850 border=1>");
print ("<tr>");
print ("<td width=850><div align=center><strong>Solicitudes</strong></div></td>");
print ("</tr>");
print ("</table>");
print ("</div>");

if ($totalLeeAutorizacion !=0) {
	print ("<div align=center>");
	print ("<table width=850 border=1 align=center>");

	print ("<tr>");
	print ("<td width=50><div align=center>Nro</div></td>");
	print ("<td width=70><div align=center>Fecha</div></td>");
	print ("<td width=160><div align=center>Delegacion</div></td>");
	print ("<td width=90><div align=center>C.U.I.L.</div></td>");
	print ("<td width=50><div align=center>Afiliado</div></td>");
	print ("<td width=65><div align=center>Tipo</div></td>");
	print ("<td width=180><div align=center>Apellido y Nombre</div></td>");
	print ("<td width=120><div align=center>Verificacion</div></td>");
	print ("<td width=65><div align=center>Accion</div></td>");
	print ("</tr>");

	while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) {

		$sqlLeeDeleg = "SELECT * FROM delegaciones where codidelega = $rowLeeAutorizacion[codidelega]";
		$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
		$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);

		print ("<tr>");
		print ("<td width=50><div align=center><font size=1 face=Verdana>".$rowLeeAutorizacion['nrosolicitud']."</font></div></td>");
		print ("<td width=70><div align=center><font size=1 face=Verdana>".invertirFecha($rowLeeAutorizacion['fechasolicitud'])."</font></div></td>");
		print ("<td width=160><div align=center><font size=1 face=Verdana>".$rowLeeAutorizacion['codidelega']." - ".$rowLeeAutorizacion['delegacion']."</font></div></td>");
		print ("<td width=90><div align=center><font size=1 face=Verdana>".$rowLeeAutorizacion['cuil']."</font></div></td>");

		if($rowLeeAutorizacion['nroafiliado']==0)
			print ("<td width=50><div align=center><font size=1 face=Verdana>-</font></div></td>");
		else
			print ("<td width=50><div align=center><font size=1 face=Verdana>".$rowLeeAutorizacion['nroafiliado']."</font></div></td>");

		if($rowLeeAutorizacion['codiparentesco']<0)
			print ("<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>");
		else {
			if($rowLeeAutorizacion['codiparentesco']==0)
				print ("<td width=65><div align=center><font size=1 face=Verdana>Titular</font></div></td>");
			else
				print ("<td width=65><div align=center><font size=1 face=Verdana>Familiar ".$rowLeeAutorizacion['codiparentesco']."</font></div></td>");			
		}

		print ("<td width=180><div align=center><font size=1 face=Verdana>".$rowLeeAutorizacion['apellidoynombre']."</font></div></td>");

		if($rowLeeAutorizacion['statusverificacion']==0) {
			print ("<td width=120><div align=center><font size=1 face=Verdana>No Verificada</font></div></td>");
			print ("<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>");
		}

		if($rowLeeAutorizacion['statusverificacion']==1) {
			print ("<td width=120><div align=center><font size=1 face=Verdana>Aprobada</font></div></td>");
			print ("<td width=65><div align=center><font size=1 face=Verdana><a href='atiendeAutorizacion.php?nroSolicitud=".$rowLeeAutorizacion['nrosolicitud']."'>".Atender."</a></font></div></td>");
		}

		if($rowLeeAutorizacion['statusverificacion']==2) {
			print ("<td width=120><div align=center><font size=1 face=Verdana>Rechazada</font></div></td>");
			print ("<td width=65><div align=center><font size=1 face=Verdana><a href='consultaVerificacion.php?nroSolicitud=".$rowLeeAutorizacion['nrosolicitud']."'>".Atender."</a></font></div></td>");
		}

		if($rowLeeAutorizacion['statusverificacion']==3) {
			print ("<td width=120><div align=center><font size=1 face=Verdana>No Reverificada</font></div></td>");
			print ("<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>");
		}

		print ("</tr>");
	}
	print ("</table>");
	print ("</div>");
}
else {
	print ("<div align=center>");
	print ("<table width=850 border=1 align=center>");
	print ("<tr>");
	print ("<td width=850><div align=center>No existen solicitudes que atender.</div></td>");
	print ("</tr>");
	print ("</table>");
	print ("</div>");
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: M&oacute;dulo Autorizaciones :.</title>
</head>
<body bgcolor="#CCCCCC">
<form id="listaSolicitudes" name="listaSolicitudes">
<div align="center">
  <table width="800" border="0">
    <tr>
      <td width="400">
        <div align="left">
          <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloAutorizaciones.php'" align="left"/>
        </div>
      <td width="400">
        <div align="right">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="right"/>
        </div>
    </tr>
  </table>
</div>
</form>
</body>
</html>