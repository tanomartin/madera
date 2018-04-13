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

$sqlacuerdos =  "SELECT c.*, e.descripcion as estado, t.descripcion as tipo 
					FROM cabacuerdosospim c, estadosdeacuerdos e, tiposdeacuerdos t 
				 	WHERE c.cuit = $cuit and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo 
					ORDER BY c.nroacuerdo";
$resulacuerdos = mysql_query($sqlacuerdos); 
$cantacuerdos = mysql_num_rows($resulacuerdos);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Sistema de Acuerdos OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<div align="center">
	<p><input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php'" /></p>
<?php  include($libPath."cabeceraEmpresa.php"); 
 	if ($cantacuerdos != 0) { ?>
 		<h3>Acuerdos Existentes </h3>
 	 	<table width="800" border="1" style="text-align: center;">
  <?php while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) { 
  			$nroacu = $rowacuerdos['nroacuerdo']; ?>
			<tr>
				<td><?php echo $rowacuerdos['nroacuerdo']." - ".$rowacuerdos['tipo']." - Acta: ".$rowacuerdos['nroacta'] ?></td>
				<td>
			<?php	if ($rowacuerdos['estadoacuerdo'] == 1 || $rowacuerdos['estadoacuerdo'] == 5) {
						if ($rowacuerdos['estadoacuerdo'] == 1) { ?>
							<input type="button" onclick="location.href = 'modificarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="MODIFICAR" /> - 
			<?php 		} else { 
							echo $rowacuerdos['estado'];
						}
					
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
						} else {
							$reemplazable = false;
						}
						if ($reemplazable == true) { ?>
							<input type="button" onclick="location.href = 'reemplazarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="REEMPLAZAR" /> - 
							<input type="button" onclick="location.href = 'incobrableAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="INCOBRABLE" /> - 
		<?php			}
					} else {
						echo $rowacuerdos['estado']." - ";
					} ?>
					<input type="button" onclick="location.href = 'consultaAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" value="CONSULTAR" />
				</td>
			</tr>
<?php	} ?>	
  		</table>
<?php } else { ?>
  		<h3><font color="blue">No existe acuerdos para esta empresa</font></h3>
<?php } ?>
   <p><input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onclick="location.href = 'nuevoAcuerdo.php?cuit=<?php echo $cuit ?> '" /> </p>
</div>
</body>
</html>
