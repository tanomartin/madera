<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$id = $_GET['id'];
$nombre =  $_GET['nombre'];
$delega = $_GET['delega'];

$sqlSeguimiento = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro, DATE_FORMAT(s.fechamodificacion, '%d-%m-%Y %H:%i:%S') as fechamodificacion
FROM seguimiento s WHERE id = $id";
$resSeguimiento = mysql_query($sqlSeguimiento,$db);
$rowSeguimiento = mysql_fetch_assoc($resSeguimiento);

$nroafil = $rowSeguimiento['nroafiliado'];
$orden = $rowSeguimiento['nroorden'];

if ($rowSeguimiento['seguimiento'] != 0) {
	$sqlSeguimientoEstado = "SELECT s.*, DATE_FORMAT(s.fecharegistro, '%d-%m-%Y %H:%i:%S') as fecharegistro FROM seguimientoestado s WHERE idseguimiento = ".$rowSeguimiento['id']." ORDER BY id DESC LIMIT 1";
	$resSeguimientoEstado = mysql_query($sqlSeguimientoEstado,$db);
	$rowSeguimientoEstado = mysql_fetch_assoc($resSeguimientoEstado);
	$rowSeguimiento['estado'] = $rowSeguimientoEstado;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Seguimiento :.</title>

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
	document.getElementById("comentario").style.display = "block"; 
	document.getElementById("tituloCambio").style.display = "block"; 
	if (seleccion.value == 'SC') {
		document.getElementById("comentario").style.display = "none"; 
		document.getElementById("tituloCambio").style.display = "none";
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
	<h3>Modificar Entrada de Seguimiento</h3> 
	<h3>Afiliado: <?php echo $nroafil." - ".$nombre?></h3> 
	<h3>Delegacion: <?php echo $delega ?></h3> 
	<form id="alta" name="alta" method="post" onsubmit="return validar(this)" action="seguimientoModificarGuardar.php">
		<p><b>Titulo: </b><input type="text" id="titulo" name="titulo" size="80" value="<?php echo $rowSeguimiento['titulo'] ?>"/></p>
		<p><b>Descripcion</b> </p> 
		<p><textarea name="descripcion" id="descripcion" cols="100" rows="7"><?php echo $rowSeguimiento['descripcion'] ?></textarea></p>
		
		<?php if ($rowSeguimiento['seguimiento'] == 1) { ?>
			<p><b>Estado Seguimiento:  </b><?php echo $rowSeguimiento['estado']['estado']." desde ".$rowSeguimiento['estado']['fecharegistro']  ?></p>
			<p><b>Comentario </b></p>
			<div style="border: groove; width: 800px"><?php echo $rowSeguimiento['estado']['comentario'] ?> </div>
			<p><b>Cambiar Estado</b> </p>
			
			<select id="cambioEstado" name="cambioEstado" onchange="mostrarComentario(this)">
				<option selected="selected" value="SC">Seleccione Estado</option>
				<?php if ($rowSeguimiento['estado']['estado'] != 'EN GESTION') { ?><option value="EN GESTION">EN GESTION</option> <?php } ?>
				<?php if ($rowSeguimiento['estado']['estado'] != 'PENDIENTE') { ?><option value="PENDIENTE">PENDIENTE</option> <?php } ?>
				<option value="FINALIZADO">FINALIZADO</option>
			</select>
			
			<p id="tituloCambio" style="display: none"><b>Comentario Cambio de Estado</b> </p>
			<p><textarea style="display: none" name="comentario" id="comentario" cols="100" rows="7"></textarea></p>
		<?php } ?>
		
		<input style="display: none" type="text" value="<?php echo $rowSeguimiento['id']  ?>" id="id" name="id"/>
		<input style="display: none" type="text" value="<?php echo $nroafil ?>" id="nroafil" name="nroafil"/>
		<input style="display: none" type="text" value="<?php echo $orden ?>" id="orden" name="orden"/>
		<input style="display: none" type="text" value="<?php echo $nombre ?>" id="nombre" name="nombre"/>
		<input style="display: none" type="text" value="<?php echo $delega ?>" id="delega" name="delega"/>
		<p><input type="submit" value="Modificar"/></p>
	</form>
</div>
</body>
</html>