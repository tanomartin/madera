<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigo = $_GET['codigo'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r WHERE p.codigoprestador = $codigo and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$sqlConsultaServcio = "SELECT s.descripcion FROM prestadorservicio p, tiposervicio s WHERE p.codigoprestador = $codigo and p.codigoservicio = s.codigoservicio";
$resConsultaServcio = mysql_query($sqlConsultaServcio,$db);

$sqlConsultaJuris = "SELECT p.codidelega, d.nombre FROM prestadorjurisdiccion p, delegaciones d WHERE p.codigoprestador = $codigo and p.codidelega = d.codidelega";
$resConsultaJuris = mysql_query($sqlConsultaJuris,$db);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Prestador :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
.miestilo {
	background-color: #CCCCCC;
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
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
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
		alert("Debe elegir como mínimo un nomenclador para el prestador");
		return false;
	}
	
	var servicioCheck = 0;
	servicios = formulario.servicios;
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
	
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC" onload="habilitarServicios('<?php echo $rowConsultaPresta['personeria'] ?>')">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'prestador.php?codigo=<?php echo $codigo ?>'" />
  </span></p>
  <p><strong>Modificar Prestador</strong></p>
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarModificacionPrestador.php">
    <table border="0">
      <tr>
        <td><div align="right"><strong>C&oacute;digo</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo2" size="4" value="<?php echo $rowConsultaPresta['codigoprestador'] ?>"/>
        </div></td>
      </tr>
      <tr>
        <td width="129"><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
        <td colspan="5"><div align="left">
          <div align="left">
            <input name="nombre" type="text" id="nombre" size="120" value="<?php echo $rowConsultaPresta['nombre'] ?>"/>
          </div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="domicilio" type="text" id="domicilio" size="120" value="<?php echo $rowConsultaPresta['domicilio'] ?>" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>C.U.I.T.</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="cuit" type="text" id="cuit" size="13" value="<?php echo $rowConsultaPresta['cuit'] ?>"/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td width="244"><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaPresta['indpostal'] ?>"/>
-
<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaPresta['numpostal'] ?>" />
-
<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaPresta['alfapostal'] ?>"/>
</div>
            <div align="right"></div></td>
        <td width="365"><div align="left"><strong>Localidad</strong><strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
            <option value="<?php echo $rowConsultaPresta['codlocali'] ?>" selected="selected"><?php echo $rowConsultaPresta['localidad'] ?></option>
          </select>
        </strong></div></td>
        <td><div align="left"><strong>Provincia          </strong><strong>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaPresta['provincia'] ?>"/>
          <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaPresta['codprovin'] ?>"/>
        </strong></div>
            <div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left">(
            <input name="ddn1" type="text" id="ddn1" size="5" value="<?php echo $rowConsultaPresta['ddn1'] ?>"/>
            )-
            <input name="telefono1" type="text" id="telefono1" size="15" value="<?php echo $rowConsultaPresta['telefono1'] ?>"/>
</div></td>
        <td colspan="4"><div align="left"><strong>Telefono 2 </strong>(
              <strong>
<input name="ddn2" type="text" id="ddn2" size="5" value="<?php echo $rowConsultaPresta['ddn2'] ?>"/>
            </strong> )-<strong>
<input name="telefono2" type="text" id="telefono2" size="15" value="<?php echo $rowConsultaPresta['telefono2'] ?>"/>
                    </strong></div></td>
      </tr>
      <tr>
        <td><div align="right">
            <div align="right"><strong>Telefono FAX </strong></div>
        </div></td>
        <td><div align="left">(
            <input name="ddnfax" type="text" id="ddnfax" size="5" value="<?php echo $rowConsultaPresta['ddnfax'] ?>"/>
            )-
            <input name="telefonofax" type="text" id="telefonofax" size="15" value="<?php echo $rowConsultaPresta['telefonofax'] ?>"/>
