<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroafiliado=$_GET['nroafil'];
$sqlCapitados = "SELECT
b.mespadron, b.anopadron, DATE_FORMAT(b.fechainforme,'%d-%m-%Y') as fechainforme,
c.nombre as nombreCapitado,
p.descrip as parentesco,
CASE WHEN b.nroorden = 0 THEN t.apellidoynombre ELSE f.apellidoynombre END AS nombreActivo,
CASE WHEN b.nroorden = 0 THEN tb.apellidoynombre ELSE fb.apellidoynombre END AS nombreBaja
FROM beneficiarioscapitados b
LEFT JOIN titulares AS t ON (b.nroorden = 0 AND b.nroafiliado = t.nroafiliado)
LEFT JOIN titularesdebaja AS tb ON (b.nroorden = 0 AND b.nroafiliado = tb.nroafiliado)
LEFT JOIN familiares AS f ON (b.nroorden != 0 AND b.nroafiliado = f.nroafiliado and b.nroorden = f.nroorden)
LEFT JOIN familiaresdebaja AS fb ON (b.nroorden != 0 AND b.nroafiliado = fb.nroafiliado and b.nroorden = fb.nroorden)
LEFT JOIN parentesco AS p ON (b.tipoparentesco = p.codparent)
INNER JOIN capitados AS c ON (b.codigocapitado = c.codigo)
WHERE b.nroafiliado = $nroafiliado";
$resCapitados = mysql_query($sqlCapitados,$db);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<title>.: Informe Capitados :.</title>
<script type="text/javascript" src="/madera/lib/jquery.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script type="text/javascript" src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script language="javascript" type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"]
		})
	});
</script>
</head>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h2>Última inclusión en padron de capitados</h2>
	<h2>Afiliado Nro. '<?php echo $nroafiliado?>' y su grupo familiar</h2>
	<table id="listado" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
		<thead>
			<tr>
				<th>Parentesco</th>
				<th>Apellido y Nombre</th>
				<th>Prestador</th>
				<th>Período Padron</th>
				<th>Fecha Informe</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($rowCapitados = mysql_fetch_array($resCapitados)) { ?>
			<tr>
				<td><?php echo $rowCapitados['parentesco']?></td>
				<td><?php if ($rowCapitados['nombreActivo'] != 'NULL') { echo $rowCapitados['nombreActivo']; } else { $rowCapitados['nombreBaja'];  }  ?></td>
				<td><?php echo $rowCapitados['nombreCapitado']?></td>
				<td><?php echo $rowCapitados['mespadron']."-".$rowCapitados['anopadron']?></td>
				<td><?php echo $rowCapitados['fechainforme']?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</div>
</body>
</body>
</html>