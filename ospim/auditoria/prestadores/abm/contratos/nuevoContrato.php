<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");
include($libPath."funcionespracticas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT codigoprestador, nombre FROM prestadores WHERE codigoprestador = $codigo";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Contrato :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechaInicio").mask("99-99-9999");
	$("#fechaFin").mask("99-99-9999");
});

function buscarContratos(inputCodigo) {
	codigo = inputCodigo.value;
	if (codigo != '') {
		console.log(codigo);
		if (esEnteroPositivo(codigo)) {
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getContratos.php",
				data: {codigo:codigo},
			}).done(function(respuesta){
				document.getElementById("listaContratos").innerHTML = respuesta;
			});
		} else {
			inputCodigo.value = "";
			alert("El codigo de prestador debe ser un numero positvo");	
		}
	}
}

function cargarFechas(fechainicio, fechafin) {
	document.getElementById("fechaInicio").value = fechainicio;
	document.getElementById("fechaFin").value = fechafin;
}

function habilitaTercero(valor) {
	document.getElementById("cargaTerceros").style.display = 'none';
	document.getElementById("codigoTercero").value = '';
	document.getElementById("fechaInicio").readonly = false;
	document.getElementById("fechaInicio").style.backgroundColor = "";
	document.getElementById("fechaInicio").value = "";
	document.getElementById("fechaFin").readonly = false;
	document.getElementById("fechaFin").style.backgroundColor = "";
	document.getElementById("fechaFin").value = "";
	document.getElementById("listaContratos").innerHTML = "";
	if (valor == 1) {
		document.getElementById("cargaTerceros").style.display = 'block';
		document.getElementById("fechaInicio").readonly = true;
		document.getElementById("fechaInicio").style.backgroundColor = "silver";
		document.getElementById("fechaFin").readonly = true;
		document.getElementById("fechaFin").style.backgroundColor = "silver";
	}
}

function validar(formulario) {
	var fechaInicio = formulario.fechaInicio.value;
	var fechaFin = formulario.fechaFin.value;
	if (fechaInicio == "") {
		alert("Debe ingresar un fecha de inicio de contrato");
		return(false);
	} else {
		if (!esFechaValida(fechaInicio)) {
			alert("La fecha de Inicio no es valida");
			return(false);
		} 
	}
	if (fechaFin != "") {
		if (!esFechaValida(fechaFin)) {
			alert("La Fecha Fin no es valida");
			return(false);
		} else {
			fechaInicio = new Date(invertirFecha(fechaInicio));
			fechaFin = new Date(invertirFecha(fechaFin));
			if (fechaInicio >= fechaFin) {
				alert("La Fecha Fin debe ser superior a la Fecha de Inicio");
				return(false);
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'contratosPrestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Alta Contratos </h3>
  <table width="500" border="1">
    <tr>
      <td width="100"><div align="right"><strong>Código</strong></div></td>
      <td width="400"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Razón Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  
  <form id="nuevoContrato" name="nuevoContrato" method="post" onsubmit="return validar(this)" action="guardarNuevoContrato.php?codigo=<?php echo $codigo ?>">
    <h3>Datos Contrato</h3>   
 <?php  $fi = ""; $ff = "";
		if (isset($_GET['err'])) {
			$fi = $_GET['fi'];
			$ff = $_GET['ff']; ?>
  			<h4><font color='#FF0000'>Existe un contrato con fecha de finalización posterior a la fecha de inicio que quiere ingresar</font></h4>
			
 <?php 	} ?>
 	<p><b>Contrato relacionado con otro Prestador</b></p>
 	<p>
 		<input type="radio" id="relacionNO" value="0" name="relacion" checked="checked" onchange="habilitaTercero(this.value)"/>NO - 
 		<input type="radio" id="relacionSI" value="1" name="relacion" onchange="habilitaTercero(this.value)"/>SI
 	</p>
    <div id="cargaTerceros" style="display: none">
    	<p>Codigo Prestador Relacionado: <input size="6" type="text" id="codigoTercero" name="codigoTercero" onchange="buscarContratos(this)"/></p>
    	<p id="listaContratos"></p>
    </div>
    <p id="inicioFin">
		Fecha Inicio: <input style="background-color: " type="text" name="fechaInicio" id="fechaInicio" size="8" value="<?php echo $fi ?>"/> - 
   	 	Fecha Fin: <input type="text" name="fechaFin" id="fechaFin" size="8" value="<?php echo $ff ?>"/>
    </p>
    <p><input type="submit" name="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>