</div></td>
        <td colspan="4"><div align="left"><strong>Email</strong>
          <input name="email" type="text" id="email" size="30" value="<?php echo $rowConsultaPresta['email'] ?>"/>
        </div>
            <div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Personer&iacute;a</strong></div></td>
        <td><div align="left">
          <?php 
				if ($rowConsultaPresta['personeria'] == 1) { 
					$profesional = "selected"; 
					$establecimiento = "" ; 
					$ciculo = "";
					$entidad = "";
					$disabled=""; 
				}
				if ($rowConsultaPresta['personeria'] == 2) {
					$profesional = "";
					$establecimiento = "selected"; 
					$ciculo = "";
					$entidad = "";
					$disabled="disabled"; 
				}
				if ($rowConsultaPresta['personeria'] == 3) {
					$profesional = "";
					$establecimiento = ""; 
					$ciculo = "selected";
					$entidad = "";
					$disabled="disabled"; 
				}
				if ($rowConsultaPresta['personeria'] == 4) {
					$profesional = "";
					$establecimiento = "";
					$ciculo = "";
					$entidad = "selected";
					$disabled="disabled";
				}
				
				if ($rowConsultaPresta['personeria'] == 3) {
					$sqlNumProfesional = "select codigoprofesional from profesionales where codigoprestador = ".$rowConsultaPresta['codigoprestador']." and activo = 1";
					$resNumProfesional=mysql_query($sqlNumProfesional,$db);
					$cantidad = mysql_num_rows($resNumProfesional);
					if ($cantidad > 0) { 
						$deshabilitado = 'onmouseover="this.disabled=true;" onmouseout="this.disabled=false;" class="miestilo"';
						$cartel = "Existe prof. activos.<br>";
					} else {
						$deshabilitado = '';
						$castel = '';
					}
				}
				
				if ($rowConsultaPresta['personeria'] == 4) {
					$sqlNumEstablecim = "select codigo from establecimientos where codigoprestador = ".$rowConsultaPresta['codigoprestador'];
					$resNumEstablecim = mysql_query($sqlNumEstablecim,$db);
					$cantidad = mysql_num_rows($resNumEstablecim);
					if ($cantidad > 0) {
						$deshabilitado = 'onmouseover="this.disabled=true;" onmouseout="this.disabled=false;" class="miestilo"';
						$cartel = "Existe Establecimientos<br>";
					} else {
						$deshabilitado = '';
						$castel = '';
					}
				}
				
				print("<span><font color='#0000CC'>$cartel</font></span>"); ?>
		  		<select name="selectPersoneria" id="selectPersoneria" onchange="habilitaCamposProfesional(this.value)" <?php echo $deshabilitado ?>>
					<option value="0">Seleccione un valor </option>
					<?php 
		              	$query="select * from tipoprestador";  
		              	$result=mysql_query($query,$db);
		              	while ($rowtipos=mysql_fetch_array($result)) { 
		              		if ($rowtipos['id'] == $rowConsultaPresta['personeria']) {   $selected = "selected"; } else { $selected = ""; } ?>
							<option value="<?php echo $rowtipos['id']?>" <?php echo $profesional ?> <?php echo $selected ?>><?php echo $rowtipos['descripcion']?> </option>
				<?php 	} ?>
				  </select>	  
</div></td>
        <td colspan="4"><div align="left">
            <div align="left"><strong>Numero Registro SSS</strong>
              <input name="nroRegistro" type="text" id="nroRegistro" size="10" value="<?php echo $rowConsultaPresta['numeroregistrosss']?>"/>
            </div>
        </div>
            <div align="left"></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Tratamiento</strong></div></td>
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
        </div></td>
        <td><div align="left"><strong>Matr&iacute;cula Nacional </strong>
          <input name="matriculaNac" type="text" id="matriculaNac" size="10" <?php echo $disabled ?> value="<?php echo $rowConsultaPresta['matriculanacional']?>"/>
        </div></td>
        <td colspan="3"><div align="left"><strong>Matr&iacute;culo Provincial </strong><strong>
          <input name="matriculaPro" type="text" id="matriculaPro" size="10" <?php echo $disabled ?> value="<?php echo $rowConsultaPresta['matriculaprovincial'] ?>"/>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Capitado</strong></div></td>
        <td colspan="5">
        	<div align="left">
	          	<?php if ($rowConsultaPresta['capitado'] == 0) { $nocapitado = "checked"; } else { $capitado = "checked"; } ?>
	          	<input name="capitado" type="radio" value="0" <?php echo $nocapitado ?> /> NO
				<input name="capitado" type="radio" value="1" <?php echo $capitado ?> /> SI 
			</div>
		</td>
      </tr>
      <tr>
	    <td><div align="right"><strong>Nomenclador </strong></div></td>
	    <td colspan="5"><div align="left">
            <?php 	
		          $today = date("Y-m-d");
		          $sqlContratoActivo = "SELECT c.* FROM cabcontratoprestador c  WHERE c.codigoprestador = ".$rowConsultaPresta['codigoprestador']." and (c.fechafin = '0000-00-00' or c.fechafin > '$today')";
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
       <tr><td colspan="5" style="text-align: center"><font color='#0000CC'><?php echo $cartel ?></font></span></td></tr> 
    </table>
    <table width="884" border="0">
      <tr>
        <td width="284" height="46"><div align="center" class="Estilo1"><strong>Servicios </strong></div></td>
        <td colspan="2"><div align="center" class="Estilo1"><strong>Jurisdiccion </strong></div></td>
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
				}	
		?>
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
    <p>&nbsp;</p>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar Modificaci&oacute;n"/>
    </p>
  </form>
</div>
</body>
</html>
