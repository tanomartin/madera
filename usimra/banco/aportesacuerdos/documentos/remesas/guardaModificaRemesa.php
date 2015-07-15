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
$sisrem="M";
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

	$sqlActualizaRemesa="UPDATE remesasusimra SET importebruto = :importebruto, importecomision = :importecomision, importeneto = :importeneto, importefaima = :importefaima, fechamodificacion = :fechamodificacion, usuariomodificacion = :usuariomodificacion WHERE codigocuenta = :codigocuenta and sistemaremesa = :sistemaremesa and fecharemesa = :fecharemesa and nroremesa = :nroremesa";
	//echo $sqlAddRemesa; echo "<br>";
	$resultActualizaRemesa = $dbh->prepare($sqlActualizaRemesa);
	if($resultActualizaRemesa->execute(array(':codigocuenta' => $cuenta, ':sistemaremesa' => $sisrem, ':fecharemesa' => $fecrem, ':nroremesa' => $remesa, ':importebruto' => $brutos, ':importecomision' => $comisi, ':importeneto' => $netoss, ':importefaima' => $faimas, ':fechamodificacion' => $fecmod, ':usuariomodificacion' => $usumod)))
	
	$dbh->commit();
	$pagina = "listarRemesas.php?ctaRemesa=$cuenta&fecRemesa=$feccar";
	Header("Location: $pagina"); 
}
catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>