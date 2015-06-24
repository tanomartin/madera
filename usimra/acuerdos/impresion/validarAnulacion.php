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

<body bgcolor="#B2A274">
<div align="center">
  <input type="button" name="volver" value="Volver" onclick="location.href = 'cargaAnulacion.php'" />
  <p class="Estilo1"><strong>Verificacion Anulacion</strong> </p>
  <form id="form1" name="form1" method="post" action="anularBoleta.php?idboleta=<?php echo $rowBol['idboleta']; ?> ">
	<p><strong>Datos Boleta </strong></p>
	<table width="522" border="1">
      <tr>
        <td width="205"><div align="left">CUIT</div></td>
        <td width="301"><div align="left"><strong><?php echo $rowBol['cuit']; ?></strong></div></td>
      </tr>
      <tr>
        <td width="205"><div align="left">Raz&oacute;n Social </div></td>
        <td><div align="left"><strong>
          <?php echo $rowBol['nombre']; ?>
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
    <table width="400" border="1">
      <tr>
        <td><div align="right">Documento en mano </div></td>
        <td>
	      <div align="center">
	        <?php 
			if ($docuMano == 1) {
				print("<input type='text' id='docuMano' name='docuMano' value='SI' size='2' readonly='readonly' style='background:#CCCCCC; text-align:center' />");
			} else {
				print("<input type='text' id='docuMano' name='docuMano' value='NO' size='2' readonly='readonly' style='background:#CCCCCC; text-align:center' />");
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
