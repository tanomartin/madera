<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$cuit = $_POST['cuit'];
$nroorden = $_POST['nroorden'];
$sqlCabecera = $_POST['insertCabeceraJui'];
$listadoPeriodosSerializado= $_POST['insertPeriodosJui'];
$sqlUpdateAcu = $_POST['updateCabeceraAcu'];
$listadoPeriodosAcuSerializado = $_POST['deletePeriodosAcu'];


$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$row = mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query($sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query($sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#fechaInicio").mask("99-99-9999");
	$("#fechafinal").mask("99-99-9999");
});

function cargoSecretarias(juzgado) {
	document.forms.nuevoTramiteJudicial.secretaria.length = 0;
	var o = ''; 
	document.forms.nuevoTramiteJudicial.juzgado.disabled=true;
	o = document.createElement("OPTION");
	o.text = 'Seleccione Secretaria';
	o.value = 0;
	document.forms.nuevoTramiteJudicial.secretaria.options.add (o);
	<?php	
		$sqlSecretarias = "select * from secretarias";
		$resSecretarias = mysql_query($sqlSecretarias,$db); 
		while ($rowSecretarias = mysql_fetch_array($resSecretarias)) { ?> 
			if (juzgado == <?php echo $rowSecretarias["codigojuzgado"]; ?>) {
				o = document.createElement("OPTION");
				o.text = '<?php echo $rowSecretarias["denominacion"]; ?>';
				o.value = <?php echo $rowSecretarias["codigosecretaria"]; ?>;
				document.forms.nuevoTramiteJudicial.secretaria.options.add(o);
			}
<?php } ?> 
	document.forms.nuevoTramiteJudicial.juzgado.disabled=false;
}

function validar(formulario) {
	if (!esFechaValida(formulario.fechaInicio.value)) {
		alert("Fecha de Inicio invalida");
		return false;
	}
	if (formulario.juzgado.value == 0) {
		alert("Debe elegir un Juzgado");
		return false;
	}
	if (formulario.secretaria.value == 0) {
		alert("Debe elegir una Secretaria");
		return false;
	}
	if(!esEnteroPositivo(formulario.nroexpe.value) || formulario.nroexpe.value == "" || formulario.nroexpe.value == 0) {
		alert("Error en el Nro. de Expediente");
		return false;
	}
	if (formulario.estado.value == 0) {
		alert("Debe elegir una Estado Procesal");
		return false;
	}
	if ((formulario.fechafinal.value != "" && formulario.montocobrado.value == 0) || (formulario.fechafinal.value == "" && formulario.montocobrado.value != "")) {
		alert("Debe completar toda la información del cierre del tramite");
		return false;
	}
	
	if (formulario.fechafinal.value != "") {
		if (!esFechaValida(formulario.fechafinal.value)) {
			alert("Fecha de Finalizacion invalida");
			return false;
		}
	}
	if(!isNumberPositivo(formulario.montocobrado.value)) {
		alert("El monto cobrado debe ser un número postivo");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Juicio - Tramite Judicial :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form id="nuevoTramiteJudicial" name="nuevoTramiteJudicial" method="post" action="guardarJuicioConTramite.php" onsubmit="return validar(this)" >
  <div align="center">
   	<input type="reset" name="volver" value="Volver" onclick="location.href = 'nuevoJuicio.php?cuit=<?php echo $cuit?>'"/>
    <input name="cuit" type="hidden" value="<?php echo $cuit ?>"/>
	<input name="nroorden" type="hidden" value="<?php echo $nroorden ?>"/>
   	<input name="insertCabeceraJui" type="hidden" value="<?php echo $sqlCabecera ?>"/>
   	<input name="insertPeriodosJui" type="hidden" value="<?php echo $listadoPeriodosSerializado ?>"/>
   	<input name="updateCabeceraAcu" type="hidden" value="<?php echo $sqlUpdateAcu ?>"/>
   	<input name="deletePeriodosAcu" type="hidden" value="<?php echo $listadoPeriodosAcuSerializado ?>"/>
    <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/madera/lib/cabeceraEmpresa.php"); 
	?>
  </div>
  <p align="center"><strong>M&oacute;dulo de Carga - Tramite Judicial </strong></p>
   	<p align="center"><strong>NRO ORDEN </strong>
      <input name="nroorden" type="text" id="nroorden" size="5" readonly="readonly" value="<?php echo $nroorden ?>" style="background-color:#CCCCCC; text-align:center" />
  </p>
  	<table align="center">
		<tr>
			<td>Fecha Inicio</td>
			<td><input id="fechaInicio" type="text" size="12" name="fechaInicio"/></td>
			<td>Autocaso</td>
			<td><textarea name="autocaso" id="autocaso" cols="50" rows="2"></textarea></td>
		</tr>
		<tr>
			<td>Juzgado</td>
			<td colspan="4">
			<select name="juzgado" id="juzgado" onchange="cargoSecretarias(document.forms.nuevoTramiteJudicial.juzgado[selectedIndex].value)">
				<option value='0' selected="selected">Seleccione Juzgado</option>
				<?php 
					$sqlJuzgado ="select * from juzgados";
					$resJuzgado = mysql_query($sqlJuzgado,$db);
					while ($rowJuzgado = mysql_fetch_assoc($resJuzgado)) { ?>
					  <option value="<?php echo $rowJuzgado['codigojuzgado'] ?>"><?php echo $rowJuzgado['denominacion'] ?></option>
			  <?php } ?>
			</select>			</td>
		</tr>
		<tr>
		  <td>Secretaria</td>
		  <td><select name="secretaria" id="secretaria">
            <option value="0" selected="selected">Seleccione Secretaria</option>
          </select></td>
	      <td>Nro. Expediente</td>
	      <td><input id="nroexpe" type="text" name="nroexpe"/></td>
	  </tr>
		<tr>
			<td>Estado Procesal</td>
			<td>
			<select name="estado" id="estado">
				<option value="0" selected="selected">Seleccione Estado Procesal</option>
				<?php 
					$sqlEstados ="select * from estadosprocesales";
					$resEstados = mysql_query($sqlEstados,$db);
					while ($rowEstados = mysql_fetch_assoc($resEstados)) { ?>
					  	<option value="<?php echo $rowEstados['codigo'] ?>"><?php echo $rowEstados['descripcion'] ?></option>
			  <?php } ?>
			</select>			</td>
			   <td>Bienes Embargados</td>
			   <td><textarea name="bienes" id="bienes" cols="50" rows="2"></textarea></td>
	    </tr>
		<tr>
		  <td colspan="4"><div align="center"><strong>FINALIZACION DE TRAMITE JUDCIAL</strong></div></td>
	  </tr>
		<tr>
			<td>Fecha Finalizaci&oacute;n </td>
			<td><input name="fechafinal" type="text" id="fechafinal" size="12"/></td>
			<td>Monto Cobrado</td>
			<td><input id="montocobrado" type="text" name="montocobrado"/></td>
		</tr>
</table>

    <div align="center">
      <p><input type="submit" name="Submit" value="Guardar" /></p>
    </div>
</form>
</body>
</html>
