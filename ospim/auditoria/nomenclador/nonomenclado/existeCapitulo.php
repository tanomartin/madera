<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlCapitulo = "SELECT * FROM capitulosdepracticas WHERE id = '$id '";
$resCapitulo = mysql_query($sqlCapitulo,$db);
$rowCapitulo = mysql_fetch_assoc($resCapitulo);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Capitulo Existe :.</title>

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
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'nuevaPractica.php'" />
  </p>
  <p><span class="Estilo2" style="color:#FF0000">El capitulo de codigo <b><?php echo $rowCapitulo['codigo'] ?></b> ya existe</span></p>
  <p><span class="Estilo2"><?php echo $rowCapitulo['codigo']." - ".$rowCapitulo['descripcion'] ?></span></p>
</div>
</body>
</html>
