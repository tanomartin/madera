<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
$pos = strpos($_SERVER['HTTP_REFERER'], 'usimra');
if ($pos === false) {
	include($libPath."controlSessionOspim.php");
} else {
	include($libPath."controlSessionUsimra.php");
}
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$anio=$_GET['anio'];
$mes=$_GET['mes'];

$sqlDdjj = "select *
			from cabddjjospim 
			where 
			cuit = $cuit and 
			anoddjj = $anio and 
			mesddjj = $mes";

//print($sqlDdjj );
$resDdjj = mysql_query($sqlDdjj,$db); 
$rowDdjj = mysql_fetch_array($resDdjj); 
//var_dump($rowDdjj);

$sqlDdjjDet = "select *
from detddjjospim
where
cuit = $cuit and
anoddjj = $anio and
mesddjj = $mes";

$resDdjjDet = mysql_query($sqlDdjjDet,$db);


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
  
</div>
</body>

