<?php 
include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");
$cuit=$_GET['cuit'];

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result);
$row = mysql_fetch_array($result); 

$numpostal=$_GET['numpostal'];
if ($numpostal == "") {
	$numpostal = $row['numpostal'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Cabecera Empresa :.</title>
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
			url: "localidadPorCP.php?origen=<?php echo $origen ?>",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectLocali").html(respuesta);
			$("#indpostal").val("");
			$("#alfapostal").val("");
			$("#provincia").val("");
			$("#codprovin").val("");
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
		});
	});
});

function validar(formulario) {
	if (!verificaCuilCuit(formulario.cuit.value)){
		alert("C.U.I.T. invalido");
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
   <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 	
  <p><strong>Modificacion Cabecera de Empresa</strong>
  <form name="modifCabeEmpresa" id="modifCabeEmpresa" method="post" onSubmit="return validar(this)" action="guardarModifCabecera.php?origen=<?php echo $origen ?>">
    <table width="723" border="0">
      <tr>
        <td width="167"><div align="right"><strong>C.U.I.T. </strong></div></td>
        <td width="540"><div align="left">
			<input style="background-color:#CCCCCC" name="cuit" type="text" id="cuit" size="12" value="<?php echo $row['cuit'];?>"  readonly="readonly"/>                
          </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Raz&oacute;n Social</strong></div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre" value="<?php echo $row['nombre'];?>" size="90" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Domicilio</strong></div></td>
        <td><div align="left">
          <input name="domicilio" type="text" id="domicilio" value="<?php echo $row['domilegal'];?>" size="90" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Codigo Postal</strong></div></td>
        <td><div align="left">
          <label>
          <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $row['indpostal'];?>"/>
          </label>
          -
          <input name="codPos" type="text" id="codPos" value="<?php echo $numpostal ?>" size="7"/>
		  -        
		  <label>
		  <input name="alfapostal" id="alfapostal" type="text" size="3" value="<?php echo $row['alfapostal'];?>"/>
		  </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Localidad</strong></div></td>
        <td><div align="left">
            <select name="selectLocali" id="selectLocali">
              <option value="0">Seleccione un valor </option>
              <?php 
			  		
					$sqlLoca="select * from localidades where numpostal = $numpostal";
					$resLoca= mysql_query($sqlLoca,$db);
					while ($rowLoca= mysql_fetch_array($resLoca)) {	
						if ($rowLoca['codlocali'] == $row['codlocali']) {?>
              				<option value="<?php echo $rowLoca['codlocali'] ?>" selected="selected"><?php echo $rowLoca['nomlocali']  ?></option>
              	 <?php } else { ?>
              				<option value="<?php echo $rowLoca['codlocali'] ?>"><?php echo $rowLoca['nomlocali']  ?></option>
             	 <?php } ?>
             <?php } ?>
            </select>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Provincia</strong></div></td>
        <td><div align="left">
			<?php	
				$codProvi = $row['codprovin'];
				$sqlProvi = "select * from provincia where codprovin = $codProvi ";
				$resProvi = mysql_query($sqlProvi,$db);
				$rowProvi = mysql_fetch_array($resProvi);
			?>
             <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowProvi['descrip'];?>" />
			  <input style="background-color:#CCCCCC; visibility:hidden" value="<?php echo $row['codprovin'] ?>" readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 1 </strong></div></td>
        <td>
          <div align="left">
            <input name="ddn1" type="text" id="ddn1" value="<?php echo $row['ddn1'];?>" size="5" />
            - 
            <input name="telefono1" type="text" id="telefono1" value="<?php echo $row['telefono1'];?>" size="10" />
          </div>        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>Contacto 1 </strong></div></td>
        <td>
          <div align="left">
            <input name="contacto1" type="text" id="contacto1" value="<?php echo $row['contactel1'];?>" size="50" />
          </div>			</td>
      </tr>
      <tr>
        <td><div align="right"><strong>Telefono 2 </strong></div></td>
        <td><div align="left">
          <input name="ddn2" type="text" id="ddn2" value="<?php echo $row['ddn2'];?>" size="5" />
          -
          <input name="telefono2" type="text" id="telefono2" value="<?php echo $row['telefono2'];?>" size="10" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Contacto 2 </strong></div></td>
        <td><div align="left">
          <input name="contacto2" type="text" id="contacto2" value="<?php echo $row['contactel2'];?>" size="50" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Actividad</strong></div></td>
        <td><div align="left">
          <label>
          <input name="actividad" id="actividad" type="text" value="<?php echo $row['actividad'];?>" size="80" />
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Obs. OSPIM </strong></div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<textarea name='obsospim' cols='60' rows='2' id='obsospim'>".$row['obsospim']."</textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsospim' cols='60' rows='2' id='obsospim'>".$row['obsospim']."</textarea>";
				} 
			?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Obs. USIMRA </strong></div></td>
        <td><div align="left">
            <?php if ($origen == "usimra") {
         	 		echo "<textarea name='obsusimra' cols='60' rows='2' id='obsusimra'>".$row['obsusimra']."</textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsusimra' cols='60' rows='2' id='obsusimra'>".$row['obsusimra']."</textarea>";
				} 
			?>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Inicio Obl. OSPIM </strong></div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<input name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10' value='".invertirFecha($row['iniobliosp'])."'/>";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10' value='".invertirFecha($row['iniobliosp'])."'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Inicio Obl. USIMRA </strong></div></td>
        <td><div align="left">
          <label>
          	<?php if ($origen == "usimra") {
         	 		echo "<input name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10' value='".invertirFecha($row['iniobliusi'])."'/>";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10' value='".invertirFecha($row['iniobliusi'])."'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Email</strong></div></td>
        <td><div align="left">
          <input name="email" type="text" id="email" value="<?php echo $row['email'];?>" size="60" />
        </div></td>
      </tr>
      <tr>
        <td><div align="right"><strong>Carpeta Archivo </strong></div></td>
        <td><div align="left">
          <?php if ($origen == "ospim") {
         	 		echo "<input name='carpetaArc' type='text' id='carpetaArc' value='".$row['carpetaenarchivo']."' size='10' />";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='carpetaArc' type='text' id='carpetaArc' value='".$row['carpetaenarchivo']."' size='10' />";
				} ?>
		  
        </div></td>
      </tr>
    </table>
    <table width="727" border="0">
      <tr>
        <td width="359"><div align="left">
          <input type="submit" name="Submit" id="Submit" value="Guardar" />
        </div></td>
        <td width="358"><div align="right">
          <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" />
        </div></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>
      <label></label>
    </p>
    <p>&nbsp;</p>
  </form>
  </p>
</div>
</body>
</html>
