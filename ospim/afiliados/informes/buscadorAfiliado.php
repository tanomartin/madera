<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

if (isset($_POST['dato']) &&  isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	
	if ($filtro == 0) {
		$cartel = "<p style='color: blue'>Resultados de Busqueda por Nro. Afiliado <b>'".$dato."'</b></p>";
	}
	if ($filtro == 1) {
		$cartel = "<p style='color: blue'>Resultados de Busqueda por C.U.I.L. <b>'".$dato."'</b></p>";
	}
	if ($filtro == 2) {
		$cartel = "<p style='color: blue'>Resultados de Busqueda por Nro. de Documento <b>'".$dato."'</b></p>";
	}
	if ($filtro == 3) {
		$cartel = "<p style='color: blue'>Resultados de Busqueda por Nombre y Apellido <b>'".$dato."'</b></p>";
	}
	
	$existe = 0;
	$existeBaja = 0;
	$existeBajaHistorico = 0;
	$existeFamilia = 0;
	$existeFamiliaBaja = 0;
	$existeFamiliaBajaHistorico = 0;
	$encontro = 0;
	
	$resultado = array();
	if (isset($dato)) {
		$sqlSele = "select nroafiliado,cuil,apellidoynombre,tipodocumento,nrodocumento,sexo from ";
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
		
		//TITULARES DE BAJA HISTORICO//
		$tabla = "titularesdebajahistorico ";
		$sqlEmpleadosBajaHistorico = $sqlSele.$tabla.$where;
		//print($sqlEmpleadosBaja."<br>");
		$resEmpleadosBajaHistorico = mysql_query($sqlEmpleadosBajaHistorico,$db);
		$canEmpleadosBajaHistorico = mysql_num_rows($resEmpleadosBajaHistorico);
		if ($canEmpleadosBajaHistorico != 0) {
			$existeBajaHistorico = 1;
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
		
		//FAMILIARES DE BAJA//
		$tabla = "familiaresdebaja  ";
		$sqlFamiliaBaja = $sqlSele.$tabla.$where;
		//print($sqlFamiliaBaja."<br>");
		$resFamiliaBaja = mysql_query($sqlFamiliaBaja,$db); 
		$canFamiliaBaja = mysql_num_rows($resFamiliaBaja); 
		if ($canFamiliaBaja != 0) {
			$existeFamiliaBaja = 1;
		}
		
		//FAMILIARES DE BAJA HISOTRICO//
		$tabla = "familiaresdebajahistorico  ";
		$sqlFamiliaBajaHistorico = $sqlSele.$tabla.$where;
		//print($sqlFamiliaBajaHistorico."<br>");
		$resFamiliaBajaHistorico = mysql_query($sqlFamiliaBajaHistorico,$db);
		$canFamiliaBajaHistorico = mysql_num_rows($resFamiliaBajaHistorico);
		if ($canFamiliaBajaHistorico != 0) {
			$existeFamiliaBajaHistorico = 1;
		}
		
		$encontro = $existe + $existeBaja + $existeBajaHistorico + $existeFamilia +$existeFamiliaBaja + $existeFamiliaBajaHistorico;
	}
} ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>

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
	if (formulario.filtro[3].checked) {
		var busqueda = formulario.dato.value;
		if (busqueda.length < 4) {
			alert("Debe buscar por lo menos con 4 caracteres por nombre");
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
    <p><input type="reset" class="nover" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
    <h3>Módulo Buscador de Afiliado</h3>
    <div class="nover"> 
		<table style="width: 400; border: 0">
		  <tr><td rowspan="6"><b>Buscar por </b></td></tr>
		  <tr><td><div align="left"><input type="radio" name="filtro" value="0" checked="checked"/> Nro. Afiliado</div></td></tr>
		  <tr><td><div align="left"><input type="radio" name="filtro" value="1"/> C.U.I.L.</div></td></tr>
		  <tr><td><div align="left"><input type="radio" name="filtro" value="2" /> Nro. Documento</div></td></tr>  
		  <tr><td><div align="left"><input type="radio" name="filtro" value="3" /> Apellido y/o Nombre Afiliado</div></td></tr>  
		</table>
		<p><b>Dato: </b><input name="dato" type="text" id="dato" size="30" /></p>
    </div>
	<p><input type="submit" name="Buscar" value="Buscar" class="nover" /></p>
<?php if (isset($dato)) {
		echo $cartel;
		if ($encontro == 0) { ?>
			<p style='color:#FF0000'><b> NO EXISTE TITULAR O FAMILIAR CON ESTE FILTRO DE BUSQUEDA </b></p>
<?php	} else { 
			if ($existe == 1 || $existeBaja == 1 || $existeBajaHistorico == 1) { ?>
				<p><b>TITULARES</b></p>
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
				   }
				   if ($existeBaja == 1) { 
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
				   }
				   if ($existeBajaHistorico == 1) { 
				   	while($rowEmpleadosBajaHistorico = mysql_fetch_assoc($resEmpleadosBajaHistorico)) { ?>
					<tr align="center">
					  <td><?php echo $rowEmpleadosBajaHistorico['nroafiliado'];?></td>
					  <td><?php echo $rowEmpleadosBajaHistorico['cuil'];?></td>
					  <td><?php echo $rowEmpleadosBajaHistorico['apellidoynombre'] ?></td>
					  <td><?php echo $rowEmpleadosBajaHistorico['tipodocumento'].": ".$rowEmpleadosBajaHistorico['nrodocumento'];?></td>
					  <td><?php echo $rowEmpleadosBajaHistorico['sexo']; ?></td>
					  <td><?php echo "SI <b>(Historico)</b>" ?></td>
					</tr>
					<?php } 
				   } ?>
				  </tbody>
				</table>
	    <?php }
			if ($existeFamilia == 1 || $existeFamiliaBaja == 1 || $existeFamiliaBajaHistorico == 1) { ?>
				<p><b>FAMILIARES</b><p>
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
				   }
				   if ($existeFamiliaBaja == 1) { 
				   	while($rowFamiliaresBaja = mysql_fetch_assoc($resFamiliaresBaja)) { ?>
					<tr align="center">
					  <td><?php echo $rowFamiliaresBaja['nroafiliado'];?></td>	
					  <td><?php echo $rowFamiliaresBaja['cuil'];?></td>
					  <td><?php echo $rowFamiliaresBaja['apellidoynombre']?></td>
					  <td><?php echo $rowFamiliaresBaja['tipodocumento'].": ".$rowFamiliaresBaja['nrodocumento'];?></td>
					  <td><?php echo $rowFamiliaresBaja['sexo']; ?></td>
					  <td><?php echo "SI" ?></td>
					</tr>
					<?php } 
				   } 
				   if ($existeFamiliaBajaHistorico == 1) { 
				   	while($rowFamiliaBajaHistorico = mysql_fetch_assoc($resFamiliaBajaHistorico)) { ?>
					<tr align="center">
					  <td><?php echo $rowFamiliaBajaHistorico['nroafiliado'];?></td>	
					  <td><?php echo $rowFamiliaBajaHistorico['cuil'];?></td>
					  <td><?php echo $rowFamiliaBajaHistorico['apellidoynombre']?></td>
					  <td><?php echo $rowFamiliaBajaHistorico['tipodocumento'].": ".$rowFamiliaBajaHistorico['nrodocumento'];?></td>
					  <td><?php echo $rowFamiliaBajaHistorico['sexo']; ?></td>
					  <td><?php echo "SI <b>(Historico)</b>" ?></td>
					</tr>
					<?php } 
				   } ?>
				  </tbody>
				</table>
	 	 <?php }
	 	 	if ($encontro != 0) { ?>
	 	 		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	  <?php	}
		}
 	} ?>
  </div>
</form>
</body>
</html>
