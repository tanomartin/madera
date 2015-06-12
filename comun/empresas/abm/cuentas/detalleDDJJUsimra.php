<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlDdjj = "select *
			from tempddjjusimra 
			where 
			cuit = $cuit and 
			anoddjj = $anio and 
			mesddjj = $mes and
			cuil = '99999999999' order by nrocontrol ASC";

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
  <p><strong>Infomación DDJJ</strong></p>
  
   <table border="1">
    <tr>
      <th>Generación</th>
      <th>Personal</th>
	  <th>Remuneracion</th>
	  <th>Aporte 0.6%</th>
	  <th>Contri 1%</th>
	  <th>Aporte 1.5%</th>
	  <th>Recargo</th>
	  <th>Declarado</th>
	  <th>Observ</th>
	  <th>Acciones</th>
    </tr>

<?php while ($rowDdjj = mysql_fetch_assoc($resDdjj)) {	 ?>	
	 <tr>
	    <td><?php print(substr($rowDdjj['nrocontrol'],6,2)."-".substr($rowDdjj['nrocontrol'],4,2)."-".substr($rowDdjj['nrocontrol'],0,4)." ".substr($rowDdjj['nrocontrol'],8,2).":".substr($rowDdjj['nrocontrol'],10,2).":".substr($rowDdjj['nrocontrol'],12,2))?></td>
	  	<td align="center"><?php print($rowDdjj['cantidadpersonal']) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['remuneraciones'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor060'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor100'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['apor150'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['recargo'],2,',','.')) ?></td>
		<td align="right"><?php print(number_format($rowDdjj['totalaporte'],2,',','.')) ?></td>
		<td align="center"><?php print($rowDdjj['observacion']) ?></td>
		<td align="center"><a href=javascript:abrirInfo('verDetalleDDJJUsimra.php?nrocontrol=<?php echo $rowDdjj['nrocontrol'] ?>&cuit=<?php echo $rowDdjj['cuit'] ?>&mes=<?php echo $rowDdjj['mesddjj'] ?>&anio=<?php echo $rowDdjj['anoddjj'] ?>')>Detalle</a></td>
	 </tr>
<?php } ?>
  </table>
  
</div>
</body>

