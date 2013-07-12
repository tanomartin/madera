<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

$datos = array_values($_POST);

//echo $datos[0]; echo "<br>";
//echo $datos[1]; echo "<br>";
$cuenta=$datos[1];
//echo "CUENTA: "; echo $cuenta; echo "<br>";
//echo $datos[2]; echo "<br>";
$feccar=$datos[2];
$fecrem=substr($datos[2], 6, 4).substr($datos[2], 3, 2).substr($datos[2], 0, 2);
//echo "FECHA REMESA: "; echo $fecrem; echo "<br>";
//echo $datos[3]; echo "<br>";
$remesa=$datos[3];
//echo "NRO REMESA: "; echo $remesa; echo "<br>";
//echo $datos[4]; echo "<br>";
$brutos=$datos[4];
//echo "BRUTO: "; echo $brutos; echo "<br>";
//echo $datos[5]; echo "<br>";
$comisi=$datos[5];
//echo "COMISION: "; echo $comisi; echo "<br>";
//echo $datos[6]; echo "<br>";
$faimas=$datos[6];
//echo "FAIMA: "; echo $faimas; echo "<br>";
//echo $datos[7]; echo "<br>";
$netoss=$datos[7];
//echo "NETO: "; echo $netoss; echo "<br>";
//echo $datos[8]; echo "<br>";
//echo $datos[9]; echo "<br>";
$estcon=$datos[9];
//echo "ESTADO CONCILIACION: "; echo $estcon; echo "<br>";
//echo $datos[10]; echo "<br>";
$rembru=$datos[10];
//echo "REMITOS BRUTO: "; echo $rembru; echo "<br>";
//echo $datos[11]; echo "<br>";
$remcom=$datos[11];
//echo "REMITOS COMISION: "; echo $remcom; echo "<br>";
//echo $datos[12]; echo "<br>";
$remnet=$datos[12];
//echo "REMITOS NETO: "; echo $remnet; echo "<br>";
//echo $datos[13]; echo "<br>";
$bolapo=$datos[13];
//echo "BOLETAS APORTE: "; echo $bolapo; echo "<br>";
//echo $datos[14]; echo "<br>";
$bolrec=$datos[14];
//echo "BOLETAS RECARGO: "; echo $bolrec; echo "<br>";
//echo $datos[15]; echo "<br>";
$bolvar=$datos[15];
//echo "BOLETAS VARIOS: "; echo $bolvar; echo "<br>";
//echo $datos[16]; echo "<br>";
$bolpag=$datos[16];
//echo "BOLETAS TOTAL APORTES: "; echo $bolpag; echo "<br>";
//echo $datos[17]; echo "<br>";
$bolacu=$datos[17];
//echo "BOLETAS ACUERDO: "; echo $bolacu; echo "<br>";
//echo $datos[18]; echo "<br>";
$bolbru=$datos[18];
//echo "BOLETAS BRUTO: "; echo $bolbru; echo "<br>";
//echo $datos[19]; echo "<br>";
$canbol=$datos[19];
//echo "CANTIDAD BOLETAS: "; echo $canbol; echo "<br>";
//echo $datos[20]; echo "<br>";
$fecacr=$datos[20];
//echo "FECHA ACREDITACION: "; echo $fecacr; echo "<br>";
$sisrem="M";
$feccon="";
//echo "FECHA CONCILIACION: "; echo $feccon; echo "<br>";
$usucon="";
//echo "USUARIO CONCILIACION: "; echo $usucon; echo "<br>";
$fecreg = date("Y-m-d H:m:s");
//echo "FECHA REGISTRO: "; echo $fecreg; echo "<br>";
$usureg = $_SESSION['usuario'];
//echo "USUARIO REGISTRO: "; echo $usureg; echo "<br>";
$fecmod="";
//echo "FECHA MODIFICACION: "; echo $fecmod; echo "<br>";
$usumod="";
//echo "USUARIO MODIFICACION: "; echo $usumod; echo "<br>";


//conexion y creacion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlAddRemesa="INSERT INTO remesasusimra (codigocuenta, sistemaremesa, fecharemesa, nroremesa, importebruto, importecomision, importeneto, importefaima, importebrutoremitos, importecomisionesremitos, importenetoremitos, importeboletasaporte, importeboletasrecargo,  importeboletasvarios, importeboletaspagos, importeboletascuotas, importeboletasbruto, cantidadboletas, estadoconciliacion, fechaconciliacion, usuarioconciliacion, fechaacreditacion, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion) VALUES (:codigocuenta, :sistemaremesa, :fecharemesa, :nroremesa, :importebruto, :importecomision, :importeneto, :importefaima, :importebrutoremitos, :importecomisionesremitos, :importenetoremitos, :importeboletasaporte, :importeboletasrecargo,  :importeboletasvarios, :importeboletaspagos, :importeboletascuotas, :importeboletasbruto, :cantidadboletas, :estadoconciliacion, :fechaconciliacion, :usuarioconciliacion, :fechaacreditacion, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion)";
	//echo $sqlAddRemesa; echo "<br>";
	$resultAddRemesa = $dbh->prepare($sqlAddRemesa);
	if($resultAddRemesa->execute(array(':codigocuenta' => $cuenta, ':sistemaremesa' => $sisrem, ':fecharemesa' => $fecrem, ':nroremesa' => $remesa, ':importebruto' => $brutos, ':importecomision' => $comisi, ':importeneto' => $netoss, ':importefaima' => $faimas, ':importebrutoremitos' => $rembru, ':importecomisionesremitos' => $remcom, ':importenetoremitos' => $remnet, ':importeboletasaporte' => $bolapo, ':importeboletasrecargo' => $bolrec, ':importeboletasvarios' => $bolvar, ':importeboletaspagos' => $bolpag, ':importeboletascuotas' => $bolacu, ':importeboletasbruto' => $bolbru, ':cantidadboletas' => $canbol, ':estadoconciliacion' => $estcon, ':fechaconciliacion' => $feccon, ':usuarioconciliacion' => $usucon, ':fechaacreditacion' => $fecacr, ':fecharegistro' => $fecreg, ':usuarioregistro' => $usureg, ':fechamodificacion' => $fecmod, ':usuariomodificacion' => $usumod)))
	
	$dbh->commit();
	$pagina = "listarRemesas.php?ctaRemesa=$cuenta&fecRemesa=$feccar";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo1 {
	font-family: Arial, Helvetica, sans-serif;
	font-style: italic;
	font-weight: bold;
}
</style>
<body bgcolor="#B2A274">
</body>
</html>