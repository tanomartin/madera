<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
set_time_limit ( 0 );
ini_set ( 'memory_limit', '448M' );
include ($libPath . "controlSessionOspimSistemas.php");
include ($libPath . "fechas.php");
include ($libPath . "ftpOspim.php");
include ($libPath . "funcionesFTP.php");
include ($libPath . "claves.php");
require_once ($libPath . "phpExcel/Classes/PHPExcel.php");
function eliminarDir($carpeta) {
	foreach ( glob ( $carpeta . "/*" ) as $archivos_carpeta ) {
		if (is_dir ( $archivos_carpeta )) {
			eliminarDir ( $archivos_carpeta );
		} else {
			unlink ( $archivos_carpeta );
		}
	}
	rmdir ( $carpeta );
}

$periodo = explode ( '-', $_POST ['periodo'] );
$mes = $periodo [0];
$mes = str_pad ( $periodo [0], 2, '0', STR_PAD_LEFT );
$anio = $periodo [1];
$quincena = $periodo [2];
if ($quincena == 2) {
	$dia = "01";
	$fecha = $anio . "-" . $mes . "-" .$dia;
	$fechaLimite = date ( 'Y-m-j', strtotime ( '+1 month', strtotime ( $fecha ) ) );
	$fechaMuestra = date ( 'Y-m-j', strtotime ( '+7 day', strtotime ( $fechaLimite ) ) );
} else {
	$dia = "15";
	$fecha = $anio . "-" . $mes . "-" .$dia;
	$fechaLimite = date ( 'Y-m-j', strtotime ($fecha) );
	$fechaMuestra = date ( 'Y-m-j', strtotime ( '+7 day', strtotime ( $fechaLimite ) ) );
}

//echo $fechaLimite."<br>";
//echo $fechaMuestra."<br>";

$maquina = $_SERVER ['SERVER_NAME'];
$carpeta = $mes . $anio;

if (strcmp ( "localhost", $maquina ) == 0) {
	$direArc = "archivos/" . $carpeta;
} else {
	$direArc = "/home/sistemas/Documentos/Repositorio/Capitados/" . $carpeta;
}

// eliminarDir($direArc);
if (! file_exists ( $direArc )) {
	mkdir ( $direArc, 0777 );
}

$finalFor = sizeof ( $_POST ) - 2;
$datos = array_values ( $_POST );
$arrayResultados = array ();

$dbhInternet = new PDO ( "mysql:host=$hostOspim;dbname=$baseOspimPrestadores", $usuarioOspim, $claveOspim );
$dbhInternet->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbhInternet->beginTransaction ();

$hostname = $_SESSION ['host'];
$dbname = $_SESSION ['dbname'];
$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$dbh->beginTransaction ();

$sqlConsultaPeriodo = "SELECT * FROM periodos WHERE anopad = $anio and mespad = $mes and quincena = $quincena";
//print($sqlConsultaPeriodo."<br>");
$resConsultaPeriodo = $dbhInternet->query ( $sqlConsultaPeriodo );
$canConsultaPeriodo = $resConsultaPeriodo->fetchColumn ();
if ($canConsultaPeriodo == 0) {
	$sqlInsertarPeriodo = "INSERT INTO periodos VALUES ($anio, $mes, $quincena,'$fechaMuestra')";
	//print($sqlInsertarPeriodo."<br>");
	$dbhInternet->exec ( $sqlInsertarPeriodo );
}

