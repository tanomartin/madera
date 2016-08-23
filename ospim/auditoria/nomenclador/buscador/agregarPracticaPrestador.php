<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$idpractica = $_GET['idpractica'];

$sqlNombrePractica = "SELECT p.codigopractica, p.descripcion, p.nomenclador, n.nombre FROM practicas p, nomencladores n WHERE p.idpractica = $idpractica and p.nomenclador = n.id ";
$resNombrePractica = mysql_query($sqlNombrePractica,$db);
$rowNombrePractica = mysql_fetch_array($resNombrePractica);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Prestadores Practica :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
	$(function() {
		$("#prestadores")
		.tablesorter({
			theme: 'blue', 
			widthFixed: true, 
			widgets: ["zebra", "filter"], 
			widgetOptions : { 
				filter_cssFilter   : '',
				filter_childRows   : false,
				filter_hideFilters : false,
				filter_ignoreCase  : true,
				filter_searchDelay : 300,
				filter_startsWith  : false,
				filter_hideFilters : false,
			}
		})
	});
	
	function habilitarValores(seleccion) {
		var opcion = seleccion.options[seleccion.selectedIndex].value;
		var idname = "moduloConultorio";
		var modCons = document.getElementById(idname);
		idname = "moduloUrgencia";
		var modUrge = document.getElementById(idname);
		idname = "gHono";
		var gHono = document.getElementById(idname);
		idname = "gHonoEspe";
		var gHonoEspe = document.getElementById(idname);
		idname = "gHonoAyud";
		var gHonoAyud = document.getElementById(idname);
		idname = "gHonoAnes";
		var gHonoAnes = document.getElementById(idname);
		idname = "gGastos";
		var gGastos = document.getElementById(idname);
		gHono.value = '';
		gHonoEspe.value = '';
		gHonoAyud.value = '';
		gHonoAnes.value = '';
		gGastos.value = '';
		gHono.disabled = true;
		gHonoEspe.disabled = true;
		gHonoAyud.disabled = true;
		gHonoAnes.disabled = true;
		gGastos.disabled = true;
		modCons.value = '';
		modUrge.value = '';
		modCons.disabled = true;
		modUrge.disabled = true;
		if (opcion != 0) {
			if (opcion == 1) {
				modCons.disabled = false;
				modUrge.disabled = false;	
			} else {
				gHono.disabled = false;
				gHonoEspe.disabled = false;
				gHonoAyud.disabled = false;
				gHonoAnes.disabled = false;
				gGastos.disabled = false;	
			}
		} 
	}

	jQuery(function($){	
		$("#codigoPresta").change(function(){
			$("#nombrePresta").html('');
			$("#contrato").prop("disabled",true);
			$("#contrato").html('<select name="contrato" id="contrato" disabled="disabled"><option value="0">Seleccione Contrato</option></select>');
			$("#tipoCarga").prop("disabled",true);
			$("#categoria").prop("disabled",true);
			var codigopresta = $(this).val();
			var codigonomenclador = $("#nomenclador").val();
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "getPresta.php",
				data: {codigopresta:codigopresta, codigonomenclador:codigonomenclador},
			}).done(function(respuesta){
				if (respuesta != 0) {
					$("#nombrePresta").html(respuesta);
					$.ajax({
						type: "POST",
						dataType: 'html',
						url: "getContratos.php",
						data: {codigopresta:codigopresta},
					}).done(function(respuesta){
						if (respuesta != 0) {
							$("#contrato").prop("disabled",false);
							$("#contrato").html(respuesta);
							$("#tipoCarga").prop("disabled",false);			
							$.ajax({
								type: "POST",
								dataType: 'html',
								url: "getCategorias.php",
								data: {codigopresta:codigopresta},
							}).done(function(respuesta){
								$("#categoria").prop("disabled",false);
								$("#categoria").html(respuesta);
							});
						}
					});
				} else {
					$("#nombrePresta").html("<font color='red'><b>El prestador no existe o <br> El prestador no tiene asociado el nomenclador</b></font>");
				}
			});	
		});	
	});


	function validar(formulario) {
		if (formulario.codigoPresta.value == '') {
			alert("Debe ingresar el código de prestador");
			return false;
		}
		if (formulario.contrato.value == 0) {
			alert("Debe seleccionar un contrato");
			return false;
		}
		if (formulario.tipoCarga.value == 0) {
			alert("Debe seleccionar el tipo de prestacion a cargar");
			return false;
		} else {
			if (formulario.tipoCarga.value == 1) {
				var moduloConsu = document.getElementById("moduloConultorio");
				var moduloUrgen = document.getElementById("moduloUrgencia");
				if (!isNumberPositivo(moduloConsu.value) || !isNumberPositivo(moduloUrgen.value)) {
					alert("Los valores por modulo deben ser numeros positivos");
					moduloConsu.focus();
					return false;
				}
			} else {
				var hono = document.getElementById("gHono");
				var honoEspe = document.getElementById("gHonoEspe");
				var honoAyud = document.getElementById("gHonoAyud");
				var honoAnes = document.getElementById("gHonoAnes");
				var honoGastos = document.getElementById("gGastos");
				if (!isNumberPositivo(hono.value) || !isNumberPositivo(honoEspe.value) || 
					!isNumberPositivo(honoAyud.value) || !isNumberPositivo(honoAnes.value) || 
					!isNumberPositivo(honoGastos.value)) {
					alert("Los valores por galeno deben ser numeros positivos");
					hono.focus();
					return false;
				}
			}
		}
		formulario.Submit.disabled = true;
		return true;
	}
		
