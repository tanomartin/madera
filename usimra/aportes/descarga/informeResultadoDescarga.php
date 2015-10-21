<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 

$nroControl = $_POST['nroControl'];
$idControl = $_POST['idControl'];

$listadoSerializadoEmpresa = $_POST['empresas'];
$listadoSerializadoEmpleados = $_POST['empleados'];
$listadoSerializadoFamiliares = $_POST['familiares'];
$listadoSerializadoEmpBaja = $_POST['empbaja'];
$listadoSerializadoFamBaja = $_POST['fambaja'];

$listadoEmpresas = unserialize(urldecode($_POST['empresas']));
$listadoEmpleados = unserialize(urldecode($_POST['empleados']));
$listadoFamiliares = unserialize(urldecode($_POST['familiares']));
$listadoEmpBaja = unserialize(urldecode($_POST['empbaja']));
$listadoFamBaja = unserialize(urldecode($_POST['fambaja']));

$sqlControl = "SELECT * FROM aporcontroldescarga WHERE id = $idControl";
$resControl = mysql_query($sqlControl,$db); 
$rowControl = mysql_fetch_assoc($resControl);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultados Descarga Aplicativo DDJJ :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>
<body bgcolor="#B2A274">
<div align="center">
	  <p><span style="text-align:center">
		<input type="reset" name="volver" value="Volver" onclick="location.href = 'moduloDescarga.php'" />
	  </span></p>
	  <p><span class="Estilo2">Resultados Descarga Aplicativo DDJJ</span></p>
	  <p><span class="Estilo2">Resumen de Descarga</span></p>
	   <table width="400" border="1">
		<tr>
		  <td><strong>Usuario</strong></td>
		  <td><?php echo $rowControl['usuariodescarga'] ?></td>
		</tr>
		<tr>
		  <td><strong>Fecha</strong></td>
		  <td><?php echo $rowControl['fechadescarga'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. DDJJ</strong></td>
		  <td><?php echo $rowControl['cantidadddjj'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Activos</strong></td>
		  <td><?php echo $rowControl['cantidadactivos'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Inactivos</strong></td>
		  <td><?php echo $rowControl['cantidadinactivos'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Empresas</strong></td>
		  <td><?php echo $rowControl['cantidadempresas'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Titulares</strong></td>
		  <td><?php echo $rowControl['cantidadtitulares'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Familiares</strong></td>
		  <td><?php echo $rowControl['cantidadfamiliares'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Titulares de Baja</strong></td>
		  <td><?php echo $rowControl['cantidadtitularesbaja'] ?></td>
		</tr>
		<tr>
		  <td><strong>Cant. Familiares de Baja</strong></td>
		  <td><?php echo $rowControl['cantidadfamiliaresbaja'] ?></td>
		</tr>
	  </table>
	  <p><span class="Estilo2">Detalle de Descarga</span></p>
	  <p><strong>D.D.J.J.</strong></p>
	  <table width="400" border="1">
		<tr>
		  <td><strong>Fecha Desde </strong></td>
		  <td><?php 
					$utlimoNroControl = $rowControl['nrocontrol'];
		  			if ($nroControl != 0) { 
		  				echo substr($nroControl,6,2)."/".substr($nroControl,4,2)."/".substr($nroControl,0,4)." - ".substr($nroControl,8,2).":".substr($nroControl,10,2).":".substr($nroControl,12,2);
		  			} else {
						echo substr($utlimoNroControl,6,2)."/".substr($utlimoNroControl,4,2)."/".substr($utlimoNroControl,0,4)." - ".substr($utlimoNroControl,8,2).":".substr($utlimoNroControl,10,2).":".substr($utlimoNroControl,12,2);
					} ?>
		</td>
		</tr>
		<tr>
		  <td><strong>Fecha Hasta</strong></td>
		  <td><?php echo substr($utlimoNroControl,6,2)."/".substr($utlimoNroControl,4,2)."/".substr($utlimoNroControl,0,4)." - ".substr($utlimoNroControl,8,2).":".substr($utlimoNroControl,10,2).":".substr($utlimoNroControl,12,2) ?></td>
		</tr>
	   </table>
	  <p><strong>Empresas</strong></p>
		<?php if (sizeof($listadoEmpresas) != 0) { ?>
			<table width="600" border="1">
				<thead>
				<tr>
				  <th>Estado</th>
				  <th>C.U.I.T.</th>
				  <th>Razon Social</th>
				</tr>
				</thead>
				<tbody align="center">
				<?php foreach($listadoEmpresas as $empresa) { ?>
						<tr>
							<td><?php echo $empresa['estado'] ?></td>
							<td><?php echo $empresa['cuit'] ?></td>
							<td><?php echo $empresa['nombre'] ?></td>
						</tr>
				<?php } ?> 
				</tbody>
  </table>
			 <p><b>E:</b> Ya Existia - <b>B:</b> De Baja - <b>I:</b> Insertada</p>
		<?php } else { 
				 echo("No se descargaron Empresas Nuevas");
			  } ?> 
		
	  <p><strong>Titulares</strong></p>
	 <?php if (sizeof($listadoEmpleados) != 0) { ?>
			<table width="600" border="1">
				<thead>
				<tr>
				  <th>Estado</th>
				  <th>C.U.I.L.</th>
				  <th>C.U.I.T.</th>
				  <th>Apellido y Nombre</th>
				</tr>
				</thead>
				<tbody align="center">
				<?php foreach($listadoEmpleados as $empleados) { ?>
						<tr>
							<td><?php echo $empleados['estado'] ?></td>
							<td><?php echo $empleados['cuil'] ?></td>
							<td><?php echo $empleados['cuit'] ?></td>
							<td><?php echo $empleados['nombre'] ?></td>
						</tr>
				<?php } ?> 
				</tbody>
  </table>
			 <p><b>M:</b> Ya Existia (Modificación) - <b>B:</b> De Baja - <b>I:</b> Insertado</p>
		<?php } else { 
				 echo("No se descargaron Titulares Nuevos");
			  } ?> 
			  
	  <p><strong>Familiares</strong></p>
	  <?php if (sizeof($listadoFamiliares) != 0) { ?>
			<table width="600" border="1">
				<thead>
				<tr>
				  <th>Estado</th>
				  <th>C.U.I.L.</th>
				  <th>C.U.I.T.</th>
				  <th>Parentesco</th>
				  <th>Apellido y Nombre</th>
				</tr>
				</thead>
				<tbody align="center">
				<?php foreach($listadoFamiliares as $familaires) { ?>
						<tr>
							<td><?php echo $familaires['estado'] ?></td>
							<td><?php echo $familaires['cuil'] ?></td>
							<td><?php echo $familaires['cuit'] ?></td>
							<td><?php echo $familaires['parentesco'] ?></td>
							<td><?php echo $familaires['nombre'] ?></td>
						</tr>
				<?php } ?> 
				</tbody>
  </table>
			 <p><b>M:</b> Ya Existia (Modificación) - <b>B:</b> De Baja - <b>I:</b> Insertado</p>
		<?php } else { 
				 echo("No se descargaron Familiares Nuevos");
			  } ?> 
			  
	  <p><strong>Titulares de Baja</strong></p>
	  <?php if (sizeof($listadoEmpBaja) != 0) { ?>
			<table width="600" border="1">
				<thead>
				<tr>
				  <th>Estado</th>
				  <th>C.U.I.L.</th>
				  <th>C.U.I.T.</th>
				  <th>Apellido y Nombre</th>
				</tr>
				</thead>
				<tbody align="center">
				<?php foreach($listadoEmpBaja as $empbaja) { ?>
						<tr>
							<td><?php echo $empbaja['estado'] ?></td>
							<td><?php echo $empbaja['cuil'] ?></td>
							<td><?php echo $empbaja['cuit'] ?></td>
							<td><?php echo $empbaja['nombre'] ?></td>
						</tr>
				<?php } ?> 
				</tbody>
  </table>
			 <p><b>E:</b> Ya Existia - <b>A:</b> De Alta - <b>I:</b> Insertado</p>
		<?php } else { 
				 echo("No se descargaron Titulares Nuevos de Baja");
			  } ?> 
			  
	  <p><strong>Familiares de Baja</strong></p>
	  <?php if (sizeof($listadoFamBaja) != 0) { ?>
			<table width="600" border="1">
				<thead>
				<tr>
				  <th>Estado</th>
				  <th>C.U.I.L.</th>
				  <th>C.U.I.T.</th>
				  <th>Parentesco</th>
				  <th>Apellido y Nombre</th>
				</tr>
				</thead>
				<tbody align="center">
				<?php foreach($listadoFamBaja as $fambaja) { ?>
						<tr>
							<td><?php echo $fambaja['estado'] ?></td>
							<td><?php echo $fambaja['cuil'] ?></td>
							<td><?php echo $fambaja['cuit'] ?></td>
							<td><?php echo $fambaja['parentesco'] ?></td>
							<td><?php echo $fambaja['nombre'] ?></td>
						</tr>
				<?php } ?> 
				</tbody>
  </table>
			 <p><b>E:</b> Ya Existia - <b>A:</b> De Alta - <b>I:</b> Insertado</p>
		     <p>
		       <?php } else { 
				 echo("No se descargaron Familiares Nuevos de Baja");
			  } ?> 
  </p>
		     <p>
		       <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
</p>
</div>
</body>
</html>