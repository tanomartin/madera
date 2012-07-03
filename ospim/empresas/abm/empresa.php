<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: moduloABM.php?err=1");
}
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
</style>

<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99-99999999-9");
});
</script>

<title>.: Módulo Empresa :.</title>
</head>
<body bgcolor="#CCCCCC" > 
<div align="center">
  <p><strong><a href="moduloABM.php"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p>
    <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/cabeceraEmpresa.php"); 
	?>
</p>
  <form name="empresa" id="empresa" method="post" action="guardarEmpresa.php">
    <table width="743" height="98" border="0">
      <tr>
        <td width="57">C.U.I.T. </td>
        <td width="253"><label>
          <input name="cuit" type="text" id="cuit" value="<?php echo $row['cuit'];?>">
        </label></td>
        <td width="117"><label>Razón Social</label></td>
        <td width="298"><input name="nombre" type="text" id="nombre" value="<?php echo $row['nombre'];?>"></td>
      </tr>
      <tr>
        <td><label>Domicilio</label></td>
        <td><input name="domicilio" type="text" id="domicilio" value="<?php echo $row['domilegal'];?>"></td>
        <td><label>Localidad</label></td>
        <td><input name="localidad" type="text" id="localidad" value="<?php echo $rowlocalidad['nomlocali'];?>"></td>
      </tr>
      <tr>
        <td><label>Provincia</label></td>
        <td><input name="provincia" type="text" id="provincia" value="<?php echo $rowprovi['descrip']; ?>"></td>
        <td><label>Codigo Postal</label></td>
        <td><input name="codPos" type="text" id="codPos" value="<?php echo $row['numpostal'];?>"></td>
      </tr>
    </table>
    <p>
      <label>
      <input type="submit" name="Submit" value="Guardar">
      </label>
    </p>
  </form>
  </div>
</body>
</html>
