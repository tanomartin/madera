<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if (isset($_GET['cuit'])) {
	$cuit=$_GET['cuit'];
} else {
	$cuit=$_POST['cuit'];
}

include($libPath."cabeceraEmpresaConsulta.php");
if ($tipo == "noexiste") {
	header ("Location: moduloABM.php?err=1");
}

$sqlacuerdos =  "select * from cabacuerdosospim c, estadosdeacuerdos e where c.cuit = $cuit and c.estadoacuerdo = e.codigo order by nroacuerdo";
$resulacuerdos = mysql_query($sqlacuerdos); 
$cantacuerdos = mysql_num_rows($resulacuerdos);
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
  <p><input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" /></p>
  <?php 	
  		include($libPath."cabeceraEmpresa.php"); 
		if ($cantacuerdos != 0) { ?>
  	 <p><b>Acuerdos Existentes </b></p>
 	 <table width="700" border="1" style="text-align: center;">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) { ?>
			<tr>
	<?php 	$nroacu = $rowacuerdos['nroacuerdo'];
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo] ";
			$result=mysql_query($query,$db);
			$rowtipos=mysql_fetch_array($result); ?>
			<td width=300><font face=Verdana size=2><?php echo $rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']." - Acta: ".$rowacuerdos['nroacta'] ?></font></td>
	<?php	if ($rowacuerdos['estadoacuerdo'] == 1 || $rowacuerdos['estadoacuerdo'] == 5) {
				if ($rowacuerdos['estadoacuerdo'] == 1) { ?>
					<td><input type="button" onclick="location.href = 'formularioModif.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="MODIFICAR" /></td>
	<?php 		} else { ?>
					<td width=100><font face=Verdana size=2><?php echo $rowacuerdos['descripcion'] ?></font></td>
	<?php 		}
				
				$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
				$resCuotas = mysql_query($sqlCuotas,$db); 
				$canCuotas = mysql_num_rows($resCuotas); 
				$reemplazable = true;
				if ($canCuotas != 0 && $rowacuerdos['tipoacuerdo'] != 3) {
					while ($rowCuotas = mysql_fetch_array($resCuotas)) {
						if ($rowCuotas['montopagada'] == 0 || $rowCuotas['fechapagada'] == '0000-00-00') {
							if (($rowCuotas['tipocancelacion'] != 8 && $reemplazable == true) || $rowCuotas['boletaimpresa'] != 0){
								$reemplazable = false;
							}	
						}										
					}
					if ($reemplazable == true) { ?>
						<td><input type="button" onclick="location.href = 'reemplazarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="REEMPLAZAR" /></td>
	<?php 			} else { ?>
						<td width=100><font face=Verdana size=2>-</font></td>
	<?php 			}
				} else { ?>
					<td width=100><font face=Verdana size=2>-</font></td>
	<?php 		}
			} else { ?>
				<td width=100><font face=Verdana size=2><?php echo $rowacuerdos['descripcion'] ?></font></td>
				<td width=100><font face=Verdana size=2>-</font></td>
	<?php	} ?>
			<td><input type="button" onclick="location.href = 'consultaAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="CONSULTAR" /></td>
			</tr>
<?php	} ?>	
  </table>
  <?php } else { ?>
  		<p><b>No existe acuerdos para esta empresa</b></p>
  <?php }?>
  <p>
  <?php   if ($tipo == "activa") { ?>
    <input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onclick="location.href = 'formularioCarga.php?cuit=<?php echo $cuit ?> '" />
  <?php }?>
  </p>
</div>
</body>
</html>
