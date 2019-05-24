<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspimSistemas.php");
include ($libPath . "fechas.php");

$sqlUsuario = "SELECT u.*, d.nombre as sector FROM usuarios u, departamentos d WHERE u.id = ".$_GET ['id']." and u.departamento = d.id";
$resUsuario = mysql_query ( $sqlUsuario, $db );
$rowUsuario = mysql_fetch_assoc ( $resUsuario );

$sqlMails = "SELECT * FROM emails u WHERE u.idusuario = " . $_GET ['id'];
$resMails = mysql_query ( $sqlMails, $db );
$canMails = mysql_num_rows( $resMails );

$sqlPc = "SELECT * FROM stockproducto p, stockubicacionproducto u WHERE u.idusuario = ".$_GET ['id']." and u.id = p.id";
$resPc = mysql_query ( $sqlPc, $db );
$canPc = mysql_num_rows( $resPc );?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Nuevo Insumo :.</title>
<style type="text/css" media="print">
.nover {display:none}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" class="nover" name="volver" value="Volver" onclick="location.href = 'usuarios.php'" /></p>
		<h3>Ficha Usuario</h3>
		<h3><?php echo $rowUsuario['nombre']?></h3>
		<table width="600" border="1" style="text-align: left">
			<tr>
				<td><b>Sector</b></td>
				<td><?php echo $rowUsuario['sector'] ?></td>
				<td><b>Nombre PC</b></td>
				<td><?php echo $rowUsuario['nombrepc']?></td>
			</tr>
			<tr>
				<td><b>Usuario Win</b></td>
				<td><?php echo $rowUsuario['usuariowin']?></td>
				<td><b>Password Win</b></td>
				<td><?php echo $rowUsuario['passwin']?></td>
			</tr>
			<tr>
				<td><b>Usuario Sistema</b></td>
				<td><?php echo $rowUsuario['usuariosistema']?></td>
				<td><b>Password Sistema</b></td>
				<td><?php echo $rowUsuario['passsistema']?></td>
			</tr>
			<tr>
				<td><b>Puerto</b></td>
				<td><?php echo $rowUsuario['puerto']?></td>
				<td><b>Conector</b></td>
				<td><?php echo $rowUsuario['conector']?></td>
			</tr>
		</table>
		<h3>Correos</h3>
		<?php if ($canMails == 0) { ?>
			<h4 style="color: blue">No Existen correos para este usuario</h4>
		<?php } else { ?>
			<table width="600" border="1" style="text-align: center">
				<thead>
					<tr>
						<td><b>Email</b></td>
						<td><b>Clave</b></td>
					</tr>
				</thead>
				<tbody>
			<?php	while ($rowMails = mysql_fetch_assoc($resMails)) { ?>
					<tr>
						<td><?php echo $rowMails['email'] ?></td>
						<td><?php echo $rowMails['password'] ?></td>
					</tr>
			<?php   }  ?>
				</tbody>
			</table>
		<?php }  ?>
		<h3>Computadora</h3>
		<?php if ($canPc == 0) { ?>
			<h4 style="color: blue">No Existen Computadoras para este usuario</h4>
		<?php } else { 
					while ($rowPc = mysql_fetch_assoc($resPc)) { ?>				
						<table width="800" border="1" style="text-align: left">
							<tr>
								<td><b>Nombre</b></td>
								<td><?php echo $rowPc['nombre'] ?></td>
								<td><b>Nº Serie</b></td>
								<td><?php echo $rowPc['numeroserie']?></td>
							</tr>
							<tr>
								<td><b>Descripcion</b></td>
								<td colspan="3"><?php echo $rowPc['descripcion']?></td>
							</tr>
							<tr>
								<td><b>Activo</b></td>
								<td colspan="3"><?php if ($rowPc['activo'] == 1) { echo "SI"; } else { echo "NO - Fecha Baja: ".invertirFecha($rowPc['fechabaja']); } ?></td>
							</tr>
						</table>
						<br></br>
			<?php  }	
		    } ?>
		<?php if (isset($_GET['eli'])) { ?>
				<p><input type="button" name="eliminar" value="Eliminar" onclick="location.href ='eliminarUsuario.php?id=<?php echo $_GET ['id']?>'" /></p>
		<?php } else { ?>
				<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
		<?php }  ?>
	</div>
</body>
</html>