<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
set_time_limit(0);
if (isset($_POST['periodo'])) {
	$periodo = explode("-",$_POST['periodo']);
	$sqlDesempleo = "SELECT d.cuilbeneficiario, d.apellidoynombre, 
							d.fechacobro, d.anofinrelacion, 
							d.mesfinrelacion, d.fechainformesss,
							titulares.nroafiliado as nrotitu, titulares.situaciontitularidad as sitututi,
							titularesdebaja.nroafiliado as nrobaja, titularesdebaja.situaciontitularidad as situbaja
					FROM desempleosss d
					LEFT JOIN titulares on d.cuilbeneficiario = titulares.cuil
					LEFT JOIN titularesdebaja on d.cuilbeneficiario = titularesdebaja.cuil
					WHERE anodesempleo = ".$periodo[1]." and mesdesempleo = ".$periodo[0]." and parentesco = 0";	
	$resDesempleo = mysql_query($sqlDesempleo,$db);
	$canDesempleoTitu = mysql_num_rows($resDesempleo);
	
	$sqlDesempleoFami = "SELECT d.cuilbeneficiario, d.apellidoynombre, 
								d.fechacobro, d.anofinrelacion,
								d.mesfinrelacion, d.fechainformesss, 
								d.cuiltitular,
								familiares.nroafiliado as nrotitu, 
								familiaresdebaja.nroafiliado as nrobaja
						FROM desempleosss d
						LEFT JOIN familiares on d.cuilbeneficiario = familiares.cuil
						LEFT JOIN familiaresdebaja on d.cuilbeneficiario = familiaresdebaja.cuil
						WHERE anodesempleo = ".$periodo[1]." and mesdesempleo = ".$periodo[0]." and parentesco != 0";
	$resDesempleoFami = mysql_query($sqlDesempleoFami,$db);
	$canDesempleoFami = mysql_num_rows($resDesempleoFami);
	
	$sqlTipoBene = "SELECT * FROM tipotitular";
	$resTipoBene = mysql_query($sqlTipoBene,$db);
	$arrayTipo = array();
	while ($rowTipoBene  = mysql_fetch_assoc($resTipoBene)) { 
		$arrayTipo[(int)$rowTipoBene['codtiptit']] = $rowTipoBene['descrip'];
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Titulares alta SSS por Delegacion :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	if (formulario.periodo.value == 0) {
		alert("Debe elegir un Periodo");
		return false;
	}
	$.blockUI({ message: "<h1>Generando Listado<br>Aguarde por favor...</h1>" });
	return true;
}

$(function() {
	$("#tituDesemple")
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
	}).tablesorterPager({container: $("#paginadorTitu")}); 
	
	$("#famiDesemple")
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
	}).tablesorterPager({container: $("#paginadorFami")}); 
});


</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'" /></p>
  	<form  name="selectDelegacion" id="selectDelegacion" method="post" onsubmit="return validar(this)" action="desempleo.php">
	  	<h3>Listado Desempleo</h3>
		<select name="periodo" id="periodo" class="nover">
			<option value="0" selected="selected">Seleccione un Periodo </option>
			<?php $sqlPeriodo ="SELECT anodesempleo,mesdesempleo FROM desempleosss d
								GROUP BY anodesempleo, mesdesempleo 
								ORDER BY anodesempleo DESC, mesdesempleo DESC LIMIT 6";
				  $resPeriodo = mysql_query($sqlPeriodo,$db);
				  while ($rowPeriodo = mysql_fetch_array($resPeriodo)) { 	?>
					  <option value="<?php echo $rowPeriodo['mesdesempleo']."-".$rowPeriodo['anodesempleo'] ?>"><?php echo $rowPeriodo['mesdesempleo']."-".$rowPeriodo['anodesempleo']  ?></option>
		    <?php } ?>
		</select>
		<p><input type="submit" name="Submit" value="Listar Desempleo" class="nover"/></p>
  	</form>

