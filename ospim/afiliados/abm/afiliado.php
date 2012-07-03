<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$datos = array_values($_POST);

echo $datos[0]; echo "<br>";

echo $datos[1]; echo "<br>";


//$sql = "select * from empresas where cuit = $cuit";
//$result = mysql_query($sql,$db); 
//$cant = mysql_num_rows($result); 
//if ($cant != 1) {
//	header ("Location: moduloABM.php?err=1");
//}
//$row = mysql_fetch_array($result);  
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
</div>
</body>
</html>
