<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

$noExiste = 0;
if (isset($_POST['dato'])) {
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	
	if ($filtro == 0) {
		$cartel = "Resultados de Busqueda por Nro. Control <b>'".$dato."'</b>";
	}
	if ($filtro == 1) {
		$cartel = "Resultados de Busqueda por Nro. Cheque <b>'".$dato."'</b>";
	}
	if ($filtro == 2) {
		$cartel = "Resultados de Busqueda por C.U.I.T. <b>'".$dato."'</b>";
	}
	
	$tipoBoletas = "";
	$resultado = array();
	if (isset($dato)) {
		if ($filtro == 0) { $sqlBoletas = "SELECT * from boletasusimra where nrocontrol = $dato"; }
		if ($filtro == 1) { $sqlBoletas = "SELECT * from boletasusimra b, cuoacuerdosusimra c where c.chequenro = '$dato' and c.cuit = b.cuit and c.nroacuerdo = b.nroacuerdo and c.nrocuota = b.nrocuota"; }
		if ($filtro == 2) { $sqlBoletas = "SELECT * from boletasusimra where cuit = $dato"; }
		$resBoletas = mysql_query($sqlBoletas,$db); 
		$canBoletas = mysql_num_rows($resBoletas); 
		if ($canBoletas != 0) {
			$tipoBoletas = "Generada";
			while($rowBoletas = mysql_fetch_array($resBoletas)) {
				$id = $rowBoletas['idboleta'];
				$resultado[$id] = array('nrocontrol' => $rowBoletas['nrocontrol'], 'cuit' => $rowBoletas['cuit'], 'acuerdo' =>  $rowBoletas['nroacuerdo'], 'cuota' =>  $rowBoletas['nrocuota'], 'importe' => $rowBoletas['importe'], 'estado' => $tipoBoletas);
			}
		}
		
		if ($filtro == 0) { $sqlBoletasValidas = "SELECT * from validasusimra where nrocontrol = $dato"; }
		if ($filtro == 1) { $sqlBoletasValidas = "SELECT * from validasusimra b, cuoacuerdosusimra c where c.chequenro = '$dato' and c.cuit = b.cuit and c.nroacuerdo = b.nroacuerdo and c.nrocuota = b.nrocuota"; }
		if ($filtro == 2) { $sqlBoletasValidas = "SELECT * from validasusimra where cuit = $dato"; }
		$resBoletasValidas = mysql_query($sqlBoletasValidas,$db); 
		$canBoletasValidas = mysql_num_rows($resBoletasValidas); 
		if ($canBoletasValidas != 0) {
			$tipoBoletas = "Validada";
			while($rowBoletasValidas = mysql_fetch_array($resBoletasValidas)) {
				$id = $rowBoletasValidas['idboleta'];
				$resultado[$id] = array('nrocontrol' => $rowBoletasValidas['nrocontrol'], 'cuit' => $rowBoletasValidas['cuit'], 'acuerdo' =>  $rowBoletasValidas['nroacuerdo'], 'cuota' =>  $rowBoletasValidas['nrocuota'], 'importe' => $rowBoletasValidas['importe'], 'estado' => $tipoBoletas);
			}
		}
		
		
		if ($filtro == 0) { $sqlBoletasAnuladas = "SELECT * from anuladasusimra where nrocontrol = $dato"; }
		if ($filtro == 1) { $sqlBoletasAnuladas = "SELECT * from anuladasusimra b, cuoacuerdosusimra c where c.chequenro = '$dato' and c.cuit = b.cuit and c.nroacuerdo = b.nroacuerdo and c.nrocuota = b.nrocuota"; }
		if ($filtro == 2) { $sqlBoletasAnuladas = "SELECT * from anuladasusimra where cuit = $dato"; }
		$resBoletasAnuladas = mysql_query($sqlBoletasAnuladas,$db); 
		$canBoletasAnuladas = mysql_num_rows($resBoletasAnuladas); 
		if ($canBoletasAnuladas != 0) {
			$tipoBoletas = "Anulada";
			while($rowBoletasAnuladas = mysql_fetch_array($resBoletasAnuladas)) {
				$id = $rowBoletasAnuladas['idboleta'];
				$resultado[$id] = array('nrocontrol' => $rowBoletasAnuladas['nrocontrol'], 'cuit' => $rowBoletasAnuladas['cuit'], 'acuerdo' =>  $rowBoletasAnuladas['nroacuerdo'], 'cuota' =>  $rowBoletasAnuladas['nrocuota'], 'importe' => $rowBoletasAnuladas['importe'], 'estado' => $tipoBoletas);
			}
		} 
		
		$controlCantidad = (int)($canBoletas + $canBoletasValidas + $canBoletasAnuladas);
		if ($controlCantidad == 0) {
			$noExiste = 1;
		}
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo USIMRA :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">

	$(function() {
		$("#listaResultado")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			headers:{6:{sorter:false, filter:false}},
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
	return true;
}

function abrirDetalle(dire) {
	a= window.open(dire,"DetalleBoleta",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>
</head>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorBoleta.php">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuBoletas.php'"/></p>
		<h3>Módulo Buscador de Bolestas</h3>
		<?php 	if ($noExiste == 1) { ?>
					<p style='color:#FF0000'><b>NO EXISTE BOLETA CON ESTE FILTRO DE BUSQUEDA</b></p>
		<?php	}  ?>
		<table>
		    <tr>
		       <td rowspan="3"><div align="center"><strong>Buscar por </strong></div></td>
		       <td><div align="left"><input type="radio" name="filtro"  value="0" checked="checked" /> Nro Control </div></td>
		    </tr>
		    <tr>
		        <td><div align="left"><input type="radio" name="filtro" value="1" /> Nro Cheque </div></td>
		    </tr>
		    <tr>
		        <td><div align="left"><input type="radio" name="filtro" value="2" />C.U.I.T.</div></td>
		    </tr>
		</table>
	    <p><b>Dato</b> <input name="dato" type="text" id="dato" size="14" /></p>
	  	<p><input type="submit" name="Buscar" value="Buscar" /></p>
	    <?php if ($noExiste == 0 and isset($dato)) { ?>
	   	<p><?php echo $cartel ?></p>
	  	<table class="tablesorter" id="listaResultado" style="width:800px">
		  <thead>
			<tr>
				<th>Nro. Control</th>
				<th>C.U.I.T.</th>
				<th>Acuerdo</th>
				<th>Cuota</th>
				<th>Importe</th>
				<th class="filter-select" data-placeholder="Seleccion Estado">Estado</th>
				<th></th>
			</tr>
		</thead>
	<?php foreach($resultado as $boleta) { ?>
			<tr align="center">
				<td><?php echo $boleta['nrocontrol'];?></td>	
				<td><?php echo $boleta['cuit'];?></td>	
				<td><?php echo $boleta['acuerdo'];?></td>	
				<td><?php echo $boleta['cuota'];?></td>	
				<td><?php echo $boleta['importe'];?></td>	
				<td><?php echo $boleta['estado'];?></td>
				<td><input type="button" onclick="javascript:abrirDetalle('detalleBoleta.php?nrocontrol=<?php echo $boleta['nrocontrol']?>&estado=<?php echo $boleta['estado'] ?>')" value="Detalle" /></td>
			</tr>
		<?php } ?>
	  </table>
	<?php } ?>
	</div>
</form>
</body>
</html>
