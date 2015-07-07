<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$lote=$_GET['nroLote'];

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
<title>.: Lote de Impresion :.</title>
</head>
<body bgcolor="#CCCCCC" >
<div align="center">
	<p><h2>Lote de Impresi&oacute;n</h2>
	<h3>Su Lote de Impresion a sido marcado con el Identificador: <?php echo $lote ?></h3>
<table width="600" border="0">
	<tr>
    	<td width="200">
          <div align="left">
            <input class="nover" type="button" name="imprimir" id="imprimir" value="Imprimir" onClick="window.print();"/> 
        </div></td>
    	<td width="200">
          <div align="center">
            <input class="nover" type="button" name="iralote" id="iralote" value="Ir a Lote" onClick="location.href = 'impresionLotes.php?nroLote=<?php echo $lote?>'"/> 
          </div></td>
    	<td width="200" align="right">
          <div align="right">
            <input class="nover" type="button" name="cerrar" id="cerrar" value="Cerrar" onClick="location.href = 'moduloImpresion.php'"/> 
          </div></td>
	</tr>
</table>
</div>
</body>
</html>