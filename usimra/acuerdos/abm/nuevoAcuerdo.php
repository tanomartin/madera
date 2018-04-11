<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$cuit=$_GET['cuit'];
$sqlacu =  "select * from cabacuerdosusimra where cuit = $cuit order by nroacuerdo DESC";
$resulacu= mysql_query($sqlacu,$db);
$cant = mysql_num_rows($resulacu);
if ($cant == 0) {
	$nacuNuevo = 1;
} else {
	$rowacu = mysql_fetch_array($resulacu);
	$nacuNuevo = $rowacu['nroacuerdo'] + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html>
<head>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
	for (var i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
});

function mostrarPeriodos() {
	if (parseInt(document.forms.nuevoAcuerdo.mostrar.value) < 120) {	
		var n = parseInt(document.forms.nuevoAcuerdo.mostrar.value);
		var o = 0;
		var f = 0;
		for (var i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoAcuerdo.mostrar.value) + i;
			if (o < 120) {
				f = "fila"+ o;
				document.getElementById(f).style.display="table-row";
			}
		}
		document.forms.nuevoAcuerdo.mostrar.value = n + 12;
	} else { 
		alert("No se pueden superar los 120 períodos");
	}
}

function habilitarCarga() {
	var control = parseInt(document.forms.nuevoAcuerdo.cantCuotas.value);
	if (control >= 0 && !isNaN(control)) {
		document.getElementById("guardar").disabled = false;
	} else {
		document.getElementById("guardar").disabled = true;
	}
}

function cargarPor(){
	<?php 
		$sqlPor = "select * from parametros where id = 1";
		$resPor= mysql_query($sqlPor,$db); 
		$rowPor = mysql_fetch_array($resPor);
	?>
	if (document.forms.nuevoAcuerdo.gasAdmi[1].checked) {
		document.forms.nuevoAcuerdo.porcentaje.value = "<?php echo $rowPor['valorgastoadmin']?>";
	} else {
		document.forms.nuevoAcuerdo.porcentaje.value ="";
	}
}

function validoMes(id) {
	nombreMes = "mes" + id;
	valorMes = document.getElementById(nombreMes).value;
	var errorMes = "Error en la carga del mes. Valor "+valorMes+" es invalido";
	if (valorMes < 0 || valorMes > 12) {
		alert(errorMes);
		document.getElementById(nombreMes).value = "";
		document.getElementById(nombreMes).focus();
		return false;
	}
	return true;
}

function cargarLiqui(requerimiento) {
	var cargado = false;
	<?php 
		$sqlLiqui = "SELECT c.nrorequerimiento, c.liquidacionorigen FROM reqfiscalizusimra r , cabliquiusimra c where r.cuit = $cuit and r.nrorequerimiento = c.nrorequerimiento";
		$resLiqui= mysql_query($sqlLiqui,$db); 
		$canLiqui = mysql_num_rows($resLiqui); 
		if ($canLiqui != 0) {
			while ($rowLiqui = mysql_fetch_assoc($resLiqui)) { ?>
				if (requerimiento == <?php echo $rowLiqui['nrorequerimiento'] ?> ) {
					document.getElementById("nombreArcReq").value = "<?php echo $rowLiqui['liquidacionorigen'] ?>";
					cargado = true;
				}
	 <?php }
		}
	?>
	if (cargado == false) {
		document.getElementById("nombreArcReq").value = "";
	}
}

