<?php
$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include ($libPath . "controlSessionOspim.php");
include ($libPath . "fechas.php");

function majorFecha($fechaDDJJ, $fechaPago, $fechaDesempleo) {
	if (($fechaDDJJ>=$fechaPago) and ($fechaDDJJ>=$fechaDesempleo)) { 
		return $fechaDDJJ;
	} 
	if (($fechaPago>=$fechaDDJJ) and ($fechaPago>=$fechaDesempleo)) { 
		return $fechaPago;
	} 
	if (($fechaDesempleo>=$fechaPago) and ($fechaDesempleo>=$fechaDDJJ)) { 
		return $fechaDesempleo;
	}  
}

$wherein = "(";
foreach ( $_POST as $value ) {
	$wherein .= "'" . $value . "',";
}
$wherein = substr ( $wherein, 0, - 1 );
$wherein .= ")";

$sqlDDJJ = "SELECT cuil, anoddjj, mesddjj FROM detddjjospim d where cuil in $wherein order by cuil, anoddjj ASC ,mesddjj ASC"; 
//echo $sqlDDJJ . "<br>";
$resDDJJ = mysql_query ( $sqlDDJJ, $db );
while ( $rowDDJJ = mysql_fetch_assoc ( $resDDJJ ) ) {
	$fecha = $rowDDJJ['anoddjj']."-".$rowDDJJ['mesddjj']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaDDJJ[$rowDDJJ['cuil']] = date ( 'Y-m-j' , $fecha );
}
var_dump($fechaDDJJ);echo"<br><br>";

$sqlPagos = "SELECT cuil, anopago, mespago FROM afiptransferencias d where cuil in $wherein order by cuil, anopago ASC ,mespago ASC";
//echo $sqlPagos . "<br>";
$resPagos = mysql_query ( $sqlPagos, $db );
while ( $rowPagos = mysql_fetch_assoc ( $resPagos ) ) {
	$fecha = $rowPagos['anopago']."-".$rowPagos['mespago']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaPago[$rowPagos['cuil']] = date ( 'Y-m-j' , $fecha );
}
var_dump($fechaPago);echo"<br><br>";

$sqlDesempleo = "SELECT cuilbeneficiario, anodesempleo, mesdesempleo FROM desempleosss d where cuilbeneficiario in $wherein order by cuilbeneficiario, anodesempleo ASC ,mesdesempleo ASC";
//echo $sqlDesempleo . "<br>";
$resDesempleo = mysql_query ( $sqlDesempleo, $db );
while ( $rowDesempleo = mysql_fetch_assoc ( $resDesempleo ) ) {
	$fecha = $rowDesempleo['anodesempleo']."-".$rowDesempleo['mesdesempleo']."-1";
	$fecha = strtotime ( '+1 month' , strtotime ($fecha)) ;
	$fecha = strtotime ( '-1 day' , strtotime (date ( 'Y-m-j' , $fecha ))) ;
	$fechaDesempleo[$rowDesempleo['cuilbeneficiario']] = date ( 'Y-m-j' , $fecha );
}
var_dump($fechaDesempleo);echo"<br><br>";


foreach ( $_POST as $value ) {
	$arrayFechasBaja[$value] = majorFecha($fechaDDJJ[$value],$fechaPago[$value],$fechaDesempleo[$value]);
}
unset($fechaDDJJ);
unset($fechaPago);
unset($fechaDesempleo);

$motivoBaja = "Filtro Titulares";
$fechaefectivizacion = date ( "Y-m-d H:i:s" );
$usuarioefectivizacion = $_SESSION ['usuario'];

$arraySqlBaja = array ();
$arrayInforme = array ();

$sqlBajar = "SELECT * FROM titulares  WHERE cuil IN " . $wherein;
$resBajar = mysql_query ( $sqlBajar, $db );

$whereinfamilia = "(";
while ( $rowBajar = mysql_fetch_assoc ( $resBajar ) ) {
	
	$fechaBaja = $arrayFechasBaja[$rowBajar['cuil']];
	$arrayFechaBajaFamiliar[$rowBajar['nroafiliado']] = $fechaBaja;
	
	$whereinfamilia .= "'" . $rowBajar['nroafiliado'] . "',";
	
	if ($fechaBaja == NULL) {
		//VER QUE PONEMOS
		$fechaBaja = "1000-01-01";
	}
	
	//'".$rowBajar['foto']."', -> 真真FOTO????
	$sqlBaja = "INSERT INTO titularesdebaja VALUE(
					'".$rowBajar['nroafiliado']."',
					'".$rowBajar['apellidoynombre']."',
					'".$rowBajar['tipodocumento']."',
					'".$rowBajar['nrodocumento']."',
					'".$rowBajar['fechanacimiento']."',
					'".$rowBajar['nacionalidad']."',
					'".$rowBajar['sexo']."',
					'".$rowBajar['estadocivil']."',
					'".$rowBajar['codprovin']."',
					'".$rowBajar['indpostal']."',
					'".$rowBajar['numpostal']."',
					'".$rowBajar['alfapostal']."',
					'".$rowBajar['codlocali']."',
					'".$rowBajar['domicilio']."',
					'".$rowBajar['ddn']."',
					'".$rowBajar['telefono']."',
					'".$rowBajar['email']."',
					'".$rowBajar['fechaobrasocial']."',
					'".$rowBajar['tipoafiliado']."',
					'".$rowBajar['solicitudopcion']."',
					'".$rowBajar['situaciontitularidad']."',
					'".$rowBajar['discapacidad']."',
					'".$rowBajar['certificadodiscapacidad']."',
					'".$rowBajar['cuil']."',
					'".$rowBajar['cuitempresa']."',
					'".$rowBajar['fechaempresa']."',
					'".$rowBajar['codidelega']."',
					'".$rowBajar['categoria']."',
					'".$rowBajar['emitecarnet']."',
					'".$rowBajar['cantidadcarnet']."',
					'".$rowBajar['fechacarnet']."',
					'".$rowBajar['lote']."',
					'".$rowBajar['tipocarnet']."',
					'".$rowBajar['vencimientocarnet']."',
					'".$rowBajar['informesss']."',
					'".$rowBajar['tipoinformesss']."',
					'".$rowBajar['fechainformesss']."',
					'".$rowBajar['usuarioinformesss']."',
					'',		
					'".$rowBajar['fecharegistro']."',
					'".$rowBajar['usuarioregistro']."',
					'".$rowBajar['fechamodificacion']."',
					'".$rowBajar['usuariomodificacion']."',
					'".$rowBajar['mirroring']."',
					'".$fechaBaja."','".$motivoBaja."','".$fechaefectivizacion."','".$usuarioefectivizacion."')";
	$arraySqlBaja[$rowBajar ['nroafiliado']] = $sqlBaja;
	$arrayInforme[$rowBajar ['nroafiliado']] = array('nroafiliado'=>$rowBajar['nroafiliado'],
													 'apellidoynombre'=>$rowBajar['apellidoynombre'],
													 'codidelega'=>$rowBajar['codidelega'],
													 'cuil'=>$rowBajar['cuil'],
													 'cuitempresa'=>$rowBajar['cuitempresa'],
													 'fechaBaja'=>$fechaBaja);
}
$whereinfamilia = substr ( $whereinfamilia, 0, - 1 );
$whereinfamilia .= ")";

