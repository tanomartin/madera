<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
include ($libPath . "fechas.php");
set_time_limit(0);

//var_dump($_POST);echo "<br>";

$fechaBaja = date ( "Y-m-d" );
$motivoBaja = "Depuración de Padrón - Proceso automatico de Baja de Familiares";
$fechaefectivizacion = date ( "Y-m-d H:i:s" );
$usuarioefectivizacion = $_SESSION ['usuario'];

$arraySqlBajaFamiliares = array();
$arrayInformeFamiliares = array();
$arraySqlDeleteFamiliares = array();

foreach ( $_POST as $value ) {
    $valueArray = explode ("-",$value);
	$nroafiliado = $valueArray[0];
    $nroorden = $valueArray[1];
    //echo $nroafiliado." - ".$nroorden."<br><br><br>";
    
    $sqlBajarFami = "SELECT * FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
    //echo $sqlBajarFami;echo "<br>";
    $resBajarFami = mysql_query ( $sqlBajarFami, $db );
    $rowBajarFami = mysql_fetch_assoc ( $resBajarFami );
    
	//'".$rowBajarFami['foto']."', -> ¿¿¿¿FOTO????
	$sqlBajaFamilia = "INSERT INTO familiaresdebaja VALUE(
						'".$rowBajarFami['nroafiliado']."',
						'".$rowBajarFami['nroorden']."',
						'".$rowBajarFami['tipoparentesco']."',
						'".addslashes($rowBajarFami['apellidoynombre'])."',
						'".$rowBajarFami['tipodocumento']."',
						'".$rowBajarFami['nrodocumento']."',
						'".$rowBajarFami['fechanacimiento']."',
						'".addslashes($rowBajarFami['nacionalidad'])."',
						'".$rowBajarFami['sexo']."',
						'".$rowBajarFami['ddn']."',
						'".$rowBajarFami['telefono']."',
						'".$rowBajarFami['email']."',
						'".$rowBajarFami['fechaobrasocial']."',
						'".$rowBajarFami['discapacidad']."',
						'".$rowBajarFami['certificadodiscapacidad']."',
						'".$rowBajarFami['estudia']."',
						'".$rowBajarFami['certificadoestudio']."',
						'".$rowBajarFami['emisioncertificadoestudio']."',
						'".$rowBajarFami['vencimientocertificadoestudio']."',
						'".$rowBajarFami['cuil']."',
						'".$rowBajarFami['emitecarnet']."',
						'".$rowBajarFami['cantidadcarnet']."',
						'".$rowBajarFami['fechacarnet']."',
						'".$rowBajarFami['lote']."',
						'".$rowBajarFami['tipocarnet']."',
						'".$rowBajarFami['vencimientocarnet']."',
						'".$rowBajarFami['informesss']."',
						'".$rowBajarFami['tipoinformesss']."',
						'".$rowBajarFami['fechainformesss']."',	
						'".$rowBajarFami['usuarioinformesss']."',
						'',
						'".$rowBajarFami['fecharegistro']."',
						'".$rowBajarFami['usuarioregistro']."',
						'".$rowBajarFami['fechamodificacion']."',	
						'".$rowBajarFami['usuariomodificacion']."',
						'".$rowBajarFami['mirroring']."',				
						'".$fechaBaja."','".$motivoBaja."','".$fechaefectivizacion."','".$usuarioefectivizacion."')";
	$arraySqlBajaFamiliares[$rowBajarFami['nroafiliado'].$rowBajarFami['nroorden']] = $sqlBajaFamilia;
	$arrayInformeFamiliares[$rowBajarFami['nroafiliado'].$rowBajarFami['nroorden']] = array('nroafiliado'=>$rowBajarFami['nroafiliado'],
																							'apellidoynombre'=>$rowBajarFami['apellidoynombre'],
																							'tipoparentesco'=>$rowBajarFami['tipoparentesco'],
																							'cuil'=>$rowBajarFami['cuil'],
																							'fechaBaja'=>$fechaBaja);
	$sqlDeleteFami = "DELETE FROM familiares WHERE nroafiliado = $nroafiliado and nroorden = $nroorden";
	$arraySqlDeleteFamiliares[$rowBajarFami['nroafiliado'].$rowBajarFami['nroorden']] = $sqlDeleteFami;

}

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	foreach ($arraySqlBajaFamiliares as $bajaSqlFami) {
		print($bajaSqlFami."<br>");
		$dbh->exec($bajaSqlFami);
	}
	unset($arraySqlBajaFamiliares);
	
	foreach ($arraySqlDeleteFamiliares as $deleteSql) {
		print($deleteSql."<br>");
		$dbh->exec($deleteSql);
	}	
	unset($arraySqlDeleteFamiliares);

	$dbh->commit();

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

$ahora = date("Y-n-j H:i:s");
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Informe Baja Filtro de Titualres :.</title>

<style>
A:link {
	text-decoration: none;
	color: #0033FF
}

A:visited {
	text-decoration: none
}

A:hover {
	text-decoration: none;
	color: #00FFFF
}

.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {
	display: none
}
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<link rel="stylesheet"
	href="/madera/lib/jquery.tablesorter/themes/theme.blue.css" />
<script src="/madera/lib/jquery.tablesorter/jquery.tablesorter.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/jquery.tablesorter.widgets.js"></script>
<script
	src="/madera/lib/jquery.tablesorter/addons/pager/jquery.tablesorter.pager.js"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">

	$(function() {
		$("#tablafami")
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
		})
	});
	
</script>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" class="nover" onclick="location.href = '../moduloProcesos.php'" /></p>
		<p><span class="Estilo2">Informe Baja Filtro</span></p>
		<p><span class="Estilo2">"<?php echo $fechaefectivizacion?>"</span></p>
		<p><span class="Estilo2">FAMILIARES</span></p>
		<table style="text-align: center; width: 800px" id="tablafami" class="tablesorter">
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th>C.U.I.L.</th>
					<th>Apellido y Nombre</th>
					<th>Parentesco</th>
					<th>Fecha Baja</th>
				</tr>
			</thead>
			<tbody>
			 <?php foreach ($arrayInformeFamiliares as $bajaFamilia) { ?>
		           	<tr>
						<td><?php echo $bajaFamilia['nroafiliado'] ?></td>
						<td><?php echo $bajaFamilia['cuil']   ?></td>
						<td><?php echo $bajaFamilia['apellidoynombre']   ?></td>
						<td><?php echo $bajaFamilia['tipoparentesco']   ?></td>
						<td><?php echo invertirFecha($bajaFamilia['fechaBaja'])   ?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		
		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	</div>
</body>
</html>