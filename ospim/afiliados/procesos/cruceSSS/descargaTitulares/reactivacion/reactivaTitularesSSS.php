<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$arrayTipo = array();

$sqlTituSSS = "SELECT DISTINCT p.cuiltitular, p.nrodocumento, p.cuit, p.apellidoynombre, p.tipotitular, p.osopcion, t.descrip FROM padronsss p, tipotitular t where p.parentesco = 0 and p.tipotitular = t.codtiptit";
$resTituSSS = mysql_query ( $sqlTituSSS, $db );
$arrayTituSSS = array();
while ($rowTituSSS = mysql_fetch_assoc ($resTituSSS)) {
	$arrayTituSSS[$rowTituSSS['cuiltitular']] = array('nrodoc' => $rowTituSSS['nrodocumento'], 'cuit' => $rowTituSSS['cuit'], 'nombre' => $rowTituSSS['apellidoynombre'], 'tipotitular' => $rowTituSSS['tipotitular'], 'osopcion' => $rowTituSSS['osopcion']);
	$arrayTipo[$rowTituSSS['cuiltitular']] = $rowTituSSS['descrip'];
}

$sqlTitu = "SELECT DISTINCT t.cuil, t.nrodocumento, t.nroafiliado, p.descrip FROM titularesdebaja t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
$resTitu = mysql_query ( $sqlTitu, $db );
$arrayTituBaja = array();
while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
	$arrayTituBaja[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
	$arrayTituNroAfil[$rowTitu['cuil']] = $rowTitu['nroafiliado'];
	$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
}

$arrayActivar = array();
foreach ($arrayTituSSS as $cuil => $titu) {
	if (array_key_exists ($cuil , $arrayTituBaja)) {
		$arrayActivar[$cuil] = $titu;
		$arrayActivar[$cuil] += array("nroafil" => $arrayTituNroAfil[$cuil]); 
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Busqueda de Reactivacion Titulares en SSS :.</title>

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
	.tablesorterPager({container: $("#paginador")}); 
});

function checkall(seleccion, formulario) {
	var grupo;
	grupo = formulario.activar;
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
	var grupo2 = formulario.activar;
	var total2 = grupo2.length;

	var mensaje = "Debe seleccionar algun titular para Reactivar";
	var checkeados = 0; 
	
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

	formulario.selecAllReactiva.disabled = "true";
	formulario.submit.disabled = "true";
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuDescInfoTituSSS.php'" />
		<h2>Reactivacion de Titulares desde S.S.S.</h2>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="procesarReactivacionTitularesSSS.php">
			<h3>Reactivacion de Titulares</h3>
			<table style="text-align: center; width: 1000px" id="tablaReactiva" class="tablesorter">	
				<thead>
					<tr>
						<th>Nro. Afiliado</th>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>C.U.I.T.</th>
						<th class="filter-select" data-placeholder="Seleccion">Tipo Titularidad</th>
						<th><input type="checkbox" name="selecAllReactiva" id="selecAllReactiva" onchange="checkall(this, this.form)" /></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($arrayActivar as $cuil => $titu) { ?>
						<tr>	
							<td><?php echo $titu['nroafil'] ?></td>
							<td><?php echo $cuil ?></td>
							<td><?php echo $titu['nombre']?></td>
							<td><?php echo $titu['cuit']?></td>
							<td><?php echo $arrayTipo[$cuil]?></td>
							<td><input type="checkbox" name="<?php echo $cuil ?>" id="activar" value="<?php echo $titu['cuit'].'-'.$titu['tipotitular']."-".$titu['osopcion']."-".$titu['nroafil'] ?>" /></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
			
			<input class="nover" type="submit" name="submit" value="Procesar" />
			
			<table class="nover">
				<tr>
					<td width="239">
						<div id="paginador" class="pager">
							<form>
								<p align="center">
									<img src="../../img/first.png" width="16" height="16" class="first"/> <img src="../../img/prev.png" width="16" height="16" class="prev"/>
									<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
									<img src="../../img/next.png" width="16" height="16" class="next"/> <img src="../../img/last.png" width="16" height="16" class="last"/>
									<select name="select" class="pagesize">
										<option selected="selected" value="50">50 por pagina</option>
										<option value="100">100 por pagina</option>
										<option value="200">200 por pagina</option>
										<option value="<?php echo sizeof($arrayActivar) ?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
			<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
		</form>
	</div>
</body>
</html>