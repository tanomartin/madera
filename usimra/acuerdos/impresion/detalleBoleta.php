<?php  $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

$nrocontrol = $_GET['nrocontrol'];
$estado = $_GET['estado'];
if ($estado == "Generada") { $sqlBoleta = "SELECT b.*, e.nombre from boletasusimra b, empresas e where b.nrocontrol = $nrocontrol and b.cuit = e.cuit"; }
if ($estado == "Validada") { $sqlBoleta = "SELECT v.*, e.nombre from validasusimra v, empresas e where v.nrocontrol = $nrocontrol and v.cuit = e.cuit"; }
if ($estado == "Anulada") { $sqlBoleta = "SELECT a.*, e.nombre from anuladasusimra a, empresas e where a.nrocontrol = $nrocontrol and a.cuit = e.cuit"; }

$resBoleta = mysql_query($sqlBoleta,$db); 
$rowAMostrar = mysql_fetch_array($resBoleta);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Detalle Boleta :.</title>
</head>
<body bgcolor="#B2A274">
<div align="center">
    <p><strong>DETALLE DE BOLETE CON NRO DE CONTROL <?php echo $nrocontrol ?></strong></p>
    <table width="543" border="2">
      <tr>
        <td width="160"><div align="right">CUIT</div></td>
        <td width="371"><div align="left"><strong><?php echo $rowAMostrar['cuit']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Raz&oacute;n Social </div></td>
        <td><div align="left">
          <div align="left"><strong>
            <?php echo $rowAMostrar['nombre']; ?>
          </strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Acuerdo </div></td>
        <td><div align="left">
          <div align="left"><strong><?php echo $rowAMostrar['nroacuerdo']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Cuota</div></td>
        <td><div align="left">
          <div align="left"><strong><?php echo $rowAMostrar['nrocuota']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Importe</div></td>
        <td><div align="left">
          <div align="left"><strong><?php echo $rowAMostrar['importe']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Identificacion Boleta </div></td>
        <td><div align="left">
          <div align="left"><strong><?php echo $rowAMostrar['nrocontrol']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Usuario Emisi&oacute;n </div></td>
        <td><div align="left">
          <div align="left"><strong><?php echo $rowAMostrar['usuarioregistro']; ?></strong></div>
        </div></td>
      </tr>
    </table>
    <p>
      <?php 
	if ($estado == "Anulada") {
		print("<div align='center' style='color:#000000'><b> INFORMACION DE ANULACION</b></div><br>"); ?>
	</p>
    <table width="543" border="2">
      <tr>
        <td width="180"><div align="right">Fecha Anulacion</div></td>
        <td width="351"><div align="left"><strong><?php echo $rowAMostrar['fechaanulacion']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Docuemtancion en Mano</div></td>
        <td><div align="left">
            <div align="left"><strong>
              <?php 
		   if($rowAMostrar['documentacionenmano'] == 0) {
		   		echo "NO";
		   } else {
		   		echo "SI";
		   }
		?>
            </strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Motivo</div></td>
        <td><div align="left">
            <div align="left"><strong><?php if($rowAMostrar['motivoanulacion'] != "" ) {echo $rowAMostrar['motivoanulacion'];} else { echo "-"; } ?></strong></div>
        </div></td>
      </tr>
    </table>
		 <?php } ?>
    <p>
      <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" />
    </p>
</div>
</body>
</html>