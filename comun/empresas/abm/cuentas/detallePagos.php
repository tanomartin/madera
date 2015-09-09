<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php"); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlPagos = "select concepto, fechapago, sum(importe), debitocredito
			from afipprocesadas 
			where 
			cuit = $cuit and 
			anopago = $anio and 
			mespago = $mes
			group by concepto, fechapago, debitocredito
			order by fechapago, concepto, debitocredito";


//print($sqlPagos );
$resPagos = mysql_query($sqlPagos,$db); 
$i = 0;
while ($rowPagos = mysql_fetch_array($resPagos)) {
	$pagos[$i] = $rowPagos;
	$i = $i + 1;
}

//var_dump($pagos);

$total = 0;
$totalRemu = 0;
for ($n=0; $n < sizeof($pagos); $n++) {
	if ($pagos[$n]['debitocredito'] == 'D') { 
		$total = $total - $pagos[$n]['sum(importe)'];
	} else {
		if ($pagos[$n]['concepto'] != 'REM') {
			$total = $total + $pagos[$n]['sum(importe)'];
		} else {
			$totalRemu = $totalRemu + $pagos[$n]['sum(importe)'];
		}
	}
}

//print("TOTAL: ".$total);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
</head>

<title>.: Pagos Empresa :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <table width="774" border="1">
    <tr>
      <td width="242">C.U.I.T.: <b><?php echo $cuit ?></b></td>
      <td width="516">Nombre: <b><?php echo $row['nombre'] ?></b></td>
    </tr>
	 <tr>
      <td colspan="2">Peridodo: <b><?php echo $mes."-".$anio ?></b></td>
	</tr>
  </table>
   <p><strong>Detalles del Pago</strong></p>
  <table width="583" border="1">
    <tr>
      <th width="193">Concepto</th>
      <th width="192">Fecha de Pago </th>
	   <th width="97">Remuneraci&oacute;n</th>
	   <th width="88">Importe</th>
    </tr>
	<?php for ($n=0; $n < sizeof($pagos); $n++) { 
		print("<tr align='center'>");
		print("<td width='193'>".$pagos[$n]['concepto']."</td>");
		print("<td width='192'>".invertirFecha($pagos[$n]['fechapago'])."</td>");
		if ($pagos[$n]['concepto'] == 'REM') {
			print("<td width='97' align='right'>".number_format($pagos[$n]['sum(importe)'],2,',','.')."</td>");
			print("<td width='88'>-</td>");
		} else {
			print("<td width='97'>-</td>");
			if ($pagos[$n]['debitocredito'] == 'D') {
				print("<td width='88' align='right' style='color:#FF0000'> -".number_format($pagos[$n]['sum(importe)'],2,',','.')."</td>");
			} else {
				print("<td width='88' align='right'>".number_format($pagos[$n]['sum(importe)'],2,',','.')."</td>");
			}
		}
		print("</tr>");
	} 
	?>
	<tr>
      <td colspan="2"><div align="center"><strong>TOTAL</strong></div></td>
	  <td width="97"><div align="right"><b><?php echo number_format($totalRemu,2,',','.') ?></b></div></td>
      <?php 
	  	if ( $total < 0 ) {
	  		print("<td width='88'><div align='right' style='color:#FF0000'><b> -".number_format($total,2,',','.')."</b></div></td>");
		} else {
			print("<td width='88'><div align='right'><b>".number_format($total,2,',','.')."</b></div></td>");
		} 
		?>
    </tr>
  </table>
  
</div>
</body>

