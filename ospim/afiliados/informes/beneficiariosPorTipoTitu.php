<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

//conexion y creacion de transaccion.
try{
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$sqlTipo = "SELECT * FROM tipotitular";
	$resTipo = $dbh->query($sqlTipo);
	$arrayTipo = array();
	foreach ($resTipo as $tipo){
		$index = (int) $tipo['codtiptit'];
		$arrayTipo[$index] = array('descrip' => $tipo['descrip'], 'total' => 0);
	}
	
	$sqlDelegaciones = "SELECT * FROM delegaciones WHERE codidelega not in (3500,4000,4001)";
	$resDelegaciones = mysql_query ( $sqlDelegaciones, $db );
	$arrayInforme = array();
	while ($rowDelegaciones = mysql_fetch_assoc ($resDelegaciones)) {
		$arrayInforme[$rowDelegaciones['codidelega']]['nombre'] = $rowDelegaciones['nombre'];
		$arrayInforme[$rowDelegaciones['codidelega']]['total'] = 0;
		foreach ($arrayTipo as $index => $tipo){
			$arrayInforme[$rowDelegaciones['codidelega']][$index] = 0;
		}
	}

	
	$sqlTitulares = "SELECT t.codidelega, t.situaciontitularidad, count(*) as cantidad
						FROM titulares t
						GROUP BY t.codidelega,t.situaciontitularidad";
	$resultTitulares = $dbh->query($sqlTitulares);
	$total = 0;
	foreach ($resultTitulares as $titulares){
		$arrayInforme[$titulares['codidelega']][$titulares['situaciontitularidad']] = $titulares['cantidad'];
		$total += $titulares['cantidad'];
		$arrayInforme[$titulares['codidelega']]['total'] += $titulares['cantidad'];
	}	
	$sqlFamiliares = "SELECT t.codidelega, t.situaciontitularidad, count(*) as cantidad
						FROM titulares t, familiares f
						WHERE t.nroafiliado = f.nroafiliado
						GROUP BY t.codidelega,t.situaciontitularidad";
	$resultFamiliares = $dbh->query($sqlFamiliares);
	foreach ($resultFamiliares as $familiares){
		$arrayInforme[$familiares['codidelega']][$familiares['situaciontitularidad']] += $familiares['cantidad'];
		$total += $familiares['cantidad'];
		$arrayInforme[$familiares['codidelega']]['total'] += $familiares['cantidad'];
	}

} catch (PDOException $e) {
	$error = $e->getMessage();
	$dbh->rollback();
	$pagina = "beneficiariosPorDelegacion.php?error=1&mensaje=$error";
	Header("Location: $pagina");
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Cantidad de Beneficiarios por Delegacion :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	
	$(function() {
		$("#tabla")
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
		})
	});
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
  	<p><span class="Estilo2">Cantidad Beneficiarios por Delegaci&oacute;n y Tipo de Titular al <?php echo date('d/m/Y') ?> </span></p>
	 <table style="text-align:center" id="tabla" class="tablesorter" >
          <thead>
            <tr>
			  <th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
			  <?php foreach ($arrayTipo as $tipo) {?>
			  		<th><?php echo $tipo['descrip'] ?></th>
			  <?php } ?>
			  <th>Total</th>
			  <th>%</th>
            </tr>
          </thead>
        <tbody>
		 <?php 	$totPorcentaje = 0;
		 		foreach ($arrayInforme as $resultado){ 	?>
            	<tr>
					<td><font size="2px"><?php echo $resultado['nombre'] ?></font> </td>
					<?php foreach ($arrayTipo as $codigo => $tipo) { $arrayTipo[$codigo]['total'] += $resultado[$codigo];  ?>
					 			<td><?php echo $resultado[$codigo] ?></td>
					<?php } ?>
					<td><b><?php echo $resultado['total'] ?></b></td>
						<?php $porcentaje = ($resultado['total'] / $total) * 100; 
							  $totPorcentaje += $porcentaje ?>
					<td><b><?php echo number_format($porcentaje,2,",",".") ?></b></td>
				</tr>
		<?php } ?>
		</tbody>
		<tr>
			<td style="background-color: aqua;"><b>TOTALES</b></td>
			<?php foreach ($arrayTipo as $codigo => $tipo) {?>
				<td style="background-color: aqua;"><b><?php echo $tipo['total'] ?></b></td>
			<?php } ?>
			<td style="background-color: aqua;"><b><?php echo $total ?></b></td>
			<td style="background-color: aqua;"><b><?php echo number_format($totPorcentaje,2,",",".") ?></b></td>
		</tr>
		
  </table>
     <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>