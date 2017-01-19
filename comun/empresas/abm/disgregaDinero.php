<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");
	
$cuit=$_GET['cuit'];

include($libPath."cabeceraEmpresaConsulta.php");

$sqljuris = "select * from jurisdiccion where cuit = $cuit";
$resjuris = mysql_query($sqljuris,$db); 
$canjuris = mysql_num_rows($resjuris); 

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	for (var i=1; i<=<?php echo $canjuris ?>; i++) {
		$("#disgdinero"+i).mask("99.99");
	}
});

function validar(formulario) {
	formulario.Submit.disabled = true;
	total = 0;
	for (var i=1; i<=<?php echo $canjuris ?>; i++) {
		nombre = "disgdinero"+i;
		if (parseFloat(document.getElementById(nombre).value) == 0) {
			alert("El porcentaje no puede ser 0 %");
			formulario.Submit.disabled = false;
			return false;
		}
		total += parseFloat(document.getElementById(nombre).value);
	}
	total = Math.round(total*100)/100;
	if (total != 100) {
		alert("La suma de los porcentajes debe ser 100%");
		formulario.Submit.disabled = false;
		return false;
	}
	return true;
}

</script>

<title>.: Disgregacion Dineraria :.</title>
</head>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
<form id="disDinero" name="disDinero" method="post" onSubmit="return validar(this)" action="guardarDisgregacion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&cantjuris=<?php echo $canjuris ?>">
       <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'"/> 
  	<p>
  	  <?php 	
		include($libPath."cabeceraEmpresa.php"); 
	?>
</p>
  	<p><strong>Disgregacion Dineraria Por Jurisdicci&oacute;n   </strong></p>
  	<table width="700" border="1">
      <tr>
        <td width="40%"><div align="center"><strong>Delegacion</strong></div></td>
        <td width="40%"><div align="center"><strong>Provincia</strong></div></td>
        <td width="20%"><div align="center"><strong>Disgregacion</strong></div></td>
  	 </tr> 			
	 
	 <?php
	 	$contador = 0;
	 	while ($rowjuris = mysql_fetch_array($resjuris)) { 
				$contador += 1;
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
				print("<td align='center'><input name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value=''> %</td>");
				print ("</tr>"); 
				
	       }?>
  	</table> 
    <p>
      <input type="submit" name="Submit" id="Submit" value="Guardar" />
    </p>
  </form>
</div>
</body>
</html>
