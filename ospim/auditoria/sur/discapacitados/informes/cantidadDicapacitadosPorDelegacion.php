<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

function array_push_key(&$array, $key, $value) { 
   $array[$key] = $value; 
}  

$sqlCantTitulares = "SELECT dele.codidelega, dele.nombre, count(*) as titulares
FROM titulares t, delegaciones dele
WHERE
t.discapacidad = 1 and
t.codidelega = dele.codidelega
GROUP BY t.codidelega;";

$sqlCantFamiliares = "SELECT dele.codidelega, dele.nombre, count(*) as familiares
FROM titulares t, familiares f, delegaciones dele
WHERE
f.discapacidad = 1 and
f.nroafiliado = t.nroafiliado and
t.codidelega = dele.codidelega
GROUP BY t.codidelega";

$sqlDelegaciones = "SELECT * FROM delegaciones WHERE codidelega <= 3200";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	$resultCanTitulares = $dbh->query($sqlCantTitulares);
	$resultCanFamiliares = $dbh->query($sqlCantFamiliares);
	$resultDelegciones = $dbh->query($sqlDelegaciones);
	
	$resultadoFinal = array();
	foreach ($resultDelegciones as $delegaciones){ 
		$resultadoFinal[$delegaciones['codidelega']] = array('nombre' => $delegaciones['nombre']);
	}
	
	if ($resultCanTitulares){
		foreach ($resultCanTitulares as $cantTitulares){ 
			array_push_key($resultadoFinal[$cantTitulares['codidelega']],'titulares',$cantTitulares['titulares']);
		}
	}

	if ($resultCanFamiliares){
		foreach ($resultCanFamiliares as $cantFamiliares){ 
			array_push_key($resultadoFinal[$cantFamiliares['codidelega']],'familiares',$cantFamiliares['familiares']);
		}
	}
} catch (PDOException $e) {
	$error = $e->getMessage();
	print($error);
	$dbh->rollback();
}

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Discapacitados por Delegacion :.</title>

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
			headers:{1:{filter:false}, 2:{filter:false}, 3:{filter:false}},
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		}),
		
		$("#totales")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
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
	
	$.unblockUI(); 
	});
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php'" /></p>
  	<p><span class="Estilo2">Cantidad Discapacitados por Delegaci&oacute;n al <?php echo date('d/m/Y') ?> </span></p>
	 <table style="text-align:center; width:800px" id="tabla" class="tablesorter" >
          <thead>
            <tr>
			  <th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
			  <th>Titulares</th>
			  <th>Familiares</th>
			  <th>Total Beneficiarios</th>
            </tr>
          </thead>
        <tbody>
		 <?php
		 	$totalTitu = 0;
		 	$totalFami = 0;		 	
		 	foreach ($resultadoFinal as $resultado){
		 		$totalPorDelega = 0;  ?>
            	<tr>
					<td><?php echo $resultado['nombre'] ?></td>
					<td><?php if (!isset($resultado['titulares'])) { 
								echo 0; 
							  } else { 
							  	echo $resultado['titulares']; 
							  	$totalTitu += (int)$resultado['titulares'];
							  	$totalPorDelega += (int)$resultado['titulares'];
							  }  ?>
					</td>
					<td><?php if (!isset($resultado['familiares'])) { 
								echo 0; 
							  } else { 
							  	echo $resultado['familiares']; 
							  	$totalFami += (int)$resultado['familiares'];
							  	$totalPorDelega += (int)$resultado['familiares'];
							  }  ?>
					</td>
					<td><?php echo $totalPorDelega ?></td>
				</tr>
		<?php } ?>
			<tr>
			<td><b>- TOTALES - </b></td>
			<td><b><?php echo $totalTitu ?></b></td>
			<td><b><?php echo $totalFami ?></b></td>
			<?php $totalGeneral = (int)$totalTitu + (int)$totalFami; ?>
			<td><b><?php echo $totalGeneral ?></b></td>
			</tr>
		</tbody>
  </table>
     <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>