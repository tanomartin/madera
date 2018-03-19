<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlPrestador = "SELECT * FROM prestadores WHERE codigoprestador = $codigo";
$resPrestador = mysql_query($sqlPrestador,$db);
$rowPrestador = mysql_fetch_assoc($resPrestador);

$sqlDelegacion = "SELECT d.codidelega, d.nombre FROM prestadorjurisdiccion p, delegaciones d WHERE p.codigoprestador = $codigo and p.codidelega = d.codidelega";
$resDelegacion = mysql_query($sqlDelegacion,$db);
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
  	<h3>Listado de Delegaciones </h3>
	<h3 style="color: blue"><?php echo $rowPrestador['nombre']." [".$rowPrestador['codigoprestador']."]" ?></h3>

		<div class="grilla">
			<table style="width:500px; font-size:14px; text-align:center">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripcion</th>
					</tr>
				</thead>
				<tbody>
				<?php while ($rowDelegacion = mysql_fetch_assoc($resDelegacion)) {  ?>
						<tr>
							<td><?php echo $rowDelegacion['codidelega'] ?></td>
							<td><?php echo $rowDelegacion['nombre']?></td>
						</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
</div>
</body>
</html>
