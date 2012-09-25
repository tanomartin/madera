<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); 

$datos = array_values($_POST);
$nroControl = $datos[0];
$docuMano = $datos[1];
$motivo = $datos[2];

$sqlBol = "select * from boletasospim where nrocontrol = $nroControl";
$resBol = mysql_query($sqlBol,$db); 
$canBol = mysql_num_rows($resBol);

if ($canBol != 1) {
	header('Location: cargaAnulacion.php?err=1');
} 

$rowBol = mysql_fetch_array($resBol); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	
	font-size: 18px;
	font-weight: bold;
}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Verificacion Anulacion :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><strong><a href="cargaAnulacion.php"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p class="Estilo1"><strong>Verificacion Anulacion</strong> </p>
  <form id="form1" name="form1" method="post" action="anularBoleta.php?idboleta=<?php echo $rowBol['idboleta']; ?> ">
	<p><strong>Datos Boleta </strong></p>
	<table width="406" border="1">
      <tr>
        <td width="196"><div align="right">CUIT</div></td>
        <td width="194"><div align="left"><strong><?php echo $rowBol['cuit']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Raz&oacute;n Social </div></td>
        <td><div align="left"><strong>
         <?php 
			$sqlEmp = "select * from empresas where cuit = ".$rowBol['cuit'];
			$resEmp = mysql_query($sqlEmp,$db); 
			$rowEmp = mysql_fetch_array($resEmp); 
			echo $rowEmp['nombre']; 
		?>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Acuerdo </div></td>
        <td><div align="left"><strong><?php echo $rowBol['nroacuerdo']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Cuota</div> </td>
        <td><div align="left"><strong><?php echo $rowBol['nrocuota']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Importe</div></td>
        <td><div align="left"><strong><?php echo $rowBol['importe']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">COD. Identificacion Boleta </div></td>
        <td><div align="left"><strong><?php echo $rowBol['nrocontrol']; ?></strong></div></td>
      </tr>
    </table>
    <p><strong>Datos Anulaci&oacute;n </strong></p>
    <table width="393" border="1">
      <tr>
        <td width="126"><div align="right">Documento en mano </div></td>
        <td width="257">
	      <div align="center">
	        <?php 
			if ($docuMano == 1) {
				print("<input type='text' id='docuMano' name='docuMano' value='SI' size='2' readonly='readonly' style='background:#CCCCCC' />");
			} else {
				print("<input type='text' id='docuMano' name='docuMano' value='NO' size='2' readonly='readonly' style='background:#CCCCCC' />");
			}
			?>
          </div></td>
      </tr>
      <tr>
        <td><div align="right">Motivo</div></td>
        <td>
          <textarea name="motivo" cols="40" rows="5" style="background:#CCCCCC" readonly="readonly"><?php echo $motivo ?></textarea>
        </td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>
      <label>
      <input type="submit" name="Submit" value="Confirmar Anulacion" />
      </label>
    </p>
  </form>
  <p class="Estilo1">&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
</body>
</html>
