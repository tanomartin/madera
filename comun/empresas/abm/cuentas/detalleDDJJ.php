<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
$pos = strpos($_SERVER['HTTP_REFERER'], 'usimra');
if ($pos === false) {
	include($libPath."controlSessionOspim.php");
} else {
	include($libPath."controlSessionUsimra.php");
}
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php"); 
$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlDdjj = "select * from cabddjjospim where cuit = $cuit and anoddjj = $anio and mesddjj = $mes";
$resDdjj = mysql_query($sqlDdjj,$db); 
$rowDdjj = mysql_fetch_array($resDdjj); 

$sqlDdjjDet = "select * from detddjjospim where cuit = $cuit and anoddjj = $anio and mesddjj = $mes";
$resDdjjDet = mysql_query($sqlDdjjDet,$db);

$sqlPagos = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas 
				where cuit = $cuit and
					  anopago = $anio and
					  mespago = $mes
				group by concepto, fechapago, debitocredito
				order by fechapago, concepto, debitocredito";
$resPagos = mysql_query($sqlPagos,$db);
$i = 0;
while ($rowPagos = mysql_fetch_array($resPagos)) {
	$pagos[$i] = $rowPagos;
	$i = $i + 1;
}
$total = 0;
$totalRemu = 0;
$pagos = array();
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
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
</head>

<title>.: Ddjj Empresa :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <table width="800" border="1">
    <tr>
      <td width="300">C.U.I.T.: <b><?php echo $cuit ?></b></td>
      <td width="500">Nombre: <b><?php echo $row['nombre'] ?></b></td>
    </tr>
	 <tr>
      <td colspan="2">Peridodo: <b><?php echo $mes."-".$anio ?></b></td>
	</tr>
  </table>
  <p><strong>Infomación DDJJ</strong></p>
   <table width="400" border="1">
    <tr>
      <td>Total De Personal:</td>
      <td><div align="center"><b><?php echo $rowDdjj['totalpersonal'] ?></b></div></td>
    </tr>
	 <tr>
	  <td>Total Remuneraci&oacute;n Declarada: </td>
	  <td><div align="center"><b><?php echo number_format($rowDdjj['totalremundeclarada'],2,',','.')?></b></div></td>
	 </tr>
  </table>
   <p><strong>Detalle DDJJ</strong></p>
  <table width="400" border="1">
    <tr>
      <th>C.U.I.L.</th>
      <th>Remuneracion</th>
    </tr>
	 <?php while ($rowDetDDJJ = mysql_fetch_array($resDdjjDet)) {?>
		 <tr>
		  <td align="center"><?php echo $rowDetDDJJ['cuil'] ?></td>
		  <td><div align="center"><b><?php echo number_format($rowDetDDJJ['remundeclarada'],2,',','.')?></b></div></td>
		 </tr>
	<?php } ?>
  </table>
  
<?php if (sizeof($pagos) > 0) {  ?>
		  <p><strong>Detalle Ingresos</strong></p>
		  <table width="400" border="1" style="text-align: center">
		    <tr>
		      <th>Concepto</th>
		      <th>Fecha de Pago</th>
		      <th>Remuneración</th>
			  <th>Importe</th>
		    </tr>
		<?php foreach ($pagos as $pago) {  ?>
				<tr>
					<td width='193'><?php echo $pago['concepto'] ?></td>
					<td width='192'><?php echo invertirFecha($pago['fechapago'])  ?></td>
			<?php 	if ($pago['concepto'] == 'REM') { ?>
						<td width='97'><?php echo number_format($pago['sum(importe)'],2,',','.') ?></td>
						<td width='88'>-</td>
			<?php 	} else { ?>
						<td width='97'>-</td>
			<?php		if ($pago['debitocredito'] == 'D') {  ?>
							<td width='88'><?php echo "-".number_format($pago['sum(importe)'],2,',','.') ?></td>
			<?php		} else { ?>
							<td width='88'><?php echo number_format($pago['sum(importe)'],2,',','.') ?></td>
			<?php		}
					} ?>
				</tr>
		<?php } ?>
		 </table> 
<?php } ?>
</div>
</body>

