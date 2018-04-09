<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
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
//echo "FECHA REMITO: "; echo $fecrem; echo "<br>";
//echo $datos[3]; echo "<br>";
$remito=$datos[3];
//echo "NRO REMITO: "; echo $remito; echo "<br>";
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
$boleta=$datos[8];
//echo "BOLETAS: "; echo $boleta; echo "<br>";
//echo $datos[9]; echo "<br>";
$sucban=$datos[9];
//echo "SUCURSAL: "; echo $sucban; echo "<br>";
//echo $datos[10]; echo "<br>";
//echo $datos[11]; echo "<br>";
$estcon=$datos[11];
//echo "ESTADO CONCILIACION: "; echo $estcon; echo "<br>";
//echo $datos[12]; echo "<br>";
$bolapo=$datos[12];
//echo "BOLETAS APORTE: "; echo $bolapo; echo "<br>";
//echo $datos[13]; echo "<br>";
$bolrec=$datos[13];
//echo "BOLETAS RECARGO: "; echo $bolrec; echo "<br>";
//echo $datos[14]; echo "<br>";
$bolvar=$datos[14];
//echo "BOLETAS VARIOS: "; echo $bolvar; echo "<br>";
//echo $datos[15]; echo "<br>";
$bolpag=$datos[15];
//echo "BOLETAS TOTAL APORTES: "; echo $bolpag; echo "<br>";
//echo $datos[16]; echo "<br>";
$bolacu=$datos[16];
//echo "BOLETAS ACUERDO: "; echo $bolacu; echo "<br>";
//echo $datos[17]; echo "<br>";
$bolbru=$datos[17];
//echo "BOLETAS BRUTO: "; echo $bolbru; echo "<br>";
//echo $datos[18]; echo "<br>";
$canbol=$datos[18];
//echo "CANTIDAD BOLETAS: "; echo $canbol; echo "<br>";
//echo $datos[19]; echo "<br>";
$fecacr=$datos[19];
//echo "FECHA ACREDITACION: "; echo $fecacr; echo "<br>";
$sisrem="M";
$feccon="";
//echo "FECHA CONCILIACION: "; echo $feccon; echo "<br>";
$usucon="";
//echo "USUARIO CONCILIACION: "; echo $usucon; echo "<br>";
$fecreg = date("Y-m-d H:i:s");
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

	$sqlAddRemito="INSERT INTO remitossueltosusimra (codigocuenta, sistemaremito, fecharemito, nroremito, sucursalbanco, importebruto, importecomision, importeneto, importefaima, boletasremito, importeboletasaporte, importeboletasrecargo,  importeboletasvarios, importeboletaspagos, importeboletascuotas, importeboletasbruto, cantidadboletas, estadoconciliacion, fechaconciliacion, usuarioconciliacion, fechaacreditacion, fecharegistro, usuarioregistro, fechamodificacion, usuariomodificacion) VALUES (:codigocuenta, :sistemaremito, :fecharemito, :nroremito, :sucursalbanco, :importebruto, :importecomision, :importeneto, :importefaima, :boletasremito, :importeboletasaporte, :importeboletasrecargo,  :importeboletasvarios, :importeboletaspagos, :importeboletascuotas, :importeboletasbruto, :cantidadboletas, :estadoconciliacion, :fechaconciliacion, :usuarioconciliacion, :fechaacreditacion, :fecharegistro, :usuarioregistro, :fechamodificacion, :usuariomodificacion)";
	//echo $sqlAddRemito; echo "<br>";
	$resultAddRemito = $dbh->prepare($sqlAddRemito);
	if($resultAddRemito->execute(array(':codigocuenta' => $cuenta, ':sistemaremito' => $sisrem, ':fecharemito' => $fecrem, ':nroremito' => $remito, ':sucursalbanco' => $sucban, ':importebruto' => $brutos, ':importecomision' => $comisi, ':importeneto' => $netoss, ':importefaima' => $faimas, ':boletasremito' => $boleta, ':importeboletasaporte' => $bolapo, ':importeboletasrecargo' => $bolrec, ':importeboletasvarios' => $bolvar, ':importeboletaspagos' => $bolpag, ':importeboletascuotas' => $bolacu, ':importeboletasbruto' => $bolbru, ':cantidadboletas' => $canbol, ':estadoconciliacion' => $estcon, ':fechaconciliacion' => $feccon, ':usuarioconciliacion' => $usucon, ':fechaacreditacion' => $fecacr, ':fecharegistro' => $fecreg, ':usuarioregistro' => $usureg, ':fechamodificacion' => $fecmod, ':usuariomodificacion' => $usumod)))
	
	$dbh->commit();
	$pagina = "listarRemitosSueltos.php?ctaRemito=$cuenta&fecRemito=$feccar";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/usimra/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}
?>