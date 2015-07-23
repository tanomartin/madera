<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

$cuit=$_GET['cuit'];
if ($cuit=="") {
	$cuit=$_POST['cuit'];
}

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
<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#fechaAcuerdo").mask("99-99-9999");
	for (var i=0; i<= 120; i++) {
		$("#mes"+i).mask("99");
		$("#anio"+i).mask("9999");
	}
});

function mostrarPeriodos() {
	if (parseInt(document.forms.nuevoAcuerdo.mostrar.value) < 120) 
	{
		var n = parseInt(document.forms.nuevoAcuerdo.mostrar.value);
		document.forms.nuevoAcuerdo.mostrar.value = n;
		var o = 0;
		var m = 0;
		var a = 0;
		var s = 0;
		for (var i=0; i<=12; i++){
			o = parseInt(document.forms.nuevoAcuerdo.mostrar.value) + i;
			m = "mes" + o;
			a = "anio" + o;
			s = "conDeuda" + o;
			document.getElementById(m).style.visibility="visible";
			document.getElementById(a).style.visibility="visible";
			document.getElementById(s).style.visibility="visible";
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
	var errorMes = "Error en la carga del mes";
	nombreMes = "mes" + id;
	valorMes = document.getElementById(nombreMes).value;
	if (valorMes < 0 || valorMes > 12) {
		alert(errorMes);
		document.getElementById(nombreMes).focus();
		return false;
	}
	return true;
}

function validoAnio(id){
	var errorAnio = "Error en la carga del año";
	nombreAnio = "anio" + id;
	valorAnio = document.getElementById(nombreAnio).value;
	if (valorAnio < 0) {
		alert(errorAnio);
		document.getElementById(nombreAnio).focus();
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
	return true;
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga de Acuerdos :.</title>
</head>

<body bgcolor="#B2A274">
	<form id="nuevoAcuerdo" name="nuevoAcuerdo" method="post" action="cargarCuotas.php" onsubmit="return validar(this)" style="visibility: visible">
		<input name="nrcuit" type="text" id="nrcuit" size="4" readonly="readonly" style="visibility: hidden; position: absolute; z-index: 1" value="<?php echo $cuit ?>" />
		<div align="center">
			<input type="button" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" />
			<?php 	
				include($libPath."cabeceraEmpresaConsulta.php");
				include($libPath."cabeceraEmpresa.php");
			?>
			<p><strong>M&oacute;dulo de Carga - Acuerdos Nuevos </strong></p>
			<p><strong>ACUERDO NUMERO</strong> <input name="nroacu" type="text" id="nroacu" size="4" readonly="readonly" value="<?php echo $nacuNuevo ?>" /></p>
			<div align="center">
				<table width="954" border="0">
					<tr>
						<td width="111" valign="bottom"><div align="left">Tipo de Acuerdo</div>
						</td>
						<td width="240" valign="bottom">

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
						<td width="106" valign="bottom"><div align="left">Fecha Acuerdo</div>
						</td>
						<td width="144" valign="bottom">
							<div align="left">
								<input name="fechaAcuerdo" type="text" id="fechaAcuerdo"
									size="12" />
							</div>
						</td>
						<td width="158" valign="bottom"><div align="left">N&uacute;mero de
								Acta</div></td>
						<td colspan="2" valign="bottom">
							<div align="left">
								<input id="numeroActa" type="text" name="numeroActa" />
							</div>
						</td>
					</tr>
					<tr>
						<td valign="bottom"><div align="left">Gestor</div></td>
						<td valign="bottom">
							<div align="left">
								<select name="gestor" id="gestor">
									<?php 
									$sqlGestor="select * from gestoresdeacuerdos";
									$resGestor=mysql_query($sqlGestor,$db);
									while ($rowGestor=mysql_fetch_array($resGestor)) { ?>
										<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
									<?php } ?>
								</select>
							</div>
						</td>
						<td valign="bottom"><div align="left">Inpector</div></td>
						<td valign="bottom">

							<div align="left">
								<select name="inpector" id="inspector">
									<option value='0'>No Especificado</option>
									<?php 
									$sqlInspec="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
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
						<td valign="bottom"><div align="left">Requerimiento de Origen</div>
						</td>
						<td colspan="2" valign="bottom">
							<div align="left">
								<select name="requerimiento" id="requerimiento"
									onchange="cargarLiqui(document.forms.nuevoAcuerdo.requerimiento[selectedIndex].value)">
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
						<td valign="bottom">
							<div align="left">Liquidacion Origen</div>
						</td>
						<td valign="bottom">
							<div align="left">
								<input name="nombreArcReq" type="text" id="nombreArcReq"
									size="40" readonly="readonly" style="background-color: silver;" />
							</div>
						</td>
						<td valign="bottom"><div align="left">Monto Acuerdo</div></td>
						<td valign="bottom">
							<div align="left">
								<input id="monto" type="text" name="monto" />
							</div>
						</td>
						<td valign="bottom"><div align="left">Gastos Administrativos</div>
						</td>
						<td width="49" valign="bottom">
							<div align="left">
								<input name="gasAdmi" type="radio" value="0" checked="checked" onblur="cargarPor()" /> NO<br /> 
								<input name="gasAdmi" type="radio" value="1" onblur="cargarPor()" /> SI
							</div>
						</td>
						<td width="100" valign="bottom">
							<div align="left">
								<input name="porcentaje" type="text" id="porcentaje" size="5" readonly="readonly" style="background-color: silver;" /> %
							</div>
						</td>
					</tr>
					<tr>
						<td height="87" valign="bottom">
							<div align="left">Obervaciones</div>
						</td>
						<td colspan="6" valign="bottom">
							<textarea name="observaciones" cols="110" rows="5" id="observaciones"></textarea>
						</td>
					</tr>
				</table>
			</div>
			<p>
				<b>Carga Períodos y Cuotas </b>
			</p>
			<p>
				Cantidad de Cuotas <input name="cantCuotas" type="text" id="cantCuotas" size="4" onblur="habilitarCarga()" />
			</p>
			<p>
				<input type="submit" name="guardar" id="guardar" value="Cargar Cuotas" disabled="disabled" />
			</p>
			<table width="446" border="0">
				<tr>
					<td width="440"><div align="center">
						<input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos" onclick="mostrarPeriodos()" />
					</div></td>
				</tr>
			</table>
			<input name="mostrar" type="text" id="mostrar" size="4" value="12"
				readonly="readonly" style="visibility: hidden" />
			<table style="width: 531; height: 32" border="0">
				<tr>
					<td width="134" height="11">
						<div align="center">Mes</div>
					</td>
					<td width="121"><div align="center">A&ntilde;o</div></td>
					<td width="262"><div align="center">Concepto de deuda</div></td>
				</tr>
				<?php
				for ($i = 0 ; $i <= 120; $i ++) {
				if ($i < 12) { ?>
				<tr>
					<td height='11'><div align='center'>
						<input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' size='2' onblur='validoMes("<?php echo $i ?>")' />
					</div></td>
					<td height='11'><div align='center'>
						<input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' size='4' onblur='validoAnio("<?php echo $i ?>")' />
						</div></td>
					<td height='11'><div align='center'>
							<select id='conDeuda<?php echo $i ?>'
								name='conDeuda<?php echo $i ?>'>
								<option selected="selected" value='A'>Período no Pagado</option>
								<option value='B'>Pagado Fuera de Término</option>
								<option value='C'>Aporte y Contribución 3.1%</option>
								<option value='D'>Aporte 1.5%</option>
								<option value='E'>Contribución 1.6%</option>
								<option value='F'>No Remunerativo</option>
								<option value='G'>Contribución 0.6%</option>
								<option value='H'>Aporte y Contribución 2.5%</option>
							</select>
						</div></td>
				</tr>
				<?php } else { ?>
				<tr>
					<td height='11'><div align='center'>
						<input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' style='visibility: hidden' onblur='validoMes("<?php echo $i ?>")' />
					</div></td>
					<td height='11'><div align='center'>
						<input name='anio<?php echo $i ?>' id='anio<?php echo $i ?>' type='text' size='4' style='visibility: hidden' onblur='validoAnio("<?php echo $i ?>")' />
					</div></td>
					<td height='11'><div align='center'>
							<select id='conDeuda<?php echo $i ?>'
								name='conDeuda<?php echo $i ?>' style='visibility: hidden'>
								<option selected="selected" value='A'>Período no Pagado</option>
								<option value='B'>Pagado Fuera de Término</option>
								<option value='C'>Aporte y Contribución 3.1%</option>
								<option value='D'>Aporte 1.5%</option>
								<option value='E'>Contribución 1.6%</option>
								<option value='F'>No Remunerativo</option>
								<option value='G'>Contribución 0.6%</option>
								<option value='H'>Aporte y Contribución 2.5%</option>
							</select>
						</div></td>
				</tr>
				<?php }
			} ?>
			</table>
		</div>
	</form>
</body>
</html>
