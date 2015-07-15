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
//echo "FECHA REMESA: "; echo $fecrem; echo "<br>";
//echo $datos[3]; echo "<br>";
$remesa=$datos[3];
//echo "NRO REMESA: "; echo $remesa; echo "<br>";
//echo $datos[4]; echo "<br>";
$remito=$datos[4];
//echo "NRO REMITO: "; echo $remito; echo "<br>";
//echo $datos[5]; echo "<br>";
$fecrto=substr($datos[5], 6, 4).substr($datos[5], 3, 2).substr($datos[5], 0, 2);
//echo "FECHA REMITO: "; echo $fecrto; echo "<br>";
//echo $datos[6]; echo "<br>";
$brutos=$datos[6];
//echo "BRUTO: "; echo $brutos; echo "<br>";
//echo $datos[7]; echo "<br>";
$comisi=$datos[7];
//echo "COMISION: "; echo $comisi; echo "<br>";
//echo $datos[8]; echo "<br>";
$netoss=$datos[8];
//echo "NETO: "; echo $netoss; echo "<br>";
//echo $datos[9]; echo "<br>";
$boleta=$datos[9];
//echo "BOLETAS: "; echo $boleta; echo "<br>";
//echo $datos[10]; echo "<br>";
$sucban=$datos[10];
//echo "SUCURSAL: "; echo $sucban; echo "<br>";
$sisrem="M";
//echo "SISTEMA: "; echo $sisrem; echo "<br>";
$fecmod=date("Y-m-d H:i:s");
//echo "FECHA MODIFICACION: "; echo $fecmod; echo "<br>";
$usumod=$_SESSION['usuario'];
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

	$sqlActualizaRemito="UPDATE remitosremesasusimra SET fecharemito = :fecharemito, importebruto = :importebruto, importecomision = :importecomision, importeneto = :importeneto, boletasremito = :boletasremito, sucursalbanco= :sucursalbanco, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE codigocuenta = :codigocuenta AND sistemaremesa = :sistemaremesa AND fecharemesa = :fecharemesa AND nroremesa = :nroremesa AND nroremito = :nroremito";
	//echo $sqlAddRemito; echo "<br>";
	$resultActualizaRemito = $dbh->prepare($sqlActualizaRemito);
	if($resultActualizaRemito->execute(array(':codigocuenta' => $cuenta, ':sistemaremesa' => $sisrem, ':fecharemesa' => $fecrem, ':nroremesa' => $remesa, ':nroremito' => $remito, ':fecharemito' => $fecrto, ':sucursalbanco' => $sucban, ':importebruto' => $brutos, ':importecomision' => $comisi, ':importeneto' => $netoss, ':boletasremito' => $boleta, ':fechamodificacion' => $fecmod, ':usuariomodificacion' => $usumod)))
	
	$dbh->commit();
	$pagina = "listarRemitos.php?ctaRemesa=$cuenta&fecRemesa=$feccar&ultRemesa=$remesa&sisRemesa=M";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>