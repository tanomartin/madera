<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$sqlPractica = "SELECT * FROM practicas WHERE codigopractica = '$codigo'";
$resPractica = mysql_query($sqlPractica,$db);
$rowPractica = mysql_fetch_assoc($resPractica);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Practia Existe :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>


<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'nuevaPractica.php'" align="center"/>
  </p>
  <p><span class="Estilo2" style="color:#FF0000">La practica de codigo <b><?php echo $codigo ?></b> ya existe</span></p>
  <p><span class="Estilo2"><?php echo $codigo." - ".$rowPractica['descripcion'] ?>
</div>
</body>
</html>
