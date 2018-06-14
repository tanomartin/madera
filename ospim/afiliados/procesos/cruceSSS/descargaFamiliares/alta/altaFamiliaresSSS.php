<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
set_time_limit(0);

$sqlMesPadron = "SELECT * FROM padronssscabecera c WHERE fechacierre is null ORDER BY c.id DESC LIMIT 1";
$resMesPadron = mysql_query ( $sqlMesPadron, $db );
$rowMesPadron = mysql_fetch_assoc ($resMesPadron);

$datos = explode("-",$_POST['delegacion']);
$codidelega = $datos[0];

$sqlCuilTitular = "SELECT nroafiliado, cuil, codidelega FROM titulares WHERE codidelega = $codidelega";
$resCuilTitular = mysql_query ( $sqlCuilTitular, $db );
$arrayCuilTitular = array();
while ($rowCuilTitular = mysql_fetch_assoc ($resCuilTitular)) {
	$arrayCuilTitular[$rowCuilTitular['cuil']] = array("codidelega" => $rowCuilTitular['codidelega'], "nroafiliado" => $rowCuilTitular['nroafiliado']);
}

$sqlFamiSSS = "SELECT DISTINCT cuilfamiliar, cuiltitular, nrodocumento, cuit, apellidoynombre, tipotitular, osopcion, calledomicilio, localidad, codigopostal  
					FROM padronsss p 
					WHERE parentesco != 0 and tipotitular in (0,2,4,5,8) and osopcion = 0";
$resFamiSSS = mysql_query ( $sqlFamiSSS, $db );
$arrayFamiSSS = array();
while ($rowFamiSSS = mysql_fetch_assoc ($resFamiSSS)) {
	$cuilfamiliar = preg_replace('/[^0-9]+/', '', $rowFamiSSS['cuilfamiliar']);
	if (strlen($cuilfamiliar) == 11) {
		$arrayFamiSSS[$cuilfamiliar] = array('cuiltitular'=> $rowFamiSSS['cuiltitular'], 'nrodoc' => $rowFamiSSS['nrodocumento'], 'cuit' => $rowFamiSSS['cuit'], 'nombre' => $rowFamiSSS['apellidoynombre'], 'tipotitular' => $rowFamiSSS['tipotitular'], 'osopcion' => $rowFamiSSS['osopcion'], 'direccion' => $rowFamiSSS['calledomicilio'], 'localidad' => $rowFamiSSS['localidad'], 'codpostal' => $rowFamiSSS['codigopostal']);
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

$arrayAlta = array();
foreach ($arrayFamiSSS as $cuil => $fami) {
	if (array_key_exists ($fami['cuiltitular'] , $arrayCuilTitular)) {
		if (!array_key_exists ($cuil , $arrayFami)) {
			if (!array_key_exists ($cuil , $arrayFamiBaja)) {
				if(!in_array($fami['nrodoc'], $arrayFami)) {
					if(!in_array($fami['nrodoc'], $arrayFamiBaja)) {
						$arrayAlta[$cuil] = $fami;
					}
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
<title>.: Alta de Familiares en SSS :.</title>

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

	var mensaje = "Debe seleccionar algun familiar para dar de Alta";
	var checkeados = 0; 
	
	if (total1 == null) {
		if (!grupo1.checked) {
			alert(mensaje);
			return false;
		} else {
			checkeados++;
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
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'altaFamiliaresDelegacionSSS.php'" />
		<h2>Alta de Familiares desde la S.S.S.</h2>
		<h2>Padrón SSS Periodo "<?php echo $rowMesPadron['mes'].'-'.$rowMesPadron['anio']?>" </h2>
		<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="procesarAltaFamiliaresSSS.php?codidelega=<?php echo $codidelega?>">
			<h3>Delegacion "<?php echo $datos[1] ?>"</h3>
			<?php if (sizeof($arrayAlta) > 0) { ?>
			<table style="text-align: center; width: 1000px" id="tablaAlta" class="tablesorter">	
				<thead>
					<tr>
						<th>C.U.I.L.</th>
						<th>Apellido y Nombre</th>
						<th>Direccion</th>
						<th>Localidad</th>
						<th>C.U.I.L. Titular</th>
						<th>C.U.I.T.</th>
						<th><input type="checkbox" name="selecAllAlta" id="selecAllAlta" onchange="checkall(this, this.form)" /></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($arrayAlta as $cuil => $fami) { ?>
						<tr>	
							<td><?php echo $cuil ?></td>
							<td><?php echo $fami['nombre']?></td>
							<?php if ($fami['direccion'] != "") { $direccion = $fami['direccion']." - C.P.: ".$fami['codpostal']; } else { $direccion = ""; }?>
							<td><?php echo $direccion ?></td>
							<td><?php echo $fami['localidad']?></td>
							<td><?php echo $fami['cuiltitular']?></td>
							<td><?php echo $fami['cuit']?></td>
							<td><input type="checkbox" name="<?php echo $cuil ?>" id="alta" value="<?php echo $fami['cuiltitular']."-".$arrayCuilTitular[$fami['cuiltitular']]['nroafiliado'] ?>" /></td>
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
		
			<?php } else { ?>
			<h2>No hay Familiares para dar de alta</h2>
			<?php } ?>
			
			
		</form>
	</div>
</body>
</html>