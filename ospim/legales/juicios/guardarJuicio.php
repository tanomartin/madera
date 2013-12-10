<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php");

print("************ GUARDAR JUICIO *****************<br>");

$datos = array_values($_POST);

$cuit = $datos[0];
$nroorden = $datos[1];
$nrocerti = $datos[2];
$status = $datos[3];
$fecExpe = fechaParaGuardar($datos[4]);
$deudaHisto = number_format($datos[5],2,'.','');
$intereses = number_format($datos[6],2,'.','');
$duedaActual = number_format($datos[7],2,'.','');
$asesor = $datos[8];
$inspector = $datos[9];
$acuAbs = $datos[10];
$nroacuerdo = $datos[11];
$usuarioregistro = $_SESSION['usuario'];
$tramiteJudicial = $datos[13];

$sqlCabecera = "INSERT INTO cabjuiciosospim VALUE($nroorden,'$cuit',$nrocerti,$status,'$fecExpe',$acuAbs,$nroacuerdo,$deudaHisto,$intereses,$duedaActual,$asesor,$inspector,$usuarioregistro,$tramiteJudicial)";
print($sqlCabecera."<br>");

if($acuAbs == 1) {
	$sqlUpdateAcu = "UPDATE cabacuerdosospim SET estadoacuerdo = 4 where cuit = '$cuit' and nroacuerdo = $nroacuerdo";
	print($sqlUpdateAcu."<br>");
}

$peridosHabili = $datos[12];
$finFor = 14 + ($peridosHabili * 3);
//print("FIN FOR: ".$finFor."<br>");
$n = 0;
$m = 0;
for ($i = 14; $i <= $finFor; $i++) {
	if ($datos[$i+1] != "" and $datos[$i+2] != "") {
		$id = $datos[$i];
		$mes = $datos[$i+1];
		$anio = $datos[$i+2];
		if ($id == '') {
			$sqlInsert = "INSERT INTO detjuiciosospim VALUES($nroorden,$id,$mes,$anio,0)"; 
		} else {
			$sqlInsert = "INSERT INTO detjuiciosospim VALUES($nroorden,$id,$mes,$anio,$nroacuerdo)"; 
		}
		if ($id != '') {
			$sqlDelete = "DELETE FROM detacuerdosospim WHERE cuit = '$cuit' and nroacuerdo = $nroacuerdo and idperiodo = $id";
			$sqlDelPer[$m] = $sqlDelete;
			$m++;
		}
		$sqlPeriodos[$n] = $sqlInsert;
		$n++;
	} 
	$i=$i+2;
}

var_dump($sqlPeriodos);
var_dump($sqlDelPer);

/*try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$dbh->exec($sqlCabecera);
	
	for($i=0; $i<sizeof($sqlPeriodos), $i++) {
		
	}
	
	
	$dbh->commit();
	
	$pagina = "asesores.php";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}*/


?>