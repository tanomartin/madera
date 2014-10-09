<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 
include($libPath."ftpZeus.php"); 
include($libPath."funcionesFTP.php"); 

$archivo = "Seguimiento.txt";
$archivoUsimra = "SegUSIMRA.txt";
$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina) == 0) {
	$direArc = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/fiscalizacion/liqui/".$archivo;
	$direArcUsimra = $_SERVER['DOCUMENT_ROOT']."/ospim/sistemas/fiscalizacion/liqui/".$archivoUsimra;
} else {
	$direArc = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$archivo;
	$direArcUsimra = "/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$archivoUsimra;
}

if (file_exists($direArc)) {
	$file = fopen($direArc, "r") or exit("Falla al abrir el archivo!");
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
			if (strlen($campos[10]) > 0) {
				$acuerdos = explode("-",$campos[10]);	
				for ($i=0; $i<sizeof($acuerdos); $i++) {
					$nroacu = (int)$acuerdos[$i];
					$acuAbs = $nroacu." - ".$acuAbs;
					$sqlInserciones[$r] = "INSERT into aculiquiospim VALUE($nroreq, $nroacu)";
					$r++;
					$sqlInserciones[$r] = "UPDATE cabacuerdosospim SET estadoacuerdo = 5 WHERE cuit = $cuit and nroacuerdo = $nroacu";
					$r++;
				}
			} else {
				$acuAbs = '-';
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
			$liqCargadas[$n] = array('reque' => $nroreq, 'cuit' => $cuit, 'fecha' => $campos[1], 'total' => number_format($totliq,2,',','.'), 'acu' => $acuAbs, 'operador' => $operador);
			$sqlInserciones[$r] = "UPDATE cabliquiospim SET fechaliquidacion = '$fechaliq', horaliquidacion = '$horaliq', fechainspeccion = '$fecinsp', deudanominal = $totdep, intereses =  $totint, gtosadmin = $gastos, totalliquidado = $totliq, nroresolucioninspeccion = $nroreso, nrocertificadodeuda = $nrosert, operadorliquidador = '$operador' WHERE nrorequerimiento = $nroreq";
			$r++;
			$n++;
		}
	}
	fclose($file);
	
	//INSERTAMOS TODO
	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		foreach($sqlInserciones as $sql) {
			//print($sql."<br>");
			$dbh->exec($sql);
		}
		$dbh->commit();
		unlink($direArc);
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}	
} else {
	$liqCargadas = 0;
}

$liqUsimra = 0;
if (file_exists($direArcUsimra)) {
	$pathZeus = "/home/sistemas/seguimiento";
	$resultado = SubirArchivo($direArcUsimra, $archivoUsimra, $pathZeus);
	if ($resultado) {
		$liqUsimra = 1;
		//unlink($direArcUsimra);
	} else {
		$liqUsimra = 2;
	}
} 

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Cierre de Liquidacion :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<script language="JavaScript">
function borrarArchivo(dire){
	a= window.open(dire,"borrarArchivo",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2"><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuFiscalizacion.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Resultado del proceso de cierra de liquidación </p>
  <?php if ($liqCargadas != 0) { ?>
  		 <p class="Estilo2">El archivo Seguimiento.txt conten&iacute;a <?php echo sizeof($liqCargadas) ?> líneas</p>
			  <table width="900" border="1" align="center">
					<tr>
					  <th>Req Nro.</th>
					  <th>C.U.I.T.</th>
					  <th>Fecha</th>
					  <th>Total Liquidado</th>
					  <th>Acuerdos Absorvidos</th>
					  <th>Operador</th>
					</tr>
			  <?php for ($i=0; $i < sizeof($liqCargadas); $i++) {
						//consultamos las liquidacioens y los aceurdos abscorvidos
						print("<tr align='center'>");
						print("<td>".$liqCargadas[$i]['reque']."</td>");
						print("<td>".$liqCargadas[$i]['cuit']."</td>");
						print("<td>".$liqCargadas[$i]['fecha']."</td>");
						print("<td>".$liqCargadas[$i]['total']."</td>");
						print("<td>".$liqCargadas[$i]['acu']."</td>");
						print("<td>".$liqCargadas[$i]['operador']."</td>");
						print("</tr>");
					}
			  ?>
			</table>
			  <p>
			    <?php } else {
				print("<p><div align='center' style='color:#000000'><b> NO SE ENCONTRÓ EL ARCHIVO Seguimiento.txt </b></div></p>");
		  		} 
	
		  if ($liqUsimra == 0) { 
		  		print("<p><div align='center' style='color:#000000'><b> NO SE ENCONTRÓ EL ARCHIVO SegUSIMRA.txt </b></div></p>");
		  }	
		  if ($liqUsimra == 1) {
				print("<p><div align='center' style='color:#0033FF'><b> SE SUBIO EL ARCHIVO SegUSIMRA.txt </b></div></p>"); ?>
				<input type="button" name="borrar" value="Borrar Archivo U.S.I.M.R.A." onclick="borrarArchivo('borrarArchivo.php')" align="center"/> 
    <?php } 
		  if ($liqUsimra == 2) {
				print("<p><div align='center' style='color:#FF0000'><b> SE PRODUJO UN ERROR INTENTANDO SUBIR EL ARCHIVO SegUSIMRA.txt </b></div></p>");
		  }
		  
	?>
  </p>
			  <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
</div>
</body>
</html>