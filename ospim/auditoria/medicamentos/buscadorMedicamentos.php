<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$numMedicamento = 0;
$arrayResultado = array();
if (isset($_POST['valor'])) {
	$seleccion = $_POST['seleccion'];
	$valor = $_POST['valor'];
	
	$selectMedicamento = "SELECT m.codigo, m.tipo, m.nombre, m.presentacion, m.precio, DATE_FORMAT(m.fecha, '%d-%m-%Y') AS fecha, m.baja FROM medicamentos m WHERE m.$seleccion = '$valor'";
	if ($seleccion == 'nombre') {
		$selectMedicamento = "SELECT m.codigo, m.tipo, m.nombre, m.presentacion, m.precio, DATE_FORMAT(m.fecha, '%d-%m-%Y') AS fecha, m.baja FROM medicamentos m WHERE m.$seleccion like '%$valor%'";
	}
	
	$resMedicamento = mysql_query($selectMedicamento,$db);
	$numMedicamento = mysql_num_rows($resMedicamento);
	if ($numMedicamento > 0) {
		while ($rowMedicamento = mysql_fetch_assoc($resMedicamento)) {
			$arrayResultado[$rowMedicamento['codigo'].$rowMedicamento['tipo']] = $rowMedicamento;
		}
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Medicamentos Alfa Beta :.</title>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">

function validar(formulario) {
	var opcion = formulario.seleccion.value;
	if (formulario.valor.value == "") {
		alert("Debe ingresar algun dato para la busqueda");
		document.getElementById("valor").focus();
		return false;
	} else {
		if(opcion == "codigo") {
			if(!esEnteroPositivo(formulario.valor.value)){
				alert("El código debe ser un numero entero");
				return false;
			}	
		}
	}

	$.blockUI({ message: "<h1>Buscando Medicamento. Aguarde por favor...</h1>" });
	return true;
};


$(function() {
	$("#lista")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
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
	}).tablesorterPager({container: $("#paginador")}); 
});

function abrirInfo(dire) {
	a= window.open(dire,"InfoMedicamentos",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>

<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="moduloABM" name="moduloABM" method="post"  onsubmit="return validar(this)" action="buscadorMedicamentos.php">
		<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuMedicamentos.php'" /></p>
		<h3>Buscador de Medicamentos e Insumos </h3> 
	    <p> <?php 
		    	if (sizeof($arrayResultado) == 0  && isset($_POST['valor'])) { ?>
				 <b style='color:#FF0000'> LA BUSQUEDA DE MEDICAMENTO NO GENERO RESULTADOS </b>
	      <?php } ?>
		</p>
		<p><b>Buscar por</b></p>
		<table>
			<tr>
				<td><input name="seleccion" type="radio" value="codigo" checked="checked"/></td>
				<td><div align="left">Código</div></td>
			</tr>
			<tr>
				<td><input name="seleccion" type="radio" value="nombre" /></td>
				<td><div align="left">Nombre</div></td>
			</tr>
		</table>
		<p><b>DATO: </b><input name="valor" id="valor" type="text" size="25" /></p>
		<p><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	</form>
<?php if ($numMedicamento > 0) { ?>
			<table style="text-align:center; width:1000px" id="lista" class="tablesorter" >
				<thead>
					<tr>
						<th>Código</th>
						<th>Tipo</th>
						<th>Nombre</th>
						<th>Presentacion</th>
						<th class="filter-select" data-placeholder="Seleccion">Prestación Activa</th>
						<th width="100px">Fecha</th>
						<th>Último Precio</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayResultado as $medicamento) { 
						$tipo = "AlfaBeta";
						if ($medicamento['tipo'] == 4) { $tipo = "Medicamento C.M."; }
						if ($medicamento['tipo'] == 5) { $tipo = "Insumo"; } ?>
					<tr>
						<td><?php echo $medicamento['codigo'] ?></td>	
						<td><?php echo $tipo ?></td>	
						<td><?php echo $medicamento['nombre'] ?></td>	
						<td><?php echo $medicamento['presentacion'] ?></td>	
						<td><?php if ($medicamento['baja'] == 1) { echo "NO"; } else { echo "SI"; } ?> </td>	
						<td><?php echo $medicamento['fecha'] ?></td>
						<td><?php echo number_format($medicamento['precio'],2,',','.')  ?></td>
						<td>
						<?php if ($medicamento['tipo'] == 3) { ?>
								<input type="button" name="info" id="info" value="+ INFO" onclick="javascript:abrirInfo('detalleMedicamento.php?codigo=<?php echo $medicamento['codigo']?>')" /></td>		
						<?php }?>
					</tr>
		    	<?php } ?>
				</tbody>
			</table>
			<div id="paginador" class="pager">
		<form>
			<p>
				<img src="img/first.png" width="16" height="16" class="first"/>
				<img src="img/prev.png" width="16" height="16" class="prev"/>
				<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
				<img src="img/next.png" width="16" height="16" class="next"/>
				<img src="img/last.png" width="16" height="16" class="last"/>
			</p>
			<p>
				<select class="pagesize">
					<option selected="selected" value="10">10 por pagina</option>
					<option value="20">20 por pagina</option>
					<option value="30">30 por pagina</option>
					<option value="50">50 por pagina</option>
					<option value="<?php echo $numMedicamento?>">Todos</option>
				</select>
			</p>
		</form>
	</div>
<?php } ?>
</div>
</body>
</html>