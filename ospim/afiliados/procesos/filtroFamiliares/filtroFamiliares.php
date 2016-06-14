<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlFamiFiltro = "SELECT
nroafiliado,
nroorden,
tipoparentesco,
p.descrip,
DATE_FORMAT(fechanacimiento,'%d/%m/%Y') as fechanacimiento,
YEAR(CURDATE())-YEAR(fechanacimiento) as edad,
estudia,
certificadoestudio,
DATE_FORMAT(vencimientocertificadoestudio,'%d/%m/%Y') as vencimientocertificadoestudioInforme,
vencimientocertificadoestudio
FROM familiares f, parentesco p
where
f.tipoparentesco in (03,04,05,06,07) and
f.tipoparentesco = p.codparent and
YEAR(CURDATE())-YEAR(fechanacimiento) > 20";

$resFamiFiltro = mysql_query ( $sqlFamiFiltro, $db );
$canFamiFiltro = mysql_num_rows ( $resFamiFiltro );
$hoy = date("Y-m-d");

$ahora = date("Y-n-j H:i:s");
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo Filtro Familares :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet"
	href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#tabla")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{6:{sorter:false, filter:false}},
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	})
});

function validar(formulario) {
	formulario.submit.disabled = "true";
	return true;
}

</script>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../moduloProcesos.php'" /></p>
		<p align="center" class="Estilo1">Familiares Filtro de Baja</p>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="bajarFamiliares.php">
			<table class="tablesorter" id="tabla" style="width: 900px; font-size: 14px">
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th class="filter-select" data-placeholder="Seleccion">Codigo - Parentesco</th>
						<th>(Edad) - Fec. Nac.</th>
						<th class="filter-select" data-placeholder="Seleccion">Estudia</th>
						<th class="filter-select" data-placeholder="Seleccion">Certificado</th>
						<th>Vto. Cert.</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php while ( $rowFamiFiltro = mysql_fetch_assoc ( $resFamiFiltro ) ) { 
							$vto = 1;
							if ($rowFamiFiltro['tipoparentesco'] == 4 && $rowFamiFiltro['certificadoestudio'] == 1) {
								if ($rowFamiFiltro['vencimientocertificadoestudio'] >= $hoy) {
									$vto = 0;
								} 
							} 
							if ($vto == 1) {?>
		 						<tr>
									<td><?php echo $rowFamiFiltro['nroafiliado'] ?></td>
									<td><?php echo $rowFamiFiltro['tipoparentesco']." - ".$rowFamiFiltro['descrip'] ?></td>
									<td><?php echo "(".$rowFamiFiltro['edad'].") - ".$rowFamiFiltro['fechanacimiento'] ?></td>
									<td><?php if ($rowFamiFiltro['estudia'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
									<td><?php if ($rowFamiFiltro['certificadoestudio'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
									<td><?php if ($rowFamiFiltro['certificadoestudio'] == 1) { echo $rowFamiFiltro['vencimientocertificadoestudioInforme']; } ?></td>
									<td><input type="checkbox" name="<?php echo $rowFamiFiltro['nroafiliado']."-". $rowFamiFiltro['nroorden'] ?>" id="baja" value="<?php echo $rowFamiFiltro['nroafiliado']."-". $rowFamiFiltro['nroorden'] ?>" /></td>
								</tr>
					<?php 	}
						} ?>
				<tbody>
				</tbody>
			</table>
			<table style="width: 800px">
				<tr>
					<td align="left"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></td>
					<td align="right"><input class="nover" type="submit" name="submit" value="Bajar" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>