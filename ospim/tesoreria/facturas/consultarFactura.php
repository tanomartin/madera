<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
if(isset($_GET['idfactura'])) {
	$idcomprobante = $_GET['idfactura'];
	setcookie($idcomprobante, $_SESSION['usuario'], strtotime( '+15 days' ));
	$sqlConsultaFactura = "SELECT f.id, f.fecharecepcion, f.idPrestador, p.nombre, p.cuit, f.idTipocomprobante, t.descripcion, f.puntodeventa, f.nrocomprobante, f.fechacomprobante, f.idCodigoautorizacion, c.descripcioncorta, f.nroautorizacion, f.fechacorreo, f.diasvencimiento, f.fechavencimiento, f.importecomprobante FROM facturas f, prestadores p, tipocomprobante t, codigoautorizacion c WHERE f.id = $idcomprobante AND f.idPrestador = p.codigoprestador AND f.idTipocomprobante = t.id AND f.idCodigoautorizacion = c.id";
	$resConsultaFactura = mysql_query($sqlConsultaFactura,$db);
	$rowConsultaFactura = mysql_fetch_array($resConsultaFactura);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Facturas :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h1>Consulta de Comprobante</h1>
</div>
<div align="center">
	<h2>ID Interno: <?php echo $rowConsultaFactura['id'];?> - Fecha de Recepcion: <?php echo invertirFecha($rowConsultaFactura['fecharecepcion']);?> - Fecha de Correo: <?php echo invertirFecha($rowConsultaFactura['fechacorreo']);?></h2>
</div>
<div align="center">
	<div class="grilla" style="margin-top:40px; margin-bottom:20px">
	<table>
		<tr>
			<td colspan="2" class="title">Prestador</td>
		</tr>
		<tr>
			<td align="right">Codigo: </td>
			<td align="left"><?php echo $rowConsultaFactura['idPrestador'];?></td>
		</tr>
		<tr>
			<td align="right">Nombre / Razon Social: </td>
			<td align="left"><?php echo $rowConsultaFactura['nombre'];?></td>
		</tr>
		<tr>
			<td align="right">C.U.I.T.: </td>
			<td align="left"><?php echo $rowConsultaFactura['cuit'];?></td>
		</tr>
	</table>
	</div>
</div>
<div align="center">
	<div class="grilla" style="margin-top:20px; margin-bottom:20px">
	<table>
		<tr>
			<td colspan="4" class="title">Comprobante</td>
		</tr>
		<tr>
			<td><?php echo $rowConsultaFactura['descripcion'].' Nro.: '.$rowConsultaFactura['puntodeventa'].'-'.$rowConsultaFactura['nrocomprobante'];?></td>
			<td align="right">Fecha: </td>
			<td align="left"><?php echo invertirFecha($rowConsultaFactura['fechacomprobante']);?></td>
			<td><?php echo $rowConsultaFactura['descripcioncorta'].' Nro.: '.$rowConsultaFactura['nroautorizacion'];?></td>
		</tr>
		<tr>
			<td align="right" colspan="2"> Vencimiento a <?php echo $rowConsultaFactura['diasvencimiento'].' dias';?></td>
			<td align="right">Fecha Vto.: </td>
			<td align="left"><?php echo invertirFecha($rowConsultaFactura['fechavencimiento']);?></td>
		</tr>
		<tr>
			<td align="right" colspan="3">Importe: </td>
			<td align="left"><?php echo $rowConsultaFactura['importecomprobante'];?></td>
		</tr>
	</table>
	</div>
</div>
<div align="center">
	<p> <input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>