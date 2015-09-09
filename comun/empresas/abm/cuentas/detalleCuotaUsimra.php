<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php"); 
$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlPagos = "SELECT * FROM  cuotaextraordinariausimra c, extraordinariosusimra e 
				WHERE c.cuit = $cuit and 
					  c.anopago = $anio and 
					  c.mespago = $mes and 
					  e.anio = c.anopago and 
					  e.mes = c.mespago";

$resPagos = mysql_query($sqlPagos,$db);
$i = 0;
$totalPagado = 0;
while ($rowPagos = mysql_fetch_assoc($resPagos)) {
	$pagos[$i] = $rowPagos;
	$totalPagado = (float) ($totalPagado + $pagos[$i]['montopagado']);
	$i = $i + 1;
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

<title>.: Pagos Cuota Extraordinaria Empresa :.</title>
</head>
<body bgcolor="#B2A274">
<div align="center">
  <table width="774" border="1">
    <tr>
      <td width="242">C.U.I.T.: <b><?php echo $cuit ?></b></td>
      <td width="516">Nombre: <b><?php echo $row['nombre'] ?></b></td>
    </tr>
  </table>
  <p><strong>Detalles del Pago Cuota Extraordinaria</strong></p>
  <table border="1">
    <tr>
      <th>Nro. Pago</th>
      <th>Periodo</th>
      <th>Fecha de Pago </th>
	  <th>Personal</th>
	  <th>Aporte</th>
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
		<td><?php echo $pagos[$n]['mespago']."-".$pagos[$n]['anopago']." | ".$pagos['mensaje'] ?></td>
		<td><?php echo invertirFecha($pagos[$n]['fechapago']) ?></td>
		<td><?php echo $pagos[$n]['cantidadpersonal'] ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['totalaporte'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['montorecargo'],2,',','.') ?></td>
		<td align='right'><?php echo number_format($pagos[$n]['montopagado'],2,',','.') ?></td>
		<td><?php echo $pagos[$n]['codigobarra'] ?></td>
		<td><?php echo $pagos[$n]['observaciones'] ?></td>
		</tr>
<?php }?>
	<tr>
      <td colspan="7"><div align="right"><strong>TOTAL</strong></div></td>
	  <td><div align='right'><b><?php echo number_format($totalPagado,2,',','.') ?></b></div></td>
	<td colspan="2"></td>
    </tr>
  </table>
  
  <p><strong>Detalles DDJJ Cuota Extraordinaria</strong></p>
  <?php if ($canDetDDJJ > 0) {?>
  			<table border="1" width="600">
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
  	<?php  }
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

