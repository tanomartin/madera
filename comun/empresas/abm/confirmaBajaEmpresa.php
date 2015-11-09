<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

$sqlCantAcuOspim = "select * from cabacuerdosospim where cuit = $cuit and estadoacuerdo = 1";
$resCantAcuOspim = mysql_query($sqlCantAcuOspim,$db);
$CantAcuOspim = mysql_num_rows($resCantAcuOspim);

$sqlCantAcuUsimra = "select * from cabacuerdosusimra where cuit = $cuit and estadoacuerdo = 1";
$resCantAcuUsimra = mysql_query($sqlCantAcuUsimra,$db);
$CantAcuUsimra = mysql_num_rows($resCantAcuUsimra);

$sqlCabJuicios = "select * from cabjuiciosospim where cuit = $cuit";
$resCabJuicios = mysql_query($sqlCabJuicios,$db);
$canCabJuicios = mysql_num_rows($resCabJuicios);

$sqlCabJuiciosUsimra = "select * from cabjuiciosusimra where cuit = $cuit";
$resCabJuiciosUsimra = mysql_query($sqlCabJuiciosUsimra,$db);
$canCabJuiciosUsimra = mysql_num_rows($resCabJuiciosUsimra);

$controlAcuYJuicios = $CantAcuOspim + $CantAcuUsimra + $canCabJuicios + $canCabJuiciosUsimra;

$controlDDjj = 0;
if ($controlAcuYJuicios == 0) {
	//TOMO LOS LIMIETES DE MES Y ANIO
	$mesActual = date("n");
	$meslimite = date("n", (strtotime ("-6 month")));
	if ($mesActual < 8) {
		$anioLimite = date("Y") - 1;
	} else {
		$anioLimite = date("Y");
	}
	$sqlCantDdjj = "select * from cabddjjospim where cuit = $cuit and anoddjj >= $anioLimite and mesddjj >= $meslimite";
	$resCantDdjj = mysql_query($sqlCantDdjj,$db);
	$CanDdjj = mysql_num_rows($resCantDdjj);
	
	$sqlCantDdjjUsimra = "select * from cabddjjusimra where cuit = $cuit and anoddjj >= $anioLimite and mesddjj >= $meslimite";
	$resCantDdjjUsimra = mysql_query($sqlCantDdjjUsimra,$db);
	$CanDdjjUsimra = mysql_num_rows($resCantDdjjUsimra);
	
	$controlDDjj = $CanDdjj + $CanDdjjUsimra;
}

$control = $controlAcuYJuicios + $controlDDjj;

if ($control > 0) {
	header ("Location: empresa.php?origen=$origen&cuit=$cuit&bajaempre=0");
	exit(0);
}
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
   		include($libPath."cabeceraEmpresaConsulta.php");
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
