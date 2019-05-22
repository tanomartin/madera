<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$busqueda = 0;
$resultado = 0;
if(isset($_POST['valor']) && isset($_POST['seleccion'])) {
	$busqueda = 1;
	$ordenbusqueda = $_POST['seleccion'];
	$valorbusqueda = $_POST['valor'];
	if ($ordenbusqueda == "nroafiliado") {
		$cartel = "Nro. Afiliado: ".$valorbusqueda;
	}
	if ($ordenbusqueda == "nrodocumento") {
		$cartel = "Nro. de Documento: ".$valorbusqueda;
	}
	if ($ordenbusqueda == "cuil") {
		$cartel = "Nro. de C.U.I.L.: ".$valorbusqueda;
	}

	//BUSCO EN TITULARES
	$sqltituacti = "SELECT t.nroafiliado, t.apellidoynombre, td.descrip as tipodocumento, t.nrodocumento, t.cuil, t.discapacidad FROM titulares t, tipodocumento td WHERE t.$ordenbusqueda = '$valorbusqueda' and t.tipodocumento = td.codtipdoc";
	$restituacti = mysql_query($sqltituacti,$db);
	$arrayTitulares = array();
	$i=0;
	if (mysql_num_rows($restituacti)!=0) {
		$resultado = 1;
		while ($rowtituacti = mysql_fetch_assoc($restituacti)) {
			$arrayTitulares[$i] = $rowtituacti;
			$i++;
		}
	}
	//BUSCO EN FAMILIARES
	$sqlfamiacti = "SELECT f.nroafiliado, f.nroorden, f.apellidoynombre, td.descrip as tipodocumento, f.nrodocumento, f.cuil, p.descrip as parentesco, f.discapacidad FROM familiares f, tipodocumento td, parentesco p WHERE f.$ordenbusqueda = '$valorbusqueda' and f.tipodocumento = td.codtipdoc and f.tipoparentesco = p.codparent";
	$resfamiacti = mysql_query($sqlfamiacti,$db);
	$arrayFamiliares = array();
	$i=0;
	if (mysql_num_rows($resfamiacti)!=0) {
		$resultado = 1;
		while ($rowfamiacti = mysql_fetch_assoc($resfamiacti)) {
			$arrayFamiliares[$i] = $rowfamiacti;
			$i++;
		}
	}
	
	//BUSCO EN TITU DE BAJA
	$sqltitubaja = "SELECT t.nroafiliado, t.apellidoynombre, td.descrip as tipodocumento, t.nrodocumento, t.cuil, t.discapacidad FROM titularesdebaja t, tipodocumento td WHERE t.$ordenbusqueda = '$valorbusqueda' and t.tipodocumento = td.codtipdoc";
	$restitubaja = mysql_query($sqltitubaja,$db);
	$arrayTituBaja = array();
	$i=0;
	if (mysql_num_rows($restitubaja)!=0) {
		$resultado = 1;
		while ($rowtitubaja = mysql_fetch_assoc($restitubaja)) {
			$arrayTituBaja[$i] = $rowtitubaja;
			$i++;
		}
	}
	
	//BUSCO EN FAMI DE BAJA
	$sqlfamibaja = "SELECT f.nroafiliado, f.nroorden, f.apellidoynombre, td.descrip as tipodocumento, f.nrodocumento, f.cuil, p.descrip as parentesco, f.discapacidad FROM familiaresdebaja f, tipodocumento td, parentesco p WHERE f.$ordenbusqueda = '$valorbusqueda' and f.tipodocumento = td.codtipdoc  and f.tipoparentesco = p.codparent";
	$resfamibaja = mysql_query($sqlfamibaja,$db);
	$arrayFamiBaja = array();
	$i=0;
	if (mysql_num_rows($resfamibaja)!=0) {
		$resultado = 1;
		while ($rowfamibaja = mysql_fetch_assoc($resfamibaja)) {
			$arrayFamiBaja[$i] = $rowfamibaja;
			$i++;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABMC Discapacidad :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#tablatitulares")
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
		}),
		
		$("#tablafamiliares")
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
		}),
		
		$("#tablatitubaja")
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
		}),
		
		$("#tablafamibaja")
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
		});
	});


