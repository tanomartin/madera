<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$archivo = "Seguimiento.txt";
$archivoUsimra = "SegUSIMRA.txt";
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0) {
	$direArc = "liqui/".$archivo;
	$direArcUsimra = "liqui/".$archivoUsimra;
} else {
	$direArc = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$archivo;
	$direArcUsimra = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$archivoUsimra;
}

$liqCargadas = array();
$sqlInserciones = array();
if (file_exists($direArc)) {
	$file = fopen($direArc, "r") or exit("Falla al abrir el archivo OSPIM!");
	$r = 0;
	$n = 0;
	while(!feof($file)) {
		$linea = trim(fgets($file));
		if (strlen($linea) > 0) {
			$acuAbs = "";
			$linea = str_replace('"','',$linea);
			$campos = explode("|",$linea);
			$nroreq = (int)$campos[0];
			
			$sqlCuit = "SELECT cuit FROM reqfiscalizospim where nrorequerimiento = $nroreq";
			$resCuit = mysql_query($sqlCuit,$db);
			$rowCuit = mysql_fetch_array($resCuit);
			$cuit = $rowCuit['cuit'];

			$sqlInserciones[$r] = "DELETE FROM aculiquiospim where nrorequerimiento = $nroreq";
			$r++;
			
			$sqlAcuerdos = "SELECT nroacuerdo FROM aculiquiospim where nrorequerimiento = $nroreq";
			$resAcuerdos = mysql_query($sqlAcuerdos,$db);
			while ($rowAcuerdos = mysql_fetch_array($resAcuerdos)) {
				$nroacuActivar = $rowAcuerdos['nroacuerdo'];
				$sqlInserciones[$r] = "UPDATE cabacuerdosospim SET estadoacuerdo = 1 WHERE cuit = $cuit and nroacuerdo = $nroacuActivar";
				$r++;
			}
		
			if (strlen($campos[10]) > 0) {
				$acuerdos = explode("-",$campos[10]);	
				for ($i=0; $i<sizeof($acuerdos); $i++) {
					$nroacu = (int)$acuerdos[$i];
					$sqlInserciones[$r] = "INSERT into aculiquiospim VALUE($nroreq, $nroacu)";
					$r++;
					$sqlInserciones[$r] = "UPDATE cabacuerdosospim SET estadoacuerdo = 5 WHERE cuit = $cuit and nroacuerdo = $nroacu";
					$r++;
				}
			}
			$fechaliq = fechaParaGuardar($campos[1]);
			$horaliq = $campos[2];
			$fecinsp = fechaParaGuardar($campos[3]);
			$totdep = (float)$campos[4];
			$totint = (float)$campos[5];
			$gastos = (float)$campos[6];
			$totliq = (float)$campos[7];
			$nroreso = (int)$campos[8];
			$nrosert = (int)$campos[9];
			$operador = $campos[11];
			$liqCargadas[$n] = array('reque' => $nroreq);
			$sqlInserciones[$r] = "UPDATE cabliquiospim SET fechaliquidacion = '$fechaliq', horaliquidacion = '$horaliq', fechainspeccion = '$fecinsp', deudanominal = $totdep, intereses =  $totint, gtosadmin = $gastos, totalliquidado = $totliq, nroresolucioninspeccion = $nroreso, nrocertificadodeuda = $nrosert, operadorliquidador = '$operador' WHERE nrorequerimiento = $nroreq";
			$r++;
			$n++;
		}
	}
	fclose($file);
}

