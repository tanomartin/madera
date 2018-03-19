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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Servicios Discpacidad :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  	<h3>Listado de Servicios </h3>
	<h3 style="color: blue"><?php echo $rowPrestador['nombre']." [".$rowPrestador['codigoprestador']."]" ?></h3>
	<?php if ($canServicios != 0) { ?>
		<div class="grilla">
			<table style="width:500px; font-size:14px; text-align:center">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripcion</th>
					</tr>
				</thead>
				<tbody>
				<?php while ($rowServicio = mysql_fetch_assoc($resServicios)) { ?>
						<tr>
							<td><?php echo $rowServicio['codigoservicio'] ?></td>
							<td><?php echo $rowServicio['descripcion']?></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	<?php } else {?>
			<h3><font color="red">No tiene servios de discapacidad cargados</font></h3>
	<?php }?>
</div>
</body>
</html>
