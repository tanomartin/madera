<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php");

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r WHERE p.codigoprestador = $codigopresta and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$codigo = $_GET['codigo'];
$sqlConsultaEsta = "SELECT p.*, pr.nombre as prestador, l.nomlocali as localidad, r.descrip as provincia FROM establecimientos p, prestadores pr, localidades l, provincia r WHERE p.codigo = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.codigoprestador = pr.codigoprestador";
$resConsultaEsta = mysql_query($sqlConsultaEsta,$db);
$rowConsultaEsta = mysql_fetch_assoc($resConsultaEsta); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Establecimiento :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechadesde").mask("99-99-9999");
	$("#fechahasta").mask("99-99-9999");
	$("#cuit").mask("99999999999");
	
	$("#codPos").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "../lib/localidadPorCP.php",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectLocali").html(respuesta);
			$("#indpostal").val("");
			$("#provincia").val("");
			$("#codprovin").val("");
		});
	});

	$("#selectLocali").change(function(){
		var locali = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "../lib/cambioProvincia.php",
			data: {locali:locali},
		}).done(function(respuesta){
			$("#indpostal").val(respuesta.indpostal);
			$("#provincia").val(respuesta.descrip);
			$("#codprovin").val(respuesta.codprovin);
		});
	});
	
});

function habilitaCalidad(valor) {
	habilitaFecha(0);
	var calidadSI = document.getElementById("calidadSI");
	var calidadNO = document.getElementById("calidadNO");
	calidadSI.checked = "";
	calidadNO.checked = "checked";
	calidadSI.disabled = true;
	calidadNO.disabled = true;
	if (valor == 1) {
		calidadSI.disabled = false;
		calidadNO.disabled = false;
	}
}


