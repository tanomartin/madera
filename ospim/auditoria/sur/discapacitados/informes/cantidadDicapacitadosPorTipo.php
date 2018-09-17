<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

function array_push_key(&$array, $key, $value) { 
   $array[$key] = $value; 
}  

$sqlCantTitulares = "SELECT disca.iddiscapacidad, count(*) as titulares
FROM discapacidadbeneficiario disca, titulares t
WHERE
disca.nroorden = 0 and
disca.nroafiliado = t.nroafiliado
GROUP BY disca.iddiscapacidad";

$sqlCantFamiliares = "SELECT disca.iddiscapacidad, count(*) as familiares
FROM discapacidadbeneficiario disca, familiares f
WHERE
disca.nroorden != 0 and
disca.nroafiliado = f.nroafiliado
GROUP BY disca.iddiscapacidad";

$sqlTipoDisca = "SELECT * FROM tipodiscapacidad";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$resultCanTitulares = $dbh->query($sqlCantTitulares);
	$resultCanFamiliares = $dbh->query($sqlCantFamiliares);
	$resultTipoDisca = $dbh->query($sqlTipoDisca);
	
	$resultadoFinal = array();
	foreach ($resultTipoDisca as $tipoDisca){
		$resultadoFinal[$tipoDisca['iddiscapacidad']] = array('descripcion' => $tipoDisca['descripcion']);
	}
	
	if ($resultCanTitulares){
		foreach ($resultCanTitulares as $cantTitulares){
			array_push_key($resultadoFinal[$cantTitulares['iddiscapacidad']],'titulares',$cantTitulares['titulares']);
		}
	}
	
	if ($resultCanFamiliares){
		foreach ($resultCanFamiliares as $cantFamiliares){
			array_push_key($resultadoFinal[$cantFamiliares['iddiscapacidad']],'familiares',$cantFamiliares['familiares']);
		}
	}

} catch (PDOException $e) {
	$error = $e->getMessage();
	print($error);
	$dbh->rollback();
} ?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Discapacitados por Tipo de Discapacidad :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
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
  	<h3>Listado De Discapacitados por Tipo de Discapacidad</h3>
	<div class="grilla">
	 <table>
          <thead>
            <tr>
			  <th>Tipo de Discapacidad</th>
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
					<td><?php echo $resultado['descripcion'] ?></td>
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
		</tbody>
		<thead>
			<tr>
				<th>TOTALES</th>
				<th><?php echo $totalTitu ?></th>
				<th><?php echo $totalFami ?></th>
				<?php $totalGeneral = (int)$totalTitu + (int)$totalFami; ?>
				<th><?php echo $totalGeneral ?></th>
			</tr>
		</thead>
 	 </table>
  </div>
     <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>

