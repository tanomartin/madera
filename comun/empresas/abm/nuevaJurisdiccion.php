<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
include($libPath."fechas.php");
$cuit=$_GET['cuit'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nueva Jurisdicciones Empresa :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	$("#alfapostal").mask("aaa");
	
	$("#codPos").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "localidadPorCP.php?origen=<?php echo $origen ?>",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectLocali").html(respuesta);
			$("#indpostal").val("");
			$("#alfapostal").val("");
			$("#provincia").val("");
			$("#codprovin").val("");
			$("#selectDelegacion").html("<option title ='Seleccione un valor' value='0'>Seleccione un valor</option>");
		});
	});

	$("#selectLocali").change(function(){
		var locali = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "cambioProvincia.php?origen=<?php echo $origen ?>",
			data: {locali:locali},
		}).done(function(respuesta){
			$("#indpostal").val(respuesta.indpostal);
			$("#provincia").val(respuesta.descrip);
			$("#codprovin").val(respuesta.codprovin);
			$("#selectDelegacion").html("<option title ='Seleccione un valor' value='0'>Seleccione un valor</option>");
		});
	});
	
	$("#selectLocali").focusout(function(){
		var codigo = $("#codprovin").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "buscaJurisdicciones.php?origen=<?php echo $origen ?>",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectDelegacion").html(respuesta);
		});
	});	
	
});

function validar(formulario) {
	if (formulario.domicilio.value == "") {
		alert("El campo domicilio es obligatrio");
		return false;
	}
	if (formulario.codPos.value == "") {
		alert("El campo Codigo Postal es obligatrio");
		return false;
	} else {
		if (!esEnteroPositivo(formulario.codPos.value)){
		 	alert("El campo Codigo Postal tiene que ser numerico");
			return false;
		}
	}
	if (formulario.selectLocali.options[formulario.selectLocali.selectedIndex].value == 0) {
		alert("Debe elegir una Localidad");
		return false;
	}
	
	if (formulario.ddn1.value != "") {
		if (!esEnteroPositivo(formulario.ddn1.value)) {
			alert("El codigo de area 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	} else {
		formulario.telefono1.value = "0";
	}
	
	if (formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value == 0) {
		alert("Debe elegir una Delegacion");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <form name="nuevaJurisdiccion" id="nuevaJurisdiccion" method="post" onsubmit="return validar(this)" action="disgregaNuevaJurisdiccion.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>">	
	<input type="reset" name="volver" value="Volver" onclick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'"/> 
  	<p><strong>Nueva  Jurisdicci&oacute;n de Empresa</strong></p>
  
	 	<table width="723" border="0">
		  <tr>
			<td width="167"><div align="right"><strong>C.U.I.T. </strong></div></td>
			<td width="540"><div align="left">
				<input style="background-color:#CCCCCC" name="cuit" type="text" id="cuit" size="12" value="<?php echo $cuit ?>"  readonly="readonly"/>                
			  </div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Domicilio</strong></div></td>
			<td><div align="left">
			  <input name="domicilio" type="text" id="domicilio" size="90" />
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Codigo Postal</strong></div></td>
			<td><div align="left">
			  <label>
			  <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
			  </label>
			  -
			  <input name="codPos" type="text" id="codPos" size="7"/>
			  -        
			  <label>
			  <input name="alfapostal" id="alfapostal" type="text" size="3" />
			  </label>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Localidad</strong></div></td>
			<td><div align="left">
				<select name="selectLocali" id="selectLocali">
				  <option value="0">Seleccione un valor </option>
				</select>
			</div></td>
		  </tr>
		  
		  <tr>
			<td><div align="right"><strong>Provincia</strong></div></td>
			<td><div align="left">
				<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" />
				<input style="background-color:#CCCCCC; visibility:hidden" readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Delegacion</strong></div></td>
			<td><div align="left">
				<select name="selectDelegacion" id="selectDelegacion">
				  <option value="0">Seleccione un valor </option>
				</select>
			</div></td>
		  </tr>
		  
		  <tr>
			<td><div align="right"><strong>Telefono 1 </strong></div></td>
			<td>
			  <div align="left">
				<input name="ddn1" type="text" id="ddn1" size="5" />
				- 
				<input name="telefono1" type="text" id="telefono1" size="10" />
			  </div>        </td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Contacto 1 </strong></div></td>
			<td>
			  <div align="left">
				<input name="contacto1" type="text" id="contacto1" size="50" />
			  </div>			</td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Email</strong></div></td>
			<td><div align="left">
				<input name="email" type="text" id="email" size="50" />
			</div></td>
		  </tr>
	</table>
    <p>
      <label>
      <input type="submit" name="Submit" id="Submit" value="Reasingar Disgregacion Dineraria" />
      </label>
    </p>
  </form>
</div>
</body>
</html>
