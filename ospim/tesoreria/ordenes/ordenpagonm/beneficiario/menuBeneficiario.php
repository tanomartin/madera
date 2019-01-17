<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$noExiste = 0;
if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	if ($filtro == 0) {
		$cartel = "Resultados de Busqueda por Código de Beneficiario <b>'".$dato."'</b>";
	}
	if ($filtro == 1) {
		$cartel = "Resultados de Busqueda por Nombre o Dirgido A <b>'".$dato."'</b>";
	}
	$resultado = array();
	if (isset($dato)) {
		if ($filtro == 0) { $sqlPrestador = "SELECT * from prestadoresnm where codigo = $dato order by codigo DESC"; }
		if ($filtro == 1) { $sqlPrestador = "SELECT * from prestadoresnm where nombre like '%$dato%' or dirigidoa like '%$dato%' order by codigo DESC"; }
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
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="menuBeneficiario.php">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../moduloOrdenPagoNM.php'" /></p>
	  	<h3>Módulo Beneficiarios Ordenes de Pago </h3>  
	  	<p><input type="button" name="nuevo" value="Nuevo Beneficiario" onclick="location.href = 'nuevoBeneficiario.php' " /></p>
	  	<?php if ($noExiste == 1) { ?>
				<p style='color:#FF0000'><b> NO EXISTE BENEFICIARIO CON ESTE FILTRO DE BUSQUEDA </b></p>
		<?php } ?>   
      	<table width="400" border="0">
	      	<tr>
	        	<td rowspan="6"><div align="center"><strong>Buscar por </strong></div></td>
	        	<td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Código </div></td>
	      	</tr>
	      	<tr>
	        	<td><div align="left"><input type="radio" name="filtro" value="1" /> Nombre o Dirigido A</div></td>
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
						<th>Dirigido A</th>
						<th>Telefono</th>
						<th>E-mail</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
		  <?php while($rowPrestador = mysql_fetch_array($resPrestador)) { ?>
					<tr>
						<td><?php echo $rowPrestador['codigo'];?></td>
						<td><?php echo $rowPrestador['nombre'];?></td>
						<td><?php echo $rowPrestador['dirigidoa'];?></td>
						<td><?php echo $rowPrestador['telefono'];?></td>
						<td><?php echo $rowPrestador['email'];?></td>
						<td>
							<input name="ficha" type="button" value="Ficha" onclick="abrirPantalla('beneficiario.php?codigo=<?php echo $rowPrestador['codigo']; ?>&volver=0')"/> | 
							<input name="modif" type="button" value="Modificar" onclick="location.href = 'modificarBeneficiario.php?codigo=<?php echo $rowPrestador['codigo']; ?>'"/>
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
