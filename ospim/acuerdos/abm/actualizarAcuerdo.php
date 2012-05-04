<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 
$fechamodificacion = date("Y-m-d H:m:s");
$usuariomodificacion = $_SESSION['usuario'];


$datos = array_values($_POST);
$cuit = $datos[0];
//echo "CUIT: ".$cuit;echo "<br>";
$nroacu = $datos[1];
//echo "NRO ACUERDO: ".$nroacu;echo "<br>";
$tipoacuerdo = $datos[2];
//echo "TIPO ACUERDO: ".$tipoacuerdo; echo "<br>";
$fechaacuerdo = $datos[3];
$invert = explode("-",$fechaacuerdo); 
$fechaacuerdo = $invert[2]."-".$invert[1]."-".$invert[0]; 
//echo "FECHA: ".$fechaacuerdo; echo "<br>";
$nroacta = $datos[4];
//echo "ACTA: ".$nroacta; echo "<br>";
$gestoracuerdo = $datos[5];
//echo "GESTOR: ".$gestoracuerdo; echo "<br>";
$inspectorinterviene = $datos[6];
//echo "INSPECTOR: ".$inspectorinterviene; echo "<br>";
$requerimientoorigen = $datos[7];
$liquidacionorigen = $datos[8];
if ($liquidacionorigen == "") {
	$requerimientoorigen = 0;
}
//echo "REQUERI: ".$requerimientoorigen; echo "<br>";
//echo "LIQUI: ".$liquidacionorigen; echo "<br>";
$montoacuerdo = $datos[9];
//echo "MONTO: ".$montoacuerdo; echo "<br>";
$porcGastos = $datos[10];
//echo "PORC GAST: ".$porcGastos; echo "<br>";
$observaciones = $datos[11];
//echo "OBSER: ".$observaciones; echo "<br>";

$peridosHabili =  $datos[13];
//echo "Peridoso Cantidad: ".$peridosHabili; echo "<br>";


$sqlModifCabe = "UPDATE cabacuerdosospim set tipoacuerdo = ".$tipoacuerdo.", fechaacuerdo = '".$fechaacuerdo."', nroacta = ".$nroacta.", gestoracuerdo = ".$gestoracuerdo.", inspectorinterviene=".$inspectorinterviene.", requerimientoorigen = ".$requerimientoorigen.", liquidacionorigen = '".$liquidacionorigen."', montoacuerdo = ".$montoacuerdo.", observaciones = '".$observaciones."'  where cuit = ".$cuit." and nroacuerdo = ".$nroacu;
//echo $sqlModifCabe;echo "<br>";

//conexion y craecion de transaccion.
try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	//echo "$hostname"; echo "<br>";
	//echo "$dbname"; echo "<br>";
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	//echo 'Connected to database<br/>';
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();
	//CABECERA
	$dbh->exec($sqlModifCabe);
	
	//DELETEO LOS PERIDOS...
	$sqlDeletePeridos = "DELETE FROM detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
	//echo $sqlDeletePeridos; echo("<br>");
	$dbh->exec($sqlDeletePeridos); 
	
	//Creo los SQL para los periodos
	if ($peridosHabili > 108) {
		$peridosHabili = 119;
	}
	$id = 1;
	$finFor = 14 + ($peridosHabili * 3);
	echo "FIN FOR: ".$finFor;  echo("<br>");
	for ($i = 14; $i <= $finFor; $i++) {
		if ($datos[$i] != "" and $datos[$i+1] != "") {
			$mes = $datos[$i];
			$anio = $datos[$i+1];
			$deuda = $datos[$i+2];
			$sqlInsertPeriodos = "INSERT INTO detacuerdosospim VALUES('$cuit','$nroacu','$id','$mes','$anio','$deuda')";
			$dbh->exec($sqlInsertPeriodos); 
			$id = $id + 1;
			echo($sqlInsertPeriodos); echo("<br>");
		} 
	$i=$i+2;
	}
	
	$dbh->commit();
	$pagina = "consultaAcuerdo.php?cuit=$cuit&nroacu=$nroacu";
	Header("Location: $pagina"); 
	
}catch (PDOException $e) {
	echo $e->getMessage();
	$dbh->rollback();
}
?>