function validar(formulario) {
	if (!isNumberPositivo(formulario.nroacu.value)) {
		alert("Error en el numero de acuerdo");
		document.getElementById("nroacu").focus();
		return(false);
	}
	if (formulario.tipoAcuerdo.options[formulario.tipoAcuerdo.selectedIndex].value == 0) {
		alert("Error en el tipo de acuerdo");
		document.getElementById("tipoAcuerdo").focus();
		return(false);
	}
	if (!esFechaValida(formulario.fechaAcuerdo.value)) {
		alert("La fecha no es valida");
		document.getElementById("fechaAcuerdo").focus();
		return(false);
	} 
	if (!esEnteroPositivo(formulario.numeroActa.value)) {
			alert("Error Número de Acta");
			document.getElementById("numeroActa").focus();
			return(false);
	}
	if (!isNumberPositivo(formulario.monto.value)){
		alert("Error en el monto");
		document.getElementById("monto").focus();
		return(false);
	}
	
	var totalPeriodos = parseInt(formulario.mostrar.value) + 12;
	var errorMes = "Error en la carga del mes";
	var errorAnio = "Error en la carga del año";
	for (var i=0; i<=totalPeriodos; i++) {
		nombreMes = "mes" + i;
		nombreAnio = "anio" + i;
		valorMes = document.getElementById(nombreMes).value;
		valorAnio = document.getElementById(nombreAnio).value;
		if (valorMes == 0 && valorAnio != 0) {
			alert(errorMes);
			document.getElementById(nombreMes).focus();
			return (false);
		}
		if (valorMes != 0 && valorAnio == 0 ) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
		if (valorAnio < 1000 && valorMes!= 0) {
			alert(errorAnio);
			document.getElementById(nombreAnio).focus();
			return (false);
		}
	}
	$.blockUI({ message: "<h1>Modificando Acuerdo... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga de Acuerdos :.</title>
</head>

<body bgcolor="#B2A274">
	<form id="nuevoAcuerdo" name="nuevoAcuerdo" method="post" action="cargarCuotas.php" onsubmit="return validar(this)" style="visibility: visible">
		<p><input name="nrcuit" type="text" id="nrcuit" size="4" readonly="readonly" style="display: none" value="<?php echo $cuit ?>" /></p>
		<div align="center">
			<p><input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" /></p>
			<?php 	
				include($libPath."cabeceraEmpresaConsulta.php");
				include($libPath."cabeceraEmpresa.php");
			?>
			<h3>Módulo de Carga - Acuerdos Nuevos </h3>
			<p><b>ACUERDO Nº</b> <input name="nroacu" type="text" id="nroacu" size="2" readonly="readonly" value="<?php echo $nacuNuevo ?>" style="background-color: silver; text-align: center"/></p>
			<div align="center">
				<table width="954">
					<tr>
						<td><div align="left">Tipo</div></td>
						<td>
							<div align="left">
								<select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
									<option value='0' selected="selected">Seleccione un valor</option>
								  <?php $query="select * from tiposdeacuerdos";
										$result=mysql_query($query,$db);
										while ($rowtipos=mysql_fetch_array($result)) { ?>
											<option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
								  <?php } ?>
								</select>
							</div>
						</td>
						<td><div align="left">Fecha</div></td>
						<td><div align="left"><input name="fechaAcuerdo" type="text" id="fechaAcuerdo" size="12" /></div></td>
						<td><div align="left">Nº de Acta</div></td>
						<td colspan="2"><div align="left"><input id="numeroActa" type="text" name="numeroActa" /></div></td>
					</tr>
					<tr>
						<td><div align="left">Gestor</div></td>
						<td>
							<div align="left">
								<select name="gestor" id="gestor">
									<?php 
									$sqlGestor="select * from gestoresdeacuerdos order by apeynombre";
									$resGestor=mysql_query($sqlGestor,$db);
									while ($rowGestor=mysql_fetch_array($resGestor)) { ?>
										<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
									<?php } ?>
								</select>
							</div>
						</td>
						<td><div align="left">Inpector</div></td>
						<td>
							<div align="left">
								<select name="inpector" id="inspector">
									<option value='0'>No Especificado</option>
									<?php 
									$sqlInspec="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega order by apeynombre";
									$resInspec=mysql_query($sqlInspec,$db);
									while ($rowInspec=mysql_fetch_array($resInspec)) {
										if ($rowInspec['codigo'] == "35") {?>
											<option value="<?php echo $rowInspec['codigo'] ?>" selected="selected"><?php echo $rowInspec['apeynombre'] ?></option>
									<?php } else { ?>
											<option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
									<?php }
									 } ?>
								</select>
							</div>
						</td>
						<td><div align="left">Req. Origen</div></td>
						<td colspan="2">
							<div align="left">
								<select name="requerimiento" id="requerimiento" onchange="cargarLiqui(document.forms.nuevoAcuerdo.requerimiento[selectedIndex].value)">
									<option value='0'>Seleccione un valor</option>
									<?php 
									$sqlNroReq = "select * from reqfiscalizusimra where cuit = $cuit and requerimientoanulado = 0";
									$resNroReq = mysql_query($sqlNroReq,$db);
									while ($rowNroReq=mysql_fetch_array($resNroReq)) { ?>
										<option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
							  <?php } ?>
								</select>
							</div>
						</td>
					</tr>
					<tr>
						<td><div align="left">Liq. Origen</div></td>
						<td>
							<div align="left">
								<input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" style="background-color: silver;" />
							</div>
						</td>
						<td><div align="left">Monto</div></td>
						<td><div align="left"><input id="monto" type="text" name="monto" /></div></td>
						<td><div align="left">Gastos Admin.</div></td>
						<td>
							<div align="left">
								<input name="gasAdmi" type="radio" value="0" checked="checked" onclick="cargarPor()" /> NO<br /> 
								<input name="gasAdmi" type="radio" value="1" onclick="cargarPor()" /> SI
							</div>
						</td>
						<td>
							<div align="left">
								<input name="porcentaje" type="text" id="porcentaje" size="5" readonly="readonly" style="background-color: silver;" /> %
							</div>
						</td>
					</tr>
					<tr>
						<td><div align="left">Obervaciones</div></td>
						<td colspan="6"><textarea name="observaciones" cols="115" rows="5" id="observaciones"></textarea></td>
					</tr>
				</table>
			</div>
			<p><b>Carga Cuotas</b></p>
			<p>Nº Cuotas <input name="cantCuotas" type="text" id="cantCuotas" size="4" onblur="habilitarCarga()" /></p>
			<p><input type="submit" name="guardar" id="guardar" value="Cargar Cuotas" disabled="disabled" /></p>
			<p><b>Carga Períodos</b></p>
			<p><input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos" onclick="mostrarPeriodos()" /></p>
			<input name="mostrar" type="text" id="mostrar" size="4" value="12" readonly="readonly" style="display: none" />
			<table width="300" style="text-align: center">
				<tr>
					<th>Mes</th>
	         		<th>Año</th>
	          		<th>Concepto de deuda </th>
				</tr>
				<?php
				for ($i = 0 ; $i <= 120; $i ++) {
					if ($i < 12) { ?>
						<tr>
							<td><input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' size='2' onfocusout='validoMes("<?php echo $i ?>")' /></td>
							<td><input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' size='4'  /></td>
							<td>
								<select id='conDeuda<?php echo $i ?>' name='conDeuda<?php echo $i ?>'>
							<?php 	$sqlConceptos = "SELECT * FROM conceptosdeudas";
									$resConceptos =  mysql_query($sqlConceptos,$db);
									while($rowConceptos=mysql_fetch_array($resConceptos)) {?>
										<option value='<?php echo $rowConceptos['codigo'] ?>' ><?php echo $rowConceptos['descripcion'] ?></option>
							  <?php } ?>
								</select>
							</td>
						</tr>
				<?php } else { ?>
						<tr id='fila<?php echo  $i ?>' style="display: none">
							<td><input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' onfocusout='validoMes("<?php echo $i ?>")' /></td>
							<td><input name='anio<?php echo $i ?>' id='anio<?php echo $i ?>' type='text' size='4' /></td>
							<td>
								<select id='conDeuda<?php echo $i ?>' name='conDeuda<?php echo $i ?>'>
							<?php 	$sqlConceptos = "SELECT * FROM conceptosdeudas";
									$resConceptos =  mysql_query($sqlConceptos,$db);
									while($rowConceptos=mysql_fetch_array($resConceptos)) {?>
										<option value='<?php echo $rowConceptos['codigo'] ?>' ><?php echo $rowConceptos['descripcion'] ?></option>
							  <?php } ?>
								</select>
							</td>
						</tr>
				<?php }
			} ?>
			</table>
		</div>
	</form>
</body>
</html>
