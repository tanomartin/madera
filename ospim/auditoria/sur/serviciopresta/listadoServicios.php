<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlServicios = "SELECT * FROM tiposerviciodisca ORDER BY codigoservicio";
$resServicios = mysql_query($sqlServicios,$db);
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
  	<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloServicioPresta.php'" /> </p>
  	<h3>Listado de Servicios para Prestadores de Discapacidad </h3>
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
</div>
</body>
</html>
