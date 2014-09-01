<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");

$dato = $_POST['dato'];
$filtro = $_POST['filtro'];

if ($filtro == 0) {
	$cartel = "Resultados de Busqueda por Código de Prestador <b>".$dato."</b>";
}
if ($filtro == 1) {
	$cartel = "Resultados de Busqueda por Nombre o Razón SSocial <b>".$dato."</b>";
}
if ($filtro == 2) {
	$cartel = "Resultados de Busqueda por C.U.I.T. <b>".$dato."</b>";
}

$noExiste = 0;
$resultado = array();
if (isset($dato)) {
	if ($filtro == 0) { $sqlPrestador = "SELECT * from prestadores where codigoprestador = $dato order by codigoprestador DESC"; }
	if ($filtro == 1) { $sqlPrestador = "SELECT * from prestadores where nombre like '%$dato%' order by codigoprestador DESC"; }
	if ($filtro == 2) { $sqlPrestador = "SELECT * from prestadores where cuit = $dato order by codigoprestador DESC"; }
	$resPrestador = mysql_query($sqlPrestador,$db); 
	$canPrestador = mysql_num_rows($resPrestador); 
	if ($canPrestador == 0) {
		$noExiste = 1;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Prestadores :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			headers:{5:{sorter:false, filter: false}},
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
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked) {
		resultado = esEnteroPositivo(formulario.dato.value);
		if (!resultado) {
			alert("El Código de Prestador debe ser un numero entero positivo");
			return false;
		} 
	}
	if (formulario.filtro[2].checked) {
		if (!verificaCuilCuit(formulario.dato.value)) {
			alert("C.U.I.T. invalido");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirPantalla(dire) {
	a= window.open(dire,"DetalleAutorizacion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onSubmit="return validar(this)" action="moduloAbmPrestadores.php">
  <div align="center">
	  <input type="reset" name="volver" value="Volver" onClick="location.href = '../menuPrestadores.php'" align="center"/>
	  <p class="Estilo1">M&oacute;dulo Prestadores </p>
	  <p><label><input type="button" name="nuevo" value="Nuevo Prestador" onClick="location.href = 'nuevoPrestador.php'" /></label></p>
	  <?php 
			if ($noExiste == 1) {
				print("<div style='color:#FF0000'><b> NO EXISTE PRESTADOR CON ESTE FILTRO DE BUSQUEDA </b></div><br>");
			}
	  ?>   
    <table width="400" border="0">
      <tr>
        <td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
        <td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Código </div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="1" /> Nombre o Razón Social</div></td>
      </tr>
      <tr>
        <td><div align="left"><input type="radio" name="filtro" value="2" /> C.U.I.T.</div></td>
      </tr> 
	</table>
    <p><strong>Dato</strong> <input name="dato" type="text" id="dato" size="14" /></p>
    <p><input type="submit" name="Buscar" value="Buscar" /></p>
   <?php if ($noExiste == 0 and isset($dato)) { ?>
  		<p><?php echo $cartel ?></p>
   <table style="text-align:center; width:800px" id="listaResultado" class="tablesorter" >
	<thead>
		<tr>
			<th>Código</th>
			<th>Nombre o Razón Social</th>
			<th>C.U.I.T.</th>
			<th>Telefono</th>
			<th>E-mail</th>
			<th>Acci&oacute;n</th>
		</tr>
	</thead>
	<tbody>
	<?php
		while($rowPrestador = mysql_fetch_array($resPrestador)) {
	?>
		<tr>
			<td><?php echo $rowPrestador['codigoprestador'];?></td>
			<td><?php echo $rowPrestador['nombre'];?></td>
			<td><?php echo $rowPrestador['cuit'];?></td>
			<td><?php echo $rowPrestador['telefono1'];?></td>
			<td><?php echo $rowPrestador['email'];?></td>
			<td><input name="perfil" type="button" value="Perfil" onclick="abrirPantalla('prestador.php?codigo=<?php echo $rowPrestador['codigoprestador'] ?>')"/> | <input name="modificar" type="button" value="Modificar" onclick="abrirPantalla('#')"/> </td>
		</tr>
	<?php
		}
	?>
	</tbody>
  </table>
  <?php } ?>
  </div>
</form>
</body>
</html>
