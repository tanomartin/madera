<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");
$arrayResultado = array();
if (isset($_POST['valor'])) {
	$i = 0;
	$seleccion = $_POST['seleccion'];
	$valor = $_POST['valor'];
	
	$selectTitular = "SELECT t.*, d.descrip as tipdoc FROM titulares t, tipodocumento d WHERE $seleccion = $valor and t.tipodocumento = d.codtipdoc";
	$resTitular = mysql_query($selectTitular,$db);
	$numTitular = mysql_num_rows($resTitular);
	if ($numTitular > 0) {
		while ($rowTitular = mysql_fetch_assoc($resTitular)) {
			$arrayResultado[$i] = $rowTitular;
			$i++;
		}
	} 
	
	$selectFamiliar = "SELECT f.*, p.descrip as parentesco, d.descrip as tipdoc FROM familiares f, parentesco p, tipodocumento d WHERE $seleccion = $valor and f.tipoparentesco = p.codparent and f.tipodocumento = d.codtipdoc";
	$resFamiliar = mysql_query($selectFamiliar,$db);
	$numFamiliar = mysql_num_rows($resFamiliar);
	if ($numFamiliar > 0) {
		while ($rowFamiliar = mysql_fetch_assoc($resFamiliar)) {
			$arrayResultado[$i] = $rowFamiliar;
			$i++;
		}
	}
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: ABM Seguimiento :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>

<script language="javascript" type="text/javascript">

function validar(formulario) {
	formulario.buscar.disabled = true;
	var elementos = document.forms.moduloABM.elements;
	var longitud = document.forms.moduloABM.length;
	var elementoradio = 0;
	for(var i=0; i<longitud; i++) {
		if(elementos[i].name == "seleccion" && elementos[i].type == "radio" && elementos[i].checked == true) {
			elementoradio=i;
		}
	}

	if (formulario.valor.value == "") {
		formulario.buscar.disabled = false;
		alert("Debe ingresar algun dato para la busqueda");
		document.getElementById("valor").focus();
		return false;
	} else {
		if(elementoradio == 2 || elementoradio == 1) {
			if(!esEnteroPositivo(formulario.valor.value)){
				alert("El Nro. Afiliado ");
				formulario.buscar.disabled = false;
				return false;
			}	
		}
		if(elementoradio == 3) {
			if(!verificaCuilCuit(formulario.valor.value)){
				alert("El C.U.I.L. es invalido");
				formulario.buscar.disabled = false;
				return false;
			}	
		}
	}

	$.blockUI({ message: "<h1>Buscando Afiliado. Aguarde por favor...</h1>" });
	return true;
};

</script>
<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="moduloABM" name="moduloABM" method="post"  onsubmit="return validar(this)" action="moduloABM.php">
		<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'menuSeguimiento.php'" /></p>
		<h3>Alta Baja y Modificacion De Seguimiento</h3> 
	    <p> <?php 
		    	if (isset($i)) { 
		    		if ($i == 0) {?>
						<b style='color:#FF0000'> LA BUSQUEDA DE AFILIADO NO GENERO RESULTADOS </b>
	        <?php 	}
		    	} ?>
		</p>
		<table>
			<tr>
				<td width="23"><input name="seleccion" type="radio" value="nroafiliado" checked="checked"/></td>
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
		<p><b>DATO: </b><input name="valor" id="valor" type="text" size="11" /></p>
		<p><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	</form>
<?php if (sizeof($arrayResultado ) > 0) { ?>
		<div class="grilla">
			<table style="width: 1000px">
				<thead>
					<tr>
						<th>Nro Afiliado</th>
						<th>Nombre y Apellido</th>
						<th>Nro Documento</th>
						<th>Tipo Afiliado</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($arrayResultado as $afiliado) { ?>
					<tr>
						<td><?php echo $afiliado['nroafiliado'] ?></td>	
						<td><?php echo $afiliado['apellidoynombre'] ?></td>	
						<td><?php echo $afiliado['tipdoc'].": ".$afiliado['nrodocumento'] ?></td>	
						<td><?php $tipo = "TITULAR";
								  $orden = 0;
								  if (isset($afiliado['nroorden'])) { 
									$tipo = "FAMILIAR - ".$afiliado['parentesco']; 
									$orden = $afiliado['nroorden']; 
								  } 
								  echo $tipo; ?>
						</td>
						<td><input type="button" name="ver" id="ver" value="VER" onclick="location='seguimiento.php?nroafil=<?php echo $afiliado['nroafiliado']?>&orden=<?php echo $orden?>&nombre=<?php echo $afiliado['apellidoynombre']?>'" /></td>		
					</tr>
		    	<?php } ?>
				</tbody>
			</table>
		</div>
<?php } ?>
</div>
</body>
</html>