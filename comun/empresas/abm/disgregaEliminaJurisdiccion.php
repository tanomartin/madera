<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");
	
$cuit=$_GET['cuit'];
$codidelega=$_GET['coddel'];

$sqljuris = "select * from jurisdiccion where cuit = $cuit";
$resjuris = mysql_query($sqljuris,$db); 
$canjuris = mysql_num_rows($resjuris); 

$sqlDeleteJurisdiccion = "DELETE FROM jurisdiccion WHERE cuit = $cuit and codidelega = $codidelega";

//print($sqlDeleteJurisdiccion);print("<br>");
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
	for (var i=1; i<=<?php echo $canjuris + 1 ?>; i++) {
		if (<?php echo $canjuris ?> == 2) {
			$("#disgdinero"+i).mask("999.99");
		} else {
			$("#disgdinero"+i).mask("99.99");
		}
	}
});

function validar(formulario) {
	formulario.Submit.disabled = true;
	total = 0;
	for (var i=1; i<=<?php echo $canjuris ?>; i++) {
		nombre = "disgdinero"+i;
		disgre = document.getElementById(nombre).value;
		delega = "delega"+i;
		codidelega = document.getElementById(delega).value;
		if (codidelega != <?php echo $codidelega ?>) {
			if (parseFloat(disgre) == 0) {
				alert("El porcentaje no puede ser 0 %");
				formulario.Submit.disabled = false;
				return false;
			}
		}
		total += parseFloat(disgre);
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
<form id="disDinero" name="disDinero" method="post" onSubmit="return validar(this)" action="guardarDisgregacionElimina.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&cantjuris=<?php echo $canjuris ?>&coddel=<?php echo $codidelega ?>">
    <p>
	 <input type="reset" name="volver" value="Volver" onClick="location.href = 'confirmaEliminarJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&coddel=<?php echo $codidelega ?>'"/> 
  </p>
  	<p>
  	  <?php 	
  	  	include($libPath."cabeceraEmpresaConsulta.php");
		include($libPath."cabeceraEmpresa.php"); 
	?>
</p>
    <input style="visibility:hidden" name="sqlNuevaJuris" type="text" id="sqlNuevaJuris" size="50" value="<?php echo $sqlDeleteJurisdiccion ?>"  readonly="readonly"/>
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
				
				if ($delega == $codidelega) {
					if ($canjuris == 2) {
						print("<td align='center'><input style='background-color:#CCCCCC' readonly='readonly'  name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value='000.00'> %</td>");
					} else {
						print("<td align='center'><input style='background-color:#CCCCCC' readonly='readonly'  name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value='00.00'> %</td>");
					}
				} else {
					if ($canjuris == 2) {
						print("<td align='center'><input style='background-color:#CCCCCC' readonly='readonly'  name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value='100.00'> %</td>");
					} else {
						print("<td align='center'><input name='disgdinero".$contador."' id='disgdinero".$contador."' type='text' size='4' value=''> %</td>");
					}
				}
				print ("</tr>"); 
				
	       }
		   ?>
  	</table> 
    <p>      
      <input type="submit" name="Submit" id="Submit" value="Guardar" />
    </p>
</form>
</div>
</body>
</html>