unset($arrayFechasBaja);
$sqlDeleteTitu = "DELETE FROM titulares WHERE cuil IN $wherein";

$arraySqlBajaFamiliares = array ();
$arrayInformeFamiliares = array ();

$sqlBajarFami = "SELECT * FROM familiares WHERE nroafiliado IN $whereinfamilia";
//echo $sqlBajarFami;echo "<br>";
$resBajarFami = mysql_query ( $sqlBajarFami, $db );
while ( $rowBajarFami = mysql_fetch_assoc ( $resBajarFami ) ) {
	$fechaBajaFami = $arrayFechaBajaFamiliar[$rowBajarFami['nroafiliado']];
	if ($fechaBajaFami == NULL) {
		//VER QUE PONEMOS
		$fechaBajaFami = "1000-01-01";
	}
	//'".$rowBajarFami['foto']."', -> 真真FOTO????
	$sqlBajaFamilia = "INSERT INTO familiaresdebaja VALUE(
						'".$rowBajarFami['nroafiliado']."',
						'".$rowBajarFami['nroorden']."',
						'".$rowBajarFami['tipoparentesco']."',
						'".$rowBajarFami['apellidoynombre']."',
						'".$rowBajarFami['tipodocumento']."',
						'".$rowBajarFami['nrodocumento']."',
						'".$rowBajarFami['fechanacimiento']."',
						'".$rowBajarFami['nacionalidad']."',
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
						'".$fechaBajaFami."','".$motivoBaja."','".$fechaefectivizacion."','".$usuarioefectivizacion."')";
	$arraySqlBajaFamiliares[$rowBajarFami['nroafiliado'].$rowBajarFami['nroorden']] = $sqlBajaFamilia;
	$arrayInformeFamiliares[$rowBajarFami['nroafiliado'].$rowBajarFami['nroorden']] = array('nroafiliado'=>$rowBajarFami['nroafiliado'],
																							'apellidoynombre'=>$rowBajarFami['apellidoynombre'],
																							'tipoparentesco'=>$rowBajarFami['tipoparentesco'],
																							'cuil'=>$rowBajarFami['cuil'],
																							'fechaBaja'=>$fechaBajaFami);
}
unset($arrayFechaBajaFamiliar);
$sqlDeleteFami = "DELETE FROM familiares WHERE nroafiliado IN $whereinfamilia";


try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	foreach ($arraySqlBajaFamiliares as $bajaSqlFami) {
		//print($bajaSqlFami."<br>");
		//$dbh->exec($bajaSqlFami);
	}
	unset($arraySqlBajaFamiliares);
	
	foreach ($arraySqlBaja as $bajaSql) {
		//print($bajaSql."<br>");
		//$dbh->exec($bajaSql);
	}	
	unset($arraySqlBaja);
	
	//print($sqlDeleteFami."<br>");
	//$dbh->exec($sqlDeleteFami);
	
	//print($sqlDeleteTitu."<br>");
	//$dbh->exec($sqlDeleteTitu);
	
	$dbh->commit();

}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}

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
		<p><span class="Estilo2">Informe Baja Filtro</span></p>
		<p><span class="Estilo2">"<?php echo $fechaefectivizacion?>"</span></p>
		<p><span class="Estilo2">TITULARES</span></p>
		<table style="text-align: center; width: 800px" id="tabla" class="tablesorter">
			<thead>
				<tr>
					<th>Nro. Afiliado</th>
					<th class="filter-select" data-placeholder="Seleccione Delegacion">Delegacion</th>
					<th>C.U.I.L.</th>
					<th>Apellido y Nombre</th>
					<th>C.U.I.T. Empresa</th>
					<th>Fecha Baja</th>
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
						<td><?php echo invertirFecha($baja['fechaBaja'])   ?></td>
					</tr>
			<?php } ?>
			</tbody>
		</table>
		
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