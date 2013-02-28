<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php");
	
$cuit=$_GET['cuit'];

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqljuris = "select * from jurisdiccion where cuit = $cuit";
$resjuris = mysql_query($sqljuris,$db); 
$canjuris = mysql_num_rows($resjuris); 

$datos = array_values($_POST);

$cuit = $datos[0];
$domicilio = $datos[1];
$domicilio = strtoupper($domicilio);
$indpostal = $datos[2];
$codpostal = $datos[3];
$alfapostal = $datos[4];
$localidad = $datos[5];
$provincia = $datos[6];
$codprovin = $datos[7];
$delegacion = $datos[8];
$ddn1 = $datos[9];
$telefono1 = $datos[10];
$contacto1 = $datos[11];
$email = $datos[12];
$disgdinero = 0;

$sqlControlExistencia = "select * from jurisdiccion where cuit = $cuit and codidelega = $delegacion";
$resControlExistencia = mysql_query($sqlControlExistencia,$db); 
$canControlExistencia = mysql_num_rows($resControlExistencia); 
if ($canControlExistencia > 0) {
	header ("Location: empresa.php?origen=$origen&cuit=$cuit&err=$delegacion");
}

$sqlInsertJurisNueva = "INSERT INTO jurisdiccion VALUES ('$cuit','$delegacion','$codprovin','$indpostal','$codpostal','$alfapostal','$localidad','$domicilio','$ddn1','$telefono1','$contacto1','$email','$disgdinero')";

//print($sqlInsertJurisNueva);print("<br>");
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
<script language="javascript" type="text/javascript">

jQuery(function($){
	for (i=1; i<=<?php echo $canjuris + 1 ?>; i++) {
		$("#disgdinero"+i).mask("99.99");
	}
});

function validar(formulario) {
	total = 0;
	for (i=1; i<=<?php echo $canjuris + 1 ?>; i++) {
		nombre = "disgdinero"+i;
		if (parseFloat(document.getElementById(nombre).value) == 0) {
			alert("El porcentaje no puede ser 0 %");
			return false;
		}
		total += parseFloat(document.getElementById(nombre).value);
	}
	total = Math.round(total*100)/100;
	if (total != 100) {
		alert("La suma de los porcentajes debe ser 100%");
		return false;
	}
	return true;
}

</script>

<title>.: Disgregacion Dineraria :.</title>
</head>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
<form id="disDinero" name="disDinero" method="post" onSubmit="return validar(this)" action="guardarDisgregacionNueva.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&cantjuris=<?php echo $canjuris ?>">
  <p><strong><a href="nuevaJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <input style="visibility:hidden" name="sqlNuevaJuris" type="text" id="sqlNuevaJuris" size="50" value="<?php echo $sqlInsertJurisNueva ?>"  readonly="readonly"/>
  	<p>
  	  <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/comun/lib/cabeceraEmpresa.php"); 
	?>
</p>
  	<p><strong>Disgregacion Dineraria Por Jurisdicci&oacute;n   </strong></p>
  	<table width="800" border="1">
      <tr>
        <td width="40%"><div align="center"><strong>Delegacion</strong></div></td>
        <td width="40%"><div align="center"><strong>Provincia</strong></div></td>
        <td width="20%"><div align="center"><strong>Disgregacion</strong></div></td>
  	 </tr> 			
	 
	 <?php while ($rowjuris = mysql_fetch_array($resjuris)) { 
				$contador = $contador + 1;
	 			$delega = $rowjuris['codidelega'];
				$sqldelegacion = "select * from delegaciones where codidelega = $delega";
				$resultdelegacion = mysql_query($sqldelegacion,$db); 
				$rowdelegacion = mysql_fetch_array($resultdelegacion); 
	 			print("<td align='center'><font face=Verdana size=2>".$rowdelegacion['nombre']."</font>");
				print("<input style='visibility:hidden' name='delega".$contador."' id='delega".$contador."' type='text' size='1' value='".$delega."'></td>");
				$sqlprovi =  "select * from provincia where codprovin = $rowjuris[codprovin]";
				$resultprovi = mysql_query($sqlprovi,$db); 
				$rowprovi = mysql_fetch_array($resultprovi);
				print("<td align='center'><font face=Verdana size=2>".$rowprovi['descrip']."</font></td>");
				print("<td align='center'><input name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value='".$rowjuris['disgdinero']."'> %</td>");
				print ("</tr>"); 
				
	       }
		   //COLOCO LA NUEVA JURISDICCION
		   $contador = $contador + 1;
		   $sqldelegacion = "select * from delegaciones where codidelega = $delegacion";
		   $resultdelegacion = mysql_query($sqldelegacion,$db); 
		   $rowdelegacion = mysql_fetch_array($resultdelegacion); 
	 	   print("<td align='center'><font face=Verdana size=2>".$rowdelegacion['nombre']."</font>");
		   print("<input style='visibility:hidden' name='delega".$contador."' id='delega".$contador."' type='text' size='1' value='".$delegacion."'></td>");
		   $sqlprovi =  "select * from provincia where codprovin = $codprovin";
		   $resultprovi = mysql_query($sqlprovi,$db); 
		   $rowprovi = mysql_fetch_array($resultprovi);
		   print("<td align='center'><font face=Verdana size=2>".$rowprovi['descrip']."</font></td>");
		   print("<td align='center'><input name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value='".$disgdinero."'> %</td>");
		   print ("</tr>"); 
		   ?>
  	</table> 
    <p>      
      <input type="submit" name="Submit" value="Guardar" />
    </p>
</form>
</div>
</body>
</html>
