<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

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
$sqlacuerdos =  "select * from cabacuerdosusimra c, estadosdeacuerdos e where c.cuit = $cuit and c.estadoacuerdo = e.codigo order by nroacuerdo";
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

<title>.: Sistema de Acuerdos USIMRA :.</title>
</head>
<body bgcolor="#B2A274" > 
<div align="center">
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloABM.php'"/> 
  <?php 	
  		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="600" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$nroacu = $rowacuerdos['nroacuerdo'];
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_query($query,$db);
			$rowtipos=mysql_fetch_array($result); ?>
			<tr>
				<td width="400" align='center'><font face=Verdana size=2><?php echo $rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']." - Acta: ".$rowacuerdos['nroacta'] ?></font></td>
		 <?php 	if ($rowacuerdos['estadoacuerdo'] == 1 || $rowacuerdos['estadoacuerdo'] == 5) { 
					if ($rowacuerdos['estadoacuerdo'] == 1) { ?>
						<td align="center"><input type="button" value="Modificar" onclick="location.href = 'formularioModif.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo']?>'" /></td>
			 <?php	} else { ?>
						<td width="100" align='center'><font face=Verdana size=2>-</font></td>
			<?php	}
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
						if ($reemplazable == true) { ?>
							<td align="center"><input type="button" value="Reemplazar" onclick="location.href = 'reemplazarAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" /></td>
				<?php	} else { ?>
							<td width=100  align='center'><font face=Verdana size=2>-</font></td>
				<?php	}  
					} else { ?>
						<td width=100  align='center'><font face=Verdana size=2>-</font></td>
			<?php	}
				} else { ?>
					<td width=100  align='center'><font face=Verdana size=2><?php echo $rowacuerdos['descripcion'] ?></font></td>
					<td width=100  align='center'><font face=Verdana size=2>-</font></td>
		<?php	} ?>
				<td align="center"><input type="button" value="Consultar" onclick="location.href = 'consultaAcuerdo.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $rowacuerdos['nroacuerdo'] ?>'" /></td>
			</tr>
<?php	} ?>
  </table>
  <p>
    <input type="submit" name="nuevoAcuerdo" value="Nuevo Acuerdo" onClick="location.href = 'formularioCarga.php?cuit=<?php echo $cuit ?> '"/>
  </p>
</div>
</body>
</html>
