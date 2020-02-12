<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");
$err = 0;
$tipoafiliado = '';
$totalpmi = 0;
if(isset($_GET['err'])) {
	$err = $_GET['err'];
}
if(isset($_GET['tipAfi'])) {
	$tipoafiliado = $_GET['tipAfi']; 
	if(isset($_GET['nroAfi'])) {
		$nroafiliado = $_GET['nroAfi'];

		if(strcmp($tipoafiliado, 'A')==0) {
			$sqlBusquedaTitular = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, codidelega FROM titulares WHERE nroafiliado = $nroafiliado AND sexo = 'F'";
			$resBusquedaTitular = mysql_query($sqlBusquedaTitular,$db);
			$sqlBusquedaFamiliar = "SELECT f.nroafiliado, f.nroorden, f.tipoparentesco, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, t.codidelega FROM familiares f, titulares t, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.sexo = 'F' AND f.nroafiliado = t.nroafiliado AND f.tipoparentesco = k.codparent";
			$resBusquedaFamiliar = mysql_query($sqlBusquedaFamiliar,$db);
		}

		if(strcmp($tipoafiliado, 'T')==0) {
			$sqlBusquedaTitular = "SELECT nroafiliado, apellidoynombre, nrodocumento, cuil, codidelega FROM titulares WHERE nroafiliado = $nroafiliado AND sexo = 'F'";
			$resBusquedaTitular = mysql_query($sqlBusquedaTitular,$db);
		}
		
		if(strcmp($tipoafiliado, 'F')==0) {		
			if(isset($_GET['nroOrd'])) {
				$nroorden = $_GET['nroOrd'];
				$sqlBusquedaFamiliar = "SELECT f.nroafiliado, f.nroorden, f.tipoparentesco, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, t.codidelega FROM familiares f, titulares t, parentesco k WHERE f.nroafiliado = $nroafiliado AND f.nroorden = $nroorden AND f.sexo = 'F' AND f.nroafiliado = t.nroafiliado AND f.tipoparentesco = k.codparent";
				$resBusquedaFamiliar = mysql_query($sqlBusquedaFamiliar,$db);
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Plan Materno Infantil :.</title>
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
	$("#beneficiarias")
		.tablesorter({
			theme: 'blue',
			widthFixed: true, 
			widgets: ["zebra", "filter"],
			headers:{0:{sorter:false},
					 1:{filter:false},
					 5:{filter:false},
					 6:{filter:false},
					 7:{filter:false},
					 8:{filter:false},
					 9:{filter:false},
					 10:{sorter:false}}
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

	$.blockUI({ message: "<h1>Buscando Beneficiaria. Aguarde por favor...</h1>" });

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
		<input class="nover" type="button" name="volver" value="Volver" onclick="location.href = '../menuAfiliados.php'" /> 
	</div>
	<div id="ocultos" align="center">
		<input name="tipoerror" id="tipoerror" type="text" value="<?php echo $err ?>" size="1"/>
		<input name="tipoafili" id="tipoafili" type="text" value="<?php echo $tipoafiliado ?>" size="1"/>
	</div>
	<div align="center">
		<h1>Plan Materno Infantil</h1>
	</div>
	<div align="center">
		<table id="beneficiarias" class="tablesorter" style="font-size:14px; text-align:center">
			<thead>
				<tr>
					<th colspan="10">Beneficiarias</th>
				</tr>
				<tr>
					<th>Nro. Afiliado </th>
					<th>Tipo</th>
					<th>Apellido y Nombre</th>
					<th>Documento</th>
					<th>C.U.I.L.</th>
					<th>Delegacion</th>
					<th>Fecha Email</th>
					<th>F.P.P.</th>
					<th>Nacimiento</th>
					<th>Ficha</th>
				</tr>
			</thead>
			<tbody>
		<?php
		$sqlTitular = "SELECT p.id, p.nroafiliado, t.apellidoynombre, t.nrodocumento, t.cuil, t.codidelega, p.emailfecha, p.fpp, p.nacimiento FROM pmibeneficiarios p, titulares t WHERE p.tipoparentesco = 0 AND p.nroafiliado = t.nroafiliado ORDER BY p.emailfecha, p.nroafiliado";
		$resTitular = mysql_query($sqlTitular,$db);
		$totalpmi = $totalpmi + mysql_num_rows($resTitular);
		while($rowTitular = mysql_fetch_array($resTitular)) { ?>
			<tr>
				<td><?php echo $rowTitular['nroafiliado'] ?></td>
				<td>Titular</td>
				<td><?php echo $rowTitular['apellidoynombre'] ?></td>
				<td><?php echo $rowTitular['nrodocumento'] ?></td>
				<td><?php echo $rowTitular['cuil'] ?></td>
				<td><?php echo $rowTitular['codidelega'] ?></td>
				<td><?php echo invertirFecha($rowTitular['emailfecha']) ?></td>
				<td><?php echo invertirFecha($rowTitular['fpp']) ?></td>
				<td><?php if($rowTitular['nacimiento']==1) { echo 'Si'; } else { echo 'No'; }?></td>
				<td><input class="nover" type="button" id="editatitular" name="editatitular" value="Editar" onclick="location.href = 'editarFicha.php?nroId=<?php echo $rowTitular['id']?>'"/></td>
			</tr>
		<?php
		}
		$sqlFamiliar = "SELECT p.id, p.nroafiliado, p.nroorden, k.descrip, f.apellidoynombre, f.nrodocumento, f.cuil, t.codidelega, p.emailfecha, p.fpp, p.nacimiento FROM pmibeneficiarios p, familiares f, titulares t, parentesco k WHERE p.tipoparentesco != 0 AND p.nroafiliado = t.nroafiliado AND p.nroafiliado = f.nroafiliado AND p.nroorden = f.nroorden AND p.tipoparentesco = k.codparent ORDER BY p.emailfecha, p.nroafiliado, p.nroorden";
		$resFamiliar = mysql_query($sqlFamiliar,$db);
		$totalpmi = $totalpmi + mysql_num_rows($resFamiliar);
		while($rowFamiliar = mysql_fetch_array($resFamiliar)) { ?>
			<tr>
				<td><?php echo $rowFamiliar['nroafiliado'] ?></td>
				<td><?php echo "Familiar ".$rowFamiliar['descrip'] ?></td>
				<td><?php echo $rowFamiliar['apellidoynombre'] ?></td>
				<td><?php echo $rowFamiliar['nrodocumento'] ?></td>
				<td><?php echo $rowFamiliar['cuil'] ?></td>
				<td><?php echo $rowFamiliar['codidelega'] ?></td>
				<td><?php echo invertirFecha($rowFamiliar['emailfecha']) ?></td>
				<td><?php echo invertirFecha($rowFamiliar['fpp']) ?></td>
				<td><?php if($rowFamiliar['nacimiento']==1) { echo 'Si'; } else { echo 'No'; }?></td>
				<td><input class="nover" type="button" id="editafamiliar" name="editafamiliar" value="Editar" onclick="location.href = 'editarFicha.php?nroId=<?php echo $rowFamiliar['id']?>'"/></td>
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
						<img src="../img/first.png" width="16" height="16" class="first"/> <img src="../img/prev.png" width="16" height="16" class="prev"/>
						<input name="text" type="text" class="pagedisplay" style="background:#CCCCCC; text-align:center" size="8" readonly="readonly"/>
						<img src="../img/next.png" width="16" height="16" class="next"/> <img src="../img/last.png" width="16" height="16" class="last"/>
						<select name="select" class="pagesize">
							<option selected="selected" value="5">5 por pagina</option>
							<option value="10">10 por pagina</option>
							<option value="30">30 por pagina</option>
							<option value="<?php echo $totalpmi;?>">Todos</option>
							</select>
						</p>
					</form>	
				</div>
			</td>
		</tr>
	</table>
	<form id="moduloPMI" name="moduloPMI" method="post"  onsubmit="return validar(this)" action="buscaBeneficiaria.php">
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
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIA POR NRO DE AFILIADO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 2) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIA POR NRO DE DOCUMENTO NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 3) {
			print("<div align='center' style='color:#FF0000'><b> LA BUSQUEDA DE BENEFICIARIA POR CUIL NO GENERO RESULTADOS </b></div>");
		}
		if ($err == 4) {
			print("<div align='center' style='color:#FF0000'><b> LA BENEFICIARIA QUE INTENTA AGREGAR YA EXISTE EN LA LISTA DE PMI SIN REGISTRO DE NACIMIENTO </b></div>");
		}
	?>
	</div>
	<div id="resultados" align="center">
			<h2>Resultados de la Busqueda</h2>
			<table id="busqueda" class="tablesorter" style="font-size:14px; text-align:center">
				<thead>
					<tr>
						<th colspan="7">Beneficiarias Encontradas</th>
					</tr>
					<tr>
						<th>Nro. Afiliado </th>
						<th>Tipo</th>
						<th>Apellido y Nombre</th>
						<th>Documento</th>
						<th>C.U.I.L.</th>
						<th>Delegacion</th>
						<th>Accion</th>
					</tr>
				</thead>
				<tbody>
			<?php
				if(strcmp($tipoafiliado, 'A')==0 || strcmp($tipoafiliado, 'T')==0) {
					while($rowBusquedaTitular = mysql_fetch_array($resBusquedaTitular)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaTitular['nroafiliado'] ?></td>
						<td>Titular</td>
						<td><?php echo $rowBusquedaTitular['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaTitular['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaTitular['cuil'] ?></td>
						<td><?php echo $rowBusquedaTitular['codidelega'] ?></td>
						<td><input class="nover" type="button" id="agregatitular" name="agregatitular" value="Agregar" onclick="location.href = 'agregarFicha.php?nroAfi=<?php echo $rowBusquedaTitular['nroafiliado']?>&tipPar=0&nroOrd=0'"/></td>
					</tr>
			<?php
					}
				}
				if(strcmp($tipoafiliado, 'A')==0 || strcmp($tipoafiliado, 'F')==0) {
					while($rowBusquedaFamiliar = mysql_fetch_array($resBusquedaFamiliar)) {  ?>
					<tr>
						<td><?php echo $rowBusquedaFamiliar['nroafiliado'] ?></td>
						<td><?php echo "Familiar ".$rowBusquedaFamiliar['descrip'] ?></td>
						<td><?php echo $rowBusquedaFamiliar['apellidoynombre'] ?></td>
						<td><?php echo $rowBusquedaFamiliar['nrodocumento'] ?></td>
						<td><?php echo $rowBusquedaFamiliar['cuil'] ?></td>
						<td><?php echo $rowBusquedaFamiliar['codidelega'] ?></td>
						<td><input class="nover" type="button" id="agregafamiliar" name="agregafamiliar" value="Agregar" onclick="location.href = 'agregarFicha.php?nroAfi=<?php echo $rowBusquedaFamiliar['nroafiliado']?>&tipPar=<?php echo $rowBusquedaFamiliar['tipoparentesco']?>&nroOrd=<?php echo $rowBusquedaFamiliar['nroorden']?>'"/></td>
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