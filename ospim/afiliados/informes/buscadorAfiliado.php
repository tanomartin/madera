<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por Nro. Afiliado <b>'".$dato."'</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por C.U.I.L. <b>'".$dato."'</b>";
}
if ($filtro == 2) {
	$cartel = "Resultados de Busqueda por Nro. de Documento <b>'".$dato."'</b>";
}
if ($filtro == 3) {
	$cartel = "Resultados de Busqueda por Nombre y Apellido <b>'".$dato."'</b>";
}

$existe = 0;
$existeBaja = 0;
$existeFamilia = 0;
$existeFamiliaBaja = 0;
$encontro = 0;
$resultado = array();
if (isset($dato)) {
	$sqlSele = "select * from ";
	if ($filtro == 0) {
		$where = "where nroafiliado = $dato";
	}
	if ($filtro == 1) { 
		$where = "where cuil = $dato"; 
	}
	if ($filtro == 2) { 
		$where = "where nrodocumento = $dato"; 
	}
	if ($filtro == 3) { 
		$where = "where apellidoynombre like '%$dato%'"; 	
	}
	
	//TITULARES//
	$tabla = "titulares ";
	$sqlEmpleados = $sqlSele.$tabla.$where;
	//print($sqlEmpleados."<br>");
	$resEmpleados = mysql_query($sqlEmpleados,$db); 
	$canEmpleados = mysql_num_rows($resEmpleados); 
	if ($canEmpleados != 0) {
		$existe = 1;
	}
	
	//TITULARES DE BAJA//
	$tabla = "titularesdebaja ";
	$sqlEmpleadosBaja = $sqlSele.$tabla.$where;
	//print($sqlEmpleadosBaja."<br>");
	$resEmpleadosBaja = mysql_query($sqlEmpleadosBaja,$db); 
	$canEmpleadosBaja = mysql_num_rows($resEmpleadosBaja); 
	if ($canEmpleadosBaja != 0) {
		$existeBaja = 1;
	}
	
	//FAMILIARES//
	$tabla = "familiares ";
	$sqlFamiliares = $sqlSele.$tabla.$where;
	//print($sqlFamiliares."<br>");
	$resFamiliares = mysql_query($sqlFamiliares,$db); 
	$canFamiliares = mysql_num_rows($resFamiliares); 
	if ($canFamiliares != 0) {
		$existeFamilia = 1;
	}
	
	//FAMILIARES//
	$tabla = "familiaresdebaja  ";
	$sqlFamiliaBaja = $sqlSele.$tabla.$where;
	//print($sqlFamiliaBaja."<br>");
	$resFamiliaresBaja = mysql_query($sqlFamiliaBaja,$db); 
	$canFamiliaresBaja = mysql_num_rows($resFamiliaresBaja); 
	if ($canFamiliaresBaja != 0) {
		$existeFamiliaBaja = 1;
	}
	
	$encontro = $existe + $existeBaja + $existeFamilia +$existeFamiliaBaja;
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false},5:{sorter:false},6:{sorter:false, filter:false}},
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
	
		$("#listaResultadoFami")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{4:{sorter:false},5:{sorter:false}},
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
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		if (!esEnteroPositivo(formulario.dato.value)) {
			alert("El numero de afiliado debe ser un numero entero positivo");
			return false;
		}
	}
	if (formulario.filtro[1].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.L. invalido");
			return false;
		}
	}
	if (formulario.filtro[2].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Número de Documento debe ser un numero entero positivo");
			return false;
		} 
	}
	
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorAfiliado.php">
  <div align="center">
    <input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'menuInformes.php'" />
    <p align="center" class="Estilo1">M&oacute;dulo Buscador de Afiliado</p>
    <div align="center" class="nover"> 
		<table style="width: 400; border: 0">
		  <tr>
		  <td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="0" checked="checked"/> Nro. Afiliado</div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="1"/> C.U.I.L.</div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="2" /> Nro. Documento</div></td>
		  </tr>  
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="3" /> Apellido y/o Nombre Afiliado</div></td>
		  </tr>  
		</table>
		<p><strong>Dato</strong> 
		  <input name="dato" type="text" id="dato" size="30" />
		</p>
    </div>
	  <p align="center">
		<label>
		<input type="submit" name="Buscar" value="Buscar" class="nover" />
		</label>
	  </p>
	<?php 
	if (isset($dato)) {
		print("<p> $cartel </p>");
		if ($encontro == 0) {
			print("<div style='color:#FF0000'><b> NO EXISTE TITULAR O FAMILIAR CON ESTE FILTRO DE BUSQUEDA </b></div><br>");
		} else { ?>
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>			
<?php	} 
		if ($existe == 1 || $existeBaja == 1) { ?>
			<table class="tablesorter" id="listaResultado" style="width:900px; font-size:14px">
			  <thead>
				<tr>
				  <th>Nro. Afiliado</th>
				  <th>C.U.I.L.</th>
				  <th>Apellido y Nombre</th>
				  <th>Tipo y Nro Doc</th>
				  <th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">De Baja</th>
				</tr>
			  </thead>
			  <tbody>
		<?php if ($existe == 1) { 
				 print("<p><b>TITULARES</b></p>");
				 while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) { ?>
				<tr align="center">
				  <td><?php echo $rowEmpleados['nroafiliado'];?></td>
				  <td><?php echo $rowEmpleados['cuil'];?></td>
				  <td><?php echo $rowEmpleados['apellidoynombre']; ?></td>
				  <td><?php echo $rowEmpleados['tipodocumento'].": ".$rowEmpleados['nrodocumento'];?></td>
				  <td><?php echo $rowEmpleados['sexo']; ?></td>
				  <td><?php echo "NO" ?></td>
				</tr>
			<?php } 
			   } ?>
		 <?php if ($existeBaja == 1) { 
			   	while($rowEmpleadosBaja = mysql_fetch_assoc($resEmpleadosBaja)) { ?>
				<tr align="center">
				  <td><?php echo $rowEmpleadosBaja['nroafiliado'];?></td>
				  <td><?php echo $rowEmpleadosBaja['cuil'];?></td>
				  <td><?php echo $rowEmpleadosBaja['apellidoynombre'] ?></td>
				  <td><?php echo $rowEmpleadosBaja['tipodocumento'].": ".$rowEmpleadosBaja['nrodocumento'];?></td>
				  <td><?php echo $rowEmpleadosBaja['sexo']; ?></td>
				  <td><?php echo "SI" ?></td>
				</tr>
				<?php } 
			   } ?>
			  </tbody>
			</table>
    <?php }
		if ($existeFamilia == 1 || $existeFamiliaBaja == 1) { 
			 print("<p><b>FAMILIARES</b><p>");?>
			<table class="tablesorter" id="listaResultadoFami" style="width:900px; font-size:14px">
			  <thead>
				<tr>
				  <th>Nro. Afiliado Titular</th>
				  <th>C.U.I.L.</th>
				  <th>Apellido y Nombre</th>
				  <th>Tipo y Nro Doc</th>
				  <th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
				  <th class="filter-select" data-placeholder="Seleccion Estado">De Baja</th>
				</tr>
			  </thead>
			  <tbody>
		<?php if ($existeFamilia == 1) { 
				 while($rowFamiliares = mysql_fetch_assoc($resFamiliares)) { ?>
				<tr align="center">
				  <td><?php echo $rowFamiliares['nroafiliado'];?></td>
				  <td><?php echo $rowFamiliares['cuil'];?></td>
				  <td><?php echo $rowFamiliares['apellidoynombre'] ?></td>
				  <td><?php echo $rowFamiliares['tipodocumento'].": ".$rowFamiliares['nrodocumento'];?></td>
				  <td><?php echo $rowFamiliares['sexo']; ?></td>
				  <td><?php echo "NO" ?></td>
				</tr>
			<?php } 
			   } ?>
		 <?php if ($existeFamiliaBaja == 1) { 
			   	while($rowFamiliaresBaha = mysql_fetch_assoc($resFamiliaresBaja)) { ?>
				<tr align="center">
				  <td><?php echo $rowFamiliaresBaha['nroafiliado'];?></td>	
				  <td><?php echo $rowFamiliaresBaha['cuil'];?></td>
				  <td><?php echo $rowFamiliaresBaha['apellidoynombre']?></td>
				  <td><?php echo $rowFamiliaresBaha['tipodocumento'].": ".$rowFamiliaresBaha['nrodocumento'];?></td>
				  <td><?php echo $rowFamiliaresBaha['sexo']; ?></td>
				  <td><?php echo "SI" ?></td>
				</tr>
				<?php } 
			   } ?>
			  </tbody>
			</table>
 	 <?php } 
 	} ?>
  </div>
</form>
</body>
</html>
