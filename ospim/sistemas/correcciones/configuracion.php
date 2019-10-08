<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php");

$sqlDeptosModulos = "SELECT * FROM modulosdptos";
$resDeptosModulos = mysql_query($sqlDeptosModulos,$db); 
$arrayRelaciones = array();
while ($rowDptosModulos = mysql_fetch_assoc($resDeptosModulos)) {
	$index = $rowDptosModulos['iddpto']."-".$rowDptosModulos['idmodulo'];
	$arrayRelaciones[$index] = $index;
}

$sqlModulos = "SELECT * FROM modulos";
$resModulos = mysql_query($sqlModulos,$db);
$arrayModulos = array();
while ($rowModulos = mysql_fetch_assoc($resModulos)) {
	$arrayModulos[$rowModulos['id']] = $rowModulos['nombre'];
}

$sqlDptos = "SELECT * FROM departamentos";
$resDptos =  mysql_query($sqlDptos,$db);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<title>.: Módulo Empresas :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css"/>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script language="javascript" type="text/javascript">

function validar(formulario) {
	$.blockUI({ message: "<h1>Guardando Configuracion de Modulos de Correccion</h1>" });
	formulario.guardar.disabled  = true;
	return true
}

</script>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuCorrecciones.php'"/> </p>
	<h3>Configuracion de Modulos y Departamentos</h3>
	<form method="post" id="configuracion" name="configuracion" onsubmit="return validar(this)" action="configuracionGuardar.php" >
<?php   while ($rowDtpos = mysql_fetch_assoc($resDptos)) { ?>
		<div class="grilla" style="margin-bottom: 15px">
			<table>
				<thead>
					<tr>
						<th colspan="2" align="center"><?php echo $rowDtpos['nombre'] ?></th>
					</tr>
				</thead>
				<tbody>
		<?php	foreach ($arrayModulos as $idModulos => $modulos) { ?>
					<tr>
						<td><?php echo $modulos ?></td>
						<td>
				<?php		$indexBusqueda = $rowDtpos['id']."-".$idModulos;
							if (array_key_exists($indexBusqueda,$arrayRelaciones)) { ?>
								<input type="checkbox" name="<?php echo $indexBusqueda?>" id="<?php echo $indexBusqueda?>" value="<?php echo $indexBusqueda?>" checked="checked" />
				<?php		} else { ?>
								<input type="checkbox" name="<?php echo $indexBusqueda?>" id="<?php echo $indexBusqueda?>" value="<?php echo $indexBusqueda?>" />
				<?php		} ?>
						</td>
					</tr>
		<?php	}  ?>
				</tbody>
			</table>
		</div>
<?php	} 	?>
		<input type="submit" id="guardar" name="guardar" value="Guardar"/>
	</form>
</div>
</body>
</html>