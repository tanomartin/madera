<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

if (isset($_POST['delega'])) {
	$deleToSplit = $_POST['delega'];
	$deleArray = explode("|",$deleToSplit);
	$delegacion = $deleArray[0];
	$wheredelega = "";
	if ($delegacion != '0') {
		$wheredelega = "jurisdiccion.codidelega = $delegacion and ";
		$sqlJurisPrincipal = "SELECT * FROM jurisdiccion j ORDER BY cuit, disgdinero ASC";
		$resJurisPrincipal = mysql_query($sqlJurisPrincipal,$db);
		$jurisdiccion = array();
		while($rowJurisPrincipal = mysql_fetch_assoc($resJurisPrincipal)) {
			$cuit = $rowJurisPrincipal['cuit'];
			$jurisdiccion[$cuit] = $rowJurisPrincipal['codidelega'];
		}
	}
	$estadoToSplit = $_POST['estado'];
	$estadoArray = explode("|",$estadoToSplit);
	$estado = $estadoArray[0];
	$whereestado = "";
	if ($estado != 'x') {
		$whereestado = "cabacuerdosusimra.estadoacuerdo = $estado and ";
	}
	$sqlConsulta = "SELECT
						cabacuerdosusimra.nroacuerdo,
						cabacuerdosusimra.cuit,
						DATE_FORMAT(cabacuerdosusimra.fechaacuerdo,'%d-%m-%Y') as fechaacuerdo,
						tiposdeacuerdos.descripcion as tipoacuerdo,
						estadosdeacuerdos.descripcion as estado,
						jurisdiccion.codidelega as codidelega,
						empresas.nombre as nombreactivo,
						empresasdebaja.nombre as nombreinactivo
					FROM estadosdeacuerdos, tiposdeacuerdos, cabacuerdosusimra
					LEFT JOIN jurisdiccion ON cabacuerdosusimra.cuit = jurisdiccion.cuit
					LEFT JOIN empresas ON jurisdiccion.cuit = empresas.cuit
					LEFT JOIN empresasdebaja ON jurisdiccion.cuit = empresasdebaja.cuit
					WHERE
						$wheredelega $whereestado
						cabacuerdosusimra.tipoacuerdo = tiposdeacuerdos.codigo and
						cabacuerdosusimra.estadoacuerdo = estadosdeacuerdos.codigo";
	$resConsulta = mysql_query($sqlConsulta,$db);
	$canConsulta = mysql_num_rows($resConsulta);
	if ($canConsulta > 0) {
		$arrayResultado = array();
		$i = 0;
		while($rowConsulta = mysql_fetch_assoc($resConsulta)) {
			if ($delegacion != '0') {
				$cuit = $rowConsulta['cuit'];
				if ($jurisdiccion[$cuit] == $delegacion) {
					$arrayResultado[$i] = $rowConsulta;
				}
			} else {
				$arrayResultado[$i] = $rowConsulta;
			}
			$i++;
		}
	}
	
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Acuerdos por Delegacion :.</title>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$("#listado")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra","filter"],
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
		.tablesorterPager({container: $("#paginador")}); 
	});

	function validar(formulario) {
		var estadoArray = formulario.estado.value.split("|");
		var estado = estadoArray[0];
		if (formulario.delega.value == 0 && (estado == 'x' || estado == 0 || estado == 1)) {		
			alert("Debe seleccionar la delegacion para relizar la busqueda del estado seleccionado");
			return false;
		}
		$.blockUI({ message: "<h1>Realizando Busqueda. Aguarde por favor...</h1>" });
		return true;		
	}
</script>

</head>