<?php if (isset($_POST['periodo'])) { ?>
  			<h3>Periodo listado <?php echo $_POST['periodo'] ?></h3>
  			<h3>TITULARES [<?php echo $canDesempleoTitu?>]</h3>
  			<table style="text-align:center" id="tituDesemple" class="tablesorter" >
  				<thead>
	  				<tr>
	  					<td>Nro Afiliado</td>
	  					<td class="filter-select" data-placeholder="Seleccione">Estado</td>
	  					<td class="filter-select" data-placeholder="Seleccione">Tipo</td>
	  					<td>C.U.I.L.</td>
	  					<td>Apellido y Nombre</td>
	  					<td>Fecha Cobro</td>
	  					<td>Fecha Informe</td>
	  				</tr>
  				</thead>
  				<tbody>
  				<?php while ($rowDesempleo = mysql_fetch_assoc($resDesempleo)) {  ?>
  						<tr>
  							<td><?php echo $rowDesempleo['nrotitu'].$rowDesempleo['nrobaja']; ?></td>
  						<?php $estado = "NO EMPADRONADO";
  							  if ($rowDesempleo['nrotitu'] != null) { $estado = "ACTIVO"; }
				 			  if ($rowDesempleo['nrobaja'] != null) { $estado = "DE BAJA"; } ?>
  							<td><?php echo $estado ?></td>
  						<?php $situ = "SIN INFORMACION";
  							  if ($rowDesempleo['sitututi'] != null) { $situ = $arrayTipo[$rowDesempleo['sitututi']]; }
				 			  if ($rowDesempleo['situbaja'] != null) { $situ = $arrayTipo[$rowDesempleo['situbaja']]; } ?>
  							<td><?php echo $situ ?></td>
  							<td><?php echo $rowDesempleo['cuilbeneficiario'] ?></td>
  							<td><?php echo $rowDesempleo['apellidoynombre'] ?></td>
  							<td><?php echo $rowDesempleo['fechacobro'] ?></td>
  							<td><?php echo $rowDesempleo['fechainformesss'] ?></td>
  						</tr>
  				<?php } ?>
  				</tbody>
  			</table>
  			<div id="paginadorTitu" class="pager">
				<form>
					<p align="center">
						<img src="../img/first.png" width="16" height="16" class="first"/>
						<img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
						<img src="../img/next.png" width="16" height="16" class="next"/>
						<img src="../img/last.png" width="16" height="16" class="last"/>
					</p>
					<p align="center">
						<select class="pagesize">
							<option selected value="10">10 por pagina</option>
							<option value="20">20 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="50">50 por pagina</option>
							<option value="<?php echo $canDesempleoTitu;?>">Todos</option>
						</select>
					</p>
				</form>
			</div>
  			
  			<h3>FAMILIARES [<?php echo $canDesempleoFami?>]</h3>
  			<table style="text-align:center" id="famiDesemple" class="tablesorter" >
  				<thead>
	  				<tr>
	  					<td>Nro Afiliado</td>
	  					<td  class="filter-select" data-placeholder="Seleccione">Estado</td>
	  					<td>C.U.I.L.</td>
	  					<td>Apellido y Nombre</td>
	  					<td>C.U.I.L. Titular</td>
	  					<td>Fecha Cobro</td>
	  					<td>Fecha Informe</td>
	  				</tr>
  				</thead>
  				<tbody>
  				<?php while ($rowDesempleoFami = mysql_fetch_assoc($resDesempleoFami)) {  ?>
  						<tr>
  							<td><?php echo $rowDesempleoFami['nrotitu'].$rowDesempleoFami['nrobaja']; ?></td>
  						<?php $estado = "NO EMPADRONADO";
  							  if ($rowDesempleoFami['nrotitu'] != null) { $estado = "ACTIVO"; }
				 			  if ($rowDesempleoFami['nrobaja'] != null) { $estado = "DE BAJA"; } ?>
  							<td><?php echo $estado ?></td>
  							<td><?php echo $rowDesempleoFami['cuilbeneficiario'] ?></td>
  							<td><?php echo $rowDesempleoFami['apellidoynombre'] ?></td>
  							<td><?php echo $rowDesempleoFami['cuiltitular'] ?></td>
  							<td><?php echo $rowDesempleoFami['fechacobro'] ?></td>
  							<td><?php echo $rowDesempleoFami['fechainformesss'] ?></td>
  						</tr>
  				<?php } ?>
  				</tbody>
  			</table>
  			<div id="paginadorFami" class="pager">
				<form>
					<p align="center">
						<img src="../img/first.png" width="16" height="16" class="first"/>
						<img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input type="text" class="pagedisplay" size="8" readonly="readonly" style="background:#CCCCCC; text-align:center"/>
						<img src="../img/next.png" width="16" height="16" class="next"/>
						<img src="../img/last.png" width="16" height="16" class="last"/>
					</p>
					<p align="center">
						<select class="pagesize">
							<option selected value="10">10 por pagina</option>
							<option value="20">20 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="50">50 por pagina</option>
							<option value="<?php echo $canDesempleoFami;?>">Todos</option>
						</select>
					</p>
				</form>
			</div>
<?php } ?>
  	
</div>
</body>
</html>