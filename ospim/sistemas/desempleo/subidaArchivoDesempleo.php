<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."claves.php");
include($libPath."bandejaSalida.php");

$maquina = $_SERVER['SERVER_NAME'];

$carpetaMes = $_POST['periodo'];
$nombreArcProc = "DE".$carpetaMes.".txt";
$nombreArc = "Desempleo.txt";

if(strcmp("localhost",$maquina) == 0) {
	$direDirectorio = $_SERVER['DOCUMENT_ROOT']."/madera/ospim/sistemas/desempleo/Desempleo/";
} else {
	$direDirectorio="/home/sistemas/Documentos/Repositorio/DescargasSSS/Desempleo/";
}

$directorioMes = $direDirectorio.$carpetaMes;
$fileProcDirectorio = $directorioMes."/".$nombreArcProc;
$fileDirectorio = $directorioMes."/".$nombreArc;

if (file_exists($fileProcDirectorio)){
	$pagina = "menuDesempleo.php?existe=$carpetaMes";
	Header("Location: $pagina"); 
	exit();
} else { 
	if (!file_exists($directorioMes)) {
		$pagina = "menuDesempleo.php?nocarpeta=$carpetaMes";
		Header("Location: $pagina"); 
		exit();
	} 
	if (!file_exists($fileDirectorio)) {
		$pagina = "menuDesempleo.php?noexiste=$nombreArc&carpeta=$carpetaMes";
		Header("Location: $pagina"); 
		exit();
	}
}

$fp = fopen($fileDirectorio, "r");
$lineasNuevoArchivo = array();
$i = 1;
while(!feof($fp)) {
	$linea = fgets($fp);
	if (strlen($linea) > 0) {
		
		$campos = explode("|",$linea);
		
		$fechaCobro = $campos[9];
		$fechaCobro = substr($fechaCobro,6,4).substr($fechaCobro,3,2).substr($fechaCobro,0,2);
		$campos[9] = $fechaCobro;
		$mesFinRelacion = substr($campos[12],2,2);
		$anoFinRealcion = substr($campos[12],0,2);
		if ($anoFinRealcion == 0) { $anoFinRealcion  = "0000"; }
		if ($anoFinRealcion > 0 && $anoFinRealcion < 50) { $anoFinRealcion  = 2000 + $anoFinRealcion; }
		if ($anoFinRealcion > 49 && $anoFinRealcion < 99) { $anoFinRealcion  = 1900 + $anoFinRealcion; }
		$campos[16] = $campos[15];
		$campos[15] = $campos[14];
		$campos[14] = $campos[13];
		$campos[13] = $anoFinRealcion;
		$campos[12] = $mesFinRelacion;

		$linea = implode("|",$campos);
		
		$nuevaLinea = substr($carpetaMes,0,4)."|".substr($carpetaMes,4,2)."|".$i."|".$linea;
		$lineasNuevoArchivo[$i] = $nuevaLinea;
		$i++;
	}
}
fclose($fp);

$ar=fopen($fileProcDirectorio,"x") or die("Hubo un error al generar el archivo de desempleo para importar a la base. Por favor cuminiquese con el dpto. de Sistemas");
foreach($lineasNuevoArchivo as $linea) {
	fputs($ar,$linea);
}
fclose($ar);

$sqlImport = "LOAD DATA LOCAL INFILE '$fileProcDirectorio' REPLACE INTO TABLE desempleosss FIELDS TERMINATED BY '|' LINES TERMINATED BY '\\n'";

$linkid = mysqli_init();
$hostname = $_SESSION['host'];
$dbname = $_SESSION['dbname'];
mysqli_options($linkid, MYSQLI_OPT_LOCAL_INFILE, true);
mysqli_real_connect($linkid, $hostname, $_SESSION['usuario'], $_SESSION['clave'], $dbname);
$resLoadAnses = mysqli_query($linkid, $sqlImport);
mysqli_close($linkid);
if (!$resLoadAnses) {
	$mensajeError = 'La carga de los registros de desempleo de anses (Desempleo.txt) FALLO.';
} else {
	$subject = "Aviso Automático - Proceso de Archivo de Desempleo";
	$address = "contaduria@ospim.com.ar; afiliaciones@ospim.com.ar";
	$username ="sistemas@ospim.com.ar";
	$modulo = "Desempleo";
	$bodymail = "Este es un aviso para informar que se proceso el archivo de Desempleo del periodo $carpetaMes.<br><br>Dpto. Sistemas";
	guardarEmail($username, $subject, $bodymail, $address, $modulo, null);
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultado subida archivo desempleo de ANSES :.</title>

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
  <p class="Estilo2"><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'menuDesempleo.php'" />
  </span></p>
  <p class="Estilo2">Resultado del proceso de subida de archivos de desempleo de A.N.S.E.S. </p>
		<?php if ($resLoadAnses) {	  ?>
				<table width="500" border="1" align="center">
					<tr>
					  <th>Periodo</th>
					  <th>Lineas Archivo Original</th>
					  <th>Cantida de reg. importados</th>
					</tr>
			  <?php 
			  		$mesImportacion = substr($carpetaMes,4,2);
					$anoImporatacion = substr($carpetaMes,0,4);
					print("<tr align='center'>");
					print("<td>".$mesImportacion."-".$anoImporatacion."</td>");
					print("<td>".sizeof($lineasNuevoArchivo)."</td>");
					
					$sqlControlImpo = "SELECT cuilbeneficiario FROM desempleosss where anodesempleo = $anoImporatacion and mesdesempleo = $mesImportacion";
					$resControlImpo = mysql_query($sqlControlImpo,$db);
					$cantImportadas = mysql_num_rows($resControlImpo);

					print("<td>".$cantImportadas."</td>");
					print("</tr>");
			  ?>
				</table> 
		<?php } else { ?>
					<div style="color:#FF0000"><b><?php echo $mensajeError ?></b></div>
		<?php }?>
	 <p><input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/></p>
</div>
</body>
</html>