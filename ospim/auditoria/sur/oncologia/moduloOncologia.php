<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$err = 0;
$tipoafiliado = '';
$totalbeneficiarios = 0;
if(isset($_GET['err'])) {
	$err = $_GET['err'];
}
if(isset($_GET['tipAfi'])) {
	$tipoafiliado = $_GET['tipAfi']; 
	if(isset($_GET['nroAfi'])) {
		$nroafiliado = $_GET['nroAfi'];

		if(strcmp($tipoafiliado, 'A')==0) {
			$sqlBusquedaTitularActivo = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil FROM titulares WHERE nroafiliado = $nroafiliado";
			$resBusquedaTitularActivo = mysql_query($sqlBusquedaTitularActivo,$db);
			$sqlBusquedaTitularInactivo = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechabaja FROM titularesdebaja WHERE nroafiliado = $nroafiliado";
			$resBusquedaTitularInactivo = mysql_query($sqlBusquedaTitularInactivo,$db);
			$sqlBusquedaFamiliarActivo = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil FROM familiares f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.tipoparentesco = k.codparent";
			$resBusquedaFamiliarActivo= mysql_query($sqlBusquedaFamiliarActivo,$db);
			$sqlBusquedaFamiliarInactivo = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechabaja FROM familiaresdebaja f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.tipoparentesco = k.codparent";
			$resBusquedaFamiliarInactivo= mysql_query($sqlBusquedaFamiliarInactivo,$db);
		}

		if(strcmp($tipoafiliado, 'T')==0) {
			$sqlBusquedaTitularActivo = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil FROM titulares WHERE nroafiliado = $nroafiliado";
			$resBusquedaTitularActivo = mysql_query($sqlBusquedaTitularActivo,$db);
			$sqlBusquedaTitularInactivo = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, fechabaja FROM titularesdebaja WHERE nroafiliado = $nroafiliado";
			$resBusquedaTitularInactivo = mysql_query($sqlBusquedaTitularInactivo,$db);
		}
		
		if(strcmp($tipoafiliado, 'F')==0) {		
			if(isset($_GET['nroOrd'])) {
				$nroorden = $_GET['nroOrd'];
				$sqlBusquedaFamiliarActivo = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil FROM familiares f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
				$resBusquedaFamiliarActivo = mysql_query($sqlBusquedaFamiliarActivo,$db);
				$sqlBusquedaFamiliarInactivo = "SELECT f.nroafiliado, f.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechabaja FROM familiaresdebaja f, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.tipoparentesco = k.codparent";
				$resBusquedaFamiliarInactivo = mysql_query($sqlBusquedaFamiliarInactivo,$db);
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Oncologicos :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js" type="text/javascript"></script> 
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$('#ocultos').hide();
	var errores = $("#tipoerror").val();
	var tipoafi = $("#tipoafili").val();
	if(errores == 0) {
		$('#errores').hide();
	} else {
		$('#errores').show();
	};
	if(tipoafi == '') {
		$('#resultados').hide();
	} else {
		$('#resultados').show();
	};
	$("#buscar").on( "click", function() {
		$('#errores').hide();
		$('#resultados').hide();
	});
	$("#beneficiarios")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}}
		})
		.tablesorterPager({
			container: $("#paginador")
		});
	$("#busqueda")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra"],
			headers:{0:{sorter:false}, 7:{sorter:false}}
		});
});

