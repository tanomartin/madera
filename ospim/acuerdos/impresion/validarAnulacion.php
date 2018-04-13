<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$datos = array_values($_POST);
$nroControl = $datos[0];
$docuMano = $datos[1];
$motivo = $datos[3];

$sqlBol = "SELECT * FROM boletasospim b, empresas e WHERE b.nrocontrol = $nroControl and b.cuit = e.cuit";
$resBol = mysql_query($sqlBol,$db); 
$canBol = mysql_num_rows($resBol);

if ($canBol != 1) {
	header('Location: cargaAnulacion.php?err=1');
	exit(0);
} else {
	$rowBol = mysql_fetch_array($resBol); 
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Verificacion Anulacion :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'cargaAnulacion.php'" /></p>
  	<form id="form1" name="form1" method="post" action="anularBoleta.php?idboleta=<?php echo $rowBol['idboleta']; ?> ">
		<p><b>Datos Boleta </b></p>
		<table width="600" border="1" style="text-align: left">
      		<tr>
        		<td>CUIT</td>
        		<td><b><?php echo $rowBol['cuit']; ?></b></td>
      		</tr>
      		<tr>
        		<td>Razón Social</td>
        		<td><b><?php echo $rowBol['nombre']; ?></b></td>
      		</tr>
      		<tr>
        		<td>Nº Acuerdo</td>
        		<td><b><?php echo $rowBol['nroacuerdo']; ?></b></td>
      		</tr>
      		<tr>
        		<td>Nº Cuota</td>
        		<td><b><?php echo $rowBol['nrocuota']; ?></b></td>
      		</tr>
      		<tr>
        		<td>Importe</td>
        		<td><b><?php echo $rowBol['importe']; ?></b></td>
      		</tr>
      		<tr>
        		<td>COD. Identificacion Boleta</td>
        		<td><b><?php echo $rowBol['nrocontrol']; ?></b></td>
      		</tr>
    	</table>
    	<p><b>Datos Anulación </b></p>
    	<table width="500" border="1" style="text-align: center">
      		<tr>
        		<td>Documento en mano</td>
        		<td>
	        <?php  if ($docuMano == 1) { ?>
						<input type='text' id='docuMano' name='docuMano' value='SI' size='2' readonly='readonly' style='background:#CCCCCC; text-align: center' />
			<?php  } else { ?>
						<input type='text' id='docuMano' name='docuMano' value='NO' size='2' readonly='readonly' style='background:#CCCCCC; text-align: center' />
			<?php  } ?>
				</td>
      		</tr>
      		<tr>
        		<td>Motivo</td>
        		<td><textarea name="motivo" cols="43" rows="5" style="background:#CCCCCC" readonly="readonly"><?php echo $motivo ?></textarea></td>
      		</tr>
    	</table>
    	<p><input type="submit" name="Submit" value="Confirmar Anulacion" /></p>
  	</form>
</div>
</body>
</html>
