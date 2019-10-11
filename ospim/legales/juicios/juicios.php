<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

if (isset($_GET['cuit'])) {
	$cuit=$_GET['cuit'];
} else {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: moduloJuicios.php?err=1");
}

$sqlJuicios =  "select * from cabjuiciosospim where cuit = $cuit";
$resJuicios = mysql_query($sqlJuicios); 
$cantJuicios = mysql_num_rows($resJuicios);
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Sistema de Juicios :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<div align="center">
  <p><input type="button" name="volver" value="Volver" onClick="location.href = 'moduloJuicios.php'" /></p>
  <?php include($libPath."cabeceraEmpresaConsulta.php");
  		include($libPath."cabeceraEmpresa.php"); ?>
  <p><input type="button" name="nuevoJuicio" value="Nuevo Juicio" onClick="location.href = 'nuevoJuicio.php?cuit=<?php echo $cuit ?> '" ></p>
  <h3>Juicios Existentes </h3>
<?php if($cantJuicios > 0) { ?>
		  <table width="600" border="1" style="text-align: center">
		     <?php 
				while ($rowJuicios = mysql_fetch_array($resJuicios)) {
					$nroorden = $rowJuicios['nroorden'];
					$sqlTramite = "SELECT fechafinalizacion from trajuiciosospim WHERE nroorden = $nroorden";
					$resTramite  = mysql_query($sqlTramite);
					$canTramite = mysql_num_rows($resTramite); ?>
					<tr>
						<td>Orden: <b><?php echo $nroorden?></b> - Certificado: <b><?php echo $rowJuicios['nrocertificado'] ?></b></td>
				<?php 	if ($canTramite > 0) {  
							$rowTramite = mysql_fetch_array($resTramite);
			 				if ($rowTramite['fechafinalizacion'] == "0000-00-00") { ?>
								<td><input type="button" value="MODIFICAR" onclick="location.href ='modificarJuicio.php?nroorden=<?php echo $nroorden?>'"/></td>
				<?php		} else {  ?>
								<td>-</td>
				<?php		}
						} else { ?>
							<td><input type="button" value="MODIFICAR" onclick="location.href ='modificarJuicio.php?nroorden=<?php echo $nroorden?>'"/></td>
				<?php	} ?>
						<td><input type="button" value="CONSULTAR" onclick="location.href ='consultaJuicio.php?cuit=<?php echo $cuit?>&nroorden=<?php echo $nroorden?>'"/></td>
					</tr>
		<?php } ?>	
		  </table>
<?php } ?>
</div>
</body>
</html>