<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionUsimra.php");

$sqlDelega = "select * from delegaciones";
$resDelega = mysql_query ( $sqlDelega, $db );
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalisador OSPIM :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>

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
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
	$("#codpos").mask("9999");
});

function fomularios() {
	tablaEmpresa = document.getElementById("empresa");
	tablaDelega = document.getElementById("delega");
	if (document.fisaclizador.tipo[0].checked) {
		tablaEmpresa.style.display='none';
		tablaDelega.style.display='';
	}
	if (document.fisaclizador.tipo[1].checked) {
		tablaDelega.style.display='none';
		tablaEmpresa.style.display='';
	}
}

function validar(formulario) {
	if (formulario.tipo[0].checked) {
		if (formulario.selectDelegacion.options[formulario.selectDelegacion.selectedIndex].value == 0) {
			alert("Debe seleccionar una Delegación");
			return false;
		}
		if (formulario.empresas.value == "") {
			alert("La Cantidad de Empresas a fiscalizar debe ser ingresada");
			return false;
		} else {
			if (!esEnteroPositivo(formulario.empresas.value) || formulario.empresas.value == 0) {
				alert("La Cantidad de Empresas a fiscalizar debe ser un numero entero postivo");
				return false;
			}
		}
		if (formulario.deuda.value == "") {
			alert("La Deuda Minima Nominal debe ser ingresada");
			return false;
		} else {
			if (!esEnteroPositivo(formulario.deuda.value)) {
				alert("La Deuda Minima Nominal debe ser un numero entero postivo");
				return false;
			}
		}
		if (formulario.solicDele.value == "") {
			alert("Debe ingresar el Solicitante");
			return false;
		}
	}
	if (formulario.tipo[1].checked) {
		if (formulario.cuit.value == "") {
			alert("Debe ingresar el C.U.I.T. de la empresa a fiscalizar");
			return false;
		} else {
			if (!verificaCuilCuit(formulario.cuit.value)) {
				alert("C.U.I.T. invalido");
				return false;
			}
		}
		if (formulario.origenRequerimento.value == 0) {
			alert("Debe seleccionar el Origen de Requerimiento");
			return false;
		}
		if (formulario.solicitante.value == "") {
			alert("Debe ingresar el Solicitante");
			return false;
		}
		if (formulario.motivo.value == "") {
			alert("Debe ingresar el Motivo");
			return false;
		}
	}
	$.blockUI({ message: "<h1>Filtrando Empresas Candidatas... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#B2A274">
	<form id="fisaclizador" name="fisaclizador" method="post"
		onsubmit="return validar(this)" action="filtroEmpresas.php"
		style="text-align: center">
		<input type="button" name="volver" value="Volver"
			onclick="location.href = '../menuFiscalizaciones.php'" />
		<p class="Estilo1">M&oacute;dulo Fiscalizador</p>
		<p>
			<strong>Seleccionar Tipo de Fiscalización</strong>
		</p>
		<div align="center">
			<table style="width: 150; text-align: center;">
				<tr>
					<td width="34"><input type="radio" name="tipo" value="delega"
						onchange="fomularios()" checked="checked" /></td>
					<td width="106"><div align="left">Por Delegaci&oacute;n</div></td>
				</tr>
				<tr>
					<td><input type="radio" name="tipo" value="empresa"
						onchange="fomularios()" /></td>
					<td><div align="left">Por Empresa</div></td>
				</tr>
			</table>

			<br />
  <?php if (isset($_GET ['err'])) { 
		  	$err = $_GET ['err']; 
				if ($err == 1) {
					print ("<div align='center' style='color:#FF0000'><b> CUIT INEXISTENTE </b></div>") ;
				}
				if ($err == 2) {
					print ("<div align='center' style='color:#FF0000'><b> EL FILTRO POR <br>(DELEGACION - CODIDGO POSTAL)<br>NO DIO NINGUN RESULTADO </b></div>") ;
				}
				if ($err == 3) {
					print ("<div align='center' style='color:#FF0000'><b> EL FILTRO POR <br>(DELEGACION - CODIDGO POSTAL - CANTIDAD PROMEDIO DE EMPLEDAOS)<br> NO DIO NINGUN RESULTADO </b></div>") ;
				}
				if ($err == 4) {
					print ("<div align='center' style='color:#FF0000'><b> EL FILTRO POR <br>(DELEGACION - CODIDGO POSTAL - CANTIDAD PROMEDIO DE EMPLEDAOS - DEUDA NOMINAL)<br> NO DIO NINGUN RESULTADO </b></div>") ;
				}
				if ($err == 5) {
					print ("<div align='center' style='color:#FF0000'><b> EL CUIT INGRESADO NO GENERO REQUERIMIENTO DE DUEDA </b></div>") ;
				}
				if ($err == 6) {
					print ("<div align='center' style='color:#FF0000'><b> EL CUIT INGRESADO ESTA DE BAJA </b></div>") ;
				}
		    } ?>
  	<table style="width: 630; text-align: center;" id="delega">
				<tr>
					<td height="50" colspan="2"><div align="center">
							<strong>Filtros Por Delegaci&oacute;n </strong>
						</div></td>
				</tr>
				<tr>
					<td width="312"><div align="right">Delegaci&oacute;n</div></td>
					<td width="308">
						<div align="left">
							<select name="selectDelegacion" id="selectDelegacion">
								<option value="0">Seleccione una Delegación</option>        
		        <?php
										while ( $rowDelega = mysql_fetch_array ( $resDelega ) ) {
											print ("<option value='" . $rowDelega ['codidelega'] . "'>" . $rowDelega ['nombre'] . "</option>") ;
										}
										?>
	          </select>
						</div>
					</td>
				</tr>
				<tr>
					<td><div align="right">C&oacute;digo Posta</div></td>
					<td><div align="left">
							<label> <input name="codpos" type="text" id="codpos" size="5" />
							</label>
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Cantidad de Empresas</div></td>
					<td><div align="left">
							<input name="empresas" type="text" id="empresas" size="5" />
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Deuda Nominal M&iacute;nima</div></td>
					<td><div align="left">
							<input name="deuda" type="text" id="deuda" size="10" />
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Solicitante</div></td>
					<td><div align="left">
							<input name="solicDele" id="solicDele" type="text" size="50" />
						</div></td>
				</tr>
			</table>
			<br />
			<table id="empresa"
				style="display: none; width: 630; text-align: center;">
				<tr>
					<td height="50" colspan="2"><div align="center">
							<strong>Ingrese C.U.I.T. a Fiscalizar </strong>
						</div></td>
				</tr>
				<tr>
					<td width="188"><div align="right">CUIT</div></td>
					<td width="432"><div align="left">
							<input name="cuit" id="cuit" type="text" size="12" />
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Origen de Requerimiento</div></td>
					<td><div align="left">
							<select name="origenRequerimento" id="origenRequerimento">
								<option value="0">Seleccione un origen</option>
								<option value="1">Fiscalizaci&oacute;n</option>
								<option value="2">Afiliaciones</option>
								<option value="3">Prestaci&oacute;n</option>
							</select>
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Solicitante</div></td>
					<td><div align="left">
							<input name="solicitante" id="solicitante" type="text" size="50" />
						</div></td>
				</tr>
				<tr>
					<td><div align="right">Motivo</div></td>
					<td><div align="left">
							<textarea name="motivo" id="motivo" cols="50" rows="4"></textarea>
						</div></td>
				</tr>
			</table>
			<br /> <label><input type="submit" name="Submit" value="Fiscalizar" /></label>
		</div>
	</form>
</body>
</html>
