<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Prestador :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	$("#vtoSSS").mask("99-99-9999");
	$("#vtoSNR").mask("99-99-9999");
	$("#vtoExento").mask("99-99-9999");
	
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
			if (respuesta != 0) {
				$("#errorCuit").html("El C.U.I.T. '" + cuit + "' <br>ya existe (Codigo Prestador '"+ respuesta +"')");
				$("#cuit").val("");
			} else {
				$("#errorCuit").html("");
			} 
		});
	});

	$("#sitfiscal").change(function(){
		var sitfis = $(this).val();
		$("#vtoExento").val("");
		$("#vtoExento").prop("disabled", true);
		if (sitfis == 3) {
			$("#vtoExento").prop("disabled", false);
		}
	});
	
	$("#nroSSS").change(function(){
		var nroreg = $(this).val();
		$("#vtoSSS").val("");
		if (nroreg == 0) {
			$("#vtoSSS").prop("disabled", true );
		} else {
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "lib/existePrestaNroSSS.php",
				data: {nroreg:nroreg},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#errorSSS").html("El Nro de Registro de la SSS '" + nroreg + "' ya existe en el prestador con codigo '"+ respuesta +"'");
					$("#vtoSSS").prop("disabled", true );
					$("#nroSSS").val("");
				} else {
					$("#errorSSS").html("");
					$("#vtoSSS").prop("disabled", false );
				} 
			});
		}
	});

	$("#nroSNR").change(function(){
		var nroreg = $(this).val();
		$("#vtoSNR").val("");
		if (nroreg == 0) {
			$("#vtoSNR").prop("disabled", true );
		} else {
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "lib/existePrestaNroSNR.php",
				data: {nroreg:nroreg},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#errorSNR").html("El Nro de Registro de la SNR '" + nroreg + "' ya existe en el prestador con codigo '"+ respuesta +"'");
					$("#vtoSNR").prop("disabled", true );
					$("#nroSNR").val("");
				} else {
					$("#errorSNR").html("");
					$("#vtoSNR").prop("disabled", false );
				} 
			});
		}
	});
	
	$("#matriculaNac").change(function(){
		var matricula = $(this).val();
		$.ajax({
			type: "POST",
			dataType: "json",
			url: "lib/existeMatriculaNac.php",
			data: {matricula:matricula},
		}).done(function(respuesta){
			if (respuesta != 0) {
				$("#errorMatNac").html("<br>La Matricula Nac. Nro. '" + matricula + "' <br>ya existe en el prestador con codigo '"+ respuesta +"'");
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
			if (respuesta != 0) {
				$("#errorMatPro").html("<br>La Matricula Prov. Nro. '" + matricula + "' <br>ya existe en el prestador con codigo '"+ respuesta +"'");
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
				$("#divServicios").html("<font color='red'>Debe seleccionar una personeria para poder ver los servicios</font>");
			}
		});
	});

	$("#selectLocali").change(function() {
		var localidad = $("#selectLocali option:selected").html();
		if (localidad == "CAPITAL FEDERAL") {
			$("#selectBarrio").prop("disabled", false );
			$.ajax({
				type: "POST",
				dataType: "html",
				url: "lib/getBarrios.php"
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#selectBarrio").html(respuesta);
				} else {
					$("#selectBarrio").html("");
				}
			});
		} else {
			$("#selectBarrio").prop("disabled", true );
			$("#selectBarrio").html("<option title ='Seleccione un valor' value=''>Seleccione un barrio</option>");
		}
	});

	$("input[name='fijo']").click(function() {
		var valor = $(this).val();
		$("input[name='nomencladorReso']").prop("disabled", false);
		if (valor == 1) {
			$("input[name='nomencladorReso']").prop("disabled", true);
			$("input[name='nomencladorReso']").prop("checked", false);
		}
	});
});

function verPertenencia(checkbox) {
	var nameradio = "pertenencia"+checkbox.name.substring(12);
	var radio = document.getElementById(nameradio);
	radio.style.display = "none";
	radio.checked = false;
	if (checkbox.checked) {
		radio.style.display = "inline-block";
	}
}

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

