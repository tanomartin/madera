<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionUsimra.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Requerimientos :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
</script>

<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="requeListado.php">
  <p align="center">
   <input type="reset" name="volver" value="Volver" onClick="location.href = '../moduloInformes.php'" align="center"/>
  </p>
  <p align="center" class="Estilo1">Consulta de Requerimientos</p>
  <p> 
   <?php 
  		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> NO EXISTEN REQUERIMIENTOS NO ATENDIDOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> NO EXISTEN REQUERIMIENTOS ATENDIDOS </b></div>");
		}
		if ($err == 3) {
			print("<div align='center' style='color:#FF0000'><b> NO EXISTEN REQUERIMIENTOS </b></div>");
		}
  ?>
  </p>
  <div align="center">
    <table width="200" border="0">
      <tr>
        <td rowspan="3"><div align="center"><strong>FILTRO</strong></div></td>
        <td><div align="left">
          <input name="group1" type="radio" value="0" checked="checked" />
        No Atendidos</div></td>
      </tr>
      <tr>
        <td><div align="left">
          <input type="radio" name="group1" value="1" />
         Atendidos </div></td>
      </tr>
      <tr>
        <td><div align="left">
          <input type="radio" name="group1" value="2" />
        Ambos </div></td>
      </tr>
    </table>
  </div>
  <p align="center">
    <label>
    <input type="submit" name="Submit" value="Buscar" />
    </label>
  </p>
</form>
</body>
</html>
