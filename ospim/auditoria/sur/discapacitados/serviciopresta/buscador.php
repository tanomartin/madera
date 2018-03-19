<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlServicios = "SELECT * FROM tiposerviciodisca ORDER BY codigoservicio";
$resServicios = mysql_query($sqlServicios,$db);

$sqlDelega = "SELECT * FROM delegaciones WHERE codidelega  not in (1000,1001,3200,3500,4000,4001) ORDER BY codidelega ";
$resDelega = mysql_query($sqlDelega,$db);

$numPresta = 0;
if (isset($_POST['servicio'])) {
	$resultado = array();
	$codigoservicio = $_POST['servicio'];
	$codidelega = $_POST['delega'];
	
	$sqlServicioSelect = "SELECT * FROM tiposerviciodisca WHERE codigoservicio = $codigoservicio";
	$resServicioSelect = mysql_query($sqlServicioSelect,$db);
	$rowServicioSelect = mysql_fetch_assoc($resServicioSelect);
	
	if ($codidelega == 0) {
		$sqlPresta = "SELECT p.nombre, p.cuit, p.codigoprestador, p.nombre as nombrepresta 
						FROM prestadores p, prestadorserviciodisca s 
						WHERE s.codigoservicio = $codigoservicio and s.codigoprestador = p.codigoprestador";
		$resPresta = mysql_query($sqlPresta,$db);
		$numPresta = mysql_num_rows($resPresta);
		if ($numPresta > 0) {
			while ($rowPresta = mysql_fetch_assoc($resPresta)) {
				$arrayResultado[$rowPresta['codigoprestador']] = $rowPresta;
			}
		}
	} else {
		$sqlDeleSelect = "SELECT * FROM delegaciones WHERE codidelega = $codidelega";
		$resDeleSelect = mysql_query($sqlDeleSelect,$db);
		$rowDeleSelect = mysql_fetch_assoc($resDeleSelect);
		
		$sqlPresta = "SELECT p.nombre, p.cuit, p.codigoprestador, p.nombre as nombrepresta
						FROM prestadores p, prestadorserviciodisca s, prestadorjurisdiccion j
						WHERE s.codigoservicio = $codigoservicio and 
							  j.codidelega = $codidelega and 
							  s.codigoprestador = p.codigoprestador and 
							  j.codigoprestador = p.codigoprestador";
		$resPresta = mysql_query($sqlPresta,$db);
		$numPresta = mysql_num_rows($resPresta);
		if ($numPresta > 0) {
			while ($rowPresta = mysql_fetch_assoc($resPresta)) {
				$arrayResultado[$rowPresta['codigoprestador']] = $rowPresta;
			}
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Servicio Disca Buscador :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	if (formulario.servicio.value == 0) {
		alert("Debe seleccionar un servicio prestado");
		return false;
	}
	formulario.buscar.disabled = true;
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirPantalla(dire) {
	a= window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>

</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="buscador.php">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloServicioPresta.php'" /> </p>
	  	<h3>Buscador por Servicios para Prestadores de Discapacidad </h3>
		
		<h4>Servicio</h4>
		<select id="servicio" name="servicio">
			<option value="0" selected="selected">Seleccione Servicio</option>
		<?php while ($rowServicio = mysql_fetch_assoc($resServicios)) { ?>
				<option value='<?php echo $rowServicio['codigoservicio'] ?>'><?php echo $rowServicio['descripcion']?></option> 
		<?php } ?>
		</select>
		
		<h4>Delegacion</h4>
		<select id="delega" name="delega">
			<option value="0" selected="selected">Seleccione Delegación</option>
		<?php while ($rowDelega = mysql_fetch_assoc($resDelega)) { ?>
				<option value='<?php echo $rowDelega['codidelega'] ?>'><?php echo $rowDelega['nombre']?></option> 
		<?php } ?>
		</select>
		
		<p><input type="submit" name="buscar" value="Buscar" /></p>
	
		<?php if (isset($_POST['servicio'])) { ?>
			<h3>Listado de Prestadores</h3>
			<h3>Servicio: <font color="blue"><?php echo $rowServicioSelect['descripcion'] ?></font></h3>
			<?php if ($codidelega != 0) { ?>
				<h3>Delegacion: <font color="blue"><?php echo $rowDeleSelect['nombre'] ?></font></h3>
			<?php } ?>
			<?php if ($numPresta != 0) { ?>
			<div class="grilla">
				<table style="width:800px; font-size:14px; text-align:center">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nombre o Razón Social</th>
							<th>C.U.I.T.</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($arrayResultado as $presta) { ?>
							<tr>
								<td><?php echo $presta['codigoprestador'] ?></td>
								<td><?php echo $presta['nombrepresta']?></td>
								<td><?php echo $presta['cuit']?></td>
								<td>
									<input name="servic" type="button" value="Servicios" onclick="abrirPantalla('listadoPrestaServicios.php?codigo=<?php echo $presta['codigoprestador']; ?>')"/> |
									<input name="delega" type="button" value="Delegaciones" onclick="abrirPantalla('listadoPrestaDelega.php?codigo=<?php echo $presta['codigoprestador']; ?>')"/>
								</td>
							</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		<?php } else {?>
				<h3><font color="red">No existen Prestadores con estos filtros</font></h3>
		<?php }
			} ?>
	</form>
</div>
</body>
</html>
