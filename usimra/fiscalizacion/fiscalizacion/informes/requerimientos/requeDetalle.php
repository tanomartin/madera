<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

$nroreq = $_GET['nroreq'];
$cuit = $_GET['cuit'];

$sqlDeta = "SELECT * from detfiscalizusimra where nrorequerimiento = '$nroreq'";
$resDeta = mysql_query($sqlDeta,$db);


$sqlEmpresa = "SELECT * from empresas where cuit = '$cuit'";
$resEmpresa  = mysql_query($sqlEmpresa,$db);
$rowEmpresa  = mysql_fetch_array($resEmpresa)
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
		<p class="Estilo2"><?php echo $cuit." - ".$rowEmpresa['nombre'] ?></p>
		<table width="600" border="1" align="center" style="text-align: center;">
		  <tr style="font-size:12px">
			<th rowspan="2" width="65">Período</th>
			<th rowspan="2">Status</th>
			<th colspan="2">DDJJ</th>
			<th rowspan="2">Deuda Nominal</th>
		  </tr>
		  <tr style="font-size:12px">
		 	<th>Remun.</th>
			<th>Cant. Personal </th>
		  </tr>
		  <?php while($rowDeta = mysql_fetch_array($resDeta)) { 
					print("<tr>");
					$ano = $rowDeta['anofiscalizacion'];
					$mes = $rowDeta['mesfiscalizacion'];
					$id = $ano."-".$mes;
					print("<td width='65'>".$rowDeta['mesfiscalizacion']."-".$ano."</td>");
					if ($rowDeta['statusfiscalizacion'] == 'S') {
						$status = "S/DDJJ";
					}
					if ($rowDeta['statusfiscalizacion'] == 'A') {
						$status = "Deuda";
					}
					if ($rowDeta['statusfiscalizacion'] == 'F') {
						$status = "P.F.T.";
					} 
					if ($rowDeta['statusfiscalizacion'] == 'M') {
						$status = "Ap.Menor.";
					}  
					print("<td>".$status."</td>");   
					print("<td>".$rowDeta['remundeclarada']."</td>");   
					print("<td>".$rowDeta['cantidadpersonal']."</td>"); 
					print("<td>".$rowDeta['deudanominal']."</td>");   
					print("</tr>");
				} ?>
		</table>
		<p align="center"><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
	</div>
</body>
</html>