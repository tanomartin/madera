<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $codigo";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_assoc($resPrestador);

$sqlServicios = "SELECT * FROM prestadorserviciodisca p, tiposerviciodisca t
					WHERE p.codigoprestador = $codigo and p.codigoservicio = t.codigoservicio";
$resServicios = mysql_query($sqlServicios,$db);
$canServicios = mysql_num_rows($resServicios);
$arrayServicios = array();
if ($canServicios != 0) {
	while($rowServicio = mysql_fetch_assoc($resServicios)){
		$arrayServicios[$rowServicio['codigoservicio']] = $rowServicio['codigoservicio'];
	}
}

$sqlListaServicios = "SELECT * FROM tiposerviciodisca";
$resListaServicios =  mysql_query($sqlListaServicios,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Servicios Discpacidad :.</title>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>

<script type="text/javascript">
function validar(formulario) {	
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Guardando Servicios Discapacidad. Aguarde por favor...</h1>" });
	return true;
}
</script>

<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<h3>Modificar Servicios para Discapacitados </h3>
	<h3 style="color: blue"><?php echo $rowPrestador['nombre']." [".$rowPrestador['codigoprestador']."]" ?></h3>
	<form name="serviciosDisca" id="serviciosDisca" method="post" onsubmit="return validar(this)" action="guardarModificarServicios.php">
		<input type="text" value="<?php echo $rowPrestador['codigoprestador']?>" id="codigopresta" name="codigopresta" style="display: none"/>
		<div class="grilla">
			<table style="width:600px; text-align:center">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripcion</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php while ($rowListado = mysql_fetch_assoc($resListaServicios)) { 
						$checked = "";
						if (in_array($rowListado['codigoservicio'],$arrayServicios)) {
							$checked = 'checked="checked"';
						} ?>
						<tr>
							<td><?php echo $rowListado['codigoservicio'] ?></td>
							<td><?php echo $rowListado['descripcion']?></td>
							<td><input <?php echo $checked ?> type="checkbox" id="servicio<?php echo $rowListado['codigoservicio'] ?>" name="servicio<?php echo $rowListado['codigoservicio'] ?>" value="<?php echo $rowListado['codigoservicio'] ?>" /></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
		<p><input type="submit" name="Submit" id="Submit" value="Guardar" /></p>
	</form>
</div>
</body>
</html>