function validar(formulario) {
	formulario.buscar.disabled = true;
	var elementos = document.forms.moduloPMI.elements;
	var longitud = document.forms.moduloPMI.length;
	var elementoradio = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "seleccion" && elementos[i].type == "radio" && elementos[i].checked == true) {
			elementoradio=i+1;
		}
	}
	if(elementoradio == 0) {
		alert("Debe seleccionar una opcion de busqueda");
		formulario.buscar.disabled = false;
		return false;
	} else {
		if (formulario.valor.value == "") {
			alert("Debe ingresar algun dato para la busqueda");
			document.getElementById("valor").focus();
			formulario.buscar.disabled = false;
			return false;
		} else {
			if(elementoradio == 3) {
				if(!verificaCuilCuit(formulario.valor.value)){
					alert("El C.U.I.L. es invalido");
					document.getElementById("valor").focus();
					formulario.buscar.disabled = false;
					return false;
				}
			}
		}
	}

	$.blockUI({ message: "<h1>Buscando Beneficiario. Aguarde por favor...</h1>" });

	return true;
};
</script>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>
<body bgcolor="#CCCCCC">
	<div align="center">
		<input class="nover" type="button" name="volver" value="Volver" onclick="location.href = '../menuSur.php'" /> 
	</div>
	<div id="ocultos" align="center">
		<input name="tipoerror" id="tipoerror" type="text" value="<?php echo $err ?>" size="1"/>
		<input name="tipoafili" id="tipoafili" type="text" value="<?php echo $tipoafiliado ?>" size="1"/>
	</div>
	<div align="center">
		<h1>Oncologicos</h1>
	</div>
	<div align="center">
		<table id="beneficiarios" class="tablesorter" style="font-size:14px; text-align:center">
			<thead>
				<tr>
					<th colspan="7">Beneficiarios</th>
				</tr>
				<tr>
					<th>Nro. Afiliado </th>
					<th>Tipo</th>
					<th>Apellido y Nombre</th>
					<th>Documento</th>
					<th>C.U.I.L.</th>
					<th>Estado</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$sqlTitularActivo = "SELECT p.nroafiliado, t.apellidoynombre, t.nrodocumento, t.cuil FROM oncologiabeneficiarios p, titulares t WHERE p.nroorden = 0 AND p.nroafiliado = t.nroafiliado ORDER BY p.nroafiliado";
		$resTitularActivo = mysql_query($sqlTitularActivo,$db);
		$totalbeneficiarios = $totalbeneficiarios + mysql_num_rows($resTitularActivo);
		while($rowTitularActivo = mysql_fetch_array($resTitularActivo)) { ?>
			<tr>
				<td><?php echo $rowTitularActivo['nroafiliado'] ?></td>
				<td>Titular</td>
				<td><?php echo $rowTitularActivo['apellidoynombre'] ?></td>
				<td><?php echo $rowTitularActivo['nrodocumento'] ?></td>
				<td><?php echo $rowTitularActivo['cuil'] ?></td>
				<td>Activo</td>
			</tr>
		<?php
		}
		$sqlTitularInactivo = "SELECT p.nroafiliado, t.apellidoynombre, t.nrodocumento, t.cuil, t.fechabaja FROM oncologiabeneficiarios p, titularesdebaja t WHERE p.nroorden = 0 AND p.nroafiliado = t.nroafiliado ORDER BY p.nroafiliado";
		$resTitularInactivo = mysql_query($sqlTitularInactivo,$db);
		$totalbeneficiarios = $totalbeneficiarios + mysql_num_rows($resTitularInactivo);
		while($rowTitularInactivo = mysql_fetch_array($resTitularInactivo)) { ?>
			<tr>
				<td><?php echo $rowTitularInactivo['nroafiliado'] ?></td>
				<td>Titular</td>
				<td><?php echo $rowTitularInactivo['apellidoynombre'] ?></td>
				<td><?php echo $rowTitularInactivo['nrodocumento'] ?></td>
				<td><?php echo $rowTitularInactivo['cuil'] ?></td>
				<td><?php echo "Inactivo ".invertirFecha($rowTitularInactivo['fechabaja']) ?></td>
			</tr>
		<?php
		}
		$sqlFamiliarActivo = "SELECT p.nroafiliado, p.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil FROM oncologiabeneficiarios p, familiares f, parentesco k WHERE p.nroorden != 0 AND p.nroafiliado = f.nroafiliado AND p.nroorden = f.nroorden AND f.tipoparentesco = k.codparent ORDER BY p.nroafiliado, p.nroorden";
		$resFamiliarActivo = mysql_query($sqlFamiliarActivo,$db);
		$totalbeneficiarios = $totalbeneficiarios + mysql_num_rows($resFamiliarActivo);
		while($rowFamiliarActivo = mysql_fetch_array($resFamiliarActivo)) { ?>
			<tr>
				<td><?php echo $rowFamiliarActivo['nroafiliado'] ?></td>
				<td><?php echo "Familiar ".$rowFamiliarActivo['descrip'] ?></td>
				<td><?php echo $rowFamiliarActivo['apellidoynombre'] ?></td>
				<td><?php echo $rowFamiliarActivo['nrodocumento'] ?></td>
				<td><?php echo $rowFamiliarActivo['cuil'] ?></td>
				<td>Activo</td>
			</tr>
		<?php
		}
		$sqlFamiliarInactivo = "SELECT p.nroafiliado, p.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, f.fechabaja FROM oncologiabeneficiarios p, familiaresdebaja f, parentesco k WHERE p.nroorden != 0 AND p.nroafiliado = f.nroafiliado AND p.nroorden = f.nroorden AND f.tipoparentesco = k.codparent ORDER BY p.nroafiliado, p.nroorden";
		$resFamiliarInactivo = mysql_query($sqlFamiliarInactivo,$db);
		$totalbeneficiarios = $totalbeneficiarios + mysql_num_rows($resFamiliarInactivo);
		while($rowFamiliarInactivo = mysql_fetch_array($resFamiliarInactivo)) { ?>
			<tr>
				<td><?php echo $rowFamiliarInactivo['nroafiliado'] ?></td>
				<td><?php echo "Familiar ".$rowFamiliarInactivo['descrip'] ?></td>
				<td><?php echo $rowFamiliarInactivo['apellidoynombre'] ?></td>
				<td><?php echo $rowFamiliarInactivo['nrodocumento'] ?></td>
				<td><?php echo $rowFamiliarInactivo['cuil'] ?></td>
				<td><?php echo "Inactivo ".invertirFecha($rowFamiliarInactivo['fechabaja']) ?></td>
			</tr>
		<?php
		}
		?>
			</tbody>
		</table>
	</div>
	<table class="nover" align="center" width="245" border="0">
		<tr>
			<td width="239">
				<div id="paginador" class="pager">
					<form>
						<p align="center">
						<img src="../../img/first.png" width="16" height="16" class="first"/> <img src="../../img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="../../img/next.png" width="16" height="16" class="next"/> <img src="../../img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="10">10 por pagina</option>
							<option value="20">20 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="<?php echo $totalbeneficiarios;?>">Todos</option>
							</select>
						</p>
					</form>	
				</div>
			</td>
		</tr>
	</table>
	<form id="moduloOncologia" name="moduloOncologia" method="post"  onsubmit="return validar(this)" action="buscaBeneficiario.php">
		<div align="center">
			<h2>Nuevo Ingreso</h2>
		</div>
		<div align="center">
			<table width="137" border="0">
			  <tr>
				<td width="23"><input name="seleccion" type="radio" value="nroafiliado" /></td>
				<td width="104"><div align="left">Nro Afiliado</div></td>
			  </tr>
			  <tr>
				<td><input name="seleccion" type="radio" value="nrodocumento" /></td>
				<td><div align="left">Nro Documento</div></td>
			  </tr>
			  <tr>
				<td><input name="seleccion" type="radio" value="cuil" /></td>
				<td><div align="left">CUIL</div></td>
			  </tr>
			</table>
			<p><input name="valor" id="valor" type="text" size="11" /></p>
		</div>
		<p align="center"><input class="nover" type="submit" id="buscar" name="buscar" value="Buscar" /></p>
	</form>
	<div id="errores">
	<?php 
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR NRO DE AFILIADO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR NRO DE DOCUMENTO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 3) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIO POR CUIL NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 4) {
			print("<div align='center' style='color:#FF0000'><b> EL BENEFICIARIO QUE INTENTA AGREGAR YA EXISTE EN LA LISTA DE ONCOLOGIA </b></div>");
		}
	?>
	</div>
	<div id="resultados" align="center">
			<h2>Resultados de la Busqueda</h2>
			<table id="busqueda" class="tablesorter" style="font-size:14px; text-align:center">
				<thead>
					<tr>
						<th colspan="7">Beneficiarios Encontrados</th>
					</tr>
					<tr>
						<th>Nro. Afiliado </th>
						<th>Tipo</th>
						<th>Apellido y Nombre</th>
						<th>Documento</th>
						<th>C.U.I.L.</th>
						<th>Estado</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
			<?php
				if(strcmp($tipoafiliado, 'A')==0 || strcmp($tipoafiliado, 'T')==0) {
					while($rowBusquedaTitularActivo = mysql_fetch_array($resBusquedaTitularActivo)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaTitularActivo['nroafiliado'] ?></td>
						<td>Titular</td>
						<td><?php echo $rowBusquedaTitularActivo['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaTitularActivo['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaTitularActivo['cuil'] ?></td>
						<td>Activo</td>
						<td><input class="nover" type="button" id="agregartituactivo" name="agregartituactivo" value="Agregar" onclick="location.href = 'agregarBeneficiario.php?nroAfi=<?php echo $rowBusquedaTitularActivo['nroafiliado']?>&nroOrd=0'"/></td>
					</tr>
			<?php
					}
					while($rowBusquedaTitularInactivo = mysql_fetch_array($resBusquedaTitularInactivo)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaTitularInactivo['nroafiliado'] ?></td>
						<td>Titular</td>
						<td><?php echo $rowBusquedaTitularInactivo['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaTitularInactivo['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaTitularInactivo['cuil'] ?></td>
						<td><?php echo "Inactivo ".invertirFecha($rowBusquedaTitularInactivo['fechabaja']) ?></td>
						<td><input class="nover" type="button" id="agregartituinactivo" name="agregartituinactivo" value="Agregar" onclick="location.href = 'agregarBeneficiario.php?nroAfi=<?php echo $rowBusquedaTitularInactivo['nroafiliado']?>&nroOrd=0'"/></td>
					</tr>
			<?php
					}
				}
				if(strcmp($tipoafiliado, 'A')==0 || strcmp($tipoafiliado, 'F')==0) {
					while($rowBusquedaFamiliarActivo = mysql_fetch_array($resBusquedaFamiliarActivo)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaFamiliarActivo['nroafiliado'] ?></td>
						<td><?php echo "Familiar ".$rowBusquedaFamiliarActivo['descrip'] ?></td>
						<td><?php echo $rowBusquedaFamiliarActivo['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaFamiliarActivo['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaFamiliarActivo['cuil'] ?></td>
						<td>Activo</td>
						<td><input class="nover" type="button" id="agregarfamiactivo" name="agregarfamiactivo" value="Agregar" onclick="location.href = 'agregarBeneficiario.php?nroAfi=<?php echo $rowBusquedaFamiliarActivo['nroafiliado']?>&nroOrd=<?php echo $rowBusquedaFamiliarActivo['nroorden']?>'"/></td>
					</tr>
			<?php
					}
					while($rowBusquedaFamiliarInactivo = mysql_fetch_array($resBusquedaFamiliarInactivo)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaFamiliarInactivo['nroafiliado'] ?></td>
						<td><?php echo "Familiar ".$rowBusquedaFamiliarInactivo['descrip'] ?></td>
						<td><?php echo $rowBusquedaFamiliarInactivo['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaFamiliarInactivo['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaFamiliarInactivo['cuil'] ?></td>
						<td><?php echo "Inactivo ".invertirFecha($rowBusquedaFamiliarInactivo['fechabaja']) ?></td>
						<td><input class="nover" type="button" id="agregarfamiinactivo" name="agregarfamiinactivo" value="Agregar" onclick="location.href = 'agregarBeneficiario.php?nroAfi=<?php echo $rowBusquedaFamiliarInactivo['nroafiliado']?>&nroOrd=<?php echo $rowBusquedaFamiliarInactivo['nroorden']?>'"/></td>
					</tr>
			<?php
					}
				}
			?>
				</tbody>
			</table>
	</div>
</body>
</html>