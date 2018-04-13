<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

if (isset($_GET ['cuit'])) {
	$cuit = $_GET ['cuit'];
} else {
	$cuit = $_POST ['cuit'];
}

$sqlacu = "select * from cabacuerdosospim where cuit = $cuit order by nroacuerdo DESC";
$resulacu = mysql_query ( $sqlacu, $db );
$cant = mysql_num_rows ( $resulacu );
if ($cant == 0) {
	$nacuNuevo = 1;
} else {
	$rowacu = mysql_fetch_array ( $resulacu );
	$nacuNuevo = $rowacu ['nroacuerdo'] + 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga de Acuerdos :.</title>
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
	$resPor = mysql_query ( $sqlPor, $db );
	$rowPor = mysql_fetch_array ( $resPor );
	?>
	if (document.forms.nuevoAcuerdo.gasAdmi[1].checked) {
		document.forms.nuevoAcuerdo.porcentaje.value = "<?php echo $rowPor['valorgastoadmin']?>";
	} else {
		document.forms.nuevoAcuerdo.porcentaje.value ="";
	}
}

function validoMes(id) {
	var nombreMes = "mes" + id;
	var valorMes = document.getElementById(nombreMes).value;
	var errorMes = "Error en la carga del mes. Mes " + valorMes + " no es posible";
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
	$sqlLiqui = "SELECT c.nrorequerimiento, c.liquidacionorigen FROM reqfiscalizospim r , cabliquiospim c where r.cuit = $cuit and r.nrorequerimiento = c.nrorequerimiento;";
	$resLiqui = mysql_query ( $sqlLiqui, $db );
	$canLiqui = mysql_num_rows ( $resLiqui );
	if ($canLiqui != 0) {
		while ( $rowLiqui = mysql_fetch_assoc ( $resLiqui ) ) {
			?>
				if (requerimiento == <?php echo $rowLiqui['nrorequerimiento'] ?> ) {
					document.getElementById("nombreArcReq").value = "<?php echo $rowLiqui['liquidacionorigen'] ?>";
					cargado = true;
				}
	 <?php }
	} ?>
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
	if (!esEnteroPositivo(formulario.numeroActa.value) || formulario.numeroActa.value == "") {
		alert("Error Número de Acta");
		document.getElementById("numeroActa").focus();
		return(false);
	}
	if (!isNumberPositivo(formulario.monto.value) || formulario.monto.value == ""){
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
	$.blockUI({ message: "<h1>Preparando datos... <br>Esto puede tardar unos segundo.<br> Aguarde por favor</h1>" });
	return true
	
}
</script>
</head>
<body bgcolor="#CCCCCC">
<form id="nuevoAcuerdo" name="nuevoAcuerdo" method="post" action="cargarCuotas.php" onsubmit="return validar(this)" style="visibility: visible">
	<div align="center">
		<input name="nrcuit" type="text" id="nrcuit" size="4" readonly="readonly" style="display:none" value="<?php echo $cuit ?>" />
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'acuerdos.php?cuit=<?php echo $cuit?>'" />
   			<?php	
   				include ($libPath."cabeceraEmpresaConsulta.php");
   				include ($libPath."cabeceraEmpresa.php"); 
   			?>
  	 	<p><b>Módulo de Carga - Acuerdos Nuevos </b></p>
		<p><b>ACUERDO NUMERO</b> <input name="nroacu" type="text" id="nroacu" size="4" readonly="readonly" style="background-color: silver;text-align: center;" value="<?php echo $nacuNuevo ?>" /></p>

		<table width="954" border="0">
			<tr>
				<td><div align="left">Tipo</div></td>
				<td>
					<select name="tipoAcuerdo" size="1" id="tipoAcuerdo">
						<option value='0' selected="selected">Seleccione un valor</option>
		         		  <?php $query = "select * from tiposdeacuerdos";
								$result = mysql_query ( $query, $db );
							 	while ( $rowtipos = mysql_fetch_array ( $result ) ) { ?>
		         				 	<option value="<?php echo $rowtipos['codigo'] ?>"><?php echo $rowtipos['descripcion']  ?></option>
		         		  <?php } ?>
	         		</select>
				</td>
				<td>Fecha</td>
				<td><input id="fechaAcuerdo" type="text" name="fechaAcuerdo" size="10" /></td>
				<td><div align="left">Nº de Acta</div></td>
				<td colspan="2"><input id="numeroActa" type="text" name="numeroActa" /></td>
			</tr>
			<tr>
				<td>Gestor</td>
				<td>
					<select name="gestor" id="gestor">
                	  <?php $sqlGestor = "select * from gestoresdeacuerdos";
							$resGestor = mysql_query ( $sqlGestor, $db );
							while ( $rowGestor = mysql_fetch_array ( $resGestor ) ) { ?>
                 		 		<option value="<?php echo $rowGestor['codigo'] ?>"><?php echo $rowGestor['apeynombre'] ?></option>
               		  <?php } ?>
              		</select>
				</td>
				<td>Inpector</td>
				<td>
					<select name="inpector" id="inspector">
						<option value='0'>No Especificado</option>
	              <?php $sqlInspec = "select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
						$resInspec = mysql_query ( $sqlInspec, $db );
						while ( $rowInspec = mysql_fetch_array ( $resInspec ) ) {
							if ($rowInspec ['codigo'] == "35") { ?>
								<option value="<?php echo $rowInspec['codigo'] ?>" selected="selected"><?php echo $rowInspec['apeynombre'] ?></option>
	              	  <?php } else { ?>	
				  				<option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
				  	  <?php }
					    } ?>
	          		</select>
				</td>
				<td>Req. Origen</td>
				<td colspan="2">
					<select name="requerimiento" id="requerimiento" onchange="cargarLiqui(document.forms.nuevoAcuerdo.requerimiento[selectedIndex].value)">
						<option value='0'>Seleccione un valor</option>
	              <?php $sqlNroReq = "select * from reqfiscalizospim where cuit = $cuit and requerimientoanulado = 0";
						$resNroReq = mysql_query ( $sqlNroReq, $db );
						while ( $rowNroReq = mysql_fetch_array ( $resNroReq ) ) { ?>
		           			<option value="<?php echo $rowNroReq['nrorequerimiento'] ?>"><?php echo $rowNroReq['nrorequerimiento'] ?></option>
	              <?php } ?>
	          		</select>
				</td>
			</tr>
			<tr>
				<td>Liq. Origen</td>
				<td><input name="nombreArcReq" type="text" id="nombreArcReq" size="40" readonly="readonly" style="background-color: silver;" /></td>
				<td>Monto</td>
				<td><input id="monto" type="text" name="monto" /></td>
				<td>Gastos Admin.</td>
				<td>
					<input name="gasAdmi" type="radio" value='0' checked="checked" onclick="cargarPor()" /> NO<br /> 
					<input name="gasAdmi" type="radio" value='1' onclick="cargarPor()" /> SI
				</td>
				<td>
					<input name="porcentaje" type="text" id="porcentaje" size="4" readonly="readonly" style="background-color: silver;" /> %
				</td>
			</tr>
			<tr>
				<td>Obervaciones</td>
				<td colspan="6"><textarea name="observaciones" cols="115" rows="5" id="observaciones"></textarea></td>
			</tr>
		</table>
		<p><b>Carga Cuotas</b></p>
   		<p>Nº Cuotas <input name="cantCuotas" type="text" id="cantCuotas" size="4" onblur="habilitarCarga()" /></p>
   		<p><input type="submit" name="guardar" id="guardar" value="Cargar Cuotas" disabled="disabled" /></p>
	    <p><b>Carga Períodos</b></p>
	    <p><input name="masPeridos" type="button" id="masPeridos" value="Mas Periodos" onclick="mostrarPeriodos()" /></p>
	    <input name="mostrar" type="text" id="mostrar" size="4" value="12" readonly="readonly" style="display: none" />
		<table>
			<tr style="text-align: center">
				<th>Mes</th>
				<th>Año</th>
				<th>Concepto de deuda</th>
			</tr>
            <?php for($i = 0; $i < 120; $i ++) {
					if ($i < 12) { ?>
						<tr id='fila<?php echo $i ?>'>
							<td><input name='mes<?php echo $i ?>' type='text' id='mes<?php echo $i ?>' size='2' onfocusout='validoMes("<?php echo $i ?>")' /></td>
							<td><input name='anio<?php echo $i ?>' type='text' id='anio<?php echo $i ?>' size='4' /></td>
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
						<tr id='fila<?php echo $i ?>' style="display: none">
							<td><input name='mes<?php echo $i ?>' id='mes<?php echo $i ?>' type='text' size='2' onfocusout='validoMes("<?php echo $i ?>")'  /></td>
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
