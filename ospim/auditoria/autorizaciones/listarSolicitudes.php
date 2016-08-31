<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT a.nrosolicitud,a.fechasolicitud,a.codidelega,d.nombre as delegacion,a.cuil,a.nroafiliado,a.codiparentesco,a.apellidoynombre,a.statusverificacion
						FROM autorizaciones a, delegaciones d WHERE a.statusautorizacion = 0 and a.codidelega = d.codidelega ORDER BY nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);?>

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
	<div align=center>
	<table width=850 border=1>
		<tr>
			<td width=850><div align=center><strong>Solicitudes</strong></div></td>
		</tr>
	</table>
<?php if ($totalLeeAutorizacion !=0) { ?>
		<table width=850 border=1 align=center>
			<tr>
				<td width=50><div align=center>Nro</div></td>
				<td width=70><div align=center>Fecha</div></td>
				<td width=160><div align=center>Delegacion</div></td>
				<td width=90><div align=center>C.U.I.L.</div></td>
				<td width=50><div align=center>Afiliado</div></td>
				<td width=65><div align=center>Tipo</div></td>
				<td width=180><div align=center>Apellido y Nombre</div></td>
				<td width=120><div align=center>Verificacion</div></td>
				<td width=65><div align=center>Accion</div></td>
			</tr>
<?php	while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) {  ?>
			<tr>
				<td width=50><div align=center><font size=1 face=Verdana><?php echo $rowLeeAutorizacion['nrosolicitud'] ?></font></div></td>
				<td width=70><div align=center><font size=1 face=Verdana><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']) ?></font></div></td>
				<td width=160><div align=center><font size=1 face=Verdana><?php echo $rowLeeAutorizacion['codidelega']." - ".$rowLeeAutorizacion['delegacion'] ?></font></div></td>
				<td width=90><div align=center><font size=1 face=Verdana><?php echo $rowLeeAutorizacion['cuil'] ?></font></div></td>
<?php		if($rowLeeAutorizacion['nroafiliado']==0) { ?>
				<td width=50><div align=center><font size=1 face=Verdana>-</font></div></td>
<?php		} else { ?>
				<td width=50><div align=center><font size=1 face=Verdana><?php echo  $rowLeeAutorizacion['nroafiliado'] ?></font></div></td>
<?php		}
			if ($rowLeeAutorizacion['codiparentesco']<0) { ?>
				<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>
<?php		} else { 
				if($rowLeeAutorizacion['codiparentesco']==0) { ?>
					<td width=65><div align=center><font size=1 face=Verdana>Titular</font></div></td>
	<?php		} else { ?>
					<td width=65><div align=center><font size=1 face=Verdana>Familiar <?php echo $rowLeeAutorizacion['codiparentesco'] ?></font></div></td>			
	<?php		} 
			} ?>
			<td width=180><div align=center><font size=1 face=Verdana><?php echo $rowLeeAutorizacion['apellidoynombre'] ?></font></div></td>
	<?php	if($rowLeeAutorizacion['statusverificacion']==0) { ?>
				<td width=120><div align=center><font size=1 face=Verdana>No Verificada</font></div></td>
				<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==1) { ?>
				<td width=120><div align=center><font size=1 face=Verdana>Aprobada</font></div></td>
				<td width=65><div align=center><font size=1 face=Verdana><a href='atiendeAutorizacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud']?>'>Atender</a></font></div></td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==2) { ?>
				<td width=120><div align=center><font size=1 face=Verdana>Rechazada</font></div></td>
				<td width=65><div align=center><font size=1 face=Verdana><a href='consultaVerificacion.php?nroSolicitud=<?php echo $rowLeeAutorizacion['nrosolicitud']?>'>Atender</a></font></div></td>
	<?php	} 
			if($rowLeeAutorizacion['statusverificacion']==3) { ?>
				<td width=120><div align=center><font size=1 face=Verdana>No Reverificada</font></div></td>
				<td width=65><div align=center><font size=1 face=Verdana>-</font></div></td>
	<?php	}   ?>
			</tr>
<?php	}	?>
		</table>
<?php } else { ?>
	<table width=850 border=1 align=center>
		<tr>
			<td width=850><div align=center>No existen solicitudes que atender.</div></td>
		</tr>
	</table>
<?php } ?>
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

</body>
</html>