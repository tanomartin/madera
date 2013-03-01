<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/fechas.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresasdebaja where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
$resDelEmp = mysql_query($sqlDelEmp,$db);
$rowDelEmp = mysql_fetch_array($resDelEmp); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	color: #006666;
	font-weight: bold;
}
</style>


<title>.: Módulo Empresa De Baja :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><strong><a href="moduloABM.php?origen=<?php echo $origen ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <h2 class="Estilo1">EMPRESA DE BAJA </h2>
  <p>
    <?php 
		include($_SERVER['DOCUMENT_ROOT']."/comun/lib/cabeceraEmpresa.php"); 
	?>
  </p>
  <p><strong>Informaci&oacute;n de baja </strong></p>
  <table width="405" border="2">
    <tr>
      <td width="66" bordercolor="#000000"><div align="right"><strong>Motivo:</strong></div></td>
      <td width="323" bordercolor="#000000"><div align="left"><?php echo $row['motivobaja'] ?></div></td>
    </tr>
    <tr>
      <td height="23" bordercolor="#000000"><div align="right"><strong>Fecha:</strong></div></td>
      <td bordercolor="#000000"><div align="left">
        <?php echo (invertirFecha($row['fechabaja'])) ?>
      </div></td>
    </tr>
  </table>
  <p>
    <input name="cuentaCorriente" type="button" value="Cuenta Corriente">
  </p>
  <p>
    <?php
		include($_SERVER['DOCUMENT_ROOT']."/comun/empresas/abm/jurisdicEmpresaBaja.php");
	?>
  </p>
  <p>
    <input name="Input" type="button" value="Reactivar Empresa" onClick='location.href="reactivarEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'">
  </p>
  <p>
    <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left" >
  </p>
</div>
</body>
</html>
