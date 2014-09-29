<?php 
include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");
$cuit=$_GET['cuit'];
$delega=$_GET['coddel'];

$sql = "select j.*, p.descrip as provincia from jurisdiccion j, provincia p where cuit = $cuit and j.codidelega = $delega and j.codprovin = p.codprovin";
$result = mysql_query($sql,$db);
$row = mysql_fetch_array($result);

$numpostal=$_GET['numpostal'];
if ($numpostal == "") {
	$numpostal = $row['numpostal'];
}

$sqltitu = "select * from titulares where cuitempresa = $cuit and codidelega = $delega";
$restitu = mysql_query($sqltitu,$db); 
$cantitu = mysql_num_rows($restitu); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Jurisdicciones Empresa :.</title>
</head>
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
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
			$("#selectDelegacion").html("<option title ='Seleccione un valor' value='0'>Seleccione un valor</option>");
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
			$("#selectDelegacion").html("<option title ='Seleccione un valor' value='0'>Seleccione un valor</option>");
		});
	});
	
	$("#selectLocali").focusout(function(){
		var codigo = $("#codprovin").val();
		$.ajax({
			type: "POST",
			dataType: 'html',
			url: "buscaJurisdicciones.php?origen=<?php echo $origen ?>",
			data: {codigo:codigo},
		}).done(function(respuesta){
			$("#selectDelegacion").html(respuesta);
		});
	});	
		
});

function validar(formulario, deleAnterior, cantTitulares) {
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
	
	var delega = formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value;
	if (delega == 0) {
		alert("Debe elegir una Delegacion");
		return false;
	} else {
		if (delega != deleAnterior && cantTitulares != 0) {
			var cartel = "Atención: Se pasarán " + 
						cantTitulares + " titulares que se encuentran en la delegación " + deleAnterior + " a la nueva delegacion de la jurisdicción " + delega;  
			alert(cartel);
		}
	}
	formulario.Submit.disabled = true;
	return true;
}


</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
       <input type="reset" name="volver" value="Volver" onClick="location.href = 'empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 	
  <p><strong>Modificacion Jurisdicciones de Empresa</strong>
  <form name="modifJurisEmpresa" id="modifJurisEmpresa" method="post" onSubmit="return validar(this, <?php echo $delega ?>, <?php echo $cantitu ?>)" action="guardarModifJurisdiccion.php?origen=<?php echo $origen ?>&coddelega=<?php echo $delega ?>">	
	 	<table width="723" border="0">
		  <tr>
			<td width="167"><div align="right"><strong>C.U.I.T. </strong></div></td>
			<td width="540"><div align="left">
				<input style="background-color:#CCCCCC" name="cuit" type="text" id="cuit" size="12" value="<?php echo $row['cuit'];?>"  readonly="readonly"/>                
			  </div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Domicilio</strong></div></td>
			<td><div align="left">
			  <input name="domicilio" type="text" id="domicilio" value="<?php echo $row['domireal'];?>" size="90" />
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Codigo Postal</strong></div></td>
			<td><div align="left">
			  <label>
			  <input style="background-color:#CCCCCC" readonly="readonly" name="indpostal" id="indpostal" type="text" size="1" value="<?php echo $row['indpostal'];?>"/>
			  </label>
			  -
			  <input name="codPos" type="text" id="codPos" value="<?php echo $numpostal ?>" size="7" />
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
			<td><div align="right"><strong>Provincia</strong></div></td>
			<td><div align="left">
				<input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $row['provincia'];?>" />
				<input style="background-color:#CCCCCC; visibility:hidden" value="<?php echo $row['codprovin'] ?>" readonly="readonly" name="codprovin" id="codprovin" type="text" size="2"/>
			</div></td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Delegacion</strong></div></td>
			<td><div align="left">
				<select name="selectDelegacion" id="selectDelegacion">
				  <option value="0">Seleccione un valor </option>
				  <?php 
					$codidelega = $row['codidelega'];
					$codProvi = $row['codprovin'];
					$sqldelega = "select DISTINCT * from delegaciones where codidelega = $codidelega or codprovin = $codProvi";
					$resdelega = mysql_query($sqldelega,$db); 
					while ($rowdelega = mysql_fetch_array($resdelega)) { 
				  		if ($rowdelega['codidelega'] == $codidelega) { ?>
				 			 <option value="<?php echo $rowdelega['codidelega'] ?>" selected="selected"><?php echo $rowdelega['nombre'] ?></option>
				<?php   } else { ?> 
							<option value="<?php echo $rowdelega['codidelega'] ?>"><?php echo $rowdelega['nombre'] ?></option>
				<?php   }
					} ?>
				</select>
			</div></td>
		  </tr>
		  
		  <tr>
			<td><div align="right"><strong>Telefono 1 </strong></div></td>
			<td>
			  <div align="left">
				<input name="ddn1" type="text" id="ddn1" value="<?php echo $row['ddn'];?>" size="5" />
				- 
				<input name="telefono1" type="text" id="telefono1" value="<?php echo $row['telefono'];?>" size="10" />
			  </div>        </td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Contacto 1 </strong></div></td>
			<td>
			  <div align="left">
				<input name="contacto1" type="text" id="contacto1" value="<?php echo $row['contactel'];?>" size="50" />
			  </div>			</td>
		  </tr>
		  <tr>
			<td><div align="right"><strong>Email</strong></div></td>
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
