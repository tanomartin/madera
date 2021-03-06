<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Empresas :.</title>

<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}

.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet"
	href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script type="text/javascript">

$(function() {
	$("#listado")
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
	});
});

function validar(formulario) {
	if (formulario.delegacion.value == 0) {
		alert("Debe elegir una Delegación");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Listado<br>Aguarde por favor...</h1>" });
	return true;
}

</script>
</head>

<body style="background-color: <?php echo $bgcolor ?>">
	<div align="center">
		<form name="listadoEmpresa" id="listadoEmpresa" method="post"
			onsubmit="return validar(this)"
			action="listadoEmpresas.php?origen=<?php echo $origen ?>">
			<p>
				<input type="reset" name="volver" value="Volver" class="nover"
					onclick="location.href = '../menuEmpresa.php?origen=<?php echo $origen ?>'" />
			</p>
			<p>
				<span class="Estilo2">Empresas por Delegaci&oacute;n </span>
			</p>
			<table>
				<tr>
					<td width="96" class="nover"><strong>Delegación</strong>
					</td>
					<td width="177"><div align="left">
							<select name="delegacion" id="delegacion" class="nover">
								<option value="0" selected="selected">Seleccione un Valor</option>
								<?php 
								$sqlDele="select codidelega,nombre from delegaciones where codidelega not in (1000,1001,3500)";
								$resDele= mysql_query($sqlDele,$db);
						while ($rowDele=mysql_fetch_array($resDele)) { 	?>
								<option value="<?php echo $rowDele['codidelega'] ?>"><?php echo $rowDele['nombre']  ?></option>
								<?php } ?>
							</select>
						</div></td>
				</tr>
			</table>
			<p>
				<input type="submit" name="Submit" value="Listar" class="nover" />
			</p>

			<?php if (isset($_POST['delegacion'])) { 
				$codidelega = $_POST['delegacion'];
				$sqlDele="select nombre from delegaciones where codidelega = $codidelega";
				$resDele= mysql_query($sqlDele,$db);
				$rowDele=mysql_fetch_array($resDele); ?>

			<p>
				<span class="Estilo2"><?php echo $rowDele['nombre'] ?> </span>
			</p>

			<table class="tablesorter" id="listado"
				style="width: 1024px; font-size: 14px">
				<thead>
					<tr>
						<th>C.U.I.T.</th>
						<th>Razón Social</th>
						<th>Domicilio Legal</th>
						<th class="filter-select" data-placeholder="Seleccione Localidad">Localidad
							Legal</th>
						<th>Domicilio Real</th>
						<th class="filter-select" data-placeholder="Seleccione Localidad">Localidad
							Real</th>
						<th>Fecha Registro</th>
						<th>Disg. Dinero</th>
						<th>Otra Juris. (Disg. Dinero)</th>
					</tr>
				</thead>
				<tbody>
					<?php $sqlEmpre = "SELECT e.cuit, e.nombre, e.domilegal, e.numpostal as numlegal, e.fecharegistro as creacion, l.nomlocali as localidad, j.domireal, j.numpostal as numreal, lreal.nomlocali as localidadReal, j.disgdinero 
											FROM empresas e, jurisdiccion j, localidades l, localidades lreal 
											WHERE j.codidelega = $codidelega and j.cuit = e.cuit and e.codlocali = l.codlocali and j.codlocali = lreal.codlocali";
					$resEmpre = mysql_query($sqlEmpre,$db);
					$whereIn = "(";
					while ($rowEmpre = mysql_fetch_assoc($resEmpre)) { 
						$cuit =  $rowEmpre['cuit'];
						$arrayEmpresa[$cuit] = array('nombre'=>$rowEmpre['nombre'], 
													 'domilegal'=> $rowEmpre['domilegal']." [".$rowEmpre['numlegal']."]", 
													 'localidadlegal' => $rowEmpre['localidad'], 
													 'domireal' => $rowEmpre['domireal']." [".$rowEmpre['numreal']."]", 
													 'localidadreal'=>$rowEmpre['localidadReal'], 
													 'fechacreacion' => substr($rowEmpre['creacion'],0,10),
													 'disgdinero' => $rowEmpre['disgdinero']."%");
						$whereIn .= "'".$cuit."',";
					}		
					$whereIn = substr($whereIn, 0, -1);
					$whereIn .= ")";
					
					$sqlOtraJuris = "select d.nombre, j.disgdinero, j.codidelega, j.cuit from jurisdiccion j, delegaciones d where cuit in $whereIn and j.codidelega != $codidelega and j.codidelega not in (1000,1001,3500) and j.codidelega = d.codidelega";
					$resOtraJuris = mysql_query($sqlOtraJuris,$db);
					$canOtraJuris = mysql_num_rows($resOtraJuris);
					if ($canOtraJuris > 0) {
						while ($rowOtraJuris = mysql_fetch_assoc($resOtraJuris)) {
							$arrayJuris[$rowOtraJuris['cuit']][$rowOtraJuris['codidelega']] = array('nombre'=>$rowOtraJuris['nombre'], 'disgdinero'=>$rowOtraJuris['disgdinero']);
						}
					}
					
					foreach($arrayEmpresa as $cuit=>$empresa) {
					?>
					
					<tr align="center">
						<td><?php echo $cuit ?></td>
						<td><?php echo $empresa['nombre'] ?></td>
						<td><?php echo $empresa['domilegal'] ?></td>
						<td><?php echo $empresa['localidadlegal'] ?></td>
						<td><?php echo $empresa['domireal'] ?></td>
						<td><?php echo $empresa['localidadreal'] ?></td>
						<td><?php echo $empresa['fechacreacion'] ?></td>
						<td><?php echo $empresa['disgdinero'] ?></td>
						<td><?php
								if (array_key_exists($cuit, $arrayJuris)) {
									foreach($arrayJuris[$cuit] as $jurisdiccion) {
										echo $jurisdiccion['nombre']. " (".$jurisdiccion['disgdinero']."%)<br>";
									}
								} else {
									echo "-";	
								}
							?>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<input class="nover" type="button" name="imprimir" value="Imprimir"
				onclick="window.print();"/>
			<?php } ?>
		</form>
	</div>
</body>
</html>
