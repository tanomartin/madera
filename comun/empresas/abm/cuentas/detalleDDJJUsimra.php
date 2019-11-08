<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
include($libPath."cabeceraEmpresaConsulta.php"); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlDdjj = "SELECT d.* FROM ddjjusimra d
			WHERE 
			nrcuit = $cuit and 
			perano = $anio and 
			permes = $mes and
			nrcuil = '99999999999' order by nrctrl ASC";
$resDdjj = mysql_query($sqlDdjj,$db);


$sqlDJJNorem = "SELECT d.*, d.remune * e.valor as alicuota, e.tipo 
				FROM ddjjusimra d, extraordinariosusimra e WHERE
					d.nrcuit = $cuit and
					d.perano = $anio and
					d.permes > 12 and
					d.perano = e.anio and
					d.permes = e.mes and
					e.relacionmes = $mes and
					d.nrcuil = '99999999999'
					order by nrctrl ASC";
$resDdjjNorem = mysql_query($sqlDJJNorem,$db); 
$numDdjjNorem = mysql_num_rows($resDdjjNorem);?>

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
	  <th>Alicuota</th>
	  <th>Aporte 0.6%</th>
	  <th>Contri 1%</th>
	  <th>Aporte 1.5%</th>
	  <th>Recargo</th>
	  <th>Declarado</th>
	  <th>Observ</th>
	  <th>Acciones</th>
    </tr>

<?php while ($rowDdjj = mysql_fetch_assoc($resDdjj)) {	?>	
	 <tr>
	    <td><?php print(substr($rowDdjj['nrctrl'],6,2)."-".substr($rowDdjj['nrctrl'],4,2)."-".substr($rowDdjj['nrctrl'],0,4)." ".substr($rowDdjj['nrctrl'],8,2).":".substr($rowDdjj['nrctrl'],10,2).":".substr($rowDdjj['nrctrl'],12,2))?></td>
	  	<td align="center"><?php echo $rowDdjj['nfilas'] ?></td>
		<td align="right"><?php echo number_format($rowDdjj['remune'],2,',','.') ?></td>
		<td align="right"><?php  echo "-"; ?>
		<td align="right"><?php echo number_format($rowDdjj['apo060'],2,',','.') ?></td>
		<td align="right"><?php echo number_format($rowDdjj['apo100'],2,',','.') ?></td>
		<td align="right"><?php echo number_format($rowDdjj['apo150'],2,',','.') ?></td>
		<td align="right"><?php echo number_format($rowDdjj['recarg'],2,',','.') ?></td>
		<td align="right"><?php echo number_format($rowDdjj['totapo'],2,',','.') ?></td>
		<td align="center"><?php echo $rowDdjj['observ'] ?></td>
		<td align="center"><input type="button" onclick="javascript:abrirInfo('verDetalleDDJJUsimra.php?nrocontrol=<?php echo $rowDdjj['nrctrl'] ?>&cuit=<?php echo $rowDdjj['nrcuit'] ?>&mes=<?php echo $rowDdjj['permes'] ?>&anio=<?php echo $rowDdjj['perano'] ?>')" value="Detalle" />
	 </tr>
<?php } 
	if ($numDdjjNorem > 0 ) { 
		while ($rowDdjjNorem = mysql_fetch_assoc($resDdjjNorem)) { ?>
			<tr>
	    		<td><?php print(substr($rowDdjjNorem['nrctrl'],6,2)."-".substr($rowDdjjNorem['nrctrl'],4,2)."-".substr($rowDdjjNorem['nrctrl'],0,4)." ".substr($rowDdjjNorem['nrctrl'],8,2).":".substr($rowDdjjNorem['nrctrl'],10,2).":".substr($rowDdjjNorem['nrctrl'],12,2))?></td>
			  	<td align="center"><?php echo $rowDdjjNorem['nfilas'] ?></td>
				<td align="right"><?php echo number_format($rowDdjjNorem['remune'],2,',','.') ?></td>
				<td align="right"><?php if ($rowDdjjNorem['alicuota'] != 0 and $rowDdjjNorem['tipo'] == 1) { echo number_format($rowDdjjNorem['alicuota'],2,',','.'); } else { echo "-"; } ?>
				<td align="right"><?php echo number_format($rowDdjjNorem['apo060'],2,',','.') ?></td>
				<td align="right"><?php echo number_format($rowDdjjNorem['apo100'],2,',','.') ?></td>
				<td align="right"><?php echo number_format($rowDdjjNorem['apo150'],2,',','.') ?></td>
				<td align="right"><?php echo number_format($rowDdjjNorem['recarg'],2,',','.') ?></td>
				<td align="right"><?php echo number_format($rowDdjjNorem['totapo'],2,',','.') ?></td>
				<td align="center"><?php echo $rowDdjjNorem['observ'] ?></td>
				<td align="center"><input type="button" onclick="javascript:abrirInfo('verDetalleDDJJUsimra.php?nrocontrol=<?php echo $rowDdjjNorem['nrctrl'] ?>&cuit=<?php echo $rowDdjjNorem['nrcuit'] ?>&mes=<?php echo $rowDdjjNorem['permes'] ?>&anio=<?php echo $rowDdjjNorem['perano'] ?>')" value="Detalle" />
			 </tr>
  <?php }
	}?>
  </table>
</div>
</body>

