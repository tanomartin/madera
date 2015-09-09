<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php"); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];
$nrocontrol=$_GET['nrocontrol'];

$generacion = substr($nrocontrol,6,2)."-".substr($nrocontrol,4,2)."-".substr($nrocontrol,0,4)." ".substr($nrocontrol,8,2).":".substr($nrocontrol,10,2).":".substr($nrocontrol,12,2);

$sqlDdjj = "select *
			from ddjjusimra 
			where 
			nrcuit = '$cuit' and 
			perano = $anio and 
			permes = $mes and
			nrctrl = '$nrocontrol' and
			nrcuil != '99999999999' order by nrcuil ASC";
$resDdjj = mysql_query($sqlDdjj,$db);
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

<title>.: DDJJ Empresa :.</title>
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
  <p><strong>Detalle DDJJ <?php print("(".$generacion.")")?></strong></p>
  
   <table border="1">
    <tr>
	  <th>C.U.I.L.</th>
	  <th>Remuneracion</th>
	  <th>Aporte 0.6%</th>
	  <th>Contri 1%</th>
	  <th>Aporte 1.5%</th>
	  <th>Declarado</th>
    </tr>

<?php while ($rowDdjj = mysql_fetch_assoc($resDdjj)) {	 ?>	
	 <tr>
	    <td align="center"><?php print($rowDdjj['nrcuil']) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['remune'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apo060'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apo100'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apo150'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['totapo'],2,',','.')) ?></td>
	 </tr>
<?php } ?>
  </table>
  
</div>
</body>

