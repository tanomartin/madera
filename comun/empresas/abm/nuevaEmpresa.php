<?php 
include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");

$cuit=$_GET['cuit'];

$sqlEmpresaExiste = "select * from empresas where cuit = $cuit";
$resEmpresaExiste = mysql_query($sqlEmpresaExiste,$db);	
$canEmpresaExiste = mysql_num_rows($resEmpresaExiste); 
if ($canEmpresaExiste > 0) {
	header ("Location: moduloABM.php?origen=$origen&err=2");
}

$numpostal=$_GET['numpostal'];
$nombre=$_GET['nombre'];
$domicilio=$_GET['domicilio'];
$alfapostal=$_GET['alfapostal'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Alta Empresa :.</title>
</head>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	$("#fechaInicioOspim").mask("99-99-9999");
	$("#fechaInicioUsimra").mask("99-99-9999");
	$("#alfapostal").mask("aaa");
	
	$("#codPos").change(function(){
		var codigo = $(this).val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "localidadPorCP.php",
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
			url: "cambioProvincia.php",
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
			url: "buscaJurisdicciones.php",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectDelegacion").html(respuesta);
		});
	});
	
});

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T invalido");
		return false;
	}
	if (formulario.nombre.value == "") {
		alert("El campo Razon social es Obligatrio");
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
	
	if (formulario.ddn1.value != "") {
		if (!esEnteroPositivo(formulario.ddn1.value)) {
			alert("El codigo de area 1 debe ser un numero");
			return false;
		}
	} else {
		formulario.ddn1.value = "0";
	}
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	} else {
		formulario.telefono1.value = "0";
	}
	if (formulario.ddn2.value != "") {
		if (!esEnteroPositivo(formulario.ddn2.value)) {
			alert("El codigo de area 2 debe ser un numero");
			return false;
		}
	} else {
		formulario.ddn2.value = "0";
	}
	if (formulario.telefono2.value != "") {
		if (!esEnteroPositivo(formulario.telefono2.value)) {
			alert("El telefono 2 debe ser un numero");
			return false;
		}
	} else {
		formulario.telefono2.value = "0";
	}
	
	if (formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value == 0) {
		alert("Debe elegir una Delegacion");
		return false;
	}
	if (formulario.fechaInicioOspim.value != "" & formulario.fechaInicioOspim.value != "00-00-0000") {
		if (!esFechaValida(formulario.fechaInicioOspim.value)) {
			alert("La fecha de inicio de obligacion OSPIM no es valida");
			return false;
		}
	} else {
		formulario.fechaInicioOspim.value = "00-00-0000";
	}
	if (formulario.fechaInicioUsimra.value != "" & formulario.fechaInicioUsimra.value != "00-00-0000") {
		if (!esFechaValida(formulario.fechaInicioUsimra.value)) {
			alert("La fecha de inicio de obligacion USIMRA no es valida");
			return false;
		}
	} else {
		formulario.fechaInicioUsimra.value = "00-00-0000";
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloABM.php?origen=<?php echo $origen ?>'" align="center"/> 
  <p><strong>Alta Cabecera de Empresa</strong>
  <form name="nuevaCabeEmpresa" id="nuevaCabeEmpresa" method="post" onSubmit="return validar(this)" action="guardarEmpresa.php?origen=<?php echo $origen ?>">
    <table width="723" border="0">
      <tr>
        <td width="167"><div align="right"><strong>C.U.I.T. </strong></div></td>
        <td width="540"><div align="left">
			<input readonly='readonly' style='background-color:#CCCCCC' name="cuit" value="<?php echo $cuit ?>" type="text" id="cuit" size="12" />                
          </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre" size="90" />
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
          <input name="codPos" type="text" id="codPos" size="7" />
		  -        
		  <label>
		  <input name="alfapostal"  id="alfapostal" type="text" size="3"/>
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
             <input style="background-color:#CCCCCC; visibility:visible " readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
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
        <td><div align="right"><strong>Telefono 2 </strong></div></td>
        <td><div align="left">
          <input name="ddn2" type="text" id="ddn2" size="5" />
          -
          <input name="telefono2" type="text" id="telefono2" size="10" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Contacto 2 </strong></div></td>
        <td><div align="left">
          <input name="contacto2" type="text" id="contacto2" size="50" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Actividad</strong></div></td>
        <td><div align="left">
          <label>
          <input name="actividad" id="actividad" type="text" size="80" />
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Delegacion</strong></div></td>
        <td>
          <div align="left">
             <select name="selectDelegacion" id="selectDelegacion">
              <option value="0">Seleccione un valor </option>
            </select>
          </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Obs. OSPIM </strong></div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<textarea name='obsospim' cols='60' rows='2' id='obsospim'></textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsospim' cols='60' rows='2' id='obsospim'></textarea>";
				} 
			?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Obs. USIMRA </strong></div></td>
        <td><div align="left">
            <?php if ($origen == "usimra") {
         	 		echo "<textarea name='obsusimra' cols='60' rows='2' id='obsusimra'></textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsusimra' cols='60' rows='2' id='obsusimra'></textarea>";
				} 
			?>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Inicio Obl. OSPIM </strong></div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<input name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10'/>";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Inicio Obl. USIMRA </strong></div></td>
        <td><div align="left">
          <label>
          	<?php if ($origen == "usimra") {
         	 		echo "<input name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10' />";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Email</strong></div></td>
        <td><div align="left">
          <input name="email" type="text" id="email" size="60" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Carpeta Archivo </strong></div></td>
        <td><div align="left">
          <?php if ($origen == "ospim") {
         	 		echo "<input name='carpetaArc' type='text' id='carpetaArc' size='10' />";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='carpetaArc' type='text' id='carpetaArc'  size='10' />";
				} ?>
		  
        </div></td>
      </tr>
    </table>
    <p>
      <label>
      <input type="submit" name="Submit" id="Submit" value="Guardar">
      </label>
    </p>
  </form>
  </p>
</div>
</body>
</html>
