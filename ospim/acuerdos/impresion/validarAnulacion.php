<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$datos = array_values($_POST);
$nroControl = $datos[0];
$docuMano = $datos[1];
$motivo = $datos[3];

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
  <p>
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'cargaAnulacion.php'" align="center"/>
  </p>
  <form id="form1" name="form1" method="post" action="anularBoleta.php?idboleta=<?php echo $rowBol['idboleta']; ?> ">
	<p><strong>Datos Boleta </strong></p>
	<table width="600" border="1">
      <tr>
        <td width="191"><div align="left">CUIT</div></td>
        <td width="393"><div align="left"><strong><?php echo $rowBol['cuit']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="left">Raz&oacute;n Social </div></td>
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
        <td><div align="left">Nro Acuerdo </div></td>
        <td><div align="left"><strong><?php echo $rowBol['nroacuerdo']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="left">Nro Cuota</div></td>
        <td><div align="left"><strong><?php echo $rowBol['nrocuota']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="left">Importe</div></td>
        <td><div align="left"><strong><?php echo $rowBol['importe']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="left">COD. Identificacion Boleta </div></td>
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
    <p>
      <label>
      <input type="submit" name="Submit" value="Confirmar Anulacion" />
      </label>
    </p>
  </form>
  </div>
</body>
</html>
