<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 

$sqlLeeAutorizacion = "SELECT * FROM autorizaciones WHERE statusverificacion != 0 AND statusautorizacion != 0 ORDER BY fechasolicitud DESC, nrosolicitud DESC";
$resultLeeAutorizacion = mysql_query($sqlLeeAutorizacion,$db);
$totalLeeAutorizacion = mysql_num_rows($resultLeeAutorizacion);
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
<form id="historialSolicitudes" name="historialSolicitudes">
<div align="center">
<h1>Historial de Autorizaciones</h1>
</div>
<div align="center">
<table id="historial">
	<thead>
		<tr>
			<th>Nro</th>
			<th>Fecha</th>
			<th>Delegaci&oacute;n</th>
			<th>C.U.I.L.</th>
			<th>Afiliado</th>
			<th>Tipo</th>
			<th>Apellido y Nombre</th>
			<th>Verificaci&oacute;n</th>
			<th>Autorizaci&oacute;n</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>

<?php
		while($rowLeeAutorizacion = mysql_fetch_array($resultLeeAutorizacion)) {
?>
		<tr>
<?php
			$sqlLeeDeleg = "SELECT * FROM delegaciones where codidelega = $rowLeeAutorizacion[codidelega]";
			$resultLeeDeleg = mysql_query($sqlLeeDeleg,$db); 
			$rowLeeDeleg = mysql_fetch_array($resultLeeDeleg);		
?>
			<td><?php echo $rowLeeAutorizacion['nrosolicitud'];?></td>
			<td><?php echo invertirFecha($rowLeeAutorizacion['fechasolicitud']);?></td>
			<td><?php echo $rowLeeAutorizacion['codidelega']." - ".$rowLeeDeleg['nombre'];?></td>
			<td><?php echo $rowLeeAutorizacion['cuil'];?></td>
			<td><?php if($rowLeeAutorizacion['nroafiliado']==0) echo "-"; else echo $rowLeeAutorizacion['nroafiliado'];?></td>
			<td><?php if($rowLeeAutorizacion['codiparentesco']==0) echo "-"; else { if($rowLeeAutorizacion['codiparentesco']==1) echo "Titular"; else echo "Familiar ".$rowLeeAutorizacion['codiparentesco'];};?></td>
			<td><?php echo $rowLeeAutorizacion['apellidoynombre'];?></td>
			<td><?php if($rowLeeAutorizacion['statusverificacion']==1) echo "Aprobada"; if($rowLeeAutorizacion['statusverificacion']==2) echo "Rechazada"; if($rowLeeAutorizacion['statusverificacion']==3) echo "No Reverificada";?></td>
			<td><?php if($rowLeeAutorizacion['statusautorizacion']==1) echo "Aprobada"; if($rowLeeAutorizacion['statusautorizacion']==2) echo "Rechazada";?></td>
			<td>&nbsp;</td>
		</tr>
<?php
		}
?>
	</tbody>
</table>
</div>
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