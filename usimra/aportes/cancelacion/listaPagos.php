<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");
if(isset($_POST['cuit'])) {
	$cuit=$_POST['cuit'];
} else {
	$cuit=$_GET['cuit'];
}
include($libPath."cabeceraEmpresaConsulta.php");
if($tipo=="noexiste") {
	header('Location: moduloCancelacion.php?err=1');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Selecci&oacute;n de Periodo a Cancelar :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	$("#resultados")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 1:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}}
		});
});
</script>
</head>
<body bgcolor="#B2A274">
	<div align="center">
		<input type="button" name="volver" value="Volver" onClick="location.href = 'moduloCancelacion.php'" />
<?php 	
include($libPath."cabeceraEmpresa.php"); 
?>
		<h1>Pagos Existentes</h1>
		<table id="resultados" class="tablesorter" style="font-size:14px; text-align:center">
			<thead>
				<tr>
					<td>Mes</td>
					<td>A&ntilde;o</td>
					<td>Fecha de Pago</td>
					<td>Total Depositado</td>
					<td>Sistema de Cancelaci&oacute;n</td>
					<td>Acci&oacute;n</td>
				</tr>
			</thead>
			<tbody>
<?php
$sqlListaPagos="SELECT s.mespago, p.descripcion AS mesnombre, s.anopago, s.nropago, s.fechapago, s.montopagado, s.sistemacancelacion FROM seguvidausimra s, periodosusimra p WHERE s.cuit = '$cuit' AND s.anopago > 2009 AND s.anopago = p.anio AND s.mespago = p.mes ORDER BY s.anopago DESC, s.mespago ASC, s.nropago DESC";
$resListaPagos=mysql_query($sqlListaPagos,$db);
while($rowListaPagos=mysql_fetch_array($resListaPagos)) {
?>
				<tr>
					<td><?php echo $rowListaPagos['mesnombre'];?></td>
					<td><?php echo $rowListaPagos['anopago'];?></td>
					<td><?php echo invertirFecha($rowListaPagos['fechapago']);?></td>
					<td><?php echo $rowListaPagos['montopagado'];?></td>
					
<?php
	if($rowListaPagos['sistemacancelacion']=='E') { ?>
					<td><?php echo "Electronico"; ?></td>
					<td><?php echo "-"; ?></td>

<?php
	} else { ?>
					<td><?php echo "Manual"; ?></td>
					<td><input class="nover" type="button" id="modificapago" name="modificapago" value="Modificar" onClick="location.href = 'modificaPago.php?cuit=<?php echo $cuit?>&mespago=<?php echo $rowListaPagos['mespago']?>&anopago=<?php echo $rowListaPagos['anopago']?>&nropago=<?php echo $rowListaPagos['nropago']?>'"/></td>
<?php	
	}
?>
				</tr>
<?php
}
?>
			</tbody>
		</table>
	</div>
	<div align="center">
		<p><input class="nover" type="button" id="cancelapago" name="cancelapago" value="Cancelar Período" onClick="location.href = 'cancelaPago.php?cuit=<?php echo $cuit?>'"/></p>
	</div>
</body>
</html>
