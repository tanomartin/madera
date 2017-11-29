<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$orden = $_GET['orden'];
$nroafil = $_GET['nroafil'];
$nombre = $_GET['nombre'];
$delega = $_GET['delega']
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
	var titulo = formulario.titulo.value;
	var descripcion = formulario.descripcion.value;

	if (titulo == "") {
		alert("El titulo de la entrada de seguimiento es obligatorio");
		return false;
	}

	if (descripcion == "") {
		alert("La Descripcion de la entrada de seguimiento es obligatorio");
		return false;
	}
	
	$.blockUI({ message: "<h1>Guardando Entrada de Seguimiento. Aguarde por favor...</h1>" });
	return true;
}

function mostrarComentario(seleccion) {
	document.getElementById("comentario").value = "";
	document.getElementById("comentario").style.display = "none"; 
	document.getElementById("tituloComentario").style.display = "none"; 
	if (seleccion == 1) {
		document.getElementById("comentario").style.display = "block"; 
		document.getElementById("tituloComentario").style.display = "block";
	}
}

</script>

<style type="text/css" media="print">
.nover {display:none}
</style>

</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<p><input class="nover" type="button" name="volver" value="Volver" onclick="location.href = 'seguimiento.php?nroafil=<?php echo $nroafil ?>&orden=<?php echo $orden?>&nombre=<?php echo $nombre ?>&delega=<?php echo $delega?>'" /></p>
	<h3>Nueva Entrada de Seguimiento</h3> 
	<h3>Afiliado: <?php echo $nroafil." - ".$nombre?></h3> 
	<h3>Delegacion: <?php echo $delega ?></h3> 
	<form id="alta" name="alta" method="post" onsubmit="return validar(this)" action="seguimientoNuevoGuardar.php">
		<p><b>Titulo: </b><input type="text" id="titulo" name="titulo" size="80"/></p>
		<p><b>Descripcion</b> </p> 
		<p><textarea name="descripcion" id="descripcion" cols="100" rows="7"></textarea></p>
		<p>
			<b>Seguimiento: </b>
			<input type="radio" id="seguimientoSI" name="seguimiento" value="1" onclick="mostrarComentario(1)" checked="checked"/>SI
   			<input type="radio" id="seguimientoNO" name="seguimiento" value="0" onclick="mostrarComentario(0)"/>NO
		</p>
		<p id="tituloComentario"><b>Comentario Seguimiento</b> </p> 
		<p><textarea name="comentario" id="comentario" cols="100" rows="7"></textarea></p>
		<input style="display: none" type="text" value="<?php echo $nroafil ?>" id="nroafil" name="nroafil"/>
		<input style="display: none" type="text" value="<?php echo $orden ?>" id="orden" name="orden"/>
		<input style="display: none" type="text" value="<?php echo $nombre ?>" id="nombre" name="nombre"/>
		<input style="display: none" type="text" value="<?php echo $delega ?>" id="delega" name="delega"/>
		<p><input type="submit" value="Guardar"/></p>
	</form>
</div>
</body>
</html>