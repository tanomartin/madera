<?php

$libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
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
$fecha = $anio . "-" . $mes . "-01";
$fechaLimite = date ( 'Y-m-j', strtotime ( '+1 month', strtotime ( $fecha ) ) );
$maquina = $_SERVER ['SERVER_NAME'];
$carpeta = $mes . $anio;

if (strcmp ( "localhost", $maquina ) == 0) {
	$direArc = $_SERVER ['DOCUMENT_ROOT'] . "/madera/ospim/sistemas/padrones/archivos/" . $carpeta;
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

for($f = 0; $f < $finalFor; $f ++) {
	$presta = $datos [$f];
	$descriError = "CREACION Y SUBIDA DE PADRON CORRECTA";
	$arrayResultados [$presta] = array (
			'presta' => $presta,
			'descri' => $descriError 
	);
	
	$nomExcelTitu = $presta . "T" . $mes . $anio . ".xls";
	$direCompletaTitulares = $direArc . "/" . $nomExcelTitu;
	
	$nomExcelFami = $presta . "F" . $mes . $anio . ".xls";
	$direCompletaFamiliares = $direArc . "/" . $nomExcelFami;
	
	$nomTxtTeso = $presta . "D" . $mes . $anio . ".txt";
	$direCompletaTesoreria = $direArc . "/" . $nomTxtTeso;
	
	$nomZip = $presta . $mes . $anio . ".zip";
	$direCompletaZip = $direArc . "/" . $nomZip;
	
	try {
		if ($presta != '009') {
			require ("padronComun.php");
		}
		if ($presta == '009') {
			require ("padronEspecial.php");
		}
		
		// CONTROLO QUE NO HAYA UNA BAJADA PARA ESTE PRESTA Y ESTE PERIDOD
		if (strcmp ( "localhost", $maquina ) == 0) {
			$hostOspim = "localhost";
		}
		$dbhInternet = new PDO ( "mysql:host=$hostOspim;dbname=$baseOspimPrestadores", $usuarioOspim, $claveOspim );
		$dbhInternet->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$dbhInternet->beginTransaction ();
		
		$hostname = $_SESSION ['host'];
		$dbname = $_SESSION ['dbname'];
		$dbh = new PDO ( "mysql:host=$hostname;dbname=$dbname", $_SESSION ['usuario'], $_SESSION ['clave'] );
		$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$dbh->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$dbh->beginTransaction ();
		
		$sqlConsultaBajada = "SELECT count(*) FROM descarga WHERE codigo = $presta and anopad = $anio and mespad = $mes";
		// print($sqlConsultaBajada."<br>");
		$resConsultaBajada = $dbhInternet->query ( $sqlConsultaBajada );
		$canConsultaBajada = $resConsultaBajada->fetchColumn ();
		
		if ($canConsultaBajada == 0) {
			if (file_exists ( $direCompletaZip )) {
				$carpetaFtp = $presta . "C23" . $presta;
				$pathOspim = "/public_html/prestadores/$carpetaFtp";
				$resultado = SubirArchivo ( $direCompletaZip, $nomZip, $pathOspim );
				if ($resultado) {
					$subidaOk = 1;
					$fecsub = date ( 'Y-m-j' );
					$horsub = date ( "H:i:s" );
					
					$sqlEliminaSubidaInternet = "DELETE FROM subida WHERE codigo = '$presta' and anopad = $anio and mespad = $mes";
					// print($sqlEliminaSubidaInternet."<br>");
					$dbhInternet->exec ( $sqlEliminaSubidaInternet );
					
					$sqlEliminaSubidaMadera = "DELETE FROM subidapadroncapitados WHERE codigoprestador = '$presta' and anopadron = $anio and mespadron = $mes";
					// print($sqlEliminaSubidaMadera."<br>");
					$dbh->exec ( $sqlEliminaSubidaMadera );
					
					$sqlEliminaDetalleMadera = "DELETE FROM detallepadroncapitados WHERE codigoprestador = '$presta' and anopadron = $anio and mespadron = $mes";
					// print($sqlEliminaDetalleMadera."<br>");
					$dbh->exec ( $sqlEliminaDetalleMadera );
					
					$sqlEliminaInformeGlobalMadera = "DELETE FROM capitadosinforme WHERE codigocapitado = '$presta' and anopadron = $anio and mespadron = $mes";
					// print($sqlEliminaDetalleMadera."<br>");
					$dbh->exec ( $sqlEliminaInformeGlobalMadera );
					
					foreach ( $totalizador as $totalDele ) {
						$sqlInsertDetalle = "INSERT INTO detallepadroncapitados VALUE('$presta',$mes,$anio," . $totalDele ['delega'] . "," . $totalDele ['tottit'] . "," . $totalDele ['totfam'] . "," . $totalDele ['total'] . ")";
						// print($sqlInsertDetalle."<br>");
						$dbh->exec ( $sqlInsertDetalle );
					}
					
					foreach ( $insertInforme as $informe ) {
						$sqlEliminaInformePorAfiliado = "DELETE FROM capitadosinforme WHERE codigocapitado = '$presta' and nroafiliado = ".$informe['nroafiliado']." and nroorden = ".$informe['nroorden']." and tipoparentesco =".$informe['tipoparentesco'];
						// print($sqlEliminaInformePorAfiliado."<br>");
						$dbh->exec ( $sqlEliminaInformePorAfiliado );
						
						$sqlInforme = "INSERT INTO capitadosinforme VALUE('$presta',".$informe['nroafiliado'].",".$informe['nroorden'].",".$informe['tipoparentesco'].",".$mes.",".$anio.",'".$fecsub."')";
						// print($sqlInforme."<br>");
						$dbh->exec ( $sqlInforme );
					}
					
					$sqlInsertInternet = "INSERT INTO subida VALUE('$presta', $mes, $anio, '$fecsub', '$horsub', $totalTitulares, $totalFamiliares, $totalBeneficiarios, 'N')";
					// print($sqlInsertInternet."<br>");
					$dbhInternet->exec ( $sqlInsertInternet );
					
					$sqlInsertMadera = "INSERT INTO subidapadroncapitados VALUE('$presta',$mes,$anio,'$fecsub','$horsub',$totalTitulares,$totalFamiliares,$totalBeneficiarios)";
					// print($sqlInsertMadera."<br>");
					$dbh->exec ( $sqlInsertMadera );
					
					$dbhInternet->commit ();
					$dbh->commit ();
					
					// print("<br>");
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

// cambio la hora de secion por ahora para no perder la misma
$ahora = date ( "Y-n-j H:i:s" );
$_SESSION ["ultimoAcceso"] = $ahora;
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Generacion de Padrones :.</title>

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
</head>

<body bgcolor="#CCCCCC">
	<div align="center">
		<p class="Estilo2">
			<span style="text-align: center"> <input type="reset" name="volver"
				value="Volver" onclick="location.href = 'menuPadrones.php'" />
			</span>
		</p>
		<p class="Estilo2">Resultado del Generacion de Padrones Per�odo (<?php echo $mes." - ".$anio ?>) </p>
		<table width="800" border="1" align="center">
			<tr>
				<th>Prestador</th>
				<th>Descripcion</th>
			</tr>
			  <?php
					
foreach ( $arrayResultados as $resultado ) {
						print ("<tr align='center'>") ;
						print ("<td>" . $resultado ['presta'] . "</td>") ;
						print ("<td>" . $resultado ['descri'] . "</td>") ;
						print ("</tr>") ;
					}
					?>
  </table>
		<p>
			<input type="button" name="imprimir" value="Imprimir"
				onclick="window.print();" />
		</p>
	</div>
</body>
</html>
