<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 

$codigopresta = $_GET['codigopresta'];
$sqlConsultaPresta = "SELECT p.*, l.nomlocali as localidad, r.descrip as provincia FROM prestadores p, localidades l, provincia r WHERE p.codigoprestador = $codigopresta and p.codlocali = l.codlocali and p.codprovin = r.codprovin";
$resConsultaPresta = mysql_query($sqlConsultaPresta,$db);
$rowConsultaPresta = mysql_fetch_assoc($resConsultaPresta);

$codigoprof = $_GET['codigoprof'];
$sqlConsultaProf = "SELECT p.*, pr.nombre as prestador, l.nomlocali as localidad, r.descrip as provincia FROM profesionales p, prestadores pr, localidades l, provincia r WHERE p.codigoprofesional = $codigoprof and p.codlocali = l.codlocali and p.codprovin = r.codprovin and p.codigoprestador = pr.codigoprestador";
$resConsultaProf = mysql_query($sqlConsultaProf,$db);
$rowConsultaProf = mysql_fetch_assoc($resConsultaProf);

$sqlCategoria = "select * from practicascategorias";
$resCategoria = mysql_query($sqlCategoria,$db); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Profesional :.</title>
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

function validar(formulario) {
	if (formulario.nombre.value == "") {
		alert("El campo Nombre es Obligatrio");
		return false;
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
	if (formulario.cuit.value != "") {
		if (!verificaCuilCuit(formulario.cuit.value)){
			alert("C.U.I.T invalido");
			return false;
		}
	}
	var tratamiento = formulario.selectTratamiento.options[formulario.selectTratamiento.selectedIndex].value;
	if (tratamiento == 0) {
		alert("Debe elegir una Tramtamiento para Persona F�sica");
		return false;
	}
	if (formulario.nroRegistro.value != "") {
		if (!esEntero(formulario.nroRegistro.value)) {
			alert("El Nro. de Registro en la SSS debe ser un numero");
			return false;
		}
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
   <p><input type="button" name="volver" value="Volver" onclick="location.href = 'modificarProfesionales.php?codigo=<?php echo $codigopresta ?>'" /></p>
   <h3>Modificar Profesional</h3>
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
  <form name="nuevoPrestador" id="nuevoPrestador" method="post" onsubmit="return validar(this)" action="guardarModificacionProfesional.php?codigopresta=<?php echo $codigopresta ?>">
    <table border="0">
      <tr>
        <td><div align="right"><strong>C�digo</strong></div></td>
        <td colspan="5"><div align="left">
          <input name="codigo" readonly="readonly" style="background:#CCCCCC" type="text" id="codigo2" size="4" value="<?php echo $rowConsultaProf['codigoprofesional'] ?>"/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Nombre</strong></div></td>
        <td colspan="5"><div align="left"><input name="nombre" type="text" id="nombre" size="120" value="<?php echo $rowConsultaProf['nombre'] ?>"/></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>C.U.I.T.</strong></div></td>
        <td><div align="left"><input name="cuit" type="text" id="cuit" size="13" value="<?php echo $rowConsultaProf['cuit'] ?>"/></div></td>
      	<td><strong>Categoria</strong>
        	<select name="idcategoria" id="idcategoria">
      			<?php while($rowCategoria = mysql_fetch_assoc($resCategoria)) { 
      					if ($rowCategoria['id'] == $rowConsultaProf['idcategoria']) { $selected = "selected"; } else { $selected = ""; }?> 
      					<option value="<?php echo $rowCategoria['id'] ?>" <?php echo $selected ?>><?php echo $rowCategoria['descripcion'] ?></option>
      			<?php } ?>
      		</select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td colspan="5"><div align="left"><input name="domicilio" type="text" id="domicilio" size="120" value="<?php echo $rowConsultaProf['domicilio'] ?>" /> </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td width="244"><div align="left">
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $rowConsultaProf['indpostal'] ?>"/>
-
<input name="codPos" type="text" id="codPos" size="7" value="<?php echo $rowConsultaProf['numpostal'] ?>" />
-
<input name="alfapostal"  id="alfapostal" type="text" size="3" value="<?php echo $rowConsultaProf['alfapostal'] ?>"/>
		</div></td>
        <td width="365"><div align="left"><strong>Localidad</strong><strong>
          <select name="selectLocali" id="selectLocali">
            <option value="0">Seleccione un valor </option>
            <option value="<?php echo $rowConsultaProf['codlocali'] ?>" selected="selected"><?php echo $rowConsultaProf['localidad'] ?></option>
          </select>
        </strong></div></td>
        <td><div align="left"><strong>Provincia</strong><strong>
          <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowConsultaProf['provincia'] ?>"/>
          <input style="background-color:#CCCCCC; visibility:hidden " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2" value="<?php echo $rowConsultaProf['codprovin'] ?>"/>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td><div align="left">(<input name="ddn1" type="text" id="ddn1" size="3" value="<?php echo $rowConsultaProf['ddn1'] ?>"/>)-<input name="telefono1" type="text" id="telefono1" size="15" value="<?php echo $rowConsultaProf['telefono1'] ?>"/></div></td>
        <td colspan="4"><div align="left"><strong>Telefono 2 </strong>( <strong><input name="ddn2" type="text" id="ddn2" size="3" value="<?php echo $rowConsultaProf['ddn2'] ?>"/></strong> )-<strong><input name="telefono2" type="text" id="telefono2" size="15" value="<?php echo $rowConsultaProf['telefono2'] ?>"/></strong></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono FAX </strong></div></td>
        <td><div align="left">(<input name="ddnfax" type="text" id="ddnfax" size="3" value="<?php echo $rowConsultaProf['ddnfax'] ?>"/>)-<input name="telefonofax" type="text" id="telefonofax" size="15" value="<?php echo $rowConsultaProf['telefonofax'] ?>"/>
</div></td>
        <td colspan="4"><div align="left"><strong>Email</strong> <input name="email" type="text" id="email" size="30" value="<?php echo $rowConsultaProf['email'] ?>"/></div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Tratamiento</strong></div></td>
        <td><div align="left">
          <select name="selectTratamiento" size="1" id="selectTratamiento">
            <option value="0">Seleccione un valor </option>
            <?php 
					$query="select * from tipotratamiento";
					$result=mysql_query($query,$db);
					while ($rowtipos=mysql_fetch_array($result)) {
						if ($rowtipos['codigotratamiento'] == $rowConsultaProf['tratamiento']) { $selected = "selected"; } else { $selected = ""; }?>
            <option value="<?php echo $rowtipos['codigotratamiento'] ?>" <?php echo $selected ?>><?php echo $rowtipos['descripcion']  ?></option>
            <?php } ?>
          </select>
        </div></td>
        <td><div align="left"><strong>Matr�cula Nacional </strong>
          <input name="matriculaNac" type="text" id="matriculaNac" size="10" value="<?php echo $rowConsultaProf['matriculanacional']?>" maxlength="20"/>
        </div></td>
        <td colspan="3"><div align="left"><strong>Matr�culo Provincial </strong><strong>
          <input name="matriculaPro" type="text" id="matriculaPro" size="10" value="<?php echo $rowConsultaProf['matriculaprovincial'] ?>" maxlength="20"/>
        </strong></div></td>
      </tr>
      <tr>
        <td><div align="left"><strong>Numero Registro SSS</strong></div></td>
        <td><div align="left"><input name="nroRegistro" type="text" id="nroRegistro" size="10" value="<?php echo $rowConsultaProf['numeroregistrosss']?>" maxlength="20"/></div></td>
      	<td colspan="4"><strong>Activo</strong> 
			<?php if ($rowConsultaProf['activo'] == 0) { $selectedNo = "selected"; $selectedSi = ""; } else { $selectedNo = ""; $selectedSi = "selected"; } ?>
			 <select name="activo" id="activo">
            	<option value="0" <?php echo $selectedNo ?>>NO</option>
				<option value="1" <?php echo $selectedSi ?>>SI</option>
			 </select>
		</td>
	  </tr>
    </table>
    <p><input type="submit" name="Submit" id="Submit" value="Guardar Modificaci�n" /></p>
  </form>
  </div>
</body>
</html>