function validar(formulario) {
	var elementos = formulario.elements;
	var longitud = formulario.length;
	var elementoradio = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "seleccion" && elementos[i].type == "radio" && elementos[i].checked == true) {
			elementoradio=i;
		}
	}
	if(elementoradio == 0) {
		alert("Debe seleccionar una opcion de busqueda");
		return false;
	} else {
		if (formulario.valor.value == "") {
			alert("Debe ingresar algun dato para la busqueda");
			document.getElementById("valor").focus();
			return false;
		} else {
			if(elementoradio == 3) {
				if(!verificaCuilCuit(formulario.valor.value)){
					alert("El C.U.I.L. es invalido");
					return false;
				}
			}
		}
	}
	formulario.buscar.disabled = true;
	$.blockUI({ message: "<h1>Buscando Afiliado. Aguarde por favor...</h1>" });
	return true;
};
</script>

<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="moduloABM" name="moduloABM" method="post" onsubmit="return validar(this)" action="moduloABMDisca.php">
		<p><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '../moduloDisca.php'" /> </p>
		<h3>Afiliados Discapacidad </h3>
		<table width="137" border="0">
		  <tr>
			<td width="23"><input name="seleccion" type="radio" value="nroafiliado" checked="checked"/></td>
			<td width="104"><div align="left">Nro Afiliado</div></td>
		  </tr>
		  <tr>
			<td><input name="seleccion" type="radio" value="nrodocumento" /></td>
			<td><div align="left">Nro Documento</div></td>
		  </tr>
		  <tr>
			<td><input name="seleccion" type="radio" value="cuil" /></td>
			<td><div align="left">CUIL</div></td>
		  </tr>
		</table>
		<p><input name="valor" id="valor" type="text" size="11" /></p>
		<p><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	</form>
