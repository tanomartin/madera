<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$nroafiliado=$_GET['nroafil'];
$sqlCapitados = "SELECT
b.mespadron, b.anopadron, DATE_FORMAT(b.fechainforme,'%d-%m-%Y') as fechainforme,
c.nombre as nombreCapitado,
p.descrip as parentesco,
CASE WHEN b.nroorden = 0 THEN t.apellidoynombre ELSE f.apellidoynombre END AS nombreAFiliado
FROM beneficiarioscapitados b
LEFT JOIN titulares AS t ON (b.nroorden = 0 AND b.nroafiliado = t.nroafiliado)
LEFT JOIN familiares AS f ON (b.nroorden != 0 AND b.nroafiliado = f.nroafiliado and b.nroorden = f.nroorden)
LEFT JOIN parentesco AS p ON (b.tipoparentesco = p.codparent)
INNER JOIN capitados AS c ON (b.codigocapitado = c.codigo)
WHERE b.nroafiliado = $nroafiliado";

$resCapitados = mysql_query($sqlCapitados,$db);
$canCapitados = mysql_num_rows($resCapitados);
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
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 2:{sorter:false}, 3:{sorter:false}, 4:{sorter:false}, 5:{sorter:false}, 6:{sorter:false}, 7:{sorter:false}, 8:{sorter:false}, 9:{sorter:false}, 10:{sorter:false}}
		})
	});
</script>
</head>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<h2>�ltima inclusi�n en padron de capitados</h2>
	<h2>Afiliado Nro. '<?php echo $nroafiliado?>' y su grupo familiar</h2>
	<?php if ($canCapitados!=0) { ?>
	<table id="listado" class="tablesorter" style="width:800px; font-size:14px; text-align:center">
		<thead>
			<tr>
				<th>Parentesco</th>
				<th>Apellido y Nombre</th>
				<th>Prestador</th>
				<th>Per�odo Padron</th>
				<th>Fecha Informe</th>
			</tr>
		</thead>
		<tbody>
		<?php while ($rowCapitados = mysql_fetch_array($resCapitados)) { ?>
			<tr>
				<td><?php echo $rowCapitados['parentesco']?></td>
				<td><?php echo $rowCapitados['nombreAFiliado']?></td>
				<td><?php echo $rowCapitados['nombreCapitado']?></td>
				<td><?php echo $rowCapitados['mespadron']."-".$rowCapitados['anopadron']?></td>
				<td><?php echo $rowCapitados['fechainforme']?></td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
	<?php } else { ?>
		<h3 style="color: blue">Hasta la fecha n�nca fue incluido el titular ni su grupo familiar a ning�n prestador capitado</h3>
	<?php } ?>
</div>
</body>
</body>
</html>
