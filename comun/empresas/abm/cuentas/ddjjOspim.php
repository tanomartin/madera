<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

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
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <table width="774" border="1">
    <tr>
      <td width="242">C.U.I.T.: <b><?php echo $cuit ?></b></td>
      <td width="516">Nombre: <b><?php echo $row['nombre'] ?></b></td>
    </tr>
	 <tr>
      <td colspan="2">Peridodo: <b><?php echo $mes."-".$anio." [NO PAGO] " ?></b></td>
	</tr>
  </table>
  <p><strong>Infomación DDJJ</strong></p>
   <table width="363" border="1">
    <tr>
      <td width="225">Total De Personal:</td>
      <td width="122"><div align="center"><b><?php echo $rowDdjj['totalpersonal'] ?></b></div></td>
    </tr>
	 <tr>
	   <td width="225">Total Remuneraci&oacute;n Declarada: </td>
	  <td width="122"><div align="center"><b><?php echo number_format($rowDdjj['totalremundeclarada'],2,',','.')?></b></div></td>
	 </tr>
	 <tr>
	   <td width="225">Total Remuneraci&oacute;n Decreto: </td>
	  <td width="122"><div align="center"><b><?php echo  number_format($rowDdjj['totalremundecreto'],2,',','.') ?></b></div></td>
    </tr>
  </table>
  
</div>
</body>

