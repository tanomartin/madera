<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

$nroControl = $_POST['nroControl'];
$docuMano = $_POST['docuMano'];
$motivo = $_POST['motivo'];

$sqlBol = "select b.*, e.nombre from boletasusimra b, empresas e where b.nrocontrol = $nroControl and b.cuit = e.cuit";
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

<body bgcolor="#B2A274">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'cargaAnulacion.php'" /></p>
  <h3>Verificacion Anulacion</h3>
  <form id="form1" name="form1" method="post" action="anularBoleta.php?idboleta=<?php echo $rowBol['idboleta']; ?> ">
	<h3>Datos Boleta </h3>
	<table width="500" border="1">
      <tr>
        <td width="200"><div align="left">CUIT</div></td>
        <td width="300"><div align="left"><b><?php echo $rowBol['cuit']; ?></b></div></td>
      </tr>
      <tr>
        <td><div align="left">Raz&oacute;n Social </div></td>
        <td><div align="left"><b><?php echo $rowBol['nombre']; ?></b></div></td>
      </tr>
      <tr>
        <td><div align="left">Nro Acuerdo </div></td>
        <td><div align="left"><b><?php echo $rowBol['nroacuerdo']; ?></b></div></td>
      </tr>
      <tr>
        <td><div align="left">Nro Cuota</div></td>
        <td><div align="left"><b><?php echo $rowBol['nrocuota']; ?></b></div></td>
      </tr>
      <tr>
        <td><div align="left">Importe</div></td>
        <td><div align="left"><b><?php echo $rowBol['importe']; ?></b></div></td>
      </tr>
      <tr>
        <td><div align="left">COD. Identificacion Boleta </div></td>
        <td><div align="left"><b><?php echo $rowBol['nrocontrol']; ?></b></div></td>
      </tr>
    </table>
    <h3>Datos Anulaci&oacute;n </h3>
    <table width="500" border="1">
      <tr>
        <td><div align="right">Documento en mano </div></td>
        <td>
	      <div align="center">
	  <?php if ($docuMano == 1) { ?>
				<input type='text' id='docuMano' name='docuMano' value='SI' size='2' readonly='readonly' style='background:#CCCCCC; text-align:center' />
	  <?php	} else { ?>
				<input type='text' id='docuMano' name='docuMano' value='NO' size='2' readonly='readonly' style='background:#CCCCCC; text-align:center' />
	  <?php	} ?>
          </div>
        </td>
      </tr>
      <tr>
        <td><div align="right">Motivo</div></td>
        <td><textarea name="motivo" cols="46" rows="5" style="background:#CCCCCC" readonly="readonly"><?php echo $motivo ?></textarea></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" value="Confirmar Anulacion" /></p>
  </form>
</div>
</body>
</html>
