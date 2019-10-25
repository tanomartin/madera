<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$idpractica = $_GET['idpractica'];

$sqlNombrePractica = "SELECT codigopractica, descripcion FROM practicas WHERE idpractica = $idpractica";
$resNombrePractica = mysql_query($sqlNombrePractica,$db);
$rowNombrePractica = mysql_fetch_array($resNombrePractica);

$sqlPracticas = "SELECT n.nombre as nomenclador, nr.nombre as resolucion, r.modulo, 
						DATE_FORMAT(nr.fechainicio,'%d-%m-%Y') AS fechainicio, 
						DATE_FORMAT(nr.fechafin,'%d-%m-%Y') AS fechafin
				FROM practicasvaloresresolucion r, practicas p, nomencladoresresolucion nr, nomencladores n
				WHERE
					r.idpractica = $idpractica and
					r.idpractica = p.idpractica and
					r.idresolucion = nr.id and
					p.nomenclador = n.id";
$resPracticas = mysql_query($sqlPracticas,$db);
$catPracticas = mysql_num_rows($resPracticas);
$resultado = array();
if ($catPracticas > 0) {
	$i = 0;
	while($rowPracticas = mysql_fetch_array($resPracticas)) {
		$resultado[$i] = $rowPracticas;
		$i++;
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Prestadores Practica :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
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
		})
	});
	
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h3>Listado de Valores que contiene la Práctica </h3>
  <h4><?php echo $rowNombrePractica['codigopractica']." - ".$rowNombrePractica['descripcion'] ?></h4>
  <?php if (sizeof($resultado) > 0) { ?>
	  <table style="text-align:center; width:800px" id="listado" class="tablesorter" >
		 <thead>
		   <tr>
		   	 <th>Nomenclador</th>
		   	 <th>Resolucion</th>
			 <th>Fecha Inicio</th>
			 <th>Fecha Fin</th>
			 <th>Modulo ($)</th>
		   </tr>
		 </thead>
		 <tbody>
		  <?php foreach($resultado as $practica) { ?>
		  <tr>
				 <td><?php echo $practica['nomenclador']?></td>
				 <td><?php echo $practica['resolucion']?></td>
				 <td><?php echo $practica['fechainicio']?></td>
				 <td><?php echo $practica['fechafin']?></td>
				 <td><?php echo $practica['modulo']?></td>
		  </tr>
		 <?php } ?>
		 </tbody>
	   </table>
	  <?php } else { ?>
	  	<h3 style="color: blue"> ESTA PRACTICA NO ESTA EN NINGUNA RESOLUCION </h3>
	 <?php	} ?>
</div>
</body>
</html>