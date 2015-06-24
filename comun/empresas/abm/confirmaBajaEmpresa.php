<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
$resDelEmp = mysql_query($sqlDelEmp,$db);
$rowDelEmp = mysql_fetch_array($resDelEmp); 

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>


<title>.: Módulo Empresa De Baja :.</title>
</head>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaBaja").mask("99-99-9999");
});

function validar(formulario) {
	if (formulario.motivo.value == ""){
		alert("El motivo de baja es obligatorio");
		return false;
	}
	if (formulario.fechaBaja.value != "") {
		if (!esFechaValida(formulario.fechaBaja.value)) {
			alert("La fecha de baja no es valida");
			return false;
		}
	} else {
		alert("La fecha de baja es obligatoria");
		return false;
	}
	$.blockUI({ message: "<h1>Bajando Empresa... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
     <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'"/> 	
  <p><strong>Confirmaci&oacute;n de Baja de Empresa </strong></p>
  <p>
    <?php 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  </p>
  <p>
    <?php
		include("jurisdicEmpresaBaja.php");
	?>
  </p>
  <p><strong>Informaci&oacute;n de baja </strong></p>
 <form name="form1" method="post" onSubmit="return validar(this)" action="desactivarEmpresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>">
  	<table width="400" border="0">
		<tr>
		  <td><div align="right"><strong>Motivo:</strong></div></td>
		  <td><textarea name="motivo" cols="50" rows="5" id="motivo"></textarea></td>
		</tr>
		<tr>
		  <td><div align="right"><strong>Fecha</strong>:</div></td>
		  <td><input name="fechaBaja" type="text" id="fechaBaja" size="12"></td>
		</tr>
    <tr>
      <td colspan="2">	 
  			  <div align="center">
  			    <p>
  			      <input type="submit" name="Submit" id="Submit" value="Confirmar Baja">
		        </p>
		    </div>
	  </td>
    </tr>
  </table>
  </form>
</div>
</body>
</html>
