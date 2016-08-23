<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$idpractica = $_GET['idpractica'];

$sqlNombrePractica = "SELECT codigopractica, descripcion FROM practicas WHERE idpractica = $idpractica";
$resNombrePractica = mysql_query($sqlNombrePractica,$db);
$rowNombrePractica = mysql_fetch_array($resNombrePractica);

$sqlPracticas = "SELECT pr.*, det.*, presta.codigoprestador, presta.nombre, presta.cuit, nom.nombre as nombrenomenclador, pc.descripcion
				 FROM
					cabcontratoprestador cab,
					detcontratoprestador det,
					practicas pr,
					prestadores presta,
					nomencladores nom,
					practicascategorias pc
				 WHERE
					det.idpractica = $idpractica and
					det.idcontrato = cab.idcontrato and
					cab.codigoprestador = presta.codigoprestador and
					pr.nomenclador = nom.id and
					det.idpractica = pr.idpractica and
					pr.nomenclador = nom.id and
					det.idcategoria = pc.id";
$resPracticas = mysql_query($sqlPracticas,$db);
$catPracticas = mysql_num_rows($resPracticas);
if ($catPracticas > 0) {
	$i = 0;
	while($rowPracticas = mysql_fetch_array($resPracticas)) {
		$descriPractica = $rowPracticas['descripcion'];
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
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
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
		$("#prestadores")
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
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscadorPractica.php">
  <p align="center"><span class="Estilo1">Listado de Prestadores que contiene la Pr&aacute;ctica </span></p>
  <p align="center" class="Estilo1"><?php echo $rowNombrePractica['codigopractica']." - ".$rowNombrePractica['descripcion'] ?></p>
  <div align="center">
  <?php if (sizeof($resultado) > 0) { ?>
	  <table style="text-align:center; width:1000px" id="prestadores" class="tablesorter" >
		 <thead>
		   <tr>
			 <th>C&oacute;digo Prestador</th>
			 <th>Nombre / Razón Social</th>
			 <th>C.U.I.T.</th>
			 <th>Nomenclador</th>
			 <th>Categoria</th>
			 <th>Modulo Consultorio ($)</th>
			 <th>Modulo Urgencia ($)</th>
			 <th>G. Honorarios ($)</th>
			 <th>G. Honorarios Especialista ($)</th>
			 <th>G. Honorarios Ayudante ($)</th>
			 <th>G. Honorarios Anestesista ($)</th>
			 <th>G. Gastos ($)</th>
		   </tr>
		 </thead>
		 <tbody>
		   <?php foreach($resultado as $practica) { ?>
			   <tr>
				 <td><?php echo $practica['codigoprestador'];?></td>
				 <td><?php echo $practica['nombre'] ?></td>
				 <td><?php echo $practica['cuit'] ?></td>
				 <td><?php echo $practica['nombrenomenclador'] ?></td>
				 <td><?php echo $practica['descripcion'] ?></td>
				 <td><?php echo $practica['moduloconsultorio'] ?></td>
				 <td><?php echo $practica['modulourgencia'] ?></td>
				 <td><?php echo $practica['galenohonorario'] ?></td>
				 <td><?php echo $practica['galenohonorarioespecialista'] ?></td>
				 <td><?php echo $practica['galenohonorarioayudante'] ?></td>
				 <td><?php echo $practica['galenohonorarioanestesista'] ?></td>
				 <td><?php echo $practica['galenogastos'] ?></td>
			   </tr>
		   <?php } ?>
		 </tbody>
	   </table>
	  <?php } else {
	  			print("<div style='color:#FF0000'><b> ESTA PRACTICA NO ESTA CARGADAD EN NINGÚN PRESTADOR </b></div><br>"); 
	 		} ?>
  </div>
</form>
</body>
</html>
