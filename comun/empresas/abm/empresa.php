<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant == 0) {
	//Aca hay que buscar en empresa de baja y mandar a la pantalla de consulta y si no hay es nueva...
	header ("Location: nuevaEmpresa.php?origen=$origen&cuit=$cuit");
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


<title>.: Módulo Empresa :.</title>
</head>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><strong><a href="moduloABM.php?origen=<?php echo $origen ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p>
    <?php 
		$err = $_GET['err'];
		if ($err > 0) {
			$sqldelegacion = "select * from delegaciones where codidelega = $err";
			$resultdelegacion = mysql_query($sqldelegacion,$db); 
			$rowdelegacion = mysql_fetch_array($resultdelegacion); 
			print("<div align='center' style='color:#FF0000'><b> ERROR JURISDICCION EXISTENTE </b></div>");
			print("<div align='center' style='color:#FF0000'><b> NO SE PUEDE CARGAR LA JURISDICCION </b></div>");
			print("<div align='center' style='color:#FF0000'><b>".$rowdelegacion['nombre']." </b></div>");
		}
		
		include($_SERVER['DOCUMENT_ROOT']."/comun/lib/cabeceraEmpresa.php"); 
	?>
  </p>
  <table width="354" border="0">
    <tr>
      <td width="112"><div align="center">
        <input name="Input" type="button" value="Modificar Cabecera" onClick="location.href='modificarCabecera.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?> '">
      </div></td>
      <td width="123"><div align="center">
        <input name="Input2" type="button" value="Cuenta Corriente">
      </div></td>
      <td width="97"><div align="center">
        <input name="Input3" type="button" value="Beneficiarios">
      </div></td>
    </tr>
  </table>
  <p>
    <?php
		include($_SERVER['DOCUMENT_ROOT']."/comun/empresas/abm/jurisdicEmpresa.php");
	?>
  </p>
  <p>
    <input name="Input5" type="button" value="Disgregacion Dineraria" onClick='location.href="disgregaDinero.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'>
    <input name="Input4" type="button" value="Agreagar Jurisdiccion" onclick='location.href="nuevaJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"'>
  </p>
  <p>
    <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left" >
  </p>
</div>
</body>
</html>
