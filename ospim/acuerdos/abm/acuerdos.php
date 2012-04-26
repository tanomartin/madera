<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$dbname = $_SESSION['dbname'];
echo $dbname;

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_db_query($dbname,$sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant == 0) {
	header('Location: moduloABM.php?err=1');
}
$row = mysql_fetch_array($result); 

$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
$resDelEmp = mysql_db_query($dbname,$sqlDelEmp,$db);
$rowDelEmp = mysql_fetch_array($resDelEmp); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_db_query($dbname,$sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_db_query($dbname,$sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlacuerdos =  "select * from cabacuerdosospim where cuit = $cuit";
$resulacuerdos= mysql_db_query($dbname,$sqlacuerdos,$db); 

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: Sistema de Acuerdos OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<div align="center">
  <p><strong><a href="moduloABM.php"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <?php include ("cabezeraEmpresa.php"); ?> 
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="500" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_db_query($dbname,$query,$db);
			$rowtipos=mysql_fetch_array($result);
			echo ("<td width=300  align='center'><font face=Verdana size=2> ".$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']."</a></font></td>");
			if ($rowacuerdos['estadoacuerdo'] != 0) {
				echo ("<td width=100  align='center'><font face=Verdana size=2><a href='formularioModif.php?cuit=".$cuit."&nroacu=".$rowacuerdos['nroacuerdo']."'>MODIFICAR</a></font></td>");
			} else {
				echo ("<td width=100  align='center'><font face=Verdana size=2>CANCELADO</a></font></td>");
			}
			echo ("<td width=100  align='center'><font face=Verdana size=2><a href='consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$rowacuerdos['nroacuerdo']."'>CONSULTAR</a></font></td>");
			print ("</tr>");
		}
		
	?>	
  </table>
  <p>
    <input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onClick="location.href = 'formularioCarga.php?cuit=<? echo $cuit ?> '" sub>
  </p>
</div>
</body>
</html>
