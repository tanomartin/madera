<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlFamiSSS = "SELECT DISTINCT cuilfamiliar, cuiltitular, nrodocumento, cuit, apellidoynombre, tipotitular, osopcion FROM padronsss p where parentesco != 0";
$resFamiSSS = mysql_query ( $sqlFamiSSS, $db );
$arrayFamiSSS = array();
while ($rowFamiSSS = mysql_fetch_assoc ($resFamiSSS)) {
	$cuilfamiliar = preg_replace('/[^0-9]+/', '', $rowFamiSSS['cuilfamiliar']);
	if (strlen($cuilfamiliar) == 11) {
		$arrayFamiSSS[$cuilfamiliar] = array('cuiltitular'=> $rowFamiSSS['cuiltitular'], 'nrodoc' => $rowFamiSSS['nrodocumento'], 'cuit' => $rowFamiSSS['cuit'], 'nombre' => $rowFamiSSS['apellidoynombre'], 'tipotitular' => $rowFamiSSS['tipotitular'], 'osopcion' => $rowFamiSSS['osopcion']);
	}
}

$sqlFami = "SELECT DISTINCT cuil, nrodocumento, nroafiliado FROM familiares t";
$resFami = mysql_query ( $sqlFami, $db );
$arrayFami = array();
while ($rowFami = mysql_fetch_assoc ($resFami)) {
	$arrayFami[$rowFami['cuil']] = $rowFami['nrodocumento'];
}

$sqlFamiBaja = "SELECT DISTINCT cuil, nrodocumento, nroafiliado  FROM familiaresdebaja t";
$resFamiBaja = mysql_query ( $sqlFamiBaja, $db );
$arrayFamiBaja = array();
while ($rowFamiBaja = mysql_fetch_assoc ($resFamiBaja)) {
	$arrayFamiBaja[$rowFamiBaja['cuil']] = $rowFamiBaja['nrodocumento'];
	$arrayFamiNroAfil[$rowFamiBaja['cuil']] = $rowFamiBaja['nroafiliado'];
}

$arrayActivar = array();
$arrayAlta = array();
$arrayInforme = array();
$cantAlta = 0;
$cantReac = 0;
$limiteArray = 50;

foreach ($arrayFamiSSS as $cuil => $fami) {
	if (!array_key_exists ($cuil , $arrayFami)) {
		if (!array_key_exists ($cuil , $arrayFamiBaja)) {
			if(!in_array($fami['nrodoc'], $arrayFami)) {
				if(!in_array($fami['nrodoc'], $arrayFamiBaja)) {
					$cantAlta++;
					if (sizeof($arrayAlta) < $limiteArray) {
						$arrayAlta[$cuil] = $fami;
					}
				} else {
					$arrayInforme[$cuil] = $fami;
				}
			} else {
				$arrayInforme[$cuil] = $fami;
			}
		} else {
			$cantReac++;
			if (sizeof($arrayActivar) < $limiteArray) {
				$arrayActivar[$cuil] = $fami;
				$arrayActivar[$cuil] += array("nroafil" => $arrayFamiNroAfil[$cuil]); 
			}
		}
	} 
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Titulares en SSS :.</title>

<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#tablaAlta")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{4:{filter:false, sorter:false}},
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
	
	$("#tablaReactiva")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		headers:{5:{filter:false, sorter:false}},
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

	$("#tablaInforme")
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

function checkall(seleccion, formulario, nombre) {
	var grupo;
	if (nombre == 'alta' ) {
		grupo = formulario.alta;
	} else {
		grupo = formulario.activar;
	}
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


function validar(formulario) {
	var grupo1 = formulario.alta;
	var grupo2 = formulario.activar;
	var total1 = grupo1.length;
	var total2 = grupo2.length;

	var mensaje = "Debe seleccionar algun familiar para dar dar de Alta o Reactivar";
	var checkeados = 0; 
	
	if (total1 == null) {
		if (!grupo1.checked) {
			alert(mensaje);
			return false;
		}
	} else {
		for (var i = 0; i < total1; i++) {
			if (grupo1[i].checked) {
				checkeados++;
			}
		}
	}

	if (total2 == null) {
		if (!grupo2.checked) {
			alert(mensaje);
			return false;
		}
	} else {
		for (var i = 0; i < total2; i++) {
			if (grupo2[i].checked) {
				checkeados++;
			}
		}
	}

	if (checkeados == 0) {
		alert(mensaje);
		return false;
	}

	formulario.selecAllAlta.disabled = "true";
	formulario.selecAllReactiva.disabled = "true";
	formulario.submit.disabled = "true";
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuCruceSSS.php'" />
		<h2>Descarga Familiares S.S.S.</h2>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="procesarFamiliaresSSS.php">
			<h3>Alta de Familiares (<?php echo sizeof($arrayAlta) ?> de <?php echo $cantAlta ?>)</h3>
			<?php if (sizeof($arrayAlta) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaAlta" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.L. Titular</th>
						<th>C.U.I.T.</th>
						<th><input type="checkbox" name="selecAllAlta" id="selecAllAlta" onchange="checkall(this, this.form, 'alta')" /></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($arrayAlta as $cuil => $fami) { ?>
						<tr>	
							<td><?php echo $cuil ?></td>
							<td><?php echo $fami['nombre']?></td>
							<td><?php echo $fami['cuiltitular']?></td>
							<td><?php echo $fami['cuit']?></td>
							<td><input type="checkbox" name="<?php echo "A".$cuil ?>" id="alta" value="<?php echo $fami['cuiltitular']."-".$fami['osopcion'] ?>" /></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<h4>No hay Familiares para informar</h4>
			<?php } ?>
			<h3>Reactivacion de Familiares (<?php echo sizeof($arrayActivar) ?> de <?php echo $cantReac ?>)</h3>
			<?php if (sizeof($arrayActivar) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaReactiva" class="tablesorter">	
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.L. Titular</th>
						<th>C.U.I.T.</th>
						<th><input type="checkbox" name="selecAllReactiva" id="selecAllReactiva" onchange="checkall(this, this.form,'activar')" /></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($arrayActivar as $cuil => $fami) { ?>
						<tr>	
							<td><?php echo $fami['nroafil'] ?></td>
							<td><?php echo $cuil ?></td>
							<td><?php echo $fami['nombre']?></td>
							<td><?php echo $fami['cuiltitular']?></td>
							<td><?php echo $fami['cuit']?></td>
							<td><input type="checkbox" name="<?php echo "R".$cuil ?>" id="activar" value="<?php echo $fami['cuiltitular']."-".$fami['osopcion']."-".$fami['nroafil'] ?>" /></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<h4>No hay Familiares para informar</h4>
			<?php } ?>
			<input class="nover" type="submit" name="submit" value="Procesar" />
			
			<h3>Informe de Familiares encontrados por D.N.I.</h3>
			<?php if (sizeof($arrayInforme) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.L. Titular</th>
						<th>C.U.I.T.</th>
						<th>Nro. Documento</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($arrayInforme as $cuil => $fami) { ?>
						<tr>	
							<td><?php echo $cuil ?></td>
							<td><?php echo $fami['nombre']?></td>
							<td><?php echo $fami['cuiltitular']?></td>
							<td><?php echo $fami['cuit']?></td>
							<td><?php echo $fami['nrodoc']?></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			<?php } else { ?>
			<h4>No hay Familiares para informar</h4>
			<?php } ?>
			<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
		</form>
	</div>
</body>
</html>