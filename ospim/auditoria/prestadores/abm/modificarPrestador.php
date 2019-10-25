<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r 
						WHERE p.codigoprestador = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlConsultaServcio = "SELECT s.descripcion FROM prestadorservicio p, tiposervicio s WHERE p.codigoprestador = $codigo and p.codigoservicio = s.codigoservicio";
$resConsultaServcio = mysql_query($sqlConsultaServcio,$db);

$sqlConsultaJuris = "SELECT p.codidelega, d.nombre FROM prestadorjurisdiccion p, delegaciones d WHERE p.codigoprestador = $codigo and p.codidelega = d.codidelega";
$resConsultaJuris = mysql_query($sqlConsultaJuris,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Prestador :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
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
			$("#vtoSSS").prop("disabled", true);
		} else {
			var codigo = $("#codigo").val();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "lib/existePrestaNroSSS.php",
				data: {nroreg:nroreg, codigo:codigo},
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
			var codigo = $("#codigo").val();
			$.ajax({
				type: "POST",
				dataType: "json",
				url: "lib/existePrestaNroSNR.php",
				data: {nroreg:nroreg, codigo:codigo},
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
	
});

function habilitaCamposProfesional(valor) {
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
	
	if (formulario.nroSSS.value != "" && formulario.nroSSS.value != 0) {
		if (!esFechaValida(formulario.vtoSSS.value)){
			alert("Fecha de vto de registro SSS invalida");
			return false;
		}
	}
	if (formulario.nroSNR.value != "" && formulario.nroSNR.value != 0) {
		if (!esFechaValida(formulario.vtoSNR.value)){
			alert("Fecha de vto de registro SNR invalida");
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
	if (nomencladorCheck == 0) {
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
	
	formulario.guardar.disabled = true;
	formulario.submit();
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" onclick="location.href = 'prestador.php?codigo=<?php echo $codigo ?>'" /></p>
  <h3>Modificar Prestador</h3>
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" action="guardarModificacionPrestador.php">
    <table width="100%" border="0">
      <tr>
        <td width="130"><div align="right"><b>Código</b></div></td>
        <td colspan="3">
        	<div align="left">
          		<input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo" size="4" value="<?php echo $rowConsultaPresta['codigoprestador'] ?>"/>
        	</div>
        </td>
      </tr>
      <tr>
        <td><div align="right"><b>Razón Social</b></div></td>
        <td colspan="3">
        	<div align="left">
            	<input name="nombre" type="text" id="nombre" size="120" value="<?php echo $rowConsultaPresta['nombre'] ?>"/>
        	</div>
        </td>
      </tr>
       <tr>
        <td><div align="right"><b>C.U.I.T.</b></div></td>
        <td width="320">
        	<div align="left">
          		<input name="cuit" type="text" id="cuit" size="13" value="<?php echo $rowConsultaPresta['cuit'] ?>"/>
 				<span id="errorCuit" style="color:#FF0000;font-weight: bold;"></span>
        	</div>
        </td>
        <td>
			<div align="left"><b>Situacion Fiscal</b>
				<select id="sitfiscal" name="sitfiscal">
				<?php 	$query = "select * from tiposituacionfiscal"; 
	    	  			$result = mysql_query($query,$db);  
            			while ($rowfis = mysql_fetch_array($result)) { 
            				$selected = '';
            				if ($rowfis['id'] == $rowConsultaPresta['situacionfiscal']) {
            					$selected = 'selected="selected"';
            				} ?>
						  	<option <?php echo $selected?>  value="<?php echo $rowfis['id']?>"><?php echo $rowfis['descripcion'] ?></option>
				  <?php 	$i++;
						} ?>
				</select>		
			</div>	
		</td>
		<td>
			<div align="left">
				<b>Vto. Exento</b>
				<?php 
            		$disabled = 'disabled=disabled';
            		$vtoexento = '';
            		if ($rowConsultaPresta['situacionfiscal'] == 3) {
            			$disabled = '';
            			$vtoexento = invertirFecha($rowConsultaPresta['vtoexento'] );
	            	}?>
				<input type="text" id="vtoExento" name="vtoExento" size="8" <?php echo $disabled?> value="<?php if ($vtoexento != NULL) { echo $vtoexento; }?>"/>
			</div>	
		</td>
      </tr>
      <tr>
        <td><div align="right"><b>Domicilio</b></div></td>
        <td colspan="2">
        	<div align="left">
          		<input name="domicilio" type="text" id="domicilio" size="70" value="<?php echo $rowConsultaPresta['domicilio'] ?>" />
        	</div>
        </td>
        <td>
        	<div align="left">
        		<b>Provincia</b>
          		<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaPresta['provincia'] ?>"/>
          		<input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaPresta['codprovin'] ?>"/>
        	</div>
      	</td>
      </tr>
      <tr>
        <td><div align="right"><b>Codigo Postal</b></div></td>
        <td>
        	<div align="left">
          		<input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaPresta['indpostal'] ?>"/>
				-<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaPresta['numpostal'] ?>" />
				-<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaPresta['alfapostal'] ?>"/>
			</div>
		</td>
        <td>
        	<div align="left">
	        	<b>Localidad</b>
		        <select name="selectLocali" id="selectLocali">
		        	<option value="0">Seleccione un valor </option>
		            <option value="<?php echo $rowConsultaPresta['codlocali'] ?>" selected="selected"><?php echo $rowConsultaPresta['localidad'] ?></option>
		        </select>
        	</div>
        </td>
        <?php
        	$disabled = "disabled='disabled'";
        	if ($rowConsultaPresta['localidad'] == "CAPITAL FEDERAL") { $disabled = ""; }
        ?>
        <td>
        	<div align="left">
	        	<b>Barrio</b>
	        	<select name="selectBarrio" id="selectBarrio" <?php echo $disabled?>>
	        		<?php if ($disabled == "") {
		        			$sqlBarrios="SELECT * FROM barrios";
		        			$resBarrios=mysql_query($sqlBarrios,$db);
		        			while($rowBarrios=mysql_fetch_array($resBarrios)) { 
		        				$selected = "";
		        				if ($rowBarrios['id'] == $rowConsultaPresta['idBarrio'] ) { $selected = "selected"; } ?>
		        				<option title ='<?php echo $rowBarrios['descripcion']?>' value='<?php echo $rowBarrios['id'] ?>' <?php echo $selected?>><?php echo utf8_encode($rowBarrios['descripcion']) ?></option>
		        	  <?php } ?>	
	        		<?php } else { ?>
	        				<option title ="Seleccione un valor" value="0">Seleccione un barrio</option>
	        		<?php } ?>
	        	</select>
        	</div>
        </td>
      </tr>
      <tr>
        <td><div align="right"><b>Telefono 1 </b></div></td>
        <td>
        	<div align="left">
        		(<input name="ddn1" type="text" id="ddn1" size="3" value="<?php echo $rowConsultaPresta['ddn1'] ?>"/>)-
            	<input name="telefono1" type="text" id="telefono1" size="15" value="<?php echo $rowConsultaPresta['telefono1'] ?>"/>
			</div>
		</td>
        <td>
        	<div align="left"><b>Telefono 2 </b>
        		(<input name="ddn2" type="text" id="ddn2" size="3" value="<?php echo $rowConsultaPresta['ddn2'] ?>"/>)-
				<input name="telefono2" type="text" id="telefono2" size="15" value="<?php echo $rowConsultaPresta['telefono2'] ?>"/>
            </div>
        </td>
        <td>
        	<div align="left"><b>Telefono FAX </b>
        		(<input name="ddnfax" type="text" id="ddnfax" size="3" value="<?php echo $rowConsultaPresta['ddnfax'] ?>"/>)-
            	<input name="telefonofax" type="text" id="telefonofax" size="15" value="<?php echo $rowConsultaPresta['telefonofax'] ?>"/>
			</div>
		</td>
      </tr>
      <tr>
        <td><div align="right"><b>Email Primario</b></div></td>
        <td colspan="3"><input name="email1" type="text" id="email1" size="60" value="<?php echo $rowConsultaPresta['email1'] ?>"/></td>
      </tr>
      <tr>
        <td><div align="right"><b>Email Secundario</b></div></td>
        <td colspan="3"><input name="email2" type="text" id="email2" size="60" value="<?php echo $rowConsultaPresta['email2'] ?>"/></td>
      </tr>
      <tr>
      	<td><div align="right"><b>Registro SSS </b></div></td>
      	<td>
            	<b>- Numero </b><input name="nroSSS" type="text" id="nroSSS" size="10" value="<?php echo $rowConsultaPresta['numeroregistrosss']; ?>" /> 
            	<?php 
            		$disabled = 'disabled=disabled';
            		$vtosss = '';
            		if ($rowConsultaPresta['numeroregistrosss'] != NULL) {
            			$disabled = '';
            			$vtosss = invertirFecha($rowConsultaPresta['vtoregistrosss'] );
	            	}?>
              - <b>VTO </b><input type="text" id="vtoSSS" name="vtoSSS" size="8" <?php echo $disabled?> value="<?php if ($vtosss != NULL) { echo $vtosss; }?>"/>         	       
        </td>
        <td colspan="2"><span id="errorSSS" style="color:#FF0000;font-weight: bold;"></span></td>
      </tr>
      <tr>
      	<td><div align="right"><b>Registro SNR </b></div></td>
      	<td>
            <b>- Numero </b><input name="nroSNR" type="text" id="nroSNR" size="10" value="<?php echo $rowConsultaPresta['numeroregistrosnr']; ?>"/> 
            	<?php 
            		$disabled = 'disabled=disabled';
            		$vtosnr = '';
            		if ($rowConsultaPresta['numeroregistrosnr'] != NULL) {
            			$disabled = '';
            			$vtosnr = invertirFecha($rowConsultaPresta['vtoregistrosnr'] );
	            	}?>
              - <b>VTO </b><input type="text" id="vtoSNR" name="vtoSNR" size="8" <?php echo $disabled?> value="<?php if ($vtosnr != NULL) { echo $vtosnr; }?>"/>     	
      	</td>
      	<td colspan="2"><span id="errorSNR" style="color:#FF0000;font-weight: bold;"></span> </td>
      </tr>
      <tr>
        <td><div align="right"><b>Personer&iacute;a</b></div></td>
        <td colspan="3">
        	<div align="left">
          	<?php 
          		$cartel = '';
				if ($rowConsultaPresta['personeria'] == 1) { 
					$disabled=""; 
					$deshabilitado = '';
				}
				if ($rowConsultaPresta['personeria'] == 2) {
					$disabled="disabled"; 
					$deshabilitado = '';
				}
				if ($rowConsultaPresta['personeria'] == 3) {
					$entidad = "";
					$disabled="disabled"; 
					
					$sqlNumProfesional = "select codigoprofesional from profesionales where codigoprestador = ".$rowConsultaPresta['codigoprestador']." and activo = 1";
					$resNumProfesional=mysql_query($sqlNumProfesional,$db);
					$cantidadProf = mysql_num_rows($resNumProfesional);
					if ($cantidadProf > 0) {
						$cartel = "Existe prof. activos.<br>";
					} 
				}
				if ($rowConsultaPresta['personeria'] == 4) {
					$entidad = "selected";
					$disabled="disabled";
					
					$sqlNumEstablecim = "select codigo from establecimientos where codigoprestador = ".$rowConsultaPresta['codigoprestador'];
					$resNumEstablecim = mysql_query($sqlNumEstablecim,$db);
					$cantidadEsta = mysql_num_rows($resNumEstablecim);
					if ($cantidadEsta > 0) {
						$cartel = "Existe Establecimientos<br>";
					} 
				}
				
				print("<span><font color='#0000CC'>$cartel</font></span>"); ?>
		  		<select name="selectPersoneria" id="selectPersoneria" onchange="habilitaCamposProfesional(this.value)" >
				  <?php if ($cantidadProf > 0 || $cantidadEsta > 0) { $selected = "disabled = 'disabled'"; } 
		              	$query="select * from tipoprestador";  
		              	$result=mysql_query($query,$db);
		              	while ($rowtipos=mysql_fetch_array($result)) { 
		              		if ($rowtipos['id'] == $rowConsultaPresta['personeria']) {   
		              			$selected = "selected"; 
		              		} else { 
		              			$selected = "";
		              			if ($cantidadProf > 0 || $cantidadEsta > 0) {
		              				$selected = "disabled = 'disabled'";
		              			}
		              		} ?>
							<option value="<?php echo $rowtipos['id']?>" <?php echo $selected ?>><?php echo $rowtipos['descripcion']?> </option>
				<?php 	} ?>
				  </select>	  
			</div>
		</td>
      </tr>
      <tr>
        <td><div align="right"><b>Tratamiento</b></div></td>
        <td><div align="left">
	          <select name="selectTratamiento" size="1" id="selectTratamiento" <?php echo $disabled ?> >
	            <option value="0">Seleccione un valor </option>
	            <?php 
						$query="select * from tipotratamiento";
						$result=mysql_query($query,$db);
						while ($rowtipos=mysql_fetch_array($result)) {
							if ($rowtipos['codigotratamiento'] == $rowConsultaPresta['tratamiento']) { $selected = "selected"; } else { $selected = ""; }?>
	            <option value="<?php echo $rowtipos['codigotratamiento'] ?>" <?php echo $selected ?>><?php echo $rowtipos['descripcion']  ?></option>
	            <?php } ?>
	          </select>
        	</div>
        </td>
        <td>
        	<div align="left">
        		<b>Matr&iacute;cula Nacional </b>
          		<input name="matriculaNac" type="text" id="matriculaNac" size="10" <?php echo $disabled ?> value="<?php echo $rowConsultaPresta['matriculanacional']?>"/>
        		<span id="errorMatNac" style="color:#FF0000;font-weight: bold;"></span>
        	</div>
        </td>
        <td>
        	<div align="left">
        		<b>Matr&iacute;cula Provincial </b>
          		<input name="matriculaPro" type="text" id="matriculaPro" size="10" <?php echo $disabled ?> value="<?php echo $rowConsultaPresta['matriculaprovincial'] ?>"/>
        		<span id="errorMatPro" style="color:#FF0000;font-weight: bold;"></span>
        	</div>
        </td>
      </tr>
      <tr>
        <td><div align="right"><b>Capitado</b></div></td>
        <td>
        	<div align="left">
	          	<?php if ($rowConsultaPresta['capitado'] == 0) { $nocapitado = "checked"; $capitado = ""; } else { $nocapitado = ""; $capitado = "checked"; } ?>
	          	<input name="capitado" type="radio" value="0" <?php echo $nocapitado ?> /> NO
				<input name="capitado" type="radio" value="1" <?php echo $capitado ?> /> SI 
			</div>
		</td>
		<td colspan="2">
			<div align="left">
				<b>Arancel Fijo</b>
				<?php if ($rowConsultaPresta['montofijo'] == 0) { $nofijo = "checked"; $sifijo = ""; } else { $nofijo = ""; $sifijo = "checked"; } ?>
          		<input name="fijo" type="radio" value="0" <?php echo $nofijo ?>/> NO
  		  		<input name="fijo" type="radio" value="1" <?php echo $sifijo ?>/>SI
		  	</div>
		</td>
      </tr>
      <tr>
	    <td><div align="right"><b>Nomenclador </b></div></td>
	    <td colspan="3">
	    	<div align="left" style="width: 80%">
            <?php 	
		          $today = date("Y-m-d");
		          $sqlContratoActivo = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = ".$rowConsultaPresta['codigoprestador']." and (c.fechafin is null or c.fechafin > '$today')";
		          $resContratoActivo = mysql_query($sqlContratoActivo,$db);
		          $canContratoActivo = mysql_num_rows($resContratoActivo);
		          $tieneContrato = false;
		          $cartel = '';
		          if ($canContratoActivo > 0) {
		          	$tieneContrato = true;
		            $onclick = 'return false';
		            $cartel = "Existe contratos abiertos. No se pueden quitar Nomencladores.";
		          }

            	  $query="select * from nomencladores"; 
	    	  	  $result=mysql_query($query,$db);  
	    	  	  $i = 0;
	    	  	  
            	  while ($rownom=mysql_fetch_array($result)) {
					$codigoNomenclador = $rownom['id'];
					$sqlExiste = "select * from prestadornomenclador where codigoprestador = $codigo and codigonomenclador = $codigoNomenclador";
					$resExiste = mysql_query($sqlExiste,$db);
					$numExiste = mysql_num_rows($resExiste);
					if ($numExiste == 1) {
						$checked = "checked";
						if ($tieneContrato) {
							$onclick = 'return false';
						}
					} else {
						$checked = "";
						$onclick = '';
					} ?>
					<input name="<?php echo "nomenclador".$i ?>" id="nomenclador" type="checkbox" <?php echo $checked ?> onclick="<?php echo $onclick ?>" value="<?php echo $rownom['id'] ?>" /><?php echo $rownom['nombre']." | "; ?>
				  	<?php $i++;
				  } 
				?>
        </div></td>
      </tr>
      <tr>
      	<td><div align="right"><b>Observacion </b></div></td>
      	<td colspan="5"><textarea rows="3" cols="130" id="observacion" name="observacion"><?php echo $rowConsultaPresta['observacion'] ?></textarea></td>
      </tr>
      <tr><td colspan="4" style="text-align: center"><font color='#0000CC'><?php echo $cartel ?></font></td></tr> 
    </table>
    <table width="884" border="0">
      <tr>
        <td width="284" height="46"><div align="center" class="Estilo1"><b>Servicios </b></div></td>
        <td colspan="2"><div align="center" class="Estilo1"><b>Jurisdiccion </b></div></td>
      </tr>
      <tr>
        <td valign="top">
		<div id="divServicios" align="left">
          <?php 
		  	if ($rowConsultaPresta['personeria'] == 1) { 
				$query="SELECT * FROM tiposervicio where profesional != 0"; 
			} else {
				$query="SELECT * FROM tiposervicio where profesional != 1"; 
			}
			$result=mysql_query($query,$db);
			$i=0;
			while ($rowtipos=mysql_fetch_array($result)) { 
				$codigoServicio = $rowtipos['codigoservicio'];
				$sqlExiste = "select * from prestadorservicio where codigoprestador = $codigo and codigoservicio = $codigoServicio";
				$resExiste = mysql_query($sqlExiste,$db); 
				$numExiste = mysql_num_rows($resExiste);
				if ($numExiste == 1) {
					$checked = "checked";
				} else {
					$checked = "";
				}	?>
          		<input type="checkbox" <?php echo $checked ?> id="servicios" name="<?php echo "servicios".$i ?>" value="<?php echo $rowtipos['codigoservicio'] ?>" />
          <?php 	echo $rowtipos['descripcion']."<br>";
		  			$i++; 
           		} ?>
        </div></td>
        <td width="281" valign="top"><div align="left">
          <?php 
				$query="select * from delegaciones where codidelega >= 1002 and codidelega <= 1702";
				$result=mysql_query($query,$db);
				$i = 0;
				while ($rowtipos=mysql_fetch_array($result)) { 
					$codigoDelega = $rowtipos['codidelega'];
					$sqlExiste = "select * from prestadorjurisdiccion where codigoprestador = $codigo and codidelega = $codigoDelega";
					$resExiste = mysql_query($sqlExiste,$db); 
					$numExiste = mysql_num_rows($resExiste);
					if ($numExiste == 1) {
						$checked = "checked";
					} else {
						$checked = "";
					}	
					?>
          <input type="checkbox" <?php echo $checked ?> name="<?php echo "delegaciones".$i ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" />
          <?php echo $rowtipos['nombre'] ?><br />
          <?php 	$i++;
				} ?>
        </div></td>
        <td width="297" valign="top"><div align="left">
          <?php 
				$query="select * from delegaciones where codidelega > 1702 and codidelega < 3200";
				$result=mysql_query($query,$db);				
				while ($rowtipos=mysql_fetch_array($result)) {
					$codigoDelega = $rowtipos['codidelega'];
					$sqlExiste = "select * from prestadorjurisdiccion where codigoprestador = $codigo and codidelega = $codigoDelega";
					$resExiste = mysql_query($sqlExiste,$db); 
					$numExiste = mysql_num_rows($resExiste);
					if ($numExiste == 1) {
						$checked = "checked";
					} else {
						$checked = "";
					}
				 ?>
          <input type="checkbox" <?php echo $checked ?> name="<?php echo "delegaciones".$i  ?>" id="delegaciones" value="<?php echo $rowtipos['codidelega'] ?>" />
          <?php echo $rowtipos['nombre'] ?><br />
          <?php 	$i++;
				} ?>
        </div></td>
      </tr>
    </table>
     <p><input type="button" name="guardar" id="guardar" value="Guardar" onclick="validar()"/></p>
  </form>
</div>
</body>
</html>
