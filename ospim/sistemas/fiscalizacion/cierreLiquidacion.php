<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."fechas.php"); 

$archivo = "liqui/Seguimiento.txt";

if (file_exists($archivo)) {
	$file = fopen($archivo, "r") or exit("Unable to open file!");
	$r = 0;
	$n = 0;
	while(!feof($file)) {
		$linea = trim(fgets($file));
		if (strlen($linea) > 0) {
			$linea = str_replace('"','',$linea);
			$campos = explode("|",$linea);
			$nroreq = (int)$campos[0];
			if (strlen($campos[10]) > 0) {
				$acuerdos = explode("-",$campos[10]);
				for ($i=0; $i<sizeof($acuerdos); $i++) {
					$acuAbs = 'S';
					$nroacu = (int)$acuerdos[$i];
					$sqlInserciones[$r] = "INSERT into requeAcuerdos VALUE($nroreq, $nroacu)";
					$r++;
				}
			} else {
				$acuAbs = 'N';
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
			$liqCargadas[$n] = $nroreq;
			$sqlInserciones[$r] = "INSERT into cabliquidacionospim VALUE($nroreq, '$fechaliq', '$horaliq', '$fecinsp', $totdep, $totint, $gastos, $totliq, $nroreso, $nrosert, '$acuAbs', '$operador')";
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
		//$dbh->beginTransaction();
		foreach($sqlInserciones as $sql) {
			print($sql."<br>");
			//$dbh->exec($sql);
		}
		//$dbh->commit();
		unlink($archivo);
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
} else {
	$liqCargadas = 0;
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

<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=300, top=10, left=10");
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p class="Estilo2"><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuFiscalizacion.php'" align="center"/>
  </span></p>
  <p class="Estilo2">Resultado del proceso de cierra de liquidación </p>
  <?php if ($liqCargadas != 0) { ?>
  		 <p class="Estilo2">El archivo Seguimineto.txt conten&iacute;a <?php echo sizeof($liqCargadas) ?> líneas</p>
			  <table width="800" border="1" align="center">
					<tr>
					  <th>Req Nro.</th>
					  <th>Fecha</th>
					  <th>Total Liquidado</th>
					  <th>Acuerdos Absorvidos</th>
					  <th>Operador</th>
					</tr>
			  <?php for ($i=0; $i < sizeof($liqCargadas); $i++) {
						//consultamos las liquidacioens y los aceurdos abscorvidos
						$nroreq = $liqCargadas[$i];
						print("<tr align='center'>");
						print("<td>".$nroreq."</td>");
						print("</tr>");
					}
			  ?>
			</table>
			<p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/></p>
	<?php } else {
				print("<div align='center' style='color:#FF0000'><b> NO SE ENCONTRÓ EL ARCHIVO Seguimiento.txt </b></div>");
		  } ?>
</div>
</body>
</html>