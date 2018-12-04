<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$presta = $_GET['presta'];
$ano = $_GET['ano'];
$mes = $_GET['mes'];
$quin = $_GET['quin'];

$sqlSubidaPadron = "select * from subidapadroncapitados where codigoprestador = '$presta' and mespadron = $mes and anopadron = $ano and quincenapadron = $quin";
$resSubidaPadron = mysql_query($sqlSubidaPadron,$db);
$rowSubidaPadron = mysql_fetch_array($resSubidaPadron);

$sqlDetallePadron = "select d.*, dele.nombre from detallepadroncapitados d, delegaciones dele where d.codigoprestador = '$presta' and d.mespadron = $mes and d.anopadron = $ano and d.quincenapadron = $quin and d.codidelega = dele.codidelega";
$resDetallePadron = mysql_query($sqlDetallePadron,$db);

?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control Capitas</title>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  		<h3>Detalle de Padrones - Capitas Prestador <?php echo $presta  ?> </h3>
  		 <?php $cartel = $quin." º Quincena";
		 	   if ($quin == 0)  { $cartel = "MENSUAL"; } ?>
  		<h3>Periodo <?php echo ($ano."/".$mes." - ".$cartel) ?></h3>
		 <div class="grilla">
			 <table width="800px">
				<thead>
					<tr>
					  <th>Delegación</th>
					  <th>Cant. Titulares</th>
					  <th>Cant. Familiares</th>
					  <th>Total Beneficiarios</th>
					</tr>
				</thead>
				<tbody>
			<?php while($rowDetallePadron=mysql_fetch_array($resDetallePadron)) { ?>
					<tr>
						<td><?php echo $rowDetallePadron['codidelega']."-".$rowDetallePadron['nombre'] ?></td>
						<td><?php echo $rowDetallePadron['totaltitulares'] ?></td>
						<td><?php echo $rowDetallePadron['totalfamiliares'] ?></td>
						<td><?php echo $rowDetallePadron['totalbeneficiarios'] ?></td>
					</tr> 
			<?php	} ?>
				</tbody>
				<thead>
					<tr>
						<th>TOTAL</th>
						<th><?php echo $rowSubidaPadron['totaltitulares'] ?></th>
						<th><?php echo $rowSubidaPadron['totalfamiliares'] ?></th>
						<th><?php echo $rowSubidaPadron['totalbeneficiarios'] ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>