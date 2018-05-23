<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$sqlPrestador = "SELECT prestadores.cuit, prestadores.nombre, prestadores.codigoprestador, prestadores.telefono1, 
prestadores.email1, prestadoresauxiliar.cbu, prestadoresauxiliar.cuenta, 
prestadoresauxiliar.banco, prestadoresauxiliar.interbanking, DATE_FORMAT(prestadoresauxiliar.fechainterbanking ,'%d-%m-%Y') as fechainterbanking
FROM prestadores, prestadoresauxiliar
WHERE prestadoresauxiliar.interbanking = 1 and prestadoresauxiliar.fechainterbanking is null and 
prestadoresauxiliar.codigoprestador = prestadores.codigoprestador ORDER BY prestadores.codigoprestador DESC";
$resPrestador = mysql_query($sqlPrestador,$db);
$canPrestador = mysql_num_rows($resPrestador);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Prestadores :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

function validar(formulario) {
	$.blockUI({ message: "<h1>Generando Archivo Interbanking... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../moduloPrestadores.php'" />
	<h3>Modulo Archivo Importación Interbanking </h3>
<?php if (isset($_GET['generado'])) { ?>
		<h3 style="color: blue">Archivo generado exitosamente en la carpeta correspondiente</h3>
<?php }
	  if ($canPrestador != 0) { ?>
	<form id="form1" name="form1" method="post" onsubmit="return validar(this)" action="generarArchivo.php">
	   <div class="grilla">
	   <table>
		<thead>
			<tr>
				<th>Código</th>
				<th>Nombre o Razón Social</th>
				<th>C.U.I.T.</th>
				<th>C.B.U.</th>
				<th>Banco</th>
				<th>Cuenta</th>
				<th>Interbanking</th>
			</tr>
		</thead>
		<tbody>
		<?php while($rowPrestador = mysql_fetch_array($resPrestador)) { ?>
			<tr>
				<td><?php echo $rowPrestador['codigoprestador'];?></td>
				<td><?php echo $rowPrestador['nombre'];?></td>
				<td><?php echo $rowPrestador['cuit'];?></td>
				<td><?php echo $rowPrestador['cbu'];?></td>
				<td><?php echo $rowPrestador['banco'];?></td>
				<td><?php echo $rowPrestador['cuenta'];?></td>
				<td><?php 
						if ($rowPrestador['interbanking'] == 0) { 
							echo "NO"; 
						} else { 
							$fecha = $rowPrestador['fechainterbanking'];
							if ($rowPrestador['fechainterbanking'] == NULL) { $fecha = "No subido"; }
							echo "SI (".$fecha.")"; 
						} ?></td>
			</tr>
		<?php } ?>
		</tbody>
	  </table>
	  </div>
	  <p><input type="submit" name="generarArchivo" value="Generar Archivo"/></p>
	</form>
  <?php } else { ?>
  		<h3>No hay prestadores para informar</h3>
  <?php }?>
</div>
</body>
</html>

