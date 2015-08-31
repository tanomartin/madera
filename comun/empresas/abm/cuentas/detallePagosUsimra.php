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

$sqlExtraordinarioMes = "select mes from extraordinariosusimra where anio = $anio and relacionmes = $mes";
$resExtraordinarioMes = mysql_query($sqlExtraordinarioMes,$db);
$canExtraordinarioMes = mysql_num_rows($resExtraordinarioMes);
if ($canExtraordinarioMes > 0) {
	$rowExtraordinarioMes = mysql_fetch_assoc($resExtraordinarioMes);
	$mesExtra = $rowExtraordinarioMes['mes'];
	$sqlPagosNoRem = "select s.*, ap6.importe as importeap6, ap1.importe as importeap1, ap15.importe as importeap15
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
						s.mespago = $mesExtra";
	$resPagosNoRem = mysql_query($sqlPagosNoRem,$db);
	while ($rowPagosNoRem = mysql_fetch_assoc($resPagosNoRem)) {
		$pagos[$i] = $rowPagosNoRem;
		$totalPagado = (float) ($totalPagado + $pagos[$i]['montopagado']);
		$i = $i + 1;
	}
}

$sqlDetDDJJ = "SELECT * FROM detddjjusimra WHERE cuit = $cuit and anoddjj = $anio and mesddjj = $mes";
$resDetDDJJ = mysql_query($sqlDetDDJJ,$db);
$canDetDDJJ = mysql_num_rows($resDetDDJJ);

$sqlCabDDJJ = "SELECT * FROM cabddjjusimra WHERE cuit = $cuit and anoddjj = $anio and mesddjj = $mes";
$resCabDDJJ = mysql_query($sqlCabDDJJ,$db);
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
  </table>
  <p><strong>Detalles del Pago</strong></p>
  <table border="1">
    <tr>
      <th>Nro. Pago</th>
      <th>Periodo</th>
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
		$nroPago = $n+1; ?>
		<tr align='center'>
		<td><?php echo $nroPago ?></td>
		<td><?php echo $pagos[$n]['mespago']."-".$pagos[$n]['anopago'] ?></td>
		<td><?php echo invertirFecha($pagos[$n]['fechapago']) ?></td>
		<td><?php echo $pagos[$n]['cantidadpersonal'] ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['remuneraciones'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['importeap6'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['importeap1'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['importeap15'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['montorecargo'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['montopagado'],2,',','.') ?></td>
		<td><?php echo $pagos[$n]['codigobarra'] ?></td>
		<td><?php echo $pagos[$n]['observaciones'] ?></td>
		</tr>
<?php }?>
	<tr>
      <td colspan="9"><div align="right"><strong>TOTAL</strong></div></td>
	  <td><div align='right'><b><?php echo number_format($totalPagado,2,',','.') ?></b></div></td>
	<td colspan="2"></td>
    </tr>
  </table>
  
  <p><strong>Detalles DDJJ</strong></p>
  <?php if ($canDetDDJJ > 0) {?>
  			<table border="1">
  				<tr>
  					<th>C.U.I.L.</th>
  					<th>Remuneracion</th>
  					<th>Aporte 0.6%</th>
  					<th>Aporte 1%</th>
  					<th>Aporte 1.5%</th>
  					<th>Total</th>
  				</tr>
  	<?php  while ($rowDDJJ = mysql_fetch_assoc($resDetDDJJ)) {
  				$total = $rowDDJJ['apor060'] + $rowDDJJ['apor100'] + $rowDDJJ['apor150']; ?>	
  				<tr>
	  				<td><?php echo $rowDDJJ['cuil'] ?></td>
	  				<td align='right'><?php  echo number_format($rowDDJJ['remuneraciones'],2,',','.') ?></td>
	  				<td align='right'><?php  echo number_format($rowDDJJ['apor060'],2,',','.') ?></td>
	  				<td align='right'><?php  echo number_format($rowDDJJ['apor100'],2,',','.') ?></td>
	  				<td align='right'><?php  echo number_format($rowDDJJ['apor150'],2,',','.') ?></td>
	  				<td align='right'><?php  echo number_format($total,2,',','.') ?></td>
  				</tr>
  	<?php }
  			$rowCabDDJJ = mysql_fetch_assoc($resCabDDJJ);
  			?>		
			 <tr>
			 	<td><b>TOTAL</b></td>
			 	<td align='right'><?php  echo number_format($rowCabDDJJ['remuneraciones'],2,',','.') ?></td>
	  			<td align='right'><?php  echo number_format($rowCabDDJJ['apor060'],2,',','.') ?></td>
	  			<td align='right'><?php  echo number_format($rowCabDDJJ['apor100'],2,',','.') ?></td>
	  			<td align='right'><?php  echo number_format($rowCabDDJJ['apor150'],2,',','.') ?></td>
	  			<td align='right'><?php  echo number_format($rowCabDDJJ['totalaporte'],2,',','.') ?></td>
			 </tr>
  			</table>
  <?php } else { ?>
  <div style="text-align: center;">No se pudo leer el detalle de la DDJJ de este periodo</div>
  <?php }?>
</div>
</body>

