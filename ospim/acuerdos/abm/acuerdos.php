<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: moduloABM.php?err=1");
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

$sqlacuerdos =  "select * from cabacuerdosospim where cuit = $cuit";
$resulacuerdos= mysql_query($sqlacuerdos); 

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
  <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="500" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$nroacu = $rowacuerdos['nroacuerdo'];
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_query($query,$db);
			$rowtipos=mysql_fetch_array($result);
			echo ("<td width=300  align='center'><font face=Verdana size=2> ".$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']."</a></font></td>");
			if ($rowacuerdos['estadoacuerdo'] == 1) {
				echo ("<td width=100  align='center'><font face=Verdana size=2><a href='formularioModif.php?cuit=".$cuit."&nroacu=".$rowacuerdos['nroacuerdo']."'>MODIFICAR</a></font></td>");
				
				$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
				$resCuotas = mysql_query($sqlCuotas,$db); 
				$canCuotas = mysql_num_rows($resCuotas); 
				$reemplazable = true;
				if ($canCuotas != 0 && $rowacuerdos['tipoacuerdo'] != 3) {
					while ($rowCuotas = mysql_fetch_array($resCuotas)) {
						if ($rowCuotas['montopagada'] == 0 || $rowCuotas['fechapagada'] == '0000-00-00') {
							if ($rowCuotas['tipocancelacion'] != 8 && $reemplazable == true){
								$reemplazable = false;
							}	
						}										
					}
					if ($reemplazable == true) {
						echo ("<td width=100  align='center'><font face=Verdana size=2><a href='reemplazarAcuerdo.php?cuit=".$cuit."&nroacu=".$rowacuerdos['nroacuerdo']."'>REEMPLAZAR</a></font></td>");
					} else {
						echo ("<td width=100  align='center'><font face=Verdana size=2>-</a></font></td>");
					}
				} else {
					echo ("<td width=100  align='center'><font face=Verdana size=2>-</a></font></td>");
				}
			} else {
				if ($rowacuerdos['estadoacuerdo'] == 0) {
					echo ("<td width=100  align='center'><font face=Verdana size=2>CANCELADO</a></font></td>");
					echo ("<td width=100  align='center'><font face=Verdana size=2>-</a></font></td>");
				} 
				if ($rowacuerdos['estadoacuerdo'] == 2) {
					echo ("<td width=100  align='center'><font face=Verdana size=2>INCOBRABLE</a></font></td>");
					echo ("<td width=100  align='center'><font face=Verdana size=2>-</a></font></td>");
				}
			}
			echo ("<td width=100  align='center'><font face=Verdana size=2><a href='consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$rowacuerdos['nroacuerdo']."'>CONSULTAR</a></font></td>");
			print ("</tr>");
		}
		
	?>	
  </table>
  <p>
    <input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onClick="location.href = 'formularioCarga.php?cuit=<?php echo $cuit ?> '" sub>
  </p>
</div>
</body>
</html>
