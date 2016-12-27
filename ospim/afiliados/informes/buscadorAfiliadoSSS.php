<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];
$cabecera = $_POST['padron'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por C.U.I.L. <b>'".$dato."'</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por Nro. de Documento <b>'".$dato."'</b>";
}
if ($filtro == 2) {
	$cartel = "Resultados de Busqueda por Nombre y Apellido <b>'".$dato."'</b>";
}

$resultado = array();
if (isset($dato)) {
	$sqlSele = "select cuit,cuilfamiliar,apellidoynombre,tipodocumento,nrodocumento,sexo, parentesco from ";
	if ($filtro == 0) { 
		$where = "where cuilfamiliar = $dato and idcabecera = $cabecera"; 
	}
	if ($filtro == 1) { 
		$where = "where nrodocumento = $dato and idcabecera = $cabecera"; 
	}
	if ($filtro == 2) { 
		$where = "where apellidoynombre like '%$dato%' and idcabecera = $cabecera"; 	
	}
	
	$tabla = "padronssshistorico ";
	$sqlEmpleados = $sqlSele.$tabla.$where;
	$resEmpleados = mysql_query($sqlEmpleados,$db); 
	$canEmpleados = mysql_num_rows($resEmpleados); 
}

if (isset($cabecera)) {
	$sqlPadronBusqueda = "SELECT * FROM padronssscabecera WHERE id = $cabecera";
	$resPadronBusqueda = mysql_query($sqlPadronBusqueda,$db);
	$rowPadronBusqueda = mysql_fetch_assoc($resPadronBusqueda);
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo ospim :.</title>
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
			headers:{3:{sorter:false},4:{sorter:false}},
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
	if(formulario.padron.value == 0) {
		alert("Debe seleccionar un padron de busqueda");
		return false;
	}
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.L. invalido");
			return false;
		}
	}
	if (formulario.filtro[1].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Número de Documento debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[2].checked) {
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
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorAfiliadoSSS.php">
  <div align="center">
    <p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'menuSSS.php'" /></p>
    <h3>Buscador de Afiliado en la S.S.S.</h3>
    <div align="center" class="nover"> 
    	<?php 
    		$sqlHistorico = "SELECT * FROM padronssscabecera ORDER BY id DESC";
    		$resHistorico = mysql_query ( $sqlHistorico, $db );
    	?>
		<p>
		  	<b>Padrón</b>	
		    <select id="padron" name="padron">
		    	<option value="0">Seleccione Padrón</option>
		    	<?php while ($rowHistorico = mysql_fetch_assoc($resHistorico)) { ?>
		    		<option value="<?php echo $rowHistorico['id']?>"><?php echo $rowHistorico['mes']."-".$rowHistorico['anio']?></option>	
		    	<?php } ?>
		    </select>
		</p>
		
		<table style="width: 400; border: 0">
		  
		  <tr>
		  <td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="0"/> C.U.I.L.</div></td>
		  </tr>
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="1" /> Nro. Documento</div></td>
		  </tr>  
		  <tr>
			<td><div align="left"><input type="radio" name="filtro" value="2" /> Apellido y/o Nombre Afiliado</div></td>
		  </tr>  
		</table>
		<p>
		  <strong>Dato</strong> 
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
		echo("<p> $cartel </p>");
		echo "Padrón de Busqueda <b>".$rowPadronBusqueda['mes']."-".$rowPadronBusqueda['anio']."</b>";
		if ($canEmpleados == 0) {
			print("<div style='color:#FF0000'><b> NO EXISTE AFILIADO CON ESTE FILTRO DE BUSQUEDA EN LA S.S.S. </b></div><br>");
		} else { ?>
			<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>			
			<table class="tablesorter" id="listaResultado" style="width:900px; font-size:14px">
			  <thead>
				<tr>
				  <th>C.U.I.L.</th>
				  <th>Apellido y Nombre</th>
				  <th>C.U.I.T.</th>
				  <th>Tipo y Nro Doc</th>
				  <th class="filter-select" data-placeholder="Seleccion Tipo">Tipo Afiliado</th>
				  <th class="filter-select" data-placeholder="Seleccion Sexo">Sexo</th>
				</tr>
			  </thead>
			  <tbody>
			<?php while($rowEmpleados = mysql_fetch_assoc($resEmpleados)) { ?>
				<tr align="center"> 
				  <td><?php echo $rowEmpleados['cuilfamiliar'];?></td>
				  <td><?php echo $rowEmpleados['apellidoynombre']; ?></td>
				  <td><?php echo $rowEmpleados['cuit'];?></td>	
				  <td><?php echo $rowEmpleados['tipodocumento'].": ".$rowEmpleados['nrodocumento'];?></td>
				  <td><?php if ($rowEmpleados['parentesco'] == 0) { echo 'TITULAR'; }  else { echo "FAMILIAR"; }?></td>
				  <td><?php echo $rowEmpleados['sexo']; ?></td>
				</tr>
			<?php } ?>
			  </tbody>
			</table>

 	 <?php } 
 	} ?>
  </div>
</form>
</body>
</html>