function validar() {	
	var formulario = document.forms.nuevoPrestador;
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

	if (formulario.sitfiscal.value == 3) {
		if (!esFechaValida(formulario.vtoExento.value)){
			alert("Fecha de vto de exento invalida");
			return false;
		}
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
	if (formulario.email1.value != "") {
		if (!esCorreoValido(formulario.email1.value)){
			alert("Email Primario invalido");
			return false;
		}
	}
	if (formulario.email2.value != "") {
		if (!esCorreoValido(formulario.email2.value)){
			alert("Email Secundario invalido");
			return false;
		}
	}
	if (formulario.nroSSS.value != "") {
		if (!esFechaValida(formulario.vtoSSS.value)){
			return false;
		}
	}
	if (formulario.nroSNR.value != "") {
		if (!esFechaValida(formulario.vtoSNR.value)){
			return false;
		}
	}
	var personeria = formulario.selectPersoneria.options[formulario.selectPersoneria.selectedIndex].value;
	if (personeria == 0) {
		alert("Debe elegir una Personería");
		return false;
	}
	if (personeria == 1) {
		var tratamiento = formulario.selectTratamiento.options[formulario.selectTratamiento.selectedIndex].value;
		if (tratamiento == 0) {
			alert("Debe elegir una Tramtamiento para Persona Física");
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

	var nomencladorCheck = 0;
	var nomenclador = formulario.nomenclador;
	if (nomenclador != null) {
		for (var x=0;x<nomenclador.length;x++) {
			if(nomenclador[x].checked) {
				nomencladorCheck = 1;
			}
		}
	}

	var nomenclaResoCheck = 0;
	var nomenclaReso = formulario.nomencladorReso;
	if (nomenclaReso != null) {
		for (var x=0;x<nomenclaReso.length;x++) {
			if(nomenclaReso[x].checked) {
				nomenclaResoCheck = 1;
			}
		}
	}
	
	if (nomencladorCheck == 0 && nomenclaResoCheck == 0) {
		alert("Debe elegir como mínimo un nomenclador para el prestador");
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
		alert("Debe elegir como mínimo un servicio para el prestador");
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
		alert("Debe elegir como mínimo una Delegación para el prestador");
		return false;
	}

	var perteCheck = 0;
	pertenencias = formulario.pertenencia;
	if (pertenencias != null) {
		for (x=0;x<pertenencias.length;x++) {
			if(pertenencias[x].checked) {
				perteCheck = 1;
			}
		}
	}
	if (perteCheck == 0) {
		alert("Debe elegir una Jurisdiccion como Pertenencia del prestador");
		return false;
	}
	
	formulario.guardar.disabled = true;
	$.blockUI({ message: "<h1>Guardando Nuevo Prestador. Aguarde un minuto</h1>" });
	formulario.submit();
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <h3>Nuevo Prestador </h3>
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" action="guardarNuevoPrestador.php">
    <table width="100%" border="0">
      <tr>
        <td width="130"><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
        <td colspan="3"><div align="left"><input name="nombre" type="text" id="nombre" size="120" /></div></td>
      </tr>
       <tr>
        <td><div align="right"><strong>C.U.I.T.</strong></div></td>
        <td width="320">
			<div align="left">
				<input name="cuit" type="text" id="cuit" size="10" />
				<span id="errorCuit" style="color:#FF0000;font-weight: bold;"></span>
	        </div>
		</td>
		<td>
			<div align="left"><strong>Situacion Fiscal</strong>
				<select id="sitfiscal" name="sitfiscal">
				<?php 	$query = "select * from tiposituacionfiscal"; 
	    	  			$result = mysql_query($query,$db);  
            			while ($rowfis = mysql_fetch_array($result)) { ?>
						  	<option value="<?php echo $rowfis['id']?>"><?php echo $rowfis['descripcion'] ?></option>
				  <?php 	$i++;
						} ?>
				</select>		
			</div>	
		</td>
		<td>
			<div align="left">
				<strong>Vto. Exento</strong>
				<input type="text" id="vtoExento" name="vtoExento" size="8" disabled="disabled" />
			</div>	
		</td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="2"><div align="left"><input name="domicilio" type="text" id="domicilio" size="80" /></div></td>
      	<td>
	      	<div align="left"><strong>Provincia</strong>
	          	<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" />
	            <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
	        </div> 
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td>
        	<div align="left">
	          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1"/>
	          -<input name="codPos" type="text" id="codPos" size="7" />-<input name="alfapostal"  id="alfapostal" type="text" size="3"/>
	        </div>
	    </td>
        <td>
        	<div align="left"><strong>Localidad</strong>
	          	<select name="selectLocali" id="selectLocali">
	            	<option value="0">Seleccione una localidad </option>
	          	</select>
        	</div>
        </td>
        <td>
        	<div align="left"><strong>Barrio</strong>
	          	<select name="selectBarrio" id="selectBarrio" disabled="disabled">
	            	<option value="0">Seleccione un barrio </option>
	          	</select>
        	</div>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1</strong></div></td>
        <td>
        	<div align="left">
        		(<input name="ddn1" type="text" id="ddn1" size="3" />)-
            	<input name="telefono1" type="text" id="telefono1" size="15" />
			</div>
		</td>
        <td>
        	<div align="left"><strong>Telefono 2 </strong>
        		(<input name="ddn2" type="text" id="ddn2" size="3"/>)-
				<input name="telefono2" type="text" id="telefono2" size="15"/>
			</div>
		</td>
		<td>
			<div align="left"><strong>Telefono FAX </strong>
        		(<input name="ddnfax" type="text" id="ddnfax" size="3"/>)-
				<input name="telefonofax" type="text" id="telefonofax" size="15" />
			</div>
		</td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Email Primario</strong></div></td>
        <td colspan="3"><div align="left"><input name="email1" type="text" id="email1" size="60" /></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Email Secundario</strong></div></td>
        <td colspan="3"><div align="left"><input name="email2" type="text" id="email2" size="60" /></div></td>
      </tr>
      <tr>
      	<td><div align="right"><strong>Registro SSS </strong></div></td>
      	<td>
            <b>- Numero </b><input name="nroSSS" type="text" id="nroSSS" size="10" /> - <b>VTO </b><input type="text" id="vtoSSS" name="vtoSSS" size="8" disabled="disabled"/>         	      	     
        </td>
        <td colspan="2"><span id="errorSSS" style="color:#FF0000;font-weight: bold;"/></td>
      </tr>
      <tr>
      	<td><div align="right"><strong>Registro SNR </strong></div></td>
      	<td>
      		<b>- Numero </b><input name="nroSNR" type="text" id="nroSNR" size="10" /> - <b>VTO </b><input type="text" id="vtoSNR" name="vtoSNR" size="8" disabled="disabled"/>    	
      	</td>
      	<td colspan="2"><span id="errorSNR" style="color:#FF0000;font-weight: bold;"/> </td>
      </tr>
	  <tr>
        <td><div align="right"><strong>Personería</strong></div></td>
        <td colspan="3"><div align="left">
            <select name="selectPersoneria" id="selectPersoneria" onchange="habilitaCamposProfesional(this.value)">
          <?php $query="select * from tipoprestador WHERE id != 5";  
              	$result=mysql_query($query,$db);
              	while ($rowtipos=mysql_fetch_array($result)) { ?>
					  <option value="<?php echo $rowtipos['id']?>"><?php echo $rowtipos['descripcion']?> </option>
			<?php } ?>
            </select>
        </div></td>
        
      </tr>
	  <tr>
	    <td><div align="right"><strong>Tratamiento</strong></div></td>
	    <td>
			<div align="left">
		      <select name="selectTratamiento" size="1" id="selectTratamiento" disabled="disabled">
	            <option value="0" selected="selected">Seleccione un valor </option>
	            <?php 
						$query="select * from tipotratamiento";
						$result=mysql_query($query,$db);
						while ($rowtipos=mysql_fetch_array($result)) { ?>
	            <option value="<?php echo $rowtipos['codigotratamiento'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
	            <?php } ?>
	          </select>
	    	</div>
	    </td>
        <td>
		   	<div align="left">
		   		<strong>Matrícula Nacional </strong>
          		<input name="matriculaNac" type="text" id="matriculaNac" size="10" disabled="disabled"/>
          		<span id="errorMatNac" style="color:#FF0000;font-weight: bold;"></span>
        	</div>
        </td>
        <td>
			<div align="left">
				<strong>Matrícula Provincial </strong>
            	<input name="matriculaPro" type="text" id="matriculaPro" size="10" disabled="disabled"/>
            	<span id="errorMatPro" style="color:#FF0000;font-weight: bold;"></span>
        	</div>
        </td>
      </tr>
	  <tr>
	    <td><div align="right"><strong>Capitado</strong></div></td>
	    <td>
	    	<div align="left">
          		<input name="capitado" type="radio" value="0" checked="checked"/> NO
  		  		<input name="capitado" type="radio" value="1" />SI
		  	</div>
		</td>
		<td colspan="2">
			<div align="left">
				<strong>Arancel Fijo</strong>
          		<input name="fijo" type="radio" value="0" checked="checked"/> NO
  		  		<input name="fijo" type="radio" value="1" />SI
		  	</div>
		</td>
      </tr>
      <tr>
      	<td><div align="right"><b>Observacion </b></div></td>
      	<td colspan="5"><textarea rows="3" cols="130" id="observacion" name="observacion"></textarea></td>
      </tr>
    </table>
    <hr></hr>
<?php 	$query="select * from nomencladores"; 
	    $result=mysql_query($query,$db);  
	    $arrayConContrato = array();
	    $arrayConResolucion = array();
        while ($rownom=mysql_fetch_assoc($result)) { 
			if ($rownom['contrato'] == 1) {
				$arrayConContrato[$rownom['id']] = $rownom;
			} else {
				$arrayConResolucion[$rownom['id']] = $rownom;
			}
		} ?>
    <h3>Nomencladores</h3>
    <b>Con Contrato o Arancel Fijo |</b>
    <?php foreach ($arrayConContrato as $key => $nomenclador) { ?>
        	<input value="<?php echo $key ?>" name="<?php echo "nomenclador".$key ?>" id="nomenclador" type="checkbox"/><?php echo $nomenclador['nombre']." | "; ?>
    <?php }?>
    <br></br><b>Con Resolucion |</b>
    <?php foreach ($arrayConResolucion as $key => $nomenclador) { ?>
        	<input value="<?php echo $key ?>" name="nomencladorReso" id="nomencladorReso" type="radio"/><?php echo $nomenclador['nombre']." | "; ?>
    <?php }?>
    <hr style="margin-top: 20px"></hr>
    <table width="900">
      <tr>
        <td width="300" height="46" style="text-align: center"><h3>Servicios </h3></td>
        <td colspan="2" style="text-align: center"><h3>Jurisdiccion </h3></td>
      </tr>
      <tr>
        <td valign="top">
			<div id="divServicios" align="left"><font color="red">Debe seleccionar una Personeria para poder ver los servicios</font>  </div>
		</td>
        <td width="300" valign="top"><div align="left">
            <?php 
				$query="select * from delegaciones where codidelega >= 1002 and codidelega <= 1702";
				$result=mysql_query($query,$db);
				$i = 0;
				while ($rowtipos=mysql_fetch_array($result)) { ?>
           	 		<input type="checkbox" name="<?php echo "delegaciones".$i ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" onclick="verPertenencia(this)"/>
	          		<?php echo $rowtipos['nombre'] ?>
	          		<input style="display: none"  type="radio" name="pertenencia" id="<?php echo "pertenencia".$i  ?>" value="<?php echo $rowtipos['codidelega'] ?>" />
	          		</br>
			  <?php $i++; 
				} ?>
                </div></td>
        <td width="300" valign="top"><div align="left">
          <?php 
				$query="select * from delegaciones where codidelega > 1702 and codidelega < 3200";
				$result=mysql_query($query,$db);
				while ($rowtipos=mysql_fetch_array($result)) { ?>
         			<input type="checkbox" name="<?php echo "delegaciones".$i ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" onclick="verPertenencia(this)"/>
	          		<?php echo $rowtipos['nombre'] ?>
	          		<input style="display: none"  type="radio" name="pertenencia" id="<?php echo "pertenencia".$i  ?>" value="<?php echo $rowtipos['codidelega'] ?>" />
	          		</br>
			  <?php $i++; 
				} ?>
        </div></td>
      </tr>
    </table>
    <p><input type="button" name="guardar" id="guardar" value="Guardar" onclick="validar()"/></p>
  </form>
</div>
</body>
</html>
