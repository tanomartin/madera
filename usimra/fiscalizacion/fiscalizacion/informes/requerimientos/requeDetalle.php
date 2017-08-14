<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

$nroreq = $_GET['nroreq'];
$cuit = $_GET['cuit'];

$sqlDeta = "SELECT p.*, d.*, relacionmes, tipo, valor, retiene060*0.006 + retiene100*0.01 + retiene150*0.015 as porcentaje
				FROM detfiscalizusimra d
				LEFT JOIN extraordinariosusimra e ON anofiscalizacion = e.anio and mesfiscalizacion = e.mes
				LEFT JOIN periodosusimra p ON anofiscalizacion = p.anio and mesfiscalizacion = p.mes
				WHERE nrorequerimiento = '$nroreq'";
$resDeta = mysql_query($sqlDeta,$db);

$sqlEmpresa = "SELECT * from empresas where cuit = '$cuit'";
$resEmpresa  = mysql_query($sqlEmpresa,$db);
$rowEmpresa  = mysql_fetch_array($resEmpresa);

function calculoObligacion($remu, $personal, $tipo, $valor, $porcentaje) {
	$apagar = 0;
	if ($tipo == -1) {
		$apagar = $remu * 0.031;
	}
	if ($tipo == 0) {
		$apagar = $valor * $porcentaje * $personal;
	}
	if ($tipo == 1) {
		$apagar = $remu * $porcentaje;
	}
	if ($tipo == 2) {
		$apagar = $remu;
	}
	return $apagar;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle de Requerimientos :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>
<body bgcolor="#B2A274">
	<div align="center">
		<input name="cuit" type="text" value="<?php echo $cuit?>" style="display:none"/>
		<input name="nroreq" type="text" value="<?php echo $nroreq?>" style="display:none"/>
		<p class="Estilo2">Detalle del  Requerimiento Nro. <?php echo $nroreq ?></p>
		<p class="Estilo2"><?php echo $cuit." - ".$rowEmpresa['nombre'] ?> (U.S.I.M.R.A.)</p>
		<table width="1000" border="1" align="center" style="text-align: center;">
		  <tr style="font-size:12px">
			<th rowspan="2">Año</th>
		  	<th rowspan="2">Mes</th>
			<th rowspan="2">Status</th>
			<th colspan="4">DDJJ / Pagos</th>
			<th rowspan="2">Deuda Nominal</th>
		  </tr>
		  <tr style="font-size:12px">
		 	<th>Remun. / B. Cal.</th>
			<th>Obligacion </th>
			<th>Personal </th>
			<th>Pago </th>
		  </tr>
		  <?php while($rowDeta = mysql_fetch_array($resDeta)) { 
					
					$ano = $rowDeta['anofiscalizacion'];
					$mes = $rowDeta['mesfiscalizacion'];
					$id = $ano."-".$mes;
					
					if ($rowDeta['tipo'] == null) {
						$tipo = -1;
					} else {
						$tipo = $rowDeta['tipo'];
					}
					
		  			if ($rowDeta['statusfiscalizacion'] == 'S') {
						$status = "S/DDJJ";
					}
					if ($rowDeta['statusfiscalizacion'] == 'A') {
						$status = "Deuda";
					}
					if ($rowDeta['statusfiscalizacion'] == 'U') {
						$status = "Base USIMRA";
					}
					if ($rowDeta['statusfiscalizacion'] == 'F') {
						$status = "P.F.T.";
					}
					if ($rowDeta['statusfiscalizacion'] == 'M') {
						$status = "A.M.";
					}
					if ($rowDeta['statusfiscalizacion'] == 'O') {
						$status = "Base OSPIM";
					}  ?>
					<tr>
						<td><?php echo $ano ?></td>
						<td><?php echo $mes." - ".$rowDeta['descripcion']?></td>
						<td><?php echo $status ?></td>   
						<td><?php echo number_format($rowDeta['remundeclarada'],2,',','.'); ?></td> 
						<td>
							<?php  
								$obliga = calculoObligacion($rowDeta['remundeclarada'],$rowDeta['cantidadpersonal'],$tipo,$rowDeta['valor'],$rowDeta['porcentaje']);
								echo number_format($obliga,2,',','.');
							?>
						</td> 
						<td><?php echo $rowDeta['cantidadpersonal'] ?></td> 
						<td>
							<?php 
								$dueda = $rowDeta['deudanominal'];
								$pago = abs($obliga - $dueda);
								echo number_format($pago,2,',','.');
							?>
						</td>	
						<td><?php echo number_format($dueda,2,',','.'); ?></td>        
					</tr>
			<?php  } ?>
		</table>
		<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
	</div>
</body>
</html>