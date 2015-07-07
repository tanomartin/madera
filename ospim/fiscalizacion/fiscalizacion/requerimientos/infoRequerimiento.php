<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
$cuit = $_GET['cuit'];
$anio = $_GET['anio'];
$mes = $_GET['mes'];
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
<body bgcolor="#CCCCCC">
<div align="center">
		<p class="Estilo2"> Información detallada sobre DDJJ </p>
		<p class="Estilo2"> C.U.I.T <?php echo $cuit ?> </p>
		<p class="Estilo2"> Período <?php echo $mes."-".$anio ?></p>
		<table width="800" border="1" align="center">
		  <tr style="font-size:12px">
		 	<th rowspan="2"> Menor 240</th>
			<th colspan="4">Menor 1000</th>
			<th colspan="4">Mayor 1000</th>
		  </tr>
		  <tr style="font-size:12px">
		    <th>Remun.</th>
		    <th>Cantidad</th>
		    <th>Remu Adh. </th>
		    <th>Cant. Adh </th>
		    <th>Remun.</th>
		    <th>Cantidad</th>
		    <th>Remun. Adh </th>
		    <th>Cant. Adh </th>
		  </tr>
		  <?php $sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj = $anio and mesddjj = $mes";
				$resAgrup = mysql_query($sqlAgrup,$db);
				$canAgrup = mysql_num_rows($resAgrup);
				if ($canAgrup != 0) {
					$rowAgrup = mysql_fetch_array($resAgrup);
					print("<td>".$rowAgrup['cantcuilmenor240']."</td>"); 
					print("<td>".$rowAgrup['remucuilmenor1001']."</td>"); 
					print("<td>".$rowAgrup['cantcuilmenor1001']."</td>"); 
					print("<td>".$rowAgrup['remuadhemenor1001']."</td>"); 
					print("<td>".$rowAgrup['cantadhemenor1001']."</td>"); 
					print("<td>".$rowAgrup['remucuilmayor1000']."</td>"); 
					print("<td>".$rowAgrup['cantcuilmayor1000']."</td>"); 
					print("<td>".$rowAgrup['remuadhemayor1000']."</td>"); 
					print("<td>".$rowAgrup['cantadhemayor1000']."</td>"); 				
				} else {
					print("<td colspan='9'><b>No hay información detallada para este período</b></td>"); 	
				}
			?>
		</table>
		<p>
		  <input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" />
</p>
</div>
</body>
</html>