<body bgcolor="#B2A274">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'"/></p>
  	<h3>Acuerdos por Delegación</h3>
  	<form id="acupordele" name="acupordele" method="post" onsubmit="return validar(this)" action="acupordelega.php">
		<p>
			<b>Delegación</b>
			<select id="delega" name="delega">
				<option value='0'>Seleccione Delegación</option>
			<?php   $sqlDelega = "SELECT codidelega,nombre FROM delegaciones d WHERE codidelega not in(1000,1001,3500,4000,4001)";
					$resDelega = mysql_query($sqlDelega,$db);
					while($rowDelega = mysql_fetch_assoc($resDelega)) { ?>
						<option value='<?php echo $rowDelega['codidelega']."|".$rowDelega['nombre']?>'><?php echo $rowDelega['codidelega']." - ".$rowDelega['nombre']?></option>
			<?php	} ?>
			</select>
		</p>
		<p>
			<b>Estado</b>
			<select id="estado" name="estado">
				<option value='x|seleccion'>Seleccione Estado</option>
			<?php   $sqlEstado = "SELECT * FROM estadosdeacuerdos";
					$resEstado = mysql_query($sqlEstado,$db);
					while($rowEstado = mysql_fetch_assoc($resEstado)) { ?>
						<option value='<?php echo $rowEstado['codigo']."|".$rowEstado['descripcion'] ?>'><?php echo $rowEstado['descripcion']?></option>
			<?php	} ?>
			</select>
		</p>
		<p><input type="submit" name="buscar" value="Buscar"/></p>
	</form>
	<?php 
		if (isset($_POST['delega'])) { ?>
			<h3>Resultado de Busqueda de Acuerdos</h3> 
			<h3 style="color: blue">
		 <?php if ($delegacion != 0) { ?>
				Delegacion: <?php echo $deleArray[1]." (".$delegacion.")"?><br/> 
		 <?php } if ($estado != 'x') { ?>
			    Estado: <?php echo $estadoArray[1]?>
		  <?php } ?>
	  		</h3>
	<?php   if ($canConsulta > 0) { ?>
				<table class="tablesorter" id="listado" style="width:1100px; font-size:14px; text-align: center">
					<thead>
						<tr>
							<th>C.U.I.T.</th>
							<th>Razon Social</th>
							<th class="filter-select" data-placeholder="Seleccione Delega">Delegacion</th>
							<th class="filter-select" data-placeholder="Seleccione Estado">Estado Empresa</th>
							<th>Nro.</th>
							<th>Fecha</th>
							<th class="filter-select" data-placeholder="Seleccione Tipo">Tipo</th>
							<th class="filter-select" data-placeholder="Seleccione Estado">Estado</th>
						</tr>
					</thead>
					<tbody>
			    <?php foreach ($arrayResultado as $resultado) { ?>
			    		<tr>
			    			<td><?php echo $resultado['cuit']?></td>
			    			<td><?php echo $resultado['nombreactivo'].$resultado['nombreinactivo'] ?></td>
			    			<td><?php echo $resultado['codidelega']?></td>
			    			<?php $estadoempre = "ACTIVA"; if ($resultado['nombreinactivo'] != null) { $estadoempre = "INACTIVA"; } ?>
			    			<td><?php echo $estadoempre ?></td>
			    			<td><?php echo $resultado['nroacuerdo']?></td>
			    			<td><?php echo $resultado['fechaacuerdo']?></td>
			    			<td><?php echo $resultado['tipoacuerdo']?></td>
			    			<td><?php echo $resultado['estado']?></td>
			    		</tr>
			    <?php } ?>
			    	</tbody>
				</table>
				<table width="245" border="0">
			     	<tr>
			        	<td width="239">
							<div id="paginador" class="pager">
						  		<form>
									<p align="center">
							  			<img src="img/first.png" width="16" height="16" class="first"/> <img src="img/prev.png" width="16" height="16" class="prev"/>
							  			<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						    			<img src="img/next.png" width="16" height="16" class="next"/> <img src="img/last.png" width="16" height="16" class="last"/>
						    			<select name="select" class="pagesize">
										      <option selected="selected" value="10">10 por pagina</option>
										      <option value="20">20 por pagina</option>
										      <option value="30">30 por pagina</option>
											  <option value="50">50 por pagina</option>
										      <option value="<?php echo $canConsulta;?>">Todos</option>
						      			</select>
						    		</p>
									<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="right"/></p>
						  		</form>	
							</div>
						</td>
			      	</tr>
			  	</table>
				
	<?php	} else { ?>
 				<h3 style="color: red">No Existen Acuerdos con los filtros utilizados</h3>
	<?php	}
	} ?>
</div>
</body>
</html>