<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: moduloJuicios.php?err=1");
}

include($libPath."cabeceraEmpresaConsulta.php");

$sqlJuicios =  "select * from cabjuiciosospim where cuit = $cuit";
$resJuicios = mysql_query($sqlJuicios); 
$cantJuicios = mysql_num_rows($resJuicios);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<title>.: Sistema de Juicios :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<div align="center">
  <p><input type="button" name="volver" value="Volver" onClick="location.href = 'moduloJuicios.php'" /></p>
  <?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/cabeceraEmpresa.php"); ?>
  <p><strong>Juicios Existentes </strong></p>
  <?php if($cantJuicios > 0) { ?>
  <table width="600" border="1">
     <?php 
		while ($rowJuicios = mysql_fetch_array($resJuicios)) {
			$nroorden = $rowJuicios['nroorden'];
			echo ("<td width=300  align='center'><font face=Verdana size=2>Orden: <b>".$nroorden."</b> - Certificado: <b>".$rowJuicios['nrocertificado']."</b></a></font></td>");
			
			
			$sqlTramite = "SELECT fechafinalizacion from trajuiciosospim WHERE nroorden = $nroorden";
			$resTramite  = mysql_query($sqlTramite); 
			$canTramite = mysql_num_rows($resTramite);
			if ($canTramite > 0) {
				$rowTramite = mysql_fetch_array($resTramite);
				if ($rowTramite['fechafinalizacion'] == "0000-00-00") {
					echo ("<td width=100  align='center'><font face=Verdana size=2><a href='modificarJuicio.php?nroorden=".$nroorden."'>MODIFICAR</a></font></td>");
				} else {
					echo ("<td width=100  align='center'><font face=Verdana size=2>-</font></td>");
				}
			} else {
				echo ("<td width=100  align='center'><font face=Verdana size=2><a href='modificarJuicio.php?nroorden=".$nroorden."'>MODIFICAR</a></font></td>");
			}
			
			echo ("<td width=100  align='center'><font face=Verdana size=2><a href='consultaJuicio.php?cuit=".$cuit."&nroorden=".$nroorden."'>CONSULTAR</a></font></td>");
			print ("</tr>");
		}
		
	?>	
  </table>
   <?php } ?>
  <p>
    <input type="submit" name="nuevoJuicio" value="Nuevo Juicio" onClick="location.href = 'nuevoJuicio.php?cuit=<?php echo $cuit ?> '" >
  </p>
</div>
</body>
</html>