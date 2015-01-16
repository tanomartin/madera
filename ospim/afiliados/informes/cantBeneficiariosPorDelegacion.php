<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 

function array_push_key(&$array, $key, $value) { 
   $array[$key] = $value; 
}  

$sqlCantTitulares = "SELECT d.codidelega, d.nombre, count(t.nroafiliado) as titulares FROM titulares t, delegaciones d WHERE t.codidelega = d.codidelega GROUP BY t.codidelega";
$sqlCantFamiliares = "SELECT d.codidelega, d.nombre, count(f.nroorden) as familiares FROM titulares t, familiares f, delegaciones d WHERE t.nroafiliado = f.nroafiliado and  t.codidelega = d.codidelega group by t.codidelega";
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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Cantidad de Beneficiarios por Delegacion :.</title>
</head>
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

<script src="/lib/jquery.js"></script>
<script src="/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/lib/jquery.tablesorter/themes/theme.blue.css">
<script src="/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
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


<body bgcolor="#CCCCCC">
<script>
	$.blockUI({ message: "<h1>Generando Informe. Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" } );
</script>
<div align="center">
  <p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php?origen=<?php echo $origen ?>'" align="center"/></p>
  	<p><span class="Estilo2">Cantidad Beneficiarios por Delegaci&oacute;n al <?php echo date('d/m/Y') ?> </span></p>
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
		 <?php foreach ($resultadoFinal as $resultado){ ?>
            	<tr>
					<td><?php echo $resultado['nombre'] ?></td>
					<td><?php if ($resultado['titulares'] == '') { echo 0; } else { echo $resultado['titulares']; } ?></td>
					<td><?php if ($resultado['familiares'] == '') { echo 0; } else { echo $resultado['familiares']; }  ?></td>
					<?php 
						$totalPorDelega = (int)$resultado['titulares'] + (int)$resultado[0]; 
						$totalTitu += (int)$resultado['titulares'];
						$totalFami += ( int)$resultado['familiares'];
					?>
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
     <p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>