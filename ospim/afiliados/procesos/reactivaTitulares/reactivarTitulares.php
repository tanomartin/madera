<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
include ($libPath . "fechas.php");
set_time_limit(0);

//var_dump($_POST);

$wherein = "(";
foreach ( $_POST as $value ) {
	$valueArray = explode("|",$value);
	$cuitAlta[$valueArray[0]] = $valueArray[1];
	$wherein .= "'" . $valueArray[0] . "',";
}
$wherein = substr ( $wherein, 0, - 1 );
$wherein .= ")";

$fechaeModif= date ( "Y-m-d H:i:s" );
$usuarioModif = $_SESSION ['usuario'];

$arraySqlReactiva = array ();
$arrayInforme = array ();

$sqlAlta = "SELECT * FROM titularesdebaja  WHERE cuil IN " . $wherein;
$resAlta = mysql_query ( $sqlAlta, $db );

while ( $rowAlta = mysql_fetch_assoc ( $resAlta ) ) {
	
	$carnet = 1;
	$fechacarnet = $rowAlta['fechabaja'];
	if ($rowAlta['cantidadcarnet'] == 0) {
		$carnet = $rowAlta['cantidadcarnet'];
		$fechacarnet = $rowAlta['fechacarnet'];
	}
	
	$cuitEmpresa = $cuitAlta[$rowAlta['cuil']];
	$sqlJurisdiccion = "SELECT codidelega from jurisdiccion WHERE cuit = ".$cuitEmpresa. "order by disgdinero DESC LIMIT 1";
	print($sqlJurisdiccion."<br>");
	$resJurisdiccion = mysql_query ( $sqlJurisdiccion, $db );
	$rowJurisdiccion = mysql_fetch_assoc ( $resJurisdiccion );
	$codidelega = $rowJurisdiccion['codidelega'];
	
	//'".$rowBajar['foto']."', -> ¿¿¿¿FOTO????
	$sqlReactiva = "INSERT INTO titulares VALUE(
					'".$rowAlta['nroafiliado']."',
					'".$rowAlta['apellidoynombre']."',
					'".$rowAlta['tipodocumento']."',
					'".$rowAlta['nrodocumento']."',
					'".$rowAlta['fechanacimiento']."',
					'".$rowAlta['nacionalidad']."',
					'".$rowAlta['sexo']."',
					'".$rowAlta['estadocivil']."',
					'".$rowAlta['codprovin']."',
					'".$rowAlta['indpostal']."',
					'".$rowAlta['numpostal']."',
					'".$rowAlta['alfapostal']."',
					'".$rowAlta['codlocali']."',
					'".$rowAlta['domicilio']."',
					'".$rowAlta['ddn']."',
					'".$rowAlta['telefono']."',
					'".$rowAlta['email']."',
					'".$rowAlta['fechaobrasocial']."',
					'".$rowAlta['tipoafiliado']."',
					'".$rowAlta['solicitudopcion']."',
					'".$rowAlta['situaciontitularidad']."',
					'".$rowAlta['discapacidad']."',
					'".$rowAlta['certificadodiscapacidad']."',
					'".$rowAlta['cuil']."',
					'".$cuitEmpresa."',
					'".$rowAlta['fechaempresa']."',
					'".$codidelega."',
					'".$rowAlta['categoria']."',
					'".$rowAlta['emitecarnet']."',
					'".$carnet."',
					'".$fechacarnet."',
					'".$rowAlta['lote']."',
					'".$rowAlta['tipocarnet']."',
					'".$rowAlta['vencimientocarnet']."',
					'1',
					'A',
					'".$rowAlta['fechainformesss']."',
					'".$rowAlta['usuarioinformesss']."',
					'',		
					'".$rowAlta['fecharegistro']."',
					'".$rowAlta['usuarioregistro']."',
					'".$fechaeModif."',
					'".$usuarioModif."',
					'".$rowAlta['mirroring']."')";
	$arraySqlReactiva[$rowAlta ['nroafiliado']] = $sqlReactiva;
	$arrayInforme[$rowAlta ['nroafiliado']] = array('nroafiliado'=>$rowAlta['nroafiliado'],
													 'apellidoynombre'=>$rowAlta['apellidoynombre'],
													 'codidelega'=>$codidelega,
													 'cuil'=>$rowAlta['cuil'],
													 'cuitempresa'=>$cuitEmpresa);
}

$sqlDeleteTitu = "DELETE FROM titularesdebaja WHERE cuil IN $wherein";

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	foreach ($arraySqlReactiva as $altaSql) {
		print($altaSql."<br>");
		//$dbh->exec($altaSql);
	}	
	unset($arraySqlReactiva);
	
	print($sqlDeleteTitu."<br>");
	//$dbh->exec($sqlDeleteTitu);
	
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
		$("#tabla")
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
		}),

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
		<p><span class="Estilo2">Informe Reactivación Masiva</span></p>
		<p><span class="Estilo2">"<?php echo $fechaeModif?>"</span></p>
		<p><span class="Estilo2">TITULARES</span></p>
		<table style="text-align: center; width: 800px" id="tabla" class="tablesorter">
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
					<th>C.U.I.L.</th>
					<th>Apellido y Nombre</th>
					<th>C.U.I.T. Empresa</th>
				</tr>
			</thead>
			<tbody>
			 <?php foreach ($arrayInforme as $baja) { ?>
		           	<tr>
						<td><?php echo $baja['nroafiliado'] ?></td>
						<td><?php echo $baja['codidelega'] ?></td>
						<td><?php echo $baja['cuil']   ?></td>
						<td><?php echo $baja['apellidoynombre']   ?></td>
						<td><?php echo $baja['cuitempresa']   ?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		<p><input class="nover" type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	</div>
</body>
</html>