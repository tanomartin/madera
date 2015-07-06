<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Afiliado :.</title>
</head>
<body bgcolor="#CCCCCC" >
	<div align="center">
		<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" align="center"/> 
	</div>
	<p></p>
	<div align="center" class="Estilo4">El afiliado que intenta dar de alta ya EXISTE con el Nro: <?php echo $nroafiliado ?></div>
	<p></p>
	<div align="center">
		<input class="nover" type="button" name="iratitular" value="Ir a Afiliado" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>'" align="center"/>
	</div>
</body>
</html>
