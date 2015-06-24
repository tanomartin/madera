<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlPagos = "select s.*, ap6.importe as importeap6, ap1.importe as importeap1, ap15.importe as importeap15
from seguvidausimra s
LEFT OUTER JOIN
apor060usimra ap6 on
s.cuit = ap6.cuit and
s.anopago = ap6.anopago and
s.mespago = ap6.mespago and
s.nropago = ap6.nropago
LEFT OUTER JOIN
apor100usimra ap1 on
s.cuit = ap1.cuit and
s.anopago = ap1.anopago and
s.mespago = ap1.mespago and
s.nropago = ap1.nropago
LEFT OUTER JOIN
apor150usimra ap15 on
s.cuit = ap15.cuit and
s.anopago = ap15.anopago and
s.mespago = ap15.mespago and
s.nropago = ap15.nropago
where 
s.cuit = $cuit and 
s.anopago = $anio and 
s.mespago = $mes";

$resPagos = mysql_query($sqlPagos,$db); 
$i = 0;
$totalPagado = 0;
while ($rowPagos = mysql_fetch_assoc($resPagos)) {
	$pagos[$i] = $rowPagos;
	$totalPagado = (float) ($totalPagado + $pagos[$i]['montopagado']);
	$i = $i + 1;
}

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
<body bgcolor="#B2A274">
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
  <table border="1">
    <tr>
      <th>Nro. Pago</th>
      <th>Fecha de Pago </th>
	  <th>Personal</th>
	  <th>Remuneraci&oacute;n</th>
	  <th>Aporte 0.6%</th>
	  <th>Contri 1%</th>
	  <th>Aporte 1.5%</th>
	  <th>Recargo</th>
	  <th>Pagado</th>
	  <th>Codigo Barra</th>
	  <th>Observ</th>
    </tr>
	<?php
	for ($n=0; $n < sizeof($pagos); $n++) { 
		$nroPago = $n+1; 
		print("<tr align='center'>");
		print("<td>".$nroPago."</td>");
		print("<td>".invertirFecha($pagos[$n]['fechapago'])."</td>");
		print("<td>".$pagos[$n]['cantidadpersonal']."</td>");
		print("<td align='right'>".number_format($pagos[$n]['remuneraciones'],2,',','.')."</td>");
		print("<td align='right'>".number_format($pagos[$n]['importeap6'],2,',','.')."</td>");
		print("<td align='right'>".number_format($pagos[$n]['importeap1'],2,',','.')."</td>");
		print("<td align='right'>".number_format($pagos[$n]['importeap15'],2,',','.')."</td>");
		print("<td align='right'>".number_format($pagos[$n]['montorecargo'],2,',','.')."</td>");
		print("<td align='right'>".number_format($pagos[$n]['montopagado'],2,',','.')."</td>");
		print("<td>".$pagos[$n]['codigobarra']."</td>");
		print("<td>".$pagos[$n]['observaciones']."</td>");
		print("</tr>");
	}?>
	<tr>
      <td colspan="8"><div align="right"><strong>TOTAL</strong></div></td>
<?php print("<td><div align='right'><b>".number_format($totalPagado,2,',','.')."</b></div></td>");?>
	<td colspan="2"></td>
    </tr>
  </table>
  
</div>
</body>

