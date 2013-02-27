<?php 
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/fechas.php");
$cuit=$_GET['cuit'];
$delega=$_GET['coddel'];

$sql = "select * from jurisdiccion where cuit = $cuit and codidelega = $delega";
$result = mysql_query($sql,$db);
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
<title>.: Modificar Jurisdicciones Empresa :.</title>
</head>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
		$("#cuit").mask("99999999999");
});

function cambioProvincia(locali) {
	var o
	document.forms.modifJurisEmpresa.selectDelegacion.length = 0;
	o = document.createElement("OPTION");
	o.text = 'Seleccione un valor';
	o.value = 0;
	document.forms.modifJurisEmpresa.selectDelegacion.options.add(o);
	<?php 
		$sqlLocali = "select codlocali, codprovin from localidades";
		$resLocali = mysql_query($sqlLocali,$db);
		while($rowLocali = mysql_fetch_array($resLocali)) { ?>
			if (locali == <?php echo $rowLocali['codlocali'] ?>)  {
				<?php	
					$codprovin =  $rowLocali['codprovin'];
					$sqlProvin = "select * from provincia where codprovin = $codprovin";
					$resProvin = mysql_query($sqlProvin,$db);
					$rowProvin = mysql_fetch_array($resProvin)
				?>
				document.forms.modifJurisEmpresa.provincia.value = "<?php echo $rowProvin['descrip'] ?>";
				document.forms.modifJurisEmpresa.indpostal.value = "<?php echo $rowProvin['indpostal'] ?>";			
				document.forms.modifJurisEmpresa.codprovin.value = "<?php echo $rowProvin['codprovin'] ?>";
				
				<?php 
					$sqlDelega = "select * from delegaciones where codprovin = $codprovin";
					$resDelega = mysql_query($sqlDelega,$db);
					while($rowDelega = mysql_fetch_array($resDelega)) { ?>
						o = document.createElement("OPTION");
						o.text = '<?php echo $rowDelega["nombre"]; ?>';
						o.value = <?php echo $rowDelega["codidelega"]; ?>;
						document.forms.modifJurisEmpresa.selectDelegacion.options.add(o);
			<?php	} ?>
			}
<?php } ?>
}

function validar(formulario) {
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
	}
	if (formulario.telefono1.value != "") {
		if (!esEnteroPositivo(formulario.telefono1.value)) {
			alert("El telefono 1 debe ser un numero");
			return false;
		}
	} else {
		formulario.telefono1.value = "0";
	}
	
	if (formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value == 0) {
		alert("Debe elegir una Delegacion");
		return false;
	}
	
	//ANTES DE ESTO TENGO QUE VER SI TIENE BENEFICIARIOS Y DAR UN SI O UN NO
	return true;
}


</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><strong><a href="empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p><strong>Modificacion Jurisdicciones de Empresa</strong>
  <form name="modifJurisEmpresa" id="modifJurisEmpresa" method="post" onSubmit="return validar(this)" action="guardarModifJurisdiccion.php?origen=<?php echo $origen ?>&coddelega=<?php echo $delega ?>">	
	 	<table width="723" border="1">
		  <tr>
			<td width="167"><div align="left">C.U.I.T. </div></td>
			<td width="540"><div align="left">
				<input style="background-color:#CCCCCC" name="cuit" type="text" id="cuit" size="12" value="<?php echo $row['cuit'];?>"  readonly="readonly"/>                
			  </div></td>
		  </tr>
		  <tr>
			<td><div align="left">Domicilio</div></td>
			<td><div align="left">
			  <input name="domicilio" type="text" id="domicilio" value="<?php echo $row['domireal'];?>" size="90" />
			</div></td>
		  </tr>
		  <tr>
			<td><div align="left">Codigo Postal</div></td>
			<td><div align="left">
			  <label>
			  <input style="background-color:#CCCCCC" name="indpostal" type="text" size="1" value="<?php echo $row['indpostal'];?>"/>
			  </label>
			  -
			  <input name="codPos" type="text" id="codPos" value="<?php echo $numpostal ?>" size="7" onchange='location.href="modificarJurisdiccion.php?origen=<?php echo $origen ?>&coddel=<?php echo $delega ?>&cuit=<?php echo $cuit ?>&numpostal="+ document.forms.modifJurisEmpresa.codPos.value'  />
			  -        
			  <label>
			  <input name="alfapostal" type="text" size="3" value="<?php echo $row['alfapostal'];?>"/>
			  </label>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="left">Localidad</div></td>
			<td><div align="left">
				<select name="selectLocali" id="selectLocali" onchange="cambioProvincia(document.forms.modifJurisEmpresa.selectLocali[selectedIndex].value)">
				  <option value="0">Seleccione un valor </option>
				  <?php 
						
						$sqlLaca="select * from localidades where numpostal = $numpostal";
						$resLoca= mysql_query($sqlLaca,$db);
						while ($rowLoca=mysql_fetch_array($resLoca)) { 	
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
			<td><div align="left">Provincia</div></td>
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
			<td><div align="left">Delegacion</div></td>
			<td><div align="left">
				<select name="selectDelegacion" id="selectDelegacion">
				  <option value="0">Seleccione un valor </option>
				  <?php 
					$codidelega = $row['codidelega'];
					$sqldelega = "select * from delegaciones where codidelega = $codidelega";
					$resdelega = mysql_query($sqldelega,$db); 
					$rowdelega = mysql_fetch_array($resdelega); 
				  ?>
				  <option value="<?php echo $codidelega ?>" selected="selected"><?php echo $rowdelega['nombre'] ?></option>
				</select>
			</div></td>
		  </tr>
		  
		  <tr>
			<td><div align="left">Telefono 1 </div></td>
			<td>
			  <div align="left">
				<input name="ddn1" type="text" id="ddn1" value="<?php echo $row['ddn'];?>" size="5" />
				- 
				<input name="telefono1" type="text" id="telefono1" value="<?php echo $row['telefono'];?>" size="10" />
				</div>        </td>
		  </tr>
		  <tr>
			<td><div align="left">Contacto 1 </div></td>
			<td>
			  <div align="left">
				<input name="contacto1" type="text" id="contacto1" value="<?php echo $row['contactel'];?>" size="50" />
				</div>			</td>
		  </tr>
		  <tr>
			<td><div align="left">Email</div></td>
			<td><div align="left">
				<input name="email" type="text" id="email" value="<?php echo $row['email'];?>" size="50" />
			    <input style="background-color:#CCCCCC; visibility:hidden" readonly="readonly" name="disgdinero" type="text" id="disgdinero" value="<?php echo $row['disgdinero'];?>" size="4" />
			</div></td>
		  </tr>
		</table>
    <p>
      <label>
      <input type="submit" name="Submit" value="Guardar">
      </label>
    </p>
  </form>
  </p>
</div>
</body>
</html>