$liqCargadasUsimra = array();
$sqlInsercionesUsimra = array();
if (file_exists($direArcUsimra)) {
	$file = fopen($direArcUsimra, "r") or exit("Falla al abrir el archivo USIMRA!");
	$r = 0;
	$n = 0;
	while(!feof($file)) {
		$linea = trim(fgets($file));
		if (strlen($linea) > 0) {
			$acuAbs = "";
			$linea = str_replace('"','',$linea);
			$campos = explode("|",$linea);
			$nroreq = (int)$campos[0];
				
			$sqlCuit = "SELECT cuit FROM reqfiscalizusimra where nrorequerimiento = $nroreq";
			$resCuit = mysql_query($sqlCuit,$db);
			$rowCuit = mysql_fetch_array($resCuit);
			$cuit = $rowCuit['cuit'];

			$sqlInsercionesUsimra[$r] = "DELETE FROM aculiquiusimra where nrorequerimiento = $nroreq";
			$r++;
				
			$sqlAcuerdos = "SELECT nroacuerdo FROM aculiquiusimra where nrorequerimiento = $nroreq";
			$resAcuerdos = mysql_query($sqlAcuerdos,$db);
			while ($rowAcuerdos = mysql_fetch_array($resAcuerdos)) {
				$nroacuActivar = $rowAcuerdos['nroacuerdo'];
				$sqlInsercionesUsimra[$r] = "UPDATE cabacuerdosusimra SET estadoacuerdo = 1 WHERE cuit = $cuit and nroacuerdo = $nroacuActivar";
				$r++;
			}

			if (strlen($campos[10]) > 0) {
				$acuerdos = explode("-",$campos[10]);
				for ($i=0; $i<sizeof($acuerdos); $i++) {
					$nroacu = (int)$acuerdos[$i];
					$sqlInsercionesUsimra[$r] = "INSERT into aculiquiusimra VALUE($nroreq, $nroacu)";
					$r++;
					$sqlInsercionesUsimra[$r] = "UPDATE cabacuerdosusimra SET estadoacuerdo = 5 WHERE cuit = $cuit and nroacuerdo = $nroacu";
					$r++;
				}
			}
			$fechaliq = fechaParaGuardar($campos[1]);
			$horaliq = $campos[2];
			$fecinsp = fechaParaGuardar($campos[3]);
			$totdep = (float)$campos[4];
			$totint = (float)$campos[5];
			$gastos = (float)$campos[6];
			$totliq = (float)$campos[7];
			$nroreso = (int)$campos[8];
			$nrosert = (int)$campos[9];
			$operador = $campos[11];
			$liqCargadasUsimra[$n] = array('reque' => $nroreq);
			$sqlInsercionesUsimra[$r] = "UPDATE cabliquiusimra SET fechaliquidacion = '$fechaliq', horaliquidacion = '$horaliq', fechainspeccion = '$fecinsp', deudanominal = $totdep, intereses =  $totint, gtosadmin = $gastos, totalliquidado = $totliq, nroresolucioninspeccion = $nroreso, nrocertificadodeuda = $nrosert, operadorliquidador = '$operador' WHERE nrorequerimiento = $nroreq";
			$r++;
			$n++;
		}
	}
	fclose($file);
}

//INSERTAMOS A LA BASE LOS CIERRES
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	
	//OSPIM
	foreach($sqlInserciones as $sqlospim) {
		$dbh->exec($sqlospim);
	}
	
	//USIMRA
	foreach($sqlInsercionesUsimra as $sqlusimra) {
		$dbh->exec($sqlusimra);
	}
	
	$dbh->commit();
	if (file_exists($direArc)) unlink($direArc);
	if (file_exists($direArcUsimra)) unlink($direArcUsimra);
}catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>



