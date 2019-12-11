<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$noExiste = 0;
if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	if ($filtro == 0) {
		$cartel = "Resultados de Busqueda por Código de Prestador No medico <b>'".$dato."'</b>";
	}
	if ($filtro == 1) {
		$cartel = "Resultados de Busqueda por Nombre <b>'".$dato."'</b>";
	}
	if ($filtro == 2) {
		$cartel = "Resultados de Busqueda por C.U.I.T. <b>'".$dato."'</b>";
	}
	$resultado = array();
	if (isset($dato)) {
		if ($filtro == 0) { $sqlPrestador = "SELECT * from prestadores where codigoprestador = $dato and personeria = 5 order by codigoprestador DESC"; }
		if ($filtro == 1) { $sqlPrestador = "SELECT * from prestadores where personeria = 5 and nombre like '%$dato%' order by codigoprestador DESC"; }
		if ($filtro == 2) { $sqlPrestador = "SELECT * from prestadores where personeria = 5 and cuit = $dato order by codigoprestador DESC"; }
		$resPrestador = mysql_query($sqlPrestador,$db);
		$canPrestador = mysql_num_rows($resPrestador);
		if ($canPrestador == 0) {
			$noExiste = 1;
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Prestadores No Medico :.</title>
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
			headers:{3:{sorter:false, filter: false},4:{sorter:false, filter: false},5:{sorter:false, filter: false}},
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
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirPantalla(dire) {
	a= window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="menuPrestadores.php">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>
	  	<h3>Módulo Prestadores No Médicos</h3>  
	  	<p><input type="button" name="nuevo" value="Nuevo Prestador" onclick="location.href = 'nuevoPrestador.php' " /></p>
	  	<?php if ($noExiste == 1) { ?>
				<p style='color:#FF0000'><b> NO EXISTE PRESTADOR NO MEDICO CON ESTE FILTRO DE BUSQUEDA </b></p>
	  	<?php } ?>   
      	<table border="0">
	      	<tr>
	        	<td rowspan="3"><div align="center"><strong>Buscar por </strong></div></td>
	        	<td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Código </div></td>
	      	</tr>
	      	<tr>
	        	<td><div align="left"><input type="radio" name="filtro" value="1" /> Nombre </div></td>
	      	</tr>
	      	<tr>
	        	<td><div align="left"><input type="radio" name="filtro" value="2" /> C.U.I.T. </div></td>
	      	</tr>
		</table>
    	<p><b>Dato</b> <input name="dato" type="text" id="dato" size="14" /></p>
    	<p><input type="submit" name="Buscar" value="Buscar" /></p>
   
  <?php if ($noExiste == 0 and isset($dato)) { ?>
  			<p><?php echo $cartel ?></p>
   			<table style="text-align:center; width:1000px" id="listaResultado" class="tablesorter" >
				<thead>
					<tr>
						<th>Código</th>
						<th>Nombre</th>
						<th>C.U.I.T.</th>
						<th>Telefono</th>
						<th>E-mail</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
		  <?php while($rowPrestador = mysql_fetch_array($resPrestador)) { ?>
					<tr>
						<td><?php echo $rowPrestador['codigoprestador'];?></td>
						<td><?php echo $rowPrestador['nombre'];?></td>
						<td><?php echo $rowPrestador['cuit'];?></td>
						<td><?php echo $rowPrestador['telefono1'];?></td>
						<td><?php echo $rowPrestador['email1'];?></td>
						<td>
							<input name="ficha" type="button" value="Ficha" onclick="abrirPantalla('prestador.php?codigo=<?php echo $rowPrestador['codigoprestador']; ?>&volver=0')"/> | 
							<input name="modif" type="button" value="Modificar" onclick="location.href = 'modificarPrestador.php?codigo=<?php echo $rowPrestador['codigoprestador']; ?>'"/>
						</td>
					</tr>
		<?php } ?>
				</tbody>
  			</table>
  <?php } ?>
	</div>
</form>
</body>
</html>
