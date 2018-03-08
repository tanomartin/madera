<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."claves.php"); 

$maquina = $_SERVER ['SERVER_NAME'];
$hostaplicativo = $hostUsimra;
if(strcmp("localhost",$maquina)==0) {
	$hostaplicativo = "localhost";
}
$usuarioaplicativo = $usuarioUsimra;
$claveaplicativo = $claveUsimra;
$dbaplicativo =  mysql_connect($hostaplicativo, $usuarioaplicativo, $claveaplicativo);
if (!$dbaplicativo) {
	die('No pudo conectarse: ' . mysql_error());
}
$dbnameaplicativo = $baseUsimraNewAplicativo;
mysql_select_db($dbnameaplicativo);

$sqlMinimo = "SELECT DATE_FORMAT(m.fecha, '%d/%m/%Y %H:%i:%s') as fecha, m.usuario, m.nrcuit , e.nombre 
					FROM empresassinminimo m, empresa e WHERE m.nrcuit = e.nrcuit";
$resMinimo = mysql_query($sqlMinimo, $dbaplicativo);
$canMinimo = mysql_num_rows($resMinimo);

$sqlMinimoHistorico = "SELECT DATE_FORMAT(m.fecha, '%d/%m/%Y %H:%i:%s') as fecha, m.usuario, m.nrcuit , e.nombre,
							  DATE_FORMAT(m.fechabaja, '%d/%m/%Y %H:%i:%s') as fechabaja, m.usuariobaja
						FROM empresassinminimohistorico m, empresa e WHERE m.nrcuit = e.nrcuit ORDER BY id DESC";
$resMinimoHistorico = mysql_query($sqlMinimoHistorico, $dbaplicativo);
$canMinimoHistorico = mysql_num_rows($resMinimoHistorico);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Minimo DDJJ :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript" ></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript" ></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"  type="text/javascript" ></script> 
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

$(document).ready(function(){
	$("#buscar").click(function() {
		$("#error").html('');	
		var cuit = $("#cuit").val();
		if (cuit != "") {
			$.blockUI({ message: "<h1>Buscando Empresa en Aplicativo DDJJ. <br> Aguarde por favor</h1>" });
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getEmpresa.php",
				data: {cuit:cuit},
			}).done(function(respuesta) {
				$.unblockUI();
				if (respuesta == 1 || respuesta == 2) {
					if (respuesta == 1) {
						$("#error").html("CUIT NO ENCONTRADO EN APLICATIVO ONLINE D.D.J.J.");
					} else {
						$("#error").html("CUIT YA HABILITADO PARA PAGAR BAJO EL MINIMO");
					}
				} else {
					var r = confirm("Desea dar autorizacion C.U.I.T.: "+cuit+" Razon Social: "+respuesta);
					if (r == true) {
						$.blockUI({ message: "<h1>Autorizando pago bajo el mínimo. <br> Aguarde por favor</h1>" });
						var redireccion = "nuevoMinimo.php?cuit="+cuit;
						location.href=redireccion;
					}
				}	
			});
		} else {
			alert("Debe ingresar el C.U.I.T. a buscar");
		}
	});
});

$(function() {
	$("#listador")
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

	$("#listadorHistorico")
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
	}).tablesorterPager({container: $("#paginadorHistorico")}); 
});

function bajarAutorizacion(cuit) {
	var r = confirm("Desea dar de baja la autorizacion del C.U.I.T. "+cuit);
	if (r == true) {
		$.blockUI({ message: "<h1>Bajando Autorizacion. <br> Aguarde por favor</h1>" });
		var redireccion = "eliminarMinimo.php?cuit="+cuit;
		location.href=redireccion;
	}
}

</script>
</head>
<body bgcolor="#B2A274">
	<div align="center">	
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAportes.php'" /></p> 
		<h3>HABILITAR EMPRESA DDJJ DEBAJO DEL MINIMO</h3>
		<h3>Habilitar Nueva Empresa</h3>
		<div id="error" style="color: DarkRed"></div>
		<p>C.U.I.T.: <input name="cuit" id="cuit" type="text" size="10" /></p>
		<p><input type="button" id="buscar" name="buscar" value="Buscar" /></p>
		<h3>Empresas Habilitadas</h3>
		<p>(Valor inferior límite del pago mínimo $80)</p>
		  <?php if ($canMinimo > 0) { ?>
		 			<table id="listador" class="tablesorter" style="width:1000px; font-size:14px; text-align: center;">
		 				<thead>
		 					<tr>
		 						<th>C.U.I.T.</th>
		 						<th>Razon Social</th>
		 						<th>Fecha Autorizacion</th>
		 						<th>Usuario Autorizacion</th>
		 						<th></th>
		 					</tr>
		 				</thead>
			  <?php while ($rowMinimo = mysql_fetch_array($resMinimo)) { ?>
						<tbody>
							<tr>
								<td><?php echo $rowMinimo['nrcuit'] ?></td>
								<td><?php echo $rowMinimo['nombre'] ?></td>
								<td><?php echo $rowMinimo['fecha'] ?></td>
								<td><?php echo $rowMinimo['usuario'] ?></td>
								<td><button id="baja" name="baja" onclick="bajarAutorizacion('<?php echo $rowMinimo['nrcuit'] ?>')">BAJAR AUTORIZACION</button></td>
							</tr>
						</tbody>
			  <?php } ?>
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
									<option value="<?php echo $canMinimo?>">Todos</option>
								</select>
							</p>
						</form>
					</div>
		  <?php } else { ?>
					<h3 style="color: blue">No existen empresas habilitadas para pagar bajo el minimo</h3>
		  <?php } ?>
		  
		  <h3>Historial de Habilitacioes</h3>
		  <?php if ($canMinimoHistorico > 0) { ?>
		 			<table id="listadorHistorico" class="tablesorter" style="width:1000px; font-size:14px; text-align: center;">
		 				<thead>
		 					<tr>
		 						<th>C.U.I.T.</th>
		 						<th>Razon Social</th>
		 						<th>Fecha Autorizacion</th>
		 						<th>Usuario Autorizacion</th>
		 						<th>Fecha Baja</th>
		 						<th>Usuario Baja</th>
		 					</tr>
		 				</thead>
			  <?php while ($rowMinimoHistorico = mysql_fetch_array($resMinimoHistorico)) { ?>
						<tbody>
							<tr>
								<td><?php echo $rowMinimoHistorico['nrcuit'] ?></td>
								<td><?php echo $rowMinimoHistorico['nombre'] ?></td>
								<td><?php echo $rowMinimoHistorico['fecha'] ?></td>
								<td><?php echo $rowMinimoHistorico['usuario'] ?></td>
								<td><?php echo $rowMinimoHistorico['fechabaja'] ?></td>
								<td><?php echo $rowMinimoHistorico['usuariobaja'] ?></td>
							</tr>
						</tbody>
			  <?php } ?>
			  		</table>
			  		<div id="paginadorHistorico" class="pager">
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
									<option value="<?php echo $canMinimoHistorico?>">Todos</option>
								</select>
							</p>
						</form>
					</div>
		  <?php } else { ?>
					<h3 style="color: blue">No existen historial de habilitaciones</h3>
		  <?php } ?>
		</div>
	
</body>
</html>