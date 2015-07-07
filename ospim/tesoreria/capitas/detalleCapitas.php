<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$presta = $_GET['presta'];
$ano = $_GET['ano'];
$mes = $_GET['mes'];

$sqlSubidaPadron = "select * from subidapadroncapitados where codigoprestador = '$presta' and mespadron = $mes and anopadron = $ano";
$resSubidaPadron = mysql_query($sqlSubidaPadron,$db);
$rowSubidaPadron = mysql_fetch_array($resSubidaPadron);

$sqlDetallePadron = "select d.*, dele.nombre from detallepadroncapitados d, delegaciones dele where d.codigoprestador = '$presta' and d.mespadron = $mes and d.anopadron = $ano and d.codidelega = dele.codidelega"  ;
$resDetallePadron = mysql_query($sqlDetallePadron,$db);

?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control Capitas</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script language="javascript">
function abrirDetelle(dire) {
	a= window.open(dire,"InfoCapitas",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2">Detalle de Padrones - Capitas Prestador <?php echo $presta  ?> </p>
	 <p class="Estilo2">Periodo <?php echo $ano."/".$mes ?></p>
		 <table width="600" border="1" align="center">
			<tr>
			  <td><div align="center"><strong>Delegación</strong></div></td>
			  <td><div align="center"><strong>Cant.<br> Titulares</strong></div></td>
			  <td><div align="center"><strong>Cant.<br> Familiares</strong></div></td>
			  <td><div align="center"><strong>Total<br> Beneficiarios</strong></div></td>
			</tr>
		<?php while($rowDetallePadron=mysql_fetch_array($resDetallePadron)) {
				print ("<tr>");
					print ("<td><div align=left><font face=Verdana size=2>".$rowDetallePadron['codidelega']."-".$rowDetallePadron['nombre']."</font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2>".$rowDetallePadron['totaltitulares']."</font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2>".$rowDetallePadron['totalfamiliares']."</font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2>".$rowDetallePadron['totalbeneficiarios']."</font></div></td>");
				print ("</tr>"); 
			  } 
			  	print ("<tr>");
					print ("<td><div align=left><font face=Verdana size=2><b>TOTAL</b></font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2><b>".$rowSubidaPadron['totaltitulares']."</b></font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2><b>".$rowSubidaPadron['totalfamiliares']."</b></font></div></td>");
					print ("<td><div align=center><font face=Verdana size=2><b>".$rowSubidaPadron['totalbeneficiarios']."</b></font></div></td>");
				print ("</tr>"); 
		?>
			  
		</table>
		<p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>