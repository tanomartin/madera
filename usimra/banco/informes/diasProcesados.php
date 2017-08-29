<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");  
include($libPath."fechas.php"); 

$diasArray = array("Domingo","Lunes","Martes","Mi&eacute;rcoles","Jueves","Viernes","S&aacute;bado");

$sqlPeriodos = "SELECT mes, ano from diasbancousimra GROUP BY ano, mes ORDER BY ano DESC, mes DESC";
$resPeriodos = mysql_query($sqlPeriodos,$db); 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script type="text/javascript">

	$(function() {
		$("#listado").tablesorter({
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
		})
	});

	function validar(formulario) {
		if (formulario.periodo.value == 0) {
			alert("Debe Seleccionar un Período");
			return false;
		}
		return true;
	}

</script>


<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Consulta dias procesos :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloInformes.php'"/>
  <p><span class="Estilo1">Consulta de d&iacute;as procesados por Per&iacute;odo </span> </p>
</div>
<form id="anulacion" name="anulacion" method="post" onsubmit="return validar(this)" action="diasProcesados.php">
  <div align="center">
            <select name="periodo" id="periodo">
				<option value='0'>Seleccione Período</option>
				<?php
					while($rowPeriodos = mysql_fetch_assoc($resPeriodos)) {
						$dato = $rowPeriodos['mes']."-".$rowPeriodos['ano'];
						print("<option value=$dato>$dato </option>");
					}
				?>
            </select>
         <p><input type="submit" name="anular" value="Consultar" /></p>   
	<?php 
		if(isset($_POST['periodo'])) { 
			$periodo = $_POST['periodo'];
			$datossplit = explode('-',$periodo); 
			$ano = $datossplit[1];
			$mes = $datossplit[0];
			
			$sqlDias = "SELECT * FROM diasbancousimra WHERE ano = $ano and mes = $mes ORDER BY dia";
			$resDias = mysql_query($sqlDias,$db);
			$canDias = mysql_num_rows($resDias); ?>
			<p><span class="Estilo1">Resultado Per&iacute;odo "<?php echo $periodo ?>"</span> </p>
			<table class="tablesorter" id="listado" style="width: 800px">
				<thead>
					<tr>
					  	<th>D&iacute;a </th>
					  	<th class="filter-select" data-placeholder="Seleccione Convenio">Convenio </th>
					    <th class="filter-select" data-placeholder="Seleccione Estado">Estado </th>
					    <th>Fecha Proceso </th>
						<th>Observación </th>
		  			</tr>
	  			</thead>
	  			<tbody>
	  <?php	if ($canDias != 0) {
				while($rowDias = mysql_fetch_array($resDias)) { 
					$estado = "";
					if ($rowDias['procesado'] == '1') {
						$estado = "Procesado";
					} elseif ($rowDias['exceptuado'] == '1') {
						$estado = "Exceptuado";
					} else {
						$estado = " Sin Procesar";
					}
					$fecha = $ano."-".$mes."-".$rowDias['dia'];
					$diaSemana = $diasArray[date('N', strtotime($fecha))]; ?>
					<tr>
						<td><?php echo $diaSemana." ".str_pad($rowDias['dia'],2,'0',STR_PAD_LEFT)."/".str_pad($mes,2,'0',STR_PAD_LEFT)."/".$ano ?></td>
					    <td><?php echo $rowDias['nroconvenio'] ?></td>
						<td><?php echo $estado ?></td>
						<td><?php echo $rowDias['fechamodificacion'] ?></td>
						<td><?php echo $rowDias['observacion'] ?></td>
					</tr>
		<?php } 
			} else { ?>
					<tr><td colspan='8' style='color:#FF0000'><b>No Existen movimientos para este código</b></td></tr>
	<?php	} ?>
			</tbody>
	</table>
	<p><input type='button' name='imprimir' value='Imprimir' onclick='window.print();'/></p>
    <?php } ?>
  </div>
</form>
<p>&nbsp;</p>
</body>
</html>