<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Cierre de Liquidacion :.</title>
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

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'menuFiscalizacion.php'"/></p>
  <p class="Estilo2">Resultado del proceso de cierra de liquidación </p>
  
  <p class="Estilo2">O.S.P.I.M.</p>
  <?php if (sizeof($liqCargadas) != 0) { ?>
  		 <p class="Estilo2">El archivo Seguimiento.txt conten&iacute;a <?php echo sizeof($liqCargadas) ?> líneas</p>
			  <table style="width: 900; text-align: center;" border="1" >
					<tr>
					  <th>Req Nro.</th>
					  <th>C.U.I.T.</th>
					  <th>Fecha</th>
					  <th>Total Liquidado</th>
					  <th>Acuerdos Absorvidos</th>
					  <th>Operador</th>
					  <th>Control</th>
					</tr>
			  <?php for ($i=0; $i < sizeof($liqCargadas); $i++) {
			  			$nroreq = $liqCargadas[$i]['reque'];
			  			$sqlControlLiqui = "SELECT *, r.cuit as cuit FROM cabliquiospim c, reqfiscalizospim r where c.nrorequerimiento = $nroreq and c.nrorequerimiento = r.nrorequerimiento";
						$resControlLiqui = mysql_query($sqlControlLiqui,$db);
						$rowControlLiqui = mysql_fetch_array($resControlLiqui);
						//consultamos las liquidacioens y los aceurdos abscorvidos
						$acuAbsor = "";
						$sqlAcuLiqui = "SELECT * FROM aculiquiospim WHERE nrorequerimiento = $nroreq";
						$resAcuLiqui = mysql_query($sqlAcuLiqui,$db);
						while ($rowAcuLiqui = mysql_fetch_array($resAcuLiqui)) {
							$acuAbsor = $acuAbsor." * ".$rowAcuLiqui['nroacuerdo'];
						}
						$liqAnulada = "";
						if ($rowControlLiqui['liquidacionanulada'] == 1) {
							$liqAnulada = "LIQ. ANULADA <br>(".$rowControlLiqui['usuarioanulacion']." - ".$rowControlLiqui['fechaanulacion'].")";
						} ?>
						<tr align='center'>
							<td><?php echo $rowControlLiqui['nrorequerimiento'] ?></td>
							<td><?php echo $rowControlLiqui['cuit'] ?></td>
							<td><?php echo $rowControlLiqui['fechaliquidacion']." / ".$rowControlLiqui['horaliquidacion'] ?></td>
							<td><?php echo $rowControlLiqui['totalliquidado'] ?></td>
							<td><?php echo $acuAbsor ?></td>
							<td><?php echo $rowControlLiqui['operadorliquidador'] ?></td>
							<td><?php echo $liqAnulada ?></td>
						</tr>
	   <?php	 } ?>
			</table>
<?php 	  } else { ?>
			 <p><b style="color:blue;"> NO SE ENCONTRÓ EL ARCHIVO Seguimiento.txt </b></p>
<?php	  }  ?>
		
	<p class="Estilo2">U.S.I.M.R.A.</p>
  	<?php if (sizeof($liqCargadasUsimra) != 0) { ?>
  		 <p class="Estilo2">El archivo SegUsimra.txt conten&iacute;a <?php echo sizeof($liqCargadasUsimra) ?> líneas</p>
			  <table style="width: 900; text-align: center;" border="1" >
					<tr>
					  <th>Req Nro.</th>
					  <th>C.U.I.T.</th>
					  <th>Fecha</th>
					  <th>Total Liquidado</th>
					  <th>Acuerdos Absorvidos</th>
					  <th>Operador</th>
					  <th>Control</th>
					</tr>
			  <?php for ($i=0; $i < sizeof($liqCargadasUsimra); $i++) {
			  			$nroreq = $liqCargadasUsimra[$i]['reque'];
			  			$sqlControlLiqui = "SELECT *, r.cuit as cuit FROM cabliquiusimra c, reqfiscalizusimra r where c.nrorequerimiento = $nroreq and c.nrorequerimiento = r.nrorequerimiento";
						$resControlLiqui = mysql_query($sqlControlLiqui,$db);
						$rowControlLiqui = mysql_fetch_array($resControlLiqui);
			  			
						//consultamos las liquidacioens y los aceurdos abscorvidos
						$acuAbsor = "";
						$sqlAcuLiqui = "SELECT * FROM aculiquiusimra WHERE nrorequerimiento = $nroreq";
						$resAcuLiqui = mysql_query($sqlAcuLiqui,$db);
						while ($rowAcuLiqui = mysql_fetch_array($resAcuLiqui)) {
							$acuAbsor = $acuAbsor." * ".$rowAcuLiqui['nroacuerdo'];
						}
						$liqAnulada = "";
						if ($rowControlLiqui['liquidacionanulada'] == 1) {
							$liqAnulada = "LIQ. ANULADA <br>(".$rowControlLiqui['usuarioanulacion']." - ".$rowControlLiqui['fechaanulacion'].")";
						} ?>
						
						<tr align='center'>
							<td><?php echo $rowControlLiqui['nrorequerimiento'] ?></td>
							<td><?php echo $rowControlLiqui['cuit'] ?></td>
							<td><?php echo $rowControlLiqui['fechaliquidacion']." / ".$rowControlLiqui['horaliquidacion'] ?></td>
							<td><?php echo $rowControlLiqui['totalliquidado'] ?></td>
							<td><?php echo $acuAbsor ?></td>
							<td><?php echo $rowControlLiqui['operadorliquidador'] ?></td>
							<td><?php echo $liqAnulada ?></td>
						</tr>
		 <?php		} ?>
			</table>
<?php 	  } else { ?>
			 <p><b style="color:blue;"> NO SE ENCONTRÓ EL ARCHIVO SegUSIMRA.txt </b></p>
<?php	  }  ?>
		
		  <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
</div>
</body>
</html>