</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="insertarPracticaPrestador.php?idpractica=<?php echo $idpractica ?>">
  <div align="center">
	  	<div class="Estilo1">
	  		<p>Agregar Practica a Prestador</p>
	  		<p style="color: blue"><?php echo $rowNombrePractica['codigopractica']." - ".$rowNombrePractica['descripcion']." (".$rowNombrePractica['nombre'].")" ?></p>
	  		<input style="display: none" type="text" id="nomenclador" name="nomenclador" value="<?php echo $rowNombrePractica['nomenclador'] ?>" />
	  		<p>Datos de Ingreso</p>
	  		<p><?php if(isset($_GET['error'])) { print("<div style='color:#FF0000'><b> NO SE PUEDE COLOCAR EN EL MISMO CONTRATO DOS PRACTICAS<br> CON EL MISMO CODIGO DEL MISMO NOMENCLADOR</b></div>");} ?></p> 
	  	</div>
  		<table>
  			<tr>
  				<th>Codigo Prestador</th>
  				<td><input type="text" name="codigoPresta" id="codigoPresta" size="10"></input></td>
  			</tr>
  			<tr>
  				<td colspan="2" style="text-align: center"><span id="nombrePresta"></span></td>
  			</tr>
  			<tr>
  				<th>Contrato</th>
  				<td colspan="2"><select name="contrato" id="contrato" disabled="disabled">
  						<option value='0'>Seleccione Contrato</option>
  					</select>
  				</td>
  			</tr>
  			<tr>
  				<th>Categoria</th>
  				<td><select id='categoria' name='categoria' disabled='disabled' >
					</select>
				</td>
  			</tr>
  			<tr>
  				<th>Tipo Prestacion</th>
  				<td><select id='tipoCarga' name='tipoCarga' onchange='habilitarValores(this)' disabled='disabled' >
						<option value='0'>Tipo Carga</option>
						<option value='1'>Por Modulo</option>
						<option value='2'>Por Galeno</option>
					</select>
				</td>
  			</tr>
  		</table>
  		<table style="margin-top: 10px; width: 650px">
  			<tr>
  				<th></th>
  				<th>Modulo Consultorio</th>
				<th>Modulo Urgencia</th>
				<th></th>
			</tr>
			<tr align="center">
				<td></td>
				<td><input id='moduloConultorio' name='moduloConultorio' type='text' disabled='disabled' size='7'/></td>
				<td><input id='moduloUrgencia' name='moduloUrgencia' type='text' disabled='disabled' size='7'/></td>
				<td></td>
			</tr>
			<tr>
				<th>G. Honorarios</th>
				<th>G. H. Especialista</th>
				<th>G. H. Ayudante</th>
				<th>G. H. Anestesista</th>
				<th>G. Gastos</th>
  			</tr>
  			<tr align="center">
  				<td><input id='gHono' name='gHono' type='text' disabled='disabled' size='7'/></td>
				<td><input id='gHonoEspe' name='gHonoEspe' type='text' disabled='disabled' size='7'/></td>
				<td><input id='gHonoAyud' name='gHonoAyud' type='text' disabled='disabled' size='7'/></td>
				<td><input id='gHonoAnes' name='gHonoAnes' type='text' disabled='disabled' size='7'/></td>
				<td><input id='gGastos' name='gGastos' type='text' disabled='disabled' size='7'/></td>
  			</tr>
  		</table>
  	<p><input type="submit" name="Submit" value="Agregar Practica" /></p>
  </div>
</form>
</body>
</html>
