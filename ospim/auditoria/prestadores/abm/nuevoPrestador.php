<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Prestador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	
	$("#codPos").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "lib/localidadPorCP.php",
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
			url: "lib/cambioProvincia.php",
			data: {locali:locali},
		}).done(function(respuesta){
			$("#indpostal").val(respuesta.indpostal);
			$("#provincia").val(respuesta.descrip);
			$("#codprovin").val(respuesta.codprovin);
		});
	});
	
	$("#cuit").change(function(){
		var cuit = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existePrestaCuit.php",
			data: {cuit:cuit},
		}).done(function(respuesta){
			if (respuesta == 1) {
				$("#errorCuit").html("El C.U.I.T. '" + cuit + "' existe en otro prestador");
				$("#cuit").val("");
			} else {
				$("#errorCuit").html("");
			} 
		});
	});
	
	$("#nroRegistro").change(function(){
		var nroreg = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existePrestaNroSSS.php",
			data: {nroreg:nroreg},
		}).done(function(respuesta){
			if (respuesta == 1) {
				$("#errorSSS").html("El Nro de Registro de la SSS '" + nroreg + "' existe en otro prestador");
				$("#nroRegistro").val("");
			} else {
				$("#errorSSS").html("");
			} 
		});
	});
	
	$("#matriculaNac").change(function(){
		var matricula = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existeMatriculaNac.php",
			data: {matricula:matricula},
		}).done(function(respuesta){
			if (respuesta == 1) {
				$("#errorMatNac").html("La Matricula Nac. Nro. '" + matricula + "' existe en otro prestador");
				$("#matriculaNac").val("");
			} else {
				$("#errorMatNac").html("");
			} 
		});
	});
	
	$("#matriculaPro").change(function(){
		var matricula = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existeMatriculaPro.php",
			data: {matricula:matricula},
		}).done(function(respuesta){
			if (respuesta == 1) {
				$("#errorMatPro").html("La Matricula Prov. Nro. '" + matricula + "' existe en otro prestador");
				$("#matriculaPro").val("");
			} else {
				$("#errorMatPro").html("");
			} 
		});
	});
	
	$("#selectPersoneria").change(function(){
		var personeria = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "html",
			url: "lib/getServicios.php",
			data: {personeria:personeria},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#divServicios").html(respuesta);
			} else {
				$("#divServicios").html("");
			}
		});
	});
	
});

function habilitaCamposProfesional(valor) {
	document.getElementById("errorMatNac").innerHTML = "";
	document.getElementById("errorMatPro").innerHTML = "";
	if (valor == 1) {
		document.forms.nuevoPrestador.selectTratamiento.disabled = false;
		document.forms.nuevoPrestador.matriculaNac.disabled = false;
		document.forms.nuevoPrestador.matriculaPro.disabled = false;
	} else {
		document.forms.nuevoPrestador.selectTratamiento.disabled = true;
		document.forms.nuevoPrestador.matriculaNac.disabled = true;
		document.forms.nuevoPrestador.matriculaPro.disabled = true;
		document.forms.nuevoPrestador.selectTratamiento.value = 0;
		document.forms.nuevoPrestador.matriculaNac.value = "";
		document.forms.nuevoPrestador.matriculaPro.value = "";
	}	
}

function validar(formulario) {	
	if (formulario.nombre.value == "") {
		alert("El campo Nombre o Razon social es Obligatrio");
		return false;
	}
	if (formulario.domicilio.value == "") {
		alert("El campo domicilio es obligatrio");
		return false;
	}
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
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
	var personeria = formulario.selectPersoneria.options[formulario.selectPersoneria.selectedIndex].value;
	if (personeria == 0) {
		alert("Debe elegir una Personer�a");
		return false;
	}
	if (personeria == 1) {
		var tratamiento = formulario.selectTratamiento.options[formulario.selectTratamiento.selectedIndex].value;
		if (tratamiento == 0) {
			alert("Debe elegir una Tramtamiento para Persona F�sica");
			return false;
		}
		if (formulario.matriculaNac.value != "") {
			if (!esEntero(formulario.matriculaNac.value)) {
				alert("El Nro. de Matricula Nacional debe ser un numero");
				return false;
			}
		}
		if (formulario.matriculaPro.value != "") {
			if (!esEntero(formulario.matriculaPro.value)) {
				alert("El Nro. de Matricula Provincial debe ser un numero");
				return false;
			}
		}
	}
	if (formulario.nroRegistro.value != "") {
		if (!esEntero(formulario.nroRegistro.value)) {
			alert("El Nro. de Registro en la SSS debe ser un numero");
			return false;
		}
	}

	var nomencladorCheck = 0;
	var nomenclador = formulario.nomenclador;
	if (nomenclador != null) {
		for (var x=0;x<nomenclador.length;x++) {
			if(nomenclador[x].checked) {
				nomencladorCheck = 1;
			}
		}
	}
	if (nomencladorCheck == 0) {
		alert("Debe elegir como m�nimo un nomenclador para el prestador");
		return false;
	}

	var servicioCheck = 0;
	var servicios = formulario.servicios;
	if (servicios != null) {
		for (var x=0;x<servicios.length;x++) {
			if(servicios[x].checked) {
				servicioCheck = 1;
			}
		}
	}
	if (servicioCheck == 0) {
		alert("Debe elegir como m�nimo un servicio para el prestador");
		return false;
	}
	
	var delegaCheck = 0;
	delegaciones = formulario.delegaciones;
	if (delegaciones != null) {
		for (x=0;x<delegaciones.length;x++) {
			if(delegaciones[x].checked) {
				delegaCheck = 1;
			}
		}
	}
	if (delegaCheck == 0) {
		alert("Debe elegir como m�nimo una Delegaci�n para el prestador");
		return false;
	}
	
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><strong>Nuevo Prestador </strong></p>
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarNuevoPrestador.php">
    <table border="0">
      <tr>
        <td width="129"><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="nombre" type="text" id="nombre" size="120" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="domicilio" type="text" id="domicilio" size="120" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>C.U.I.T.</strong></div></td>
        <td colspan="5">
			<div align="left">
				<input name="cuit" type="text" id="cuit" size="10" />
				<span id="errorCuit" style="color:#FF0000;font-weight: bold;"></span>
	        </div>
		</td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td width="244"><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
          -<input name="codPos" type="text" id="codPos" size="7" />-<input name="alfapostal"  id="alfapostal" type="text" size="3"/>
        </div><div align="right"></div></td>
        <td width="365"><div align="left"><strong>Localidad</strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
          </select>
        </div></td>
        <td><div align="left"><strong>Provincia
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" />
            <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
        </strong></div>          <div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left">(
            <input name="ddn1" type="text" id="ddn1" size="3" />
            )-
            <input name="telefono1" type="text" id="telefono1" size="15" />
