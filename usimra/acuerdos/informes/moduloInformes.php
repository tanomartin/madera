<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Informes de Acuerdos :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body bgcolor="#B2A274">
<div align="center">
	<input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'"/> 
  <p><span class="Estilo2">M&oacute;dulo De Informes</span></p>
  <table style="width: 614; height: 506" border="1">
    <tr>
     <td width="196" height="164"><p align="center">Cheques en Cartera</p>
        <p align="center"><a class="enlace" href="chequesCartera.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Liquidaciones</p>
      <p align="center"><a class="enlace" href="liquidaComisiones.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Cuotas Vencidas</p>
        <p align="center"><a class="enlace" href="cuotasVencidas.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
    </tr>
    <tr>
      <td width="196" height="164"><p align="center">Cheques Rechazados</p>
        <p align="center"><a class="enlace" href="chequesRechazados.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Deuda de Acuerdos Por Año</p>
          <p align="center"><a class="enlace" href="deudaAcuerdos.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="196" height="164"><p align="center">Verificacion de Cuotas </p>
          <p align="center"><a class="enlace" href="verificacionCuotas.php"><img src="img/excellogo.png" width="105" height="106" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
    <tr>
      <td height="164"><p align="center">Distintos Montos </p>
      <p align="center"><a class="enlace" href="distintoMonto.php"><img src="img/excellogo.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
      <td height="164"><p align="center">Periodos Repetidos </p>
      <p align="center"><a class="enlace" href="repeticionPeriodos.php"><img src="img/excellogo.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p align="center">&nbsp;</p></td>
      <td height="164"><p align="center">Existencia de Caja </p>
        <p align="center"><a class="enlace" href="caja.php"><img src="img/excellogo.png" width="105" height="105" border="0" alt="enviar"/></a></p>
      <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>