function habilitaFecha(valor) {
	var fechadesde = document.getElementById("fechadesde");
	var fechahasta = document.getElementById("fechahasta");
	fechadesde.value = "";
	fechahasta.value = "";
	fechadesde.disabled = true;
	fechahasta.disabled = true;
	if(valor == 1) {
		fechadesde.disabled = false;
		fechahasta.disabled = false;
	}
}

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("El campo Nombre es Obligatrio");
		return false;
	}

	if (formulario.cuit.value != "") {
		if (!verificaCuilCuit(formulario.cuit.value)){
			alert("C.U.I.T. Inv�lido");
			return false;
		}
	}
	
	if (formulario.codPos.value != "") {
		if (!esEnteroPositivo(formulario.codPos.value)){
		 	alert("El campo Codigo Postal tiene que ser numerico");
			return false;
		}
		if (formulario.domicilio.value == "") {
			alert("El campo domicilio es obligatrio, si se ingresa un codigo postal");
			return false;
		}
		if (formulario.selectLocali.options[formulario.selectLocali.selectedIndex].value == 0) {
			alert("Debe elegir una Localidad, si se ingresa una direccion");
			return false;
		}
		
	}
	
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddn1.value != "") {
		if (!esEnteroPositivo(formulario.ddn1.value)) {
			alert("El codigo de area 1 debe ser un numero");
			return false;
		}
	}
	if (formulario.telefono2.value != "") {
		if (!esEnteroPositivo(formulario.telefono2.value)) {
			alert("El telefono 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddn2.value != "") {
		if (!esEnteroPositivo(formulario.ddn2.value)) {
			alert("El codigo de area 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.telefonofax.value != "") {
		if (!esEnteroPositivo(formulario.telefonofax.value)) {
			alert("El telefono 2 debe ser un numero");
			return false;
		}
	}
	if (formulario.ddnfax.value != "") {
		if (!esEnteroPositivo(formulario.ddnfax.value)) {
			alert("El codigo de area fax debe ser un numero");
			return false;
		}
	}
	if (formulario.email.value != "") {
		if (!esCorreoValido(formulario.email.value)){
			alert("Email invalido");
			return false;
		}
	}
	if (formulario.calidad.value == 1) {
		var fechadesde = formulario.fechadesde.value;
		var fechahasta = formulario.fechahasta.value;
		if (!esFechaValida(fechadesde)) {
			alert("La Fecha Desde de la acreditacion no de calidad no es valida");
			return false
		}
		if (fechahasta != "") {
			if (!esFechaValida(fechahasta)) {
				alert("La Fecha Hasta de la acreditacion no de calidad no es valida");
				return false
			}
		}
		fechaInicio = new Date(invertirFecha(fechadesde));
		if (fechahasta != "") {
			fechaFin = new Date(invertirFecha(fechahasta));
			if (fechaInicio >= fechaFin) {
				alert("La Fecha Desde debe ser superior a la Fecha de Hasta");
				return false ;
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
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'establecimiento.php?codigo=<?php echo $codigo ?>&codigopresta=<?php echo $codigopresta ?>'" /> </p>
   <h3>Modificar Establecimiento</h3>
   <table width="500" border="1">
    <tr>
      <td width="163"><div align="right"><strong>C�digo</strong></div></td>
      <td width="321"><div align="left"><strong><?php echo $rowConsultaPresta['codigoprestador']  ?></strong></div></td>
    </tr>
    <tr>
      <td><div align="right"><strong>Raz�n Social</strong></div></td>
      <td><div align="left"><?php echo $rowConsultaPresta['nombre'] ?></div></td>
    </tr>
  </table>
  <form name="modifEstablecimiento" id="modifEstablecimiento" method="post" onsubmit="return validar(this)" action="guardarModificacionEstablecimiento.php?codigopresta=<?php echo $codigopresta ?>">
    <table border="1">
      <tr>
        <td align="right"><b>C�digo</b></td>
        <td align="left" colspan="3">
          <input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo" size="4" value="<?php echo $rowConsultaEsta['codigo'] ?>"/>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Nombre</b></td>
        <td colspan="2" align="left"><input name="nombre" type="text" id="nombre" size="80" value="<?php echo $rowConsultaEsta['nombre'] ?>"/></td>
        <td align="left"><b>C.U.I.T. </b><input name="cuit" type="text" id="cuit" size="9" value="<?php echo $rowConsultaEsta['cuit'] ?>"/></td>
      </tr>
      <tr>
        <td align="right"><b>Domicilio</b></td>
        <td colspan="3" align="left"><input name="domicilio" type="text" id="domicilio" size="120" value="<?php echo $rowConsultaEsta['domicilio'] ?>" /></td>
      </tr>
      <tr>
        <td align="right"><b>Codigo Postal</b></td>
        <td align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaEsta['indpostal'] ?>"/> -
		  <input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaEsta['numpostal'] ?>" /> -
		  <input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaEsta['alfapostal'] ?>"/>
		</td>
        <td align="left"><b>Localidad</b>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
            <option value="<?php echo $rowConsultaEsta['codlocali'] ?>" selected="selected"><?php echo $rowConsultaEsta['localidad'] ?></option>
          </select>
        </td>
        <td align="left"><b>Provincia</b>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaEsta['provincia'] ?>" size="25"/>
          <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaEsta['codprovin'] ?>"/>
        </td>
      </tr>
      <tr>
        <td align="right"><b>Telefono 1 </b></td>
        <td align="left">(<input name="ddn1" type="text" id="ddn1" size="3" value="<?php echo $rowConsultaEsta['ddn1'] ?>"/>) - <input name="telefono1" type="text" id="telefono1" size="15" value="<?php echo $rowConsultaEsta['telefono1'] ?>"/></td>
        <td colspan="2" align="left"><b>Telefono 2 </b> (<input name="ddn2" type="text" id="ddn2" size="3" value="<?php echo $rowConsultaEsta['ddn2'] ?>"/>) - <input name="telefono2" type="text" id="telefono2" size="15" value="<?php echo $rowConsultaEsta['telefono2'] ?>"/></td>
      </tr>
      <tr>
        <td align="right"><b>Telefono FAX </b></td>
        <td align="left">(<input name="ddnfax" type="text" id="ddnfax" size="3" value="<?php echo $rowConsultaEsta['ddnfax'] ?>"/>) - <input name="telefonofax" type="text" id="telefonofax" size="15" value="<?php echo $rowConsultaEsta['telefonofax'] ?>"/>
	  </td>
        <td colspan="2" align="left"><b>Email</b> <input name="email" type="text" id="email" size="30" value="<?php echo $rowConsultaEsta['email'] ?>"/></td>
      </tr>
      <tr>
	    <td align="right"><b>Circulo</b></td>
	    <td colspan="3" align="left">
	    <?php $ckeckedNO = 'checked="checked"' ;
	    	  $ckeckedSI = '';
	    	  if ($rowConsultaEsta['circulo'] == 1) {
	    		$ckeckedSI = 'checked="checked"';
	    		$ckeckedNO = '';
	    	  } ?>
          	<input name="circulo" type="radio" value="0" <?php echo $ckeckedNO ?> onclick="habilitaCalidad(this.value)"/> NO
  		  	<input name="circulo" type="radio" value="1" <?php echo $ckeckedSI ?> onclick="habilitaCalidad(this.value)"/> SI
		</td>
      </tr>
      <tr>
      	<td align="right"><b>Acrditacion Calidad</b></td>
      	<td align="left">
	    <?php $calidadDisabled = "";
	    	  if ($rowConsultaEsta['circulo'] == 1) {
	    			$ckeckedNO = 'checked="checked"' ;
	    			$ckeckedSI = '';
	    			if ($rowConsultaEsta['calidad'] == 1) {
	    				$ckeckedSI = 'checked="checked"';
	    				$ckeckedNO = '';
	    			}
	    			$fechainicio = "";
	    			$fechafin = "";
	    			$disabled = 'disabled="disabled"';
	    			if ($rowConsultaEsta['fechainiciocalidad'] != NULL) {
	    				$fechainicio = invertirFecha($rowConsultaEsta['fechainiciocalidad']);
	    				$fechafin = invertirFecha($rowConsultaEsta['fechafincalidad']);
	    				$disabled = "";
	    			}
	    		} else {
	    			$calidadDisabled = 'disabled="disabled"';
	    			$disabled = 'disabled="disabled"';
	    		} ?>
          	<input name="calidad" id="calidadNO" type="radio" value="0" <?php echo $ckeckedNO ?> onclick="habilitaFecha(this.value)" <?php echo $calidadDisabled ?>/> NO
  		  	<input name="calidad" id="calidadSI" type="radio" value="1" <?php echo $ckeckedSI ?> onclick="habilitaFecha(this.value)" <?php echo $calidadDisabled ?>/> SI
		</td>
		<td><b>Fecha Desde</b> <input id="fechadesde" name="fechadesde" size="8" <?php echo $disabled ?> value="<?php echo $fechainicio  ?>"></input></td>
		<td><b>Fecha Hasta</b> <input id="fechahasta" name="fechahasta" size="8" <?php echo $disabled ?> value="<?php echo $fechafin ?>" ></input></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar Modificaci�n" /></p>
  </form>
  </div>
</body>
</html>
