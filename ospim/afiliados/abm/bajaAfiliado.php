<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$tipafiliado=$_GET['tipAfi'];

if($tipafiliado == 1) {
	$sqlLeeAfiliado = "SELECT apellidoynombre FROM titulares WHERE nroafiliado = $nroafiliado";
}
else {
	$ordafiliado=$_GET['nroOrd'];
	$sqlLeeAfiliado = "SELECT apellidoynombre FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $ordafiliado";
}

$resLeeAfiliado = mysql_query($sqlLeeAfiliado,$db);
$rowLeeAfiliado = mysql_fetch_array($resLeeAfiliado);

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $tipafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
<title>.: Baja :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechabaja").mask("99-99-9999");
});
function validar(formulario) {
	var fechahoy=new Date();
	var anohoy = fechahoy.getFullYear();
	var meshoy;

	if((fechahoy.getMonth()+1) < 10) {
		meshoy = "0"+(fechahoy.getMonth()+1);
	} else {
		meshoy = (fechahoy.getMonth()+1);
	}

	var diahoy;

	if(fechahoy.getDate() < 10) {
		diahoy = "0"+fechahoy.getDate();
	} else {
		diahoy = fechahoy.getDate();
	}

	var fechacontrol = new Date(Date.parse(anohoy+"-"+meshoy+"-"+diahoy));	

	if (formulario.fechabaja.value == "") {
		alert("La fecha de baja es obligatoria");
		document.getElementById("fechabaja").focus();
		return false;
	} else {
		if (!esFechaValida(formulario.fechabaja.value)) {
			alert("La fecha de baja es invalida");
			document.getElementById("fechabaja").focus();
			return false;
		}
	}

	var fechacargada = new Date(Date.parse(invertirFecha(formulario.fechabaja.value)));

	if(fechacargada > fechacontrol) {
		alert("La fecha de baja debe ser menor o igual a la fecha de hoy");
		document.getElementById("fechabaja").focus();
		return false;
	}

	if (formulario.motivobaja.value == "") {
		alert("El motivo de baja es obligatorio");
		document.getElementById("motivobaja").focus();
		return false;
	}

	if (formulario.tipafiliado.value == "1") {
		$.blockUI({ message: "<h1>Bajando Titular y su Grupo Familiar. Aguarde por favor...</h1>" });
	} else {
		$.blockUI({ message: "<h1>Bajando Familiar. Aguarde por favor...</h1>" });
	}

	return true;
}
</script>
</head>
<body bgcolor="#CCCCCC" >
<form enctype="multipart/form-data" id="formBajaAfiliado" name="formBajaAfiliado" method="post" onSubmit="return validar(this)" action="guardarBaja.php">
	<div align="center">
	<?php
	if($tipafiliado == 1) {
	?>
			<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1'" align="center"/>
	<?php
	}
	else {
	?>
			<input class="nover" type="reset" name="volver" value="Volver" onClick="location.href = 'fichaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&estAfi=1&estFam=1&nroOrd=<?php echo $ordafiliado?>'" align="center"/>
	<?php
	}
	?>
	</div>
	<p></p>
	<div align="center" class="Estilo4">
	<?php if ($tipafiliado == 1) echo "Baja de Titular"; else echo "Baja de Familiar";?>
	</div>
	<p></p>
	<div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span>
	  <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $nroafiliado ?>" size="9" readonly="true" style="background-color:#CCCCCC" />
	  <input name="apellidoynombre" type="text" id="nroafiliado" value="<?php echo $rowLeeAfiliado['apellidoynombre'] ?>" size="100" readonly="true" style="background-color:#CCCCCC" />
	  <input name="tipafiliado" type="text" id="tipafiliado" value="<?php echo $tipafiliado ?>" size="1" readonly="true" style="visibility:hidden" />
	  <input name="nroorden" type="text" id="nroorden" value="<?php echo $ordafiliado ?>" size="3" readonly="true" style="visibility:hidden" />
    </div>
	<p></p>
	<table width="100%" border="0">
		<tr>
			<td width="125">Fecha de Baja:</td>
			<td><input name="fechabaja" type="text" id="fechabaja" value="" size="12"/></td>
		</tr>
		<tr>
			<td>Motivo de Baja:</td>
			<td><textarea name="motivobaja" cols="120" rows="5" id="motivobaja"></textarea></td>
		</tr>
	</table>
	<p></p>
	<div align="center">
		<input class="nover" type="submit" name="guardar" value="Bajar" align="center"/> 
	</div>
</form>
</body>
</html>
