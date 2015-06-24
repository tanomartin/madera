<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); ?>

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

function getRadioButtonSelectedValue(ctrl) {
    for(i=0;i<ctrl.length;i++)
        if(ctrl[i].checked) return ctrl[i].value;
}

function validar() {
	var nroControl = document.getElementById("nroControl").value;
	var docuMano = "0";
	for(i=0; i < document.anulacion.docuMano.length; i++)
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


<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
.Estilo1 {	font-size: 18px;
	font-weight: bold;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Anulacion de Boleta :.</title>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <input type="button" name="volver" value="Volver" onclick="location.href = 'menuBoletas.php'" /> 
  <p><span class="Estilo1">M&oacute;dulo Anulacion de Bolestas Impresas</span> </p>
  <p>
  <?php 
  		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> BOLETA NO ENCONTRADA </b></div>");
		}
		if ($err == 2) {
			$control = $_GET['control'];
			print("<div align='center' style='color:#0000FF'><b> SE ANULO LA BOLETA CON CODIGO DE IDENTIFICACION <".$control."></b></div>");
		}
		
  ?>
  </p>
</div>
<form id="anulacion" name="anulacion" method="post" onsubmit="return validar(this)" action="validarAnulacion.php">
  <div align="center">
    <table width="371" border="0">
      <tr>
        <td colspan="2">
			<div align="center"><p><strong id="internal-source-marker_0.5388788003474474">Codigo de identificacion de boleta</strong></p>
          		<input name="nroControl" id="nroControl" type="text" size="17" style="text-align:center"/>
       		</div>
		</td>
      </tr>
      
      <tr>
        <td><div align="right"><p><strong>&iquest;Documentacion en Mano?</strong></p></div></td>
        <td>
          <div align="left">
            <input name="docuMano" id="docuMano" type="radio" value="1" /> SI
            <input name="docuMano" id="docuMano" type="radio" value="2" /> NO		 
		  </div>	  
		 </td>
      </tr>
      <tr>
        <td><div align="right"><p><strong>&iquest;Fue al Banco?</strong></p></div></td>
        <td>
			<div align="left">
				<input name="fueBanco" id="radio" type="radio" value="1" onclick="botonAnular(this.value)"/> SI
				<input name="fueBanco" id="radio" type="radio" value="2" onclick="botonAnular(this.value)"/> NO		
			</div>		
		</td>
      </tr>
      <tr>
        <td colspan="2"><div align="center"><p><strong>Motivo de Anulaci&oacute;n </strong></p></div></td>
      </tr>
      <tr>
        <td colspan="2">
			<div align="center">
          		<textarea name="motivo" id="motivo" cols="50" rows="5"></textarea>
        	</div>
		</td>
      </tr>
      <tr>
        <td colspan="2">
          <div align="center">
            <p><input type="submit" name="anular" id="anular" value="Anular" disabled="disabled" /></p>
           </div>       
		 </td>
      </tr>
    </table>
  </div>
</form>
</body>
</html>
