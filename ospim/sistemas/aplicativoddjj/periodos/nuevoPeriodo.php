<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Periodo :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript">
jQuery(function($){
	$("#anio").mask("9999");
	$("#mes").mask("99");
	$("#mesrelacion").mask("99");

	$("#mes").change(function(){
		$("#mesrelacion").prop("disabled",true);
		$("#tipo").prop("disabled",true);
		$("#valor").prop("disabled",true);
		$("#ret060").prop("disabled",true);
		$("#ret100").prop("disabled",true);
		$("#ret150").prop("disabled",true);
		$("#mensaje").prop("disabled",true);
		var mes = $(this).val();
		if (mes > 12) {
			$("#mesrelacion").prop("disabled",false);
			$("#tipo").prop("disabled",false);
			$("#valor").prop("disabled",false);
			$("#ret060").prop("disabled",false);
			$("#ret100").prop("disabled",false);
			$("#ret150").prop("disabled",false);
			$("#mensaje").prop("disabled",false);
		}
	});

	$("#tipo").change(function(){
		$("#valor").val("");
		var tipo = $(this).val();
		if (tipo == 1)  {
			$("#valor").mask("9.999");
		} else {
			$("#valor").unmask("9.999");
		}
	});
});


function validar(formulario) {
	if(formulario.anio.value == "") {
		alert("Debe selecionar el año");
		return false;
	}
	if(formulario.mes.value == "") {
		alert("Debe selecionar el mes");
		return false;
	}
	if(formulario.descripcion.value == "") {
		alert("Debe ingresar una Descripcion para el periodo");
		return false;
	}
	if (formulario.mes.value > 12) {
		if(formulario.mesrelacion.value == "" || formulario.mesrelacion.value > 12) {
			alert("Debe ingrear el Mes al cual se relaciona el Periodo Extraordinario y este debe ser entre 1 y 12");
			return false;
		}
		if(formulario.tipo.value == -1) {
			alert("Debe seleccionar el Tipo de Periodo Extraordinario");
			return false;
		}
		if(formulario.valor.value == "") {
			alert("Debe seleccionar el Valor del Periodo Extraordinario");
			return false;
		} else {
			if (formulario.tipo.value == 1) {
				if (formulario.valor.value >= 1) {
					alert("Por el Tipo de Periodo Extraordinario el valor debe estar entre 0 y 1");
					return false;
				}
			} else {
				if (!isNumberPositivo(formulario.valor.value)) {
					alert("Por el Tipo de Periodo Extraordinario el valor debe ser un numero positivo");
					return false;
				}
			}
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'periodos.php'" />
 </p>
  <p><span class="Estilo2">Nuevo Periodo</span></p>
  <p><span class="Estilo2">Datos Periodo</span></p>
  <form id="nuevoPeriodo" name="nuevoPeriodo" method="post" action="guardarNuevoPeriodo.php" onsubmit="return validar(this)">		
	<table>
    	<tr>
           	<td>Año <input name="anio" type="text" id="anio" size="6"/></td>
            <td>Mes <input name="mes" type="text" id="mes" size="4"/></td>
            <td>Descripcion <input name="descripcion" type="text" id="nroserie" size="60"/></td>
        </tr>
    </table>
    <p><span class="Estilo2">Datos Periodo Extraordinario</span></p>
    <table>
        <tr>
           <td>Mes relacionado <input name="mesrelacion" type="text" id="mesrelacion" size="4" disabled="disabled"/></td>
           <td colspan="3">Tipo 
           		<select id="tipo" name="tipo" disabled="disabled">
           			<option value='-1'>Seleccion Tipo</option>
           			<option value='0'>0 - Contribucion Ext. No Remunerativa (Monto Fijo)</option>
           			<option value='1'>1 - Aumento No Remunerativo (Alicuota)</option>
           			<option value='2'>2 - Cuotas Extraordinarias (Monto Fijo)</option>
           			<option value='3'>3 - Extraordinario SAC</option>
           		</select>
           </td>
         </tr>
         <tr>
           <td>Valor <input name="valor" type="text" id="valor" size="14" disabled="disabled"/></td>
           <td>Retiene 060 
           		<select id="ret060" name="ret060" disabled="disabled">
           			<option value='0'>NO</option>
           			<option value='1'>SI</option>
           		</select>
           </td>
           <td>Retiene 100 
           		<select id="ret100" name="ret100" disabled="disabled">
           			<option value='0'>NO</option>
           			<option value='1'>SI</option>
           		</select>
           </td>
           <td>Retiene 150 
           		<select id="ret150" name="ret150" disabled="disabled">
           			<option value='0'>NO</option>
           			<option value='1'>SI</option>
           		</select>
           </td>
         </tr>
         <tr>
         	<td colspan="4">Mensaje <textarea id="mensaje" name="mensaje" rows="4" cols="70" disabled="disabled"></textarea></td>
         </tr>
	  </table>
	  <p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>