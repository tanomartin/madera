<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."claves.php");

if(isset($_POST['periodo'])) {
	$periodo = explode('-',$_POST['periodo']);
	$mesPedido = $periodo[0];
	$mesPedido = str_pad($periodo[0],2,'0',STR_PAD_LEFT);
	$anioPedido = $periodo[1];
}

function formatoPerido($per) {
	if ($per == 1) {
		return "01";
	}
	if ($per == 2) {
		return "02";
	}
	if ($per == 3) {
		return "03";
	}
	if ($per == 4) {
		return "04";
	}
	if ($per == 5) {
		return "05";
	}
	if (($per == 6) || ($per == -6)) {
		return "06";
	}
	if (($per == 7) || ($per == -5)) {
		return "07";
	}
	if (($per == 8) || ($per == -4)) {
		return "08";
	}
	if (($per == 9) || ($per == -3)) {
		return "09";
	}
	if (($per == 10) || ($per == -2)) {
		return "10";
	}
	if (($per == 11) || ($per == -1)) {
		return "11";
	}
	if (($per == 12) || ($per == 0)){
		return "12";
	}
}

$dia=date("j");
$mes=date("m");
$anio=date("Y");
/*if ($dia < 14) {
	$inicio=2;
	$fin=7;
}
else  {*/
	$inicio=1;
	$fin=6;
//}

for ( $i = $inicio ; $i <= $fin ; $i++) {
	$perAux=$mes - $i;
	if ($perAux <= 0) {
		$anioArc[$i]=$anio-1;
		$mesArc[$i]=formatoPerido($perAux);
	}
	else {
		$anioArc[$i]=$anio;
		$mesArc[$i]=formatoPerido($perAux);
	}
}
/*if(strcmp("localhost",$maquina)==0) {
	$hostOspim = "localhost"; //para las pruebas...
}*/
$dbInternet =  mysql_connect($hostOspim,$usuarioOspim,$claveOspim );
if (!$dbInternet) {
	die('No pudo conectarse a la base de OSPIM.COM.AR: ' . mysql_error());
}
mysql_select_db($baseOspimPrestadores);
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
  <p><span style="text-align:center"><input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '../menuTesoreria.php'" align="center"/></span></p>
  <p class="Estilo2">Informacion de Subida y Descarga de Padrones - Capitas </p>
  <p class="nover"><b>Seleccione Per&iacute;odo</b></p>
  
  <form name="controlCapitas" action="controlCapitas.php" method="POST">	 
	  <select class="nover" name="periodo" id="periodo">
	  <option selected="selected" value=0> Seleccione Periodo </option>
		  <?php 
			for ($i=$inicio;$i<=$fin;$i++){
				$valor = $mesArc[$i]."-".$anioArc[$i];
				print("<option value=$valor>$valor</option>");	
			}	
		  ?>
	  </select>
  <label><input class="nover" type="submit" name="Submit" value="Listar" /></label>
  </form>
  
  <?php
	$sqlPrestador = "select * from capitados";
	$resPrestador = mysql_query($sqlPrestador,$db);
	if (isset($mesPedido) && isset($anioPedido)) { ?>
		 <p class="Estilo2">Periodo <?php echo $mesPedido."/".$anioPedido ?></p>
		 <table width="1053" border="1" align="center">
			<tr>
			  <td><div align="center"><strong>Prestador</strong></div></td>
			  <td><div align="center"><strong>Fecha de Subida</strong></div></td>
			  <td><div align="center"><strong>Primera Bajada</strong></div></td>
			  <td><div align="center"><strong>Cant. Titulares</strong></div></td>
			  <td><div align="center"><strong>Cant. Familiares</strong></div></td>
			  <td><div align="center"><strong>Total de Beneficiarios</strong></div></td>
			  <td class="nover"><div align="center"><strong>Benef. por Deleg. </strong></div></td>
			</tr>
		<?php while($rowPrestador=mysql_fetch_array($resPrestador)) {
				$presta = $rowPrestador['codigo'];
				$sql2 = "select * from subidapadroncapitados where codigoprestador = $presta and mespadron = $mesPedido and anopadron = $anioPedido"  ;
				$result2 = mysql_query($sql2,$db);
				$row2=mysql_fetch_array($result2); 
				if (mysql_num_rows($result2)==0) {
					$subida="NO SUBIDO";
				} else {
					$subida=$row2['fechasubida']." (".$row2['horasubida'].")";
				}
				
				$sql3 = "select * from descarga where codigo = $presta and mespad = $mesPedido and anopad = $anioPedido and estdes='S' order by codigo, anopad, mespad, nrodes LIMIT 1";
				$result3 = mysql_query($sql3,$dbInternet);
				$row3=mysql_fetch_array($result3); 
				if (mysql_num_rows($result3)==0) {
					$descarga="NUNCA";
				} else {
					$descarga=$row3['fecdes']." (".$row3['hordes'].")";
				}
				print ("<tr>");
				print ("<td><div align=center><font face=Verdana size=2>".$presta." - ".$rowPrestador['nombre']."</font></div></td>");
				print ("<td><div align=center><font face=Verdana size=2>".$subida."</font></div></td>");
				print ("<td><div align=center><font face=Verdana size=2>".$descarga."</font></div></td>");
				print ("<td><div align=center><font face=Verdana size=2>".$row2['totaltitulares']."</font></div></td>");
				print ("<td><div align=center><font face=Verdana size=2>".$row2['totalfamiliares']."</font></div></td>");
				print ("<td><div align=center><font face=Verdana size=2>".$row2['totalbeneficiarios']."</font></div></td>");
				if ($subida=="NO SUBIDO") {
					print ("<td class='nover'><div align=center><font face=Verdana size=2>".$subida."</font></div></td>");
				} else {
					$dire = "detalleCapitas.php?presta=$presta&ano=$anioPedido&mes=$mesPedido";
					print ("<td class='nover' align=center><a href=javascript:abrirDetelle('$dire')>VER</a></td>"); 
				}
				print ("</tr>");
			?>
        <?php } ?>
		</table>
		 <p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
  <?php	} ?> 
</div>
</body>
</html>