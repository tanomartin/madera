<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 

$id = $_GET['id'];
$sqlSubCapitulo = "SELECT * FROM subcapitulosdepracticas WHERE id = '$id '";
$resSubCapitulo = mysql_query($sqlSubCapitulo,$db);
$rowSubCapitulo = mysql_fetch_assoc($resSubCapitulo);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Capitulo Existe :.</title>
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
  <p><span class="Estilo2" style="color:#FF0000">El SubCapitulo de codigo <b><?php echo $rowSubCapitulo['codigo'] ?></b> ya existe</span></p>
  <p><span class="Estilo2"><?php echo $rowSubCapitulo['codigo']." - ".$rowSubCapitulo['descripcion'] ?></span></p>
</div>
</body>
</html>
