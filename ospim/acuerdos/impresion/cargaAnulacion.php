<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
		$("#nroControl").mask("99999999999999");
});

function getRadioButtonSelectedValue(ctrl)
{
    for(var i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}

function validar() {
	var nroControl = document.getElementById("nroControl").value;
	var docuMano = "0";
	for(var i=0; i < document.anulacion.docuMano.length; i++)
    	if(document.anulacion.docuMano[i].checked) docuMano = document.anulacion.docuMano[i].value;
	var motivo = document.getElementById("motivo").value;
	
	if (nroControl == "") {
		alert("Debe insertar numero de control");
		return false;
	}
	if (docuMano == 0) {
		alert("Debe informar Documentacion en Mano");
		return false;
	}
	if (motivo == "") {
		alert("Debe informar Motivo de Anulacion");
		return false;
	}
	return true;
}

function botonAnular(valor) {
	if (valor == 1) {
		document.getElementById("anular").disabled = true;
	}
	if (valor == 2) {
		document.getElementById("anular").disabled = false;
	}
}

</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Anulacion de Boleta :.</title>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="anulacion" name="anulacion" method="post" onsubmit="return validar(this)" action="validarAnulacion.php">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuBoletas.php'" /></p>
		<h3>Módulo Anulacion de Bolestas Impresas</h3>
		<?php if (isset($_GET['err'])) {
			  		$err = $_GET['err'];
					if ($err == 1) { ?>
						<p style='color:red'><b> BOLETA NO ENCONTRADA </b></p>
			<?php	}
					if ($err == 2) { 
						$control = $_GET['control']; ?>
						<p style='color:blue'><b> SE ANULO LA BOLETA CON CODIGO DE IDENTIFICACION "<?php echo $control ?>"</b></p>
			<?php	}
		  	  } ?>
	   	<p>
	        <b>Codigo de identificacion de boleta</b>
	      	<input name="nroControl" id="nroControl" type="text" size="17" />
	    </p>
	    <p>
	    	<b>Documentacion en Mano</b>
	       	<input name="docuMano" id="docuMano" type="radio" value="1" /> SI 
	        <input name="docuMano" id="docuMano" type="radio" value="2" /> NO	     
	    </p>
	    <p>
	    	<b>¿Fue al Banco? </b>
	        <input name="fueBanco" id="radio" type="radio" value="1" onclick="botonAnular(this.value)"/> SI
	  		<input name="fueBanco" id="radio" type="radio" value="2" onclick="botonAnular(this.value)"/> NO	
	    </p>
	    <p><b>Motivo de Anulación </b></p>
	    <p><textarea name="motivo" id="motivo" cols="50" rows="5"></textarea></p>
		<p><input type="submit" name="anular" id="anular" value="Anular" disabled="disabled" /></p>
	</form>
</div>
</body>
</html>