for($f = 0; $f < $finalFor; $f ++) {
	$datosArray = explode("-", $datos [$f]);$datos [$f];
	$presta = $datosArray[0];
	$capitado = $datosArray[1];
	$tipo = $datosArray[2];
	$descriError = "CREACION Y SUBIDA DE PADRON CORRECTA";
	$arrayResultados [$presta] = array (
			'presta' => $presta,
			'descri' => $descriError 
	);
	
	$nomExcelTitu = $presta . "T" . $mes . $anio . $quincena .".xls";
	$direCompletaTitulares = $direArc . "/" . $nomExcelTitu;
	
	$nomExcelFami = $presta . "F" . $mes . $anio . $quincena .".xls";
	$direCompletaFamiliares = $direArc . "/" . $nomExcelFami;

	$nomZip = $presta . $mes . $anio . $quincena .".zip";
	$direCompletaZip = $direArc . "/" . $nomZip;
	
	try {
		if ($capitado == 1) {
			if ($tipo == 0) {
				require ("padronComun.php");
			}
			if ($tipo == 1) {
				require ("padronEspecial.php");
			}
		} else {
			require ("padronNoCapitado.php");
		}
		
		if (strcmp ( "poseidon", $maquina ) != 0) {
			$hostOspim = "localhost";
		}
		
		$sqlConsultaBajada = "SELECT count(*) FROM descarga WHERE codigo = $presta and anopad = $anio and mespad = $mes and quincena = $quincena";
		//print($sqlConsultaBajada."<br>");
		$resConsultaBajada = $dbhInternet->query ( $sqlConsultaBajada );
		$canConsultaBajada = $resConsultaBajada->fetchColumn ();
		
		if ($canConsultaBajada == 0) {
			if (file_exists ( $direCompletaZip )) {
				$carpetaFtp = $presta . "C23" . $presta;
				$pathOspim = "/public_html/prestadores/$carpetaFtp";
				
				$resultado = true;
				if ($hostOspim != "localhost") {
					$resultado = SubirArchivo ( $direCompletaZip, $nomZip, $pathOspim );
				} 	
				
				if ($resultado) {
					$subidaOk = 1;
					$fecsub = date ( 'Y-m-j' );
					$horsub = date ( "H:i:s" );
					
					$sqlEliminaSubidaInternet = "DELETE FROM subida WHERE codigo = '$presta' and anopad = $anio and mespad = $mes and quincena = $quincena";
					//print($sqlEliminaSubidaInternet."<br>");
					$dbhInternet->exec ( $sqlEliminaSubidaInternet );
					
					$sqlEliminaSubidaMadera = "DELETE FROM subidapadroncapitados WHERE codigoprestador = '$presta' and anopadron = $anio and mespadron = $mes and quincenapadron = $quincena";
					//print($sqlEliminaSubidaMadera."<br>");
					$dbh->exec ( $sqlEliminaSubidaMadera );
					
					$sqlEliminaDetalleMadera = "DELETE FROM detallepadroncapitados WHERE codigoprestador = '$presta' and anopadron = $anio and mespadron = $mes  and quincenapadron = $quincena";
					//print($sqlEliminaDetalleMadera."<br>");
					$dbh->exec ( $sqlEliminaDetalleMadera );
					
					$sqlEliminaInformeGlobalMadera = "DELETE FROM beneficiarioscapitados WHERE codigocapitado = '$presta' and anopadron = $anio and mespadron = $mes and quincenapadron = $quincena";
					//print($sqlEliminaDetalleMadera."<br>");
					$dbh->exec ( $sqlEliminaInformeGlobalMadera );
					
					foreach ( $totalizador as $totalDele ) {
						$sqlInsertDetalle = "INSERT INTO detallepadroncapitados VALUE('$presta', $mes, $anio, $quincena, " . $totalDele ['delega'] . "," . $totalDele ['tottit'] . "," . $totalDele ['totfam'] . "," . $totalDele ['total'] . ")";
						//print($sqlInsertDetalle."<br>");
						$dbh->exec ( $sqlInsertDetalle );
					}
					
					foreach ( $insertInforme as $informe ) {
						$sqlEliminaInformePorAfiliado = "DELETE FROM beneficiarioscapitados WHERE codigocapitado = '$presta' and nroafiliado = ".$informe['nroafiliado']." and nroorden = ".$informe['nroorden']." and tipoparentesco =".$informe['tipoparentesco'];
						//print($sqlEliminaInformePorAfiliado."<br>");
						$dbh->exec ( $sqlEliminaInformePorAfiliado );
						
						$sqlInforme = "INSERT INTO beneficiarioscapitados VALUE('$presta',".$informe['nroafiliado'].",".$informe['nroorden'].",".$informe['tipoparentesco'].",".$mes.",".$anio.",".$quincena.",'".$fecsub."')";
						//print($sqlInforme."<br>");
						$dbh->exec ( $sqlInforme );
					}
					
					$sqlInsertInternet = "INSERT INTO subida VALUE('$presta', $mes, $anio, $quincena, '$fecsub', '$horsub', $totalTitulares, $totalFamiliares, $totalBeneficiarios, 'N')";
					//print($sqlInsertInternet."<br>");
					$dbhInternet->exec ( $sqlInsertInternet );
					
					$sqlInsertMadera = "INSERT INTO subidapadroncapitados VALUE('$presta', $mes, $anio, $quincena, '$fecsub','$horsub',$totalTitulares,$totalFamiliares,$totalBeneficiarios)";
					//print($sqlInsertMadera."<br>");
					$dbh->exec ( $sqlInsertMadera );
					
				} else {
					$subidaOk = 2;
					$descriError = "ERROR AL SUBIR EL ZIP A OSPIM";
					$arrayResultados [$presta] = array (
							'presta' => $presta,
							'descri' => $descriError 
					);
					// print("$descriError<br><br>");
				}
			}
		} else {
			$descriError = "EXISTE UNA DESCARGA PARA ESTE PERIODO ($mes-$anio) Y ESTE PRESTADOR ($presta) NO SE SUBIRA NUEVAMENTE";
			$arrayResultados [$presta] = array (
					'presta' => $presta,
					'descri' => $descriError 
			);
			// print("$descriError<br><br>");
		}
	} catch ( PDOException $e ) {
		$descriError = $e->getMessage ();
		$arrayResultados [$presta] = array (
				'presta' => $presta,
				'descri' => $descriError 
		);
		// print("$descriError<br><br>");
		$dbh->rollback ();
		$dbhInternet->rollback ();
	}
}
$dbhInternet->commit ();
$dbh->commit ();

// cambio la hora de secion por ahora para no perder la misma
$ahora = date ( "Y-n-j H:i:s" );
$_SESSION ["ultimoAcceso"] = $ahora;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Generacion de Padrones :.</title>
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuPadrones.php'" /></p>
		<h3>Resultado del Generacion de Padrones Período (<?php echo $mes." - ".$anio." - ".$quincena ?>) </h3>
		<table width="800" border="1" align="center">
			<tr>
				<th>Prestador</th>
				<th>Descripcion</th>
			</tr>
			  <?php	foreach ( $arrayResultados as $resultado ) { ?>
				<tr align='center'>
					<td><?php echo $resultado ['presta'] ?></td>
					<td><?php echo $resultado ['descri'] ?></td>
				</tr>
			  <?php } ?>
  		</table>
		<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
	</div>
</body>
</html>