</div></td>
        <td colspan="4"><div align="left"><strong>Telefono 2 </strong>(
            <input name="ddn2" type="text" id="ddn2" size="3"/>
)-
<input name="telefono2" type="text" id="telefono2" size="15"/>
</div></td>
      </tr>
	  <tr>
        <td><div align="right">
          <div align="right"><strong>Telefono FAX </strong></div>
        </div></td>
        <td><div align="left">(
          <input name="ddnfax" type="text" id="ddnfax" size="3"/>
          )-
  <input name="telefonofax" type="text" id="telefonofax" size="15" />
        </div></td>
        <td colspan="4"><div align="left"><strong>Email</strong>
          <input name="email" type="text" id="email" size="40" />
        </div>          <div align="left"></div></td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Personer�a</strong></div></td>
        <td><div align="left">
            <select name="selectPersoneria" id="selectPersoneria" onchange="habilitaCamposProfesional(this.value)">
              <option value="0">Seleccione un valor </option>
			  <option value="1">Profesional </option>
			  <option value="2">Establecimiento </option>
			  <option value="3">C�rculo </option>
            </select>
        </div></td>
        <td colspan="4">
		<div id="errorSSS" style="color:#FF0000"></div>
		<div align="left">
          <div align="left"><strong>Numero Registro SSS
            <input name="nroRegistro" type="text" id="nroRegistro" size="10" />
          </strong></div>
        </div>          
        <div align="left"></div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Tratamiento</strong></div></td>
	    <td><div align="left">
	      <select name="selectTratamiento" size="1" id="selectTratamiento" disabled="disabled">
            <option value="0" selected="selected">Seleccione un valor </option>
            <?php 
					$query="select * from tipotratamiento";
					$result=mysql_query($query,$db);
					while ($rowtipos=mysql_fetch_array($result)) { ?>
            <option value="<?php echo $rowtipos['codigotratamiento'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
            <?php } ?>
          </select>
	    </div></td>
        <td>
		   <div id="errorMatNac" style="color:#FF0000"></div>
		   <div align="left"><strong>Matr&iacute;cula Nacional </strong>
          <input name="matriculaNac" type="text" id="matriculaNac" size="10" disabled="disabled"/>
        </div></td>
        <td colspan="3">
		<div id="errorMatPro" style="color:#FF0000"></div>
		<div align="left"><strong>Matr&iacute;culo Provincial </strong>
            <input name="matriculaPro" type="text" id="matriculaPro" size="10" disabled="disabled"/>
        </div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Capitado</strong></div></td>
	    <td colspan="5"><div align="left">
          <input name="capitado" type="radio" value="0" checked="checked"/> NO
  		  <input name="capitado" type="radio" value="1" />SI
		  </div></td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Nomenclador </strong></div></td>
	    <td colspan="5"><div align="left">
            	<?php 	$query="select * from nomencladores"; 
	    	  			$result=mysql_query($query,$db);  
	    	  			$i = 0;
            			while ($rownom=mysql_fetch_array($result)) { ?>
						  	<input name="<?php echo "nomenclador".$i ?>" id="nomenclador" type="checkbox"/><?php echo $rownom['nombre']." | "; ?>
				  <?php 	$i++;
						} ?>
        </div></td>
      </tr>
    </table>
    <table width="884" border="0">
      <tr>
        <td width="284" height="46"><div align="center" class="Estilo1"><strong>Servicios </strong></div></td>
        <td colspan="2"><div align="center" class="Estilo1"><strong>Jurisdiccion </strong></div></td>
      </tr>
      <tr>
        <td valign="top">
			<div id="divServicios" align="left" >	 
			</div>
		</td>
        <td width="281" valign="top"><div align="left">
            <?php 
				$query="select * from delegaciones where codidelega >= 1002 and codidelega <= 1702";
				$result=mysql_query($query,$db);
				$i = 0;
				while ($rowtipos=mysql_fetch_array($result)) { ?>
            <input type="checkbox" name="<?php echo "delegaciones".$i ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" />
            <?php echo $rowtipos['nombre'] ?><br />
            <?php 	$i++;
				} ?>
                </div></td>
        <td width="297" valign="top"><div align="left">
          <?php 
				$query="select * from delegaciones where codidelega > 1702 and codidelega < 3200";
				$result=mysql_query($query,$db);
				while ($rowtipos=mysql_fetch_array($result)) { ?>
          <input type="checkbox" name="<?php echo "delegaciones".$i  ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" />
          <?php echo $rowtipos['nombre'] ?><br />
          <?php 	$i++;
				} ?>
        </div></td>
      </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
  </form>
</div>
</body>
</html>
