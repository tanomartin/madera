<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlMesPadron = "SELECT * FROM padronssscabecera c WHERE fechacierre is null ORDER BY c.id DESC LIMIT 1";
$resMesPadron = mysql_query ( $sqlMesPadron, $db );
$rowMesPadron = mysql_fetch_assoc ($resMesPadron);

$datos = explode("-",$_POST['delegacion']);
$codidelega = $datos[0];
$sqlEmpresasCuit = "SELECT e.cuit,j.codidelega FROM empresas e, jurisdiccion j WHERE e.cuit = j.cuit order by e.cuit, j.disgdinero;";
$resEmpresasCuit = mysql_query ( $sqlEmpresasCuit, $db );
$arrayCuit = array();
while ($rowEmpresasCuit = mysql_fetch_assoc ($resEmpresasCuit)) {
	$arrayCuit[$rowEmpresasCuit['cuit']] = $rowEmpresasCuit;
}

//DEJO SOLO LA DELEGACION PEDIDA
$whereCuit = "(";
foreach ($arrayCuit as $empresas) {
	if ($empresas['codidelega'] == $codidelega) {
		$whereCuit .= "'".$empresas['cuit']."',";
	} else {
		unset($empresas[$empresas['cuit']]);
	}
}
$whereCuit = substr($whereCuit, 0, -1);
$whereCuit .= ")";

$arrayAlta = array();
if ($whereCuit != ")") {
	$arrayTipo = array();
	$sqlTituSSS = "SELECT DISTINCT p.cuiltitular, p.nrodocumento, p.cuit, p.apellidoynombre, p.tipotitular, p.osopcion, t.descrip, p.calledomicilio, p.localidad, p.codigopostal 
						FROM padronsss p, tipotitular t 
						WHERE p.cuit in $whereCuit and p.tipotitular in (0,2,4,5,8) and p.osopcion = 0 and p.parentesco = 0 and p.tipotitular = t.codtiptit";
	$resTituSSS = mysql_query ( $sqlTituSSS, $db );
	$arrayTituSSS = array();
	while ($rowTituSSS = mysql_fetch_assoc ($resTituSSS)) {
		$arrayTituSSS[$rowTituSSS['cuiltitular']] = array('nrodoc' => $rowTituSSS['nrodocumento'], 'cuit' => $rowTituSSS['cuit'], 'nombre' => $rowTituSSS['apellidoynombre'], 'tipotitular' => $rowTituSSS['tipotitular'], 'osopcion' => $rowTituSSS['osopcion'], 'direccion' => $rowTituSSS['calledomicilio'], 'localidad' => $rowTituSSS['localidad'], 'codpostal' => $rowTituSSS['codigopostal']);
		$arrayTipo[$rowTituSSS['cuiltitular']] = $rowTituSSS['descrip'];
	}
	
	$sqlTitu = "SELECT DISTINCT t.cuil, t.cuitempresa, t.nrodocumento, t.nroafiliado, p.descrip  FROM titulares t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
	$resTitu = mysql_query ( $sqlTitu, $db );
	$arrayTitu = array();
	while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
		$arrayTitu[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
		$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
	}
	
	$sqlTitu = "SELECT DISTINCT t.cuil, t.nrodocumento, t.nroafiliado, p.descrip FROM titularesdebaja t, tipotitular p WHERE t.situaciontitularidad = p.codtiptit";
	$resTitu = mysql_query ( $sqlTitu, $db );
	$arrayTituBaja = array();
	while ($rowTitu = mysql_fetch_assoc ($resTitu)) {
		$arrayTituBaja[$rowTitu['cuil']] = $rowTitu['nrodocumento'];
		$arrayTipo[$rowTitu['cuil']] = $rowTitu['descrip'];
	}
	
	$arrayTiposAceptados = array(0,2,4,5,8);
	
	foreach ($arrayTituSSS as $cuil => $titu) {
		if (in_array($titu['tipotitular'],$arrayTiposAceptados)) {
			if (!array_key_exists ($cuil , $arrayTitu)) {
				if (!array_key_exists ($cuil , $arrayTituBaja)) {
					if(!in_array($titu['nrodoc'], $arrayTitu)) {
						if(!in_array($titu['nrodoc'], $arrayTituBaja)) {
							$arrayAlta[$cuil] = $titu; 
						} 
					} 
				}
			} 
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
		headers:{6:{filter:false, sorter:false}},
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
	grupo = formulario.alta;
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
	var total1 = grupo1.length;

	var mensaje = "Debe seleccionar algun titular para dar dar de Alta";
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

	formulario.selecAllAlta.disabled = "true";
	formulario.submit.disabled = "true";
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'altaTitularesDelegacionSSS.php'" />
		<h2>Alta de Titulares desde la S.S.S.</h2>
		<h2>Padrón SSS Periodo "<?php echo $rowMesPadron['mes'].'-'.$rowMesPadron['anio']?>" </h2>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="procesarAltaSSS.php?codidelega=<?php echo $codidelega ?>">
			<h3>Delegacion "<?php echo $datos[1] ?>"</h3>
			<?php if (sizeof($arrayAlta) > 0) { ?>
			<table style="text-align: center; width: 1000px" id="tablaAlta" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>Direccion</th>
						<th>Localidad</th>
						<th>C.U.I.T.</th>
						<th class="filter-select" data-placeholder="Seleccion">Tipo Titularidad</th>
						<th><input type="checkbox" name="selecAllAlta" id="selecAllAlta" onchange="checkall(this, this.form)" /></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($arrayAlta as $cuil => $titu) { ?>
						<tr>	
							<td><?php echo $cuil ?></td>
							<td><?php echo $titu['nombre']?></td>
							<?php if ($titu['direccion'] != "") { $direccion = $titu['direccion']." - C.P.: ".$titu['codpostal']; } else { $direccion = ""; }?>
							<td><?php echo $direccion ?></td>
							<td><?php echo $titu['localidad']?></td>
							<td><?php echo $titu['cuit']?></td>
							<td><?php echo $arrayTipo[$cuil]?></td>
							<td><input type="checkbox" name="<?php echo $cuil ?>" id="alta" value="<?php echo $titu['cuit'].'-'.$titu['tipotitular'] ?>" /></td>
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
										<option value="<?php echo sizeof($arrayAlta) ?>">Todos</option>
									</select>
								</p>
							</form>	
						</div>
					</td>
				</tr>
			</table>
			<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
			<?php } else {?>
			<h2>No hay Titulares para dar de alta</h2>
			<?php } ?>
		</form>
	</div>
</body>
</html>