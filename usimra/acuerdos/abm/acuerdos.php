<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

if (isset($_GET['cuit'])) {
	$cuit=$_GET['cuit'];
} else { 
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: moduloABM.php?err=1");
}
$row = mysql_fetch_array($result); 
$sqlacuerdos =  "SELECT c.*, e.descripcion as estado, t.descripcion as tipo 
					FROM cabacuerdosusimra c, estadosdeacuerdos e, tiposdeacuerdos t
					WHERE c.cuit = $cuit and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo
					ORDER BY nroacuerdo";
$resulacuerdos= mysql_query($sqlacuerdos); 

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Sistema de Acuerdos USIMRA :.</title>
</head>
<body bgcolor="#B2A274" > 
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloABM.php'"/></p>
  <?php 	
  		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  <h3>Acuerdos Existentes </h3>
  <table width="700" border="1" style="text-align: center">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$nroacu = $rowacuerdos['nroacuerdo']; ?>
			<tr>
				<td width=350><?php echo $rowacuerdos['nroacuerdo']." - ".$rowacuerdos['tipo']." - Acta: ".$rowacuerdos['nroacta'] ?></td>
				<td>
			 <?php 	if ($rowacuerdos['estadoacuerdo'] == 1 || $rowacuerdos['estadoacuerdo'] == 5) { 
						if ($rowacuerdos['estadoacuerdo'] == 1) { ?>
							<input type="button" value="Modificar" onclick="location.href = 'modificarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo']?>'" />
				 <?php	} else { 
				 		 	echo $rowacuerdos['estado']; 
						}
						$sqlCuotas = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $nroacu";
						$resCuotas = mysql_query($sqlCuotas,$db); 
						$canCuotas = mysql_num_rows($resCuotas); 
						$reemplazable = true;
						if ($canCuotas != 0 && $rowacuerdos['tipoacuerdo'] != 3) {
							while ($rowCuotas = mysql_fetch_array($resCuotas)) {
								if ($rowCuotas['montopagada'] == 0 || $rowCuotas['fechapagada'] == '0000-00-00') { 
									if (($rowCuotas['tipocancelacion'] != 8 && $reemplazable == true) || $rowCuotas['boletaimpresa'] != 0 ){
										$reemplazable = false;
									}	
								}										
							}
						} else {
							$reemplazable = false;
						}
						if ($reemplazable == true) { ?>
							<input type="button" value="Reemplazar" onclick="location.href = 'reemplazarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" />
				<?php	} 
					} else { 
						echo $rowacuerdos['estado']." - ";
					} ?>
					<input type="button" value="Consultar" onclick="location.href = 'consultaAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" />
				</td>
			</tr>
<?php	} ?>
  </table>
  <p><input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onClick="location.href = 'nuevoAcuerdo.php?cuit=<?php echo $cuit ?> '"/></p>
</div>
</body>
</html>
