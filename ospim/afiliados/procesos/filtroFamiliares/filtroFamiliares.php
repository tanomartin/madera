<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlFamiFiltro = "SELECT
nroafiliado,
nroorden,
cuil,
tipoparentesco,
discapacidad,
p.descrip,
DATE_FORMAT(fechanacimiento,'%d/%m/%Y') as fechanacimiento,
DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0   as edad,
estudia,
certificadoestudio,
DATE_FORMAT(vencimientocertificadoestudio,'%d/%m/%Y') as vencimientocertificadoestudioInforme,
vencimientocertificadoestudio
FROM familiares f, parentesco p
where
f.tipoparentesco in (03,04,05,06,07) and
f.tipoparentesco = p.codparent and
DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(fechanacimiento)), '%Y')+0  > 20";

$resFamiFiltro = mysql_query ( $sqlFamiFiltro, $db );
$canFamiFiltro = mysql_num_rows ( $resFamiFiltro );
$hoy = date("Y-m-d");

$ahora = date("Y-n-j H:i:s");
$_SESSION["ultimoAcceso"] = $ahora;

$arrayBaja = array();
$arrayInfo = array();
while ( $rowFamiFiltro = mysql_fetch_assoc ( $resFamiFiltro ) ) {
	if ($rowFamiFiltro['tipoparentesco'] == 3 || $rowFamiFiltro['tipoparentesco'] == 5 || $rowFamiFiltro['tipoparentesco'] == 7) {
		if ($rowFamiFiltro['discapacidad'] == 1) {
			$arrayInfo[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
		} else {
			if ($rowFamiFiltro['estudia'] == 1) {
				$arrayInfo[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
			} else {
				$arrayBaja[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
			}
		}
	} else {
		if ($rowFamiFiltro['edad'] > 25) {
			if ($rowFamiFiltro['discapacidad'] == 1) {
				$arrayInfo[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
			} else {
				$arrayBaja[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
			}
		} else {
			if ($rowFamiFiltro['discapacidad'] == 0) {
				if ($rowFamiFiltro['estudia'] == 1) {
					if ($rowFamiFiltro['certificadoestudio'] == 1) {
						if ($rowFamiFiltro['vencimientocertificadoestudio'] < $hoy) {
							$arrayBaja[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
						}
					} else {
						$arrayBaja[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
					}
				} else {
					$arrayInfo[$rowFamiFiltro['cuil']] = $rowFamiFiltro;
				}
			}
		}
	}
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Filtro Familares :.</title>
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
		headers:{7:{sorter:false, filter:false}},
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	});
	
	$("#tablaInfo")
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
	})
});

function validar(formulario) {
	var grupo = formulario.baja;
	var total = grupo.length;
	var mensaje = "Debe seleccionar algun familiar para dar de baja";
	if (total == null) {
		if (!grupo.checked) {
			alert(mensaje);
			return false;
		}
	} else {
		var checkeados = 0; 
		for (var i = 0; i < total; i++) {
			if (grupo[i].checked) {
				checkeados++;
			}
		}
		if (checkeados == 0) {
			alert(mensaje);
			return false;
		}
	}
	formulario.selecAll.disabled = "true";
	formulario.submit.disabled = "true";
	return true;
}

function checkall(seleccion, formulario) {
 	var grupo = formulario.baja;
	var total = grupo.length;
	if (total == null) {
		if (seleccion.checked) {
			grupo.checked = 1;
		} else {
			grupo.checked = 0;
		}
	}
	if (seleccion.checked) {
		 for (var i=0;i< grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=1;  
	} else {
		 for (var i=0;i<grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=0;  
	}
} 

</script>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../moduloProcesos.php'" /></p>
		<p align="center" class="Estilo1">Familiares Filtro de Baja (<?php echo sizeof($arrayBaja) ?>)</p>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="bajarFamiliares.php">
			<table class="tablesorter" id="tabla" style="width: 900px; font-size: 14px">
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th class="filter-select" data-placeholder="Seleccion">Codigo - Parentesco</th>
						<th>(Edad) - Fec. Nac.</th>
						<th class="filter-select" data-placeholder="Seleccion">Discapacitado</th>
						<th class="filter-select" data-placeholder="Seleccion">Estudia</th>
						<th class="filter-select" data-placeholder="Seleccion">Certificado</th>
						<th>Vto. Cert.</th>
						<th><input type="checkbox" name="selecAll" id="selecAll" onchange="checkall(this, this.form)" /></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayBaja as $famibaja) {?>
		 				<tr>
							<td><?php echo $famibaja['nroafiliado'] ?></td>
							<td><?php echo $famibaja['tipoparentesco']." - ".$famibaja['descrip'] ?></td>
							<td><?php echo "(".$famibaja['edad'].") - ".$famibaja['fechanacimiento'] ?></td>
							<td><?php if ($famibaja['discapacidad'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($famibaja['estudia'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($famibaja['certificadoestudio'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($famibaja['certificadoestudio'] == 1) { echo $famibaja['vencimientocertificadoestudioInforme']; } ?></td>
							<td><input type="checkbox" name="<?php echo $famibaja['nroafiliado']."-".$famibaja['nroorden'] ?>" id="baja" value="<?php echo $famibaja['nroafiliado']."-".$famibaja['nroorden'] ?>" /></td>
						</tr>
				<?php 	} ?>
				<tbody>
				</tbody>
			</table>
			<div style="width: 900px; text-align: right;">
				<input class="nover" type="submit" name="submit" value="Dar de Baja" />
			</div>
		</form>
		
		<p align="center" class="Estilo1">Familiares con Inconsistencias (<?php echo sizeof($arrayInfo) ?>)</p>
		
		<table class="tablesorter" id="tablaInfo" style="width: 900px; font-size: 14px">
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th class="filter-select" data-placeholder="Seleccion">Codigo - Parentesco</th>
						<th>(Edad) - Fec. Nac.</th>
						<th class="filter-select" data-placeholder="Seleccion">Discapacitado</th>
						<th class="filter-select" data-placeholder="Seleccion">Estudia</th>
						<th class="filter-select" data-placeholder="Seleccion">Certificado</th>
						<th>Vto. Cert.</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayInfo as $faminfo) {?>
		 				<tr>
							<td><?php echo $faminfo['nroafiliado'] ?></td>
							<td><?php echo $faminfo['tipoparentesco']." - ".$faminfo['descrip'] ?></td>
							<td><?php echo "(".$faminfo['edad'].") - ".$faminfo['fechanacimiento'] ?></td>
							<td><?php if ($faminfo['discapacidad'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($faminfo['estudia'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($faminfo['certificadoestudio'] == 1) { echo "SI"; } else { echo "NO";} ?></td>
							<td><?php if ($faminfo['certificadoestudio'] == 1) { echo $faminfo['vencimientocertificadoestudioInforme']; } ?></td>
						</tr>
				<?php 	} ?>
				<tbody>
				</tbody>
			</table>
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />		
	</div>
</body>