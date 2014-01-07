<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");

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
$row = mysql_fetch_array($result); 
$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
$resDelEmp = mysql_query($sqlDelEmp,$db);
$rowDelEmp = mysql_fetch_array($resDelEmp); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlJuicios =  "select * from cabjuiciosusimra where cuit = $cuit";
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
<body bgcolor="#B2A274" > 
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloJuicios.php'" align="center"/></p>
  <?php include($libPath."cabeceraEmpresa.php"); ?>
  <p><strong>Juicios Existentes </strong></p>
  <?php if($cantJuicios > 0) { ?>
  <table width="600" border="1">
     <?php 
		while ($rowJuicios = mysql_fetch_array($resJuicios)) {
			$nroorden = $rowJuicios['nroorden'];
			echo ("<td width=300  align='center'><font face=Verdana size=2>Orden: <b>".$nroorden."</b> - Certificado: <b>".$rowJuicios['nrocertificado']."</b></a></font></td>");
			
			
			$sqlTramite = "SELECT fechafinalizacion from trajuiciosusimra WHERE nroorden = $nroorden";
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
    <input type="submit" name="nuevoJuicio" value="Nuevo Juicio" onClick="location.href = 'nuevoJuicio.php?cuit=<?php echo $cuit ?> '" sub>
  </p>
</div>
</body>
</html>