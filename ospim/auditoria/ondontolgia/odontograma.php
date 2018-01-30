<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$nroafiliado = $_GET['nroafil'];
$nroorden = $_GET['nroorden'];
$tipo = $_GET['tipo'];

$tablaTitu = "titulares";
$tablaFami = "familiares";
if ($tipo == 'B') {
	$tablaTitu = "titularesdebaja";
	$tablaFami = "familiaresdebaja";
}

if ($nroorden == 0) {
	$sqlBeneficiario = "SELECT apellidoynombre, '' as parentesco FROM $tablaTitu WHERE nroafiliado = $nroafiliado";
	$tipoBeneficiario = "TITULAR";
} else {
	$sqlBeneficiario = "SELECT f.apellidoynombre, p.descrip as parentesco FROM $tablaFami f, parentesco p WHERE f.nroafiliado = $nroafiliado and f.nroorden = $nroorden and f.tipoparentesco = p.codparent";
	$tipoBeneficiario = "FAMILIAR";
}

if ($tipo == 'B') {
	$tipoBeneficiario .= " (DE BAJA)";
}

$resBeneficiario = mysql_query($sqlBeneficiario,$db);
$rowBeneficiario = mysql_fetch_assoc($resBeneficiario);

$sqlOdonto = "SELECT 
				o.id, o.fecha, pr.codigoprestador, pr.nombre as prestador, 
				p.codigopractica, p.descripcion as practica, 
				pi.codigo as codigopieza, pi.descripcion as nombrepieza, piezadentalcaras.descripcion as nombrecara
				FROM practicas p, prestadores pr, piezadental pi, odontograma o
				LEFT JOIN piezadentalcaras ON piezadentalcaras.id = o.idcara
				WHERE 
				o.nroafiliado = $nroafiliado AND 
				o.nroorden = $nroorden AND
				o.idpractica = p.idpractica AND
				o.codigoprestador = pr.codigoprestador AND
				o.codigopieza = pi.codigo
				ORDER BY fecha DESC";
$resOdonto = mysql_query($sqlOdonto,$db);
$canOdonto = mysql_num_rows($resOdonto);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Odontograma :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

$(function() {
	$("#lista")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	}).tablesorterPager({container: $("#paginador")}); 
});


function ConfirmDelete(id,nroafil,nroorden) {
	if (confirm("¿Esta seguro que desea elminar la entrada?")) {
		$.blockUI({ message: "<h1>Eliminando Entrada Seleccionadas</h1>" });
		location.href="odontogramaEliminar.php?id="+id+"&nroafil="+nroafil+"&nroorden="+nroorden;	
	}
}
</script>

</head>

<body bgcolor="#CCCCCC">
	<div align="center">
	  	<p><input type="button" name="volver" value="Volver" onclick="location.href='moduloOdontograma.php'" /></p>
	  	<h3>Odontograma  </h3>
	  	<table width="500" border="1">
	    	<tr>
	      		<td width="163"><div align="right"><strong>Nro Afiliado </strong></div></td>
	     		<td width="321"><div align="left"><strong><?php echo $nroafiliado ?></strong></div></td>
	    	</tr>
	    	<tr>
	      		<td><div align="right"><strong>Apellido y Nombre </strong></div></td>
	      		<td><div align="left"><?php echo $rowBeneficiario['apellidoynombre'] ?></div></td>
	    	</tr>
	    	<tr>
	      		<td><div align="right"><strong>Tipo de Beneficiario </strong></div></td>
	      		<td><div align="left"><?php echo $tipoBeneficiario." - ".$rowBeneficiario['parentesco'] ?></div></td>
	   	 	</tr>
	  </table>
	  <h3>Detalle Odontograma</h3>
	  <?php if ($tipo == 'A') { ?>
	    		<input type="button" value="Nueva Entrada" onclick="location.href = 'odontogramaNuevo.php?nroafil=<?php echo $nroafiliado?>&nroorden=<?php echo $nroorden?>'"/>
	  <?php }
			if ($canOdonto > 0) { ?>
				<table style="text-align:center; width:1000px" id="lista" class="tablesorter" >
					<thead>
						<tr>
							<th>Fecha</th>
							<th>Prestador</th>
							<th>Practica</th>
							<th>Pieza - Cara</th>
							<th>Accion</th>
						</tr>
					</thead>
					<tbody>
					<?php while ($rowOdonto = mysql_fetch_assoc($resOdonto)) { ?>
						<tr>
							<td><?php echo invertirFecha($rowOdonto['fecha']) ?></td>
							<td><?php echo $rowOdonto['codigoprestador']." - ".$rowOdonto['prestador']?></td>
							<td><?php echo $rowOdonto['codigopractica']." - ".$rowOdonto['practica'] ?></td>
							<td><?php echo $rowOdonto['codigopieza']." (".$rowOdonto['nombrepieza'].") - ".$rowOdonto['nombrecara']?></td>
							<td><input type="button" value="Eliminar" onclick="ConfirmDelete(<?php echo $rowOdonto['id']?>, <?php echo $nroafiliado ?>, <?php echo $nroorden ?>)"/></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	 <?php	} else { ?>
	  			<h3 style="color: blue">No existen entradas para este afiliado </h3>
	  <?php }?>
	</div>
</body>
</html>
   