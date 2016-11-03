<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlFami = "SELECT DISTINCT f.cuil, f.nroorden, f.nrodocumento, f.apellidoynombre, f.nroafiliado, t.cuil as cuiltitular FROM familiares f, titulares t where f.informesss = 0 and f.nroafiliado = t.nroafiliado;";
$resFami = mysql_query ( $sqlFami, $db );
$arrayFami = array();
while ($rowFami = mysql_fetch_assoc ($resFami)) {
	$arrayFami[$rowFami['cuil']] = array('nrodoc' => $rowFami['nrodocumento'], 'cuiltitular' => $rowFami['cuiltitular'], 'nombre' => $rowFami['apellidoynombre'], 'nroafil' => $rowFami['nroafiliado'], 'nroord' => $rowFami['nroorden']);
}

$sqlFamiSSS = "SELECT DISTINCT cuilfamiliar FROM padronsss p where parentesco != 0";
$resFamiSSS = mysql_query ( $sqlFamiSSS, $db );
$arrayFamiSSS = array();
while ($rowFamiSSS = mysql_fetch_assoc ($resFamiSSS)) {
	$arrayFamiSSS[$rowFamiSSS['cuilfamiliar']] = $rowFamiSSS['cuilfamiliar'];
}


$arrayTitu = array();
$sqlTituSSS = "SELECT DISTINCT cuiltitular FROM padronsss p where parentesco = 0";
$resTituSSS = mysql_query ( $sqlTituSSS, $db );
while ($rowTituSSS = mysql_fetch_assoc ($resTituSSS)) {
	$arrayTitu[$rowTituSSS['cuiltitular']] = $rowTituSSS['cuiltitular'];
}
$sqlTitu = "SELECT DISTINCT cuil FROM titulares where informesss = 1";
$resTitu = mysql_query ( $sqlTitu, $db );
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTitu[$rowTitu['cuil']] = $rowTitu['cuil'];
}

$arrayInformar = array();
foreach ($arrayFami as $cuil => $fami) {
	if (!array_key_exists ($cuil , $arrayFamiSSS)){
		$cuiltitular = $fami['cuiltitular'];
		if (array_key_exists ($cuiltitular , $arrayTitu)) {
			$arrayInformar[$cuil] = $fami;
		}
	}
}

$limit = 100;
$count = 0;
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Familiares en OSPIM para Informar :.</title>

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
	$("#tablaInforme")
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
});

function checkall(seleccion, formulario) {
	var grupo = formulario.informar;
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
	var grupo1 = formulario.informar;
	var total1 = grupo1.length;

	var mensaje = "Debe seleccionar algun familiar para Informar a la SSS";
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

	if (checkeados == 0) {
		alert(mensaje);
		return false;
	}

	formulario.selecAllInformar.disabled = "true";
	formulario.submit.disabled = "true";
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuCruceSSS.php'" />
		<h2>Informe de Familiares de OSPIM a S.S.S.</h2>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="procesarFamiliares.php">
			<h3>Informe de Familiares (<?php echo $limit ?> de <?php echo sizeof($arrayInformar) ?>)</h3>
			<?php if (sizeof($arrayInformar) > 0) { ?>
			<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>Nro. Documento</th>
						<th>C.U.I.L. Titular</th>
						<th><input type="checkbox" name="selecAllInformar" id="selecAllInformar" onchange="checkall(this, this.form)" /></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($arrayInformar as $cuil => $fami) {
						$count++;?>
						<tr>	
							<td><?php echo $cuil ?></td>
							<td><?php echo $fami['nombre']?></td>
							<td><?php echo $fami['nrodoc']?></td>
							<td><?php echo $fami['cuiltitular']?></td>
							<td><input type="checkbox" name="<?php echo $cuil ?>" id="informar" value="<?php echo $fami['nroafil']."-".$fami['nombre']."-".$fami['nroord']."-".$fami['cuiltitular'] ?>" /></td>
						</tr>
				<?php if ($count > $limit) {
						break;
					  }
					} ?>
				</tbody>
			</table>
			<table style="width: 900px">
				<tr>
					<td style="text-align: left;"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></td>
					<td style="text-align: right;"><input class="nover" type="submit" name="submit" value="Procesar" /></td>
				</tr>
			</table>
			<?php } else { ?>
				<h4>No hay Familiares para informar</h4>
			<?php }?>
		</form>
	</div>
</body>
</html>