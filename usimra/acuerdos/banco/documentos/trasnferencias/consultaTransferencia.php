<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 

$nroTrans = $_GET['nrotrans'];
$sqlTransfe = "SELECT * FROM transferenciasusimra WHERE idtransferencia = $nroTrans";
$resTransfe = mysql_query($sqlTransfe,$db);
$rowTransfe = mysql_fetch_assoc($resTransfe);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Trasnferencia USIMRA :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#B2A274">
<div align="center">
	 <input type="reset" name="volver" value="Volver" onclick="location.href = 'trasnferencias.php'" align="center"/>
	<p><span class="Estilo2"> Transferencia N&ordm; <?php echo $rowTransfe['idtransferencia'] ?></span></p>
	  <table width="400" border="1">
        <tr>
          <td width="136"><div align="right"><strong>Banco</strong></div></td>
          <td width="248">
            <div align="left"><?php echo $rowTransfe['banco'] ?></div>
		  </td>
        </tr>
        <tr>
          <td><div align="right"><strong>Sucursal</strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['sucursal'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>N&ordm; Cuenta </strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['numerocuenta'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>C.U.I.T.</strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['cuit'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Monto</strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['monto'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Nro Orden </strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['numeroorden'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Fecha</strong></div></td>
          <td><div align="left"><?php echo invertirFecha($rowTransfe['fecha']) ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Importe Comisi&oacute;n </strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['importecomision'] ?></div></td>
        </tr>
        <tr>
          <td><div align="right"><strong>Iva Comici&oacute;n </strong></div></td>
          <td><div align="left"><?php echo $rowTransfe['ivacomision'] ?></div></td>
        </tr>
  </table>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left"/>
      </p>
</div>
</body>
</html>