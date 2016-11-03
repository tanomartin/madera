<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");

$fechamodificacion = date("Y-m-d H:i:s");
$usuariomodificacion = $_SESSION['usuario'];

$whereIn = "(";
foreach ($_POST as $cuil => $datos) {
	$arrayDatos = explode("-",$datos);
	$nroafil = $arrayDatos[0];
	$nombre = $arrayDatos[1];
	$whereIn .= "".$nroafil.",";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

if ($whereIn != ")") {
	$sqlUpdateTitulares = "UPDATE titulares SET informesss = 1, tipoinformesss = 'A', usuariomodificacion = '$usuariomodificacion', fechamodificacion = '$fechamodificacion' WHERE nroafiliado in $whereIn";
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		//echo $sqlUpdateTitulares."<br>";
		$dbh->exec($sqlUpdateTitulares);
		$dbh->commit();
	} catch(PDOException $e) {
		$error =  $e->getMessage();
		$dbh->rollback();
		$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
		header ($redire);
		exit(0);
	}
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Informe de Subida de Titulares en la SSS :.</title>

<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet" href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

$(function() {
	$("#tablaInforme")
	.tablesorter({
		theme: 'blue', 
		widthFixed: true, 
		widgets: ["zebra", "filter"], 
		widgetOptions : { 
			filter_cssFilter   : '',
			filter_childRows   : false,
			filter_hideFilters : false,
			filter_ignoreCase  : true,
			filter_searchDelay : 300,
			filter_startsWith  : false,
			filter_hideFilters : false,
		}
	});
});
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../menuCruceSSS.php'" />
		<h2>Informe de Subida de Titulares a SSS</h2>
		<h3>Titulares que subirán en la proxima actualización a la S.S.S.</h3>
		<table style="text-align: center; width: 900px" id="tablaInforme" class="tablesorter">
			<thead>
				<tr>
					<th>C.U.I.L.</th>
					<th>Nro. Afiliado</th>
					<th>Nombre</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($_POST as $cuil => $datos) { 
					$arrayDatos = explode("-",$datos);
					$nroafil = $arrayDatos[0];
					$nombre = $arrayDatos[1]; ?>
					<tr>	
						<td><?php echo $cuil ?></td>
						<td><?php echo $nroafil ?></td>
						<td><?php echo $nombre ?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		<input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
	</div>
</body>
</html>