<?php  if ($busqueda == 1) { ?>
			<h3>Resultados Busqueda por "<?php echo $cartel ?>" </h3>		
<?php		if($resultado == 0) { 
		  		print("<p><font color='#0000FF'><b> No Existen Discapacitados con la busqueda realizada </b></font></p>");
		  	} else {  
				if (sizeof($arrayTitulares) > 0) { ?>
				 <h3>Titulares</h3>
				 <table style="text-align:center; width:1100px" id="tablatitulares" class="tablesorter" >
         		 <thead>
            		<tr>
			  			<th>Nro. Afliado</th>
			  			<th>Nombre y Apellido</th>
			 			<th>Tipo y Num de Doc</th>
						<th>C.U.I.L.</th>
						<th>Discapacitado</th>
						<th>Acciones</th>
					</tr>
          		</thead>
        		<tbody> 
		<?php 	foreach($arrayTitulares as $titular) {?>
					<tr>
						<td><?php echo $titular['nroafiliado'] ?></td>
						<td><?php echo $titular['apellidoynombre']?> </td>
						<td><?php echo $titular['tipodocumento'].": ".$titular['nrodocumento']?> </td>
						<td><?php echo $titular['cuil']?> </td>
						<td><?php if ($titular['discapacidad'] == 0) { echo "NO"; } else { echo "SI"; }?> </td>
						<td><?php if ($titular['discapacidad'] == 0) { ?>
									<input type='button' name='alta' value='Alta' onclick="location.href='nuevoDiscapacitado.php?nroafiliado=<?php echo $titular['nroafiliado'] ?>&nroorden=0'" />
							<?php } else { ?>
								  	<input type='button' name='modificar' value='Modificar' onclick="location.href='modificarDiscapacitado.php?nroafiliado=<?php echo $titular['nroafiliado'] ?>&nroorden=0'" />
								  	<input type='button' name='consultar' value='Consultar' onclick="location.href='consultarDiscapacitado.php?nroafiliado=<?php echo $titular['nroafiliado'] ?>&nroorden=0&activo=1'" />
							<?php }?> 
						</td>
					</tr>
		  <?php } ?>
		  		</tbody> 
				</table>
	<?php	}
		 if (sizeof($arrayFamiliares) > 0) { ?>
				 <h3>Familiares</h3>
				 <table style="text-align:center; width:1100px" id="tablafamiliares" class="tablesorter" >
         		 <thead>
            		<tr>
			  			<th>Nro. Afliado</th>
						<th>Parentesco</th>
			  			<th>Nombre y Apellido</th>
			 			<th>Tipo y Num de Doc</th>
						<th>C.U.I.L.</th>
						<th>Discapacitado</th>
						<th>Acciones</th>
					</tr>
          		</thead>
        		<tbody> 
		<?php 	foreach($arrayFamiliares as $familiar) {?>
					<tr>
						<td><?php echo $familiar['nroafiliado'] ?></td>
						<td><?php echo $familiar['parentesco'] ?></td>
						<td><?php echo $familiar['apellidoynombre']?> </td>
						<td><?php echo $familiar['tipodocumento'].": ".$familiar['nrodocumento']?> </td>
						<td><?php echo $familiar['cuil']?> </td>
						<td><?php if ($familiar['discapacidad'] == 0) { echo "NO"; } else { echo "SI"; }?> </td>
						<td><?php if ($familiar['discapacidad'] == 0) {  ?>
									<input type='button' name='alta' value='Alta' onclick="location.href='nuevoDiscapacitado.php?nroafiliado=<?php echo $familiar['nroafiliado'] ?>&nroorden=<?php echo $familiar['nroorden'] ?>'" />
							<?php } else {  ?>
								  	<input type='button' name='modificar' value='Modificar' onclick="location.href='modificarDiscapacitado.php?nroafiliado=<?php echo $familiar['nroafiliado'] ?>&nroorden=<?php echo $familiar['nroorden'] ?>'" />
								  	<input type='button' name='consultar' value='Consultar' onclick="location.href='consultarDiscapacitado.php?nroafiliado=<?php echo $familiar['nroafiliado'] ?>&nroorden=<?php echo $familiar['nroorden'] ?>&activo=1'" />
							<?php }?> 
						</td>
					</tr>
		  <?php } ?>
		  		</tbody> 
				</table>
	<?php	} 
			if (sizeof($arrayTituBaja) > 0) { ?>
				 <h3>Titulares Inactivos</h3>
				 <table style="text-align:center; width:1100px" id="tablatitubaja" class="tablesorter" >
         		 <thead>
            		<tr>
			  			<th>Nro. Afliado</th>
			  			<th>Nombre y Apellido</th>
			 			<th>Tipo y Num de Doc</th>
						<th>C.U.I.L.</th>
						<th>Discapacitado</th>
						<th>Acciones</th>
					</tr>
          		</thead>
        		<tbody> 
		<?php 	foreach($arrayTituBaja as $titularBaja) {?>
					<tr>
						<td><?php echo $titularBaja['nroafiliado'] ?></td>
						<td><?php echo $titularBaja['apellidoynombre']?> </td>
						<td><?php echo $titularBaja['tipodocumento'].": ".$titularBaja['nrodocumento']?> </td>
						<td><?php echo $titularBaja['cuil']?> </td>
						<td><?php if ($titularBaja['discapacidad'] == 0) { echo "NO"; } else { echo "SI"; }?> </td>
						<td><?php if ($titularBaja['discapacidad'] == 0) { 
										echo ("-");
								  } else { ?>
								  		<input type='button' name='consultar' value='Consultar' onclick="location.href='consultarDiscapacitado.php?nroafiliado=<?php echo $titularBaja['nroafiliado'] ?>&nroorden=0&activo=0'" />
							<?php }?> 
						</td>
					</tr>
		  <?php } ?>
		  		</tbody> 
				</table>
	<?php	} 
		if (sizeof($arrayFamiBaja) > 0) { ?>
				 <h3>Familiares Inactivos</h3>
				 <table style="text-align:center; width:1100px" id="tablafamibaja" class="tablesorter" >
         		 <thead>
            		<tr>
			  			<th>Nro. Afliado</th>
						<th>Parentesco</th>
			  			<th>Nombre y Apellido</th>
			 			<th>Tipo y Num de Doc</th>
						<th>C.U.I.L.</th>
						<th>Discapacitado</th>
						<th>Acciones</th>
					</tr>
          		</thead>
        		<tbody> 
		<?php 	foreach($arrayFamiBaja as $familiarBaja) {?>
					<tr>
						<td><?php echo $familiarBaja['nroafiliado'] ?></td>
						<td><?php echo $familiarBaja['parentesco'] ?></td>
						<td><?php echo $familiarBaja['apellidoynombre']?> </td>
						<td><?php echo $familiarBaja['tipodocumento'].": ".$familiarBaja['nrodocumento']?> </td>
						<td><?php echo $familiarBaja['cuil']?> </td>
						<td><?php if ($familiarBaja['discapacidad'] == 0) { echo "NO"; } else { echo "SI"; }?> </td>
						<td><?php if ($familiarBaja['discapacidad'] == 0) { 
									echo ("-");
								  } else { ?>
								  	<input type='button' name='consultar' value='Consultar' onclick="location.href='consultarDiscapacitado.php?nroafiliado=<?php echo $familiarBaja['nroafiliado'] ?>&nroorden=<?php echo $familiarBaja['nroorden'] ?>&activo=0'" />
							<?php }?> 
						</td>
					</tr>
		  <?php } ?>
		  		</tbody> 
				</table>
	<?php	} 
		 }?>
<?php } ?>
</div>
</body>
</html>