<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 

$sqlModulos = "SELECT m.* FROM usuarios u, departamentos d, modulos m, modulosdptos md
				WHERE u.usuariosistema = '".$_SESSION['usuario']."' AND
					  u.departamento = d.id AND 
					  d.id = md.iddpto AND 
					  md.idmodulo = m.id";
$resModulos = mysql_query($sqlModulos,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Empresas :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" type="text/css" id="" media="print, projection, screen" />
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery-ui.min.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function clearForm(modulo) {
	var arrayModulo = modulo.split('-');
	moduloName = arrayModulo[0]
	var nombreData = "nombre-"+moduloName;
	document.getElementById(nombreData).innerHTML = "";
	for (var i = 1; i<5; i++) {
		var nameImput = "dato"+i+"-"+moduloName;
		if (document.getElementById(nameImput) != null) {
			document.getElementById(nameImput).value = "";
		}
	}
}

function cargarFormulario(modulo) {
	modulos = document.getElementById('selectModulo')
	for (var i = 1; i < modulos.length; i++) {
		var opt = modulos[i].value;
		var arrayNombreSelect = opt.split('-');
		document.getElementById(arrayNombreSelect[0]).style.display = "none";
		document.getElementById(arrayNombreSelect[0]).value = "";	 
	}
	if (modulo != 0) {
		clearForm(modulo);
		var arrayModulo = modulo.split('-');
		document.getElementById(arrayModulo[0]).style.display = "block";
		var id = "id-"+arrayModulo[0];
		document.getElementById(id).value = arrayModulo[1];
	}
}

function buscarEntidad(inputObject) {
	var moduloArray = inputObject.name.split("-");
	var valor = inputObject.value;
	var labelName = "nombre-"+moduloArray[1];
	document.getElementById(labelName).innerHTML = "";
	if (valor != "") {
		if (esEnteroPositivo(valor)) {
			$.ajax({
				type: "POST",
				dataType: 'html',
				url: "buscarEntidad.php?origen=<?php echo $origen ?>",
				data: {modulo:moduloArray[1], valor:valor},
			}).done(function(respuesta){
				document.getElementById(labelName).innerHTML = respuesta;
			});
		} else {
			inputObject.value = "";
			alert("El dato debe ser numerico");	
		}
	}
}

function validar(formulario, modulo) {
	var nameDato1 = "dato1-"+modulo;
	var nameMotivo = "motivo-"+modulo;
	var nameObs = "obs-"+modulo;
	var nameGuardar = "guardar-"+modulo;
	if (document.getElementById(nameDato1).value == "") {
		alert("El 1er dato del formulario es obligatorio");
		document.getElementById(nameDato1).focus();
		return false;
	}
	if (document.getElementById(nameMotivo).value == 0) {
		alert("El motivo de la correccion es oblitagorio");
		document.getElementById(nameMotivo).focus();
		return false;
	}
	if (document.getElementById(nameObs).value == "") {
		alert("Debe ingresar una observación para la correccion");
		document.getElementById(nameObs).focus();
		return false;
	}
	$.blockUI({ message: "<h1>Validando datos de la Corrección...<br> Aguarde por favor</h1>" });
	document.getElementById(nameGuardar).disabled  = true;
	return true
}

</script>

</head>
<body style="background-color: <?php echo $bgcolor ?>">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloCorrecciones.php?origen=<?php echo $origen ?>'"/> </p>
  <h3>Nueva Correcciones </h3>
  <h3>Usuario <font color="blue">"<?php echo $_SESSION['usuario']?>"</font> </h3>
  <select id="selectModulo" name="selectModulo" onchange="cargarFormulario(this.value)">
  	  <option value="0">Seleccione Modulo</option>
<?php while ($rowModulos = mysql_fetch_assoc($resModulos)) { ?>
  		<option value="<?php echo $rowModulos['nombre']."-".$rowModulos['id'] ?>"><?php echo $rowModulos['nombre'] ?></option>
<?php } ?>
  </select>
<?php if (isset($_GET['error'])) { ?>
		 <h4>
		 	<font color="black">NO SE GENERO EL PEDIDO DE CORRECCION</font></br>
		 	<font color="brown"><?php echo $_GET['error'] ?></font></br>
		 	<font color="black">(Revise la información y vuelva a cargar la corrección)</font> 	
		</h4>	
<?php }
	  mysql_data_seek( $resModulos, 0 );
	  while ($rowModulos = mysql_fetch_assoc($resModulos)) { 
		 $modulo = $rowModulos['nombre']; ?>
		 <form method="post" id="<?php echo $modulo ?>" onsubmit="return validar(this, '<?php echo $modulo ?>')" name="<?php echo $modulo ?>" action="validador.php?origen=<?php echo $origen ?>&modulo=<?php echo $modulo ?>" style="display: none">
		  	<input type="text" name="id-<?php echo $modulo ?>" id="id-<?php echo $modulo ?>" style="display: none"/>
		  	<h3>Correccion de <?php echo $modulo ?></h3>
		  	<?php if ($rowModulos['etiquetadato1'] != NULL) { ?>
		  			<p>
		  			   <b><?php echo $rowModulos['etiquetadato1'].": " ?></b>
		  			   <input type="text" name="dato1-<?php echo $modulo ?>" id="dato1-<?php echo $modulo ?>" onchange="buscarEntidad(this)" size="10"/>
		  			</p>
		  			<p style="color: blue"><b><label id="nombre-<?php echo $modulo ?>"></label></b></p>
		  	<?php } 
		  		  if ($rowModulos['etiquetadato2'] != NULL) { ?>
		  			<p><b><?php echo $rowModulos['etiquetadato2'].": " ?></b><input type="text" name="dato2-<?php echo $modulo ?>" id="dato2-<?php echo $modulo ?>" /></p>
		  	<?php } 
		  		  if ($rowModulos['etiquetadato3'] != NULL) { ?>
		  			<p><b><?php echo $rowModulos['etiquetadato3'].": " ?></b><input type="text" name="dato3-<?php echo $modulo ?>" id="dato3-<?php echo $modulo ?>" /></p>
		  	<?php } 
		  		  if ($rowModulos['etiquetadato4'] != NULL) { ?>
		  			<p><b><?php echo $rowModulos['etiquetadato4'].": " ?></b><input type="text" name="dato4-<?php echo $modulo ?>" id="dato4-<?php echo $modulo ?>" /></p>
		  	<?php } ?>
		  		<p><b>Motivo: </b>
		  		<select name="motivo-<?php echo $modulo ?>" id="motivo-<?php echo $modulo ?>">
		  			<option value="0">Seleccione Motivo</option>
			  	<?php  $sqlMotivos = "SELECT mm.* FROM modulosmotivos mm, modulos m WHERE m.nombre = '$modulo' and m.id = mm.idmodulo";
			  		   $resMotivos = mysql_query($sqlMotivos,$db); 
			  		   while ($rowMotivos = mysql_fetch_assoc($resMotivos)) { ?>
			  				<option value="<?php echo $rowMotivos['id'] ?>"><?php echo $rowMotivos['descripcion'] ?></option>
			  	<?php  } ?>	
		  		</select>
		  	</p>
		  	<p><b>Solicitud</b></p>
		  	<p><textarea rows="6" cols="100" name="obs-<?php echo $modulo ?>" id="obs-<?php echo $modulo ?>"></textarea></p>
		  	<input type="submit" id="guardar-<?php echo $modulo ?>" name="guardar-<?php echo $modulo ?>" value="Guardar"/>
		 </form>
<?php } ?>
</div>
</body>
</html>
