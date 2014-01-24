<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

$cuit=$_GET['cuit'];
$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];
$nrocontrol=$_GET['nrocontrol'];

$generacion = substr($nrocontrol,6,2)."-".substr($nrocontrol,4,2)."-".substr($nrocontrol,0,4)." ".substr($nrocontrol,8,2).":".substr($nrocontrol,10,2).":".substr($nrocontrol,12,2);

$sqlDdjj = "select *
			from tempddjjusimra 
			where 
			cuit = '$cuit' and 
			anoddjj = $anio and 
			mesddjj = $mes and
			nrocontrol = '$nrocontrol' and
			cuil != '99999999999' order by cuil ASC";

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

<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"DetalleDDJJUsimra",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
</head>

<title>.: DDJJ Empresa :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
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
	    <td align="center"><?php print($rowDdjj['cuil']) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['remuneraciones'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor060'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor100'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor150'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['totalaporte'],2,',','.')) ?></td>
	 </tr>
<?php } ?>
  </table>
  
</div>
</body>

