<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php");

$sqlPrestador = "SELECT prestadores.cuit, prestadores.nombre, prestadores.codigoprestador, prestadores.telefono1, 
prestadores.email1, prestadoresauxiliar.cbu, prestadoresauxiliar.cuenta, 
prestadoresauxiliar.banco, prestadoresauxiliar.interbanking, DATE_FORMAT(prestadoresauxiliar.fechainterbanking ,'%d-%m-%Y') as fechainterbanking
FROM prestadores, prestadoresauxiliar
WHERE prestadoresauxiliar.interbanking = 1 and prestadoresauxiliar.fechainterbanking is null and 
prestadoresauxiliar.codigoprestador = prestadores.codigoprestador ORDER BY prestadores.codigoprestador DESC";
$resPrestador = mysql_query($sqlPrestador,$db);
$canPrestador = mysql_num_rows($resPrestador);

$maquina = $_SERVER['SERVER_NAME'];
$fechagenera=date("YmdHis");

if(strcmp("localhost",$maquina)==0)
	$archivo_name="archivos/imp_prestadores_$fechagenera.txt";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/Interbanking/imp_prestadores_$fechagenera.txt";

$file = fopen($archivo_name, "w");

//primera linea
$codigoCliente = str_pad("X88739A",7," ",STR_PAD_LEFT);
$restoPrimera = str_pad(" ",152," ",STR_PAD_LEFT);
$primeraLinea = "1".$codigoCliente.$restoPrimera;
fwrite($file, $primeraLinea . PHP_EOL);
//echo $primeraLinea."<br>";

//cuerpo
$whereIn = "(";
while ($rowPresatador = mysql_fetch_array($resPrestador)) {
	$noutlizzados = str_pad(" ",22," ",STR_PAD_LEFT);
	$denominacionCuenta = str_pad(substr($rowPresatador["nombre"],0,29),29,' ',STR_PAD_LEFT);
	$referenciaUso = str_pad("O.S.P.I.M. - Pago Prestaciones",50,' ',STR_PAD_LEFT);
	$restoLinea = str_pad(" ",22," ",STR_PAD_LEFT);
	
	$linea = "2".$noutlizzados.$denominacionCuenta."SNN".$rowPresatador['cuit'].$rowPresatador['cbu'].$referenciaUso.$restoLinea;
	fwrite($file, $linea . PHP_EOL);
	//echo $linea."<br>";
	$whereIn .= $rowPresatador['codigoprestador'].",";
}
$whereIn = substr($whereIn, 0, -1);
$whereIn .= ")";

//ultima linea
$cantidadSubida = str_pad($canPrestador,6,'0',STR_PAD_LEFT);
$restoUltima = str_pad("",146,' ',STR_PAD_LEFT);
$ultimaLinea = "3".$codigoCliente.$cantidadSubida.$restoUltima;
fwrite($file, $ultimaLinea . PHP_EOL);
//echo $ultimaLinea."<br>";

fclose($file);

try {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh->beginTransaction();

	$sqlUpdatePresentacion = "UPDATE prestadoresauxiliar SET fechainterbanking = CURDATE() WHERE codigoprestador in $whereIn";
	$dbh->exec($sqlUpdatePresentacion);
	//echo $sqlUpdatePresentacion;
	$dbh->commit();

	Header("Location: listadoPrestaSubir.php?generado=1");
} catch (PDOException $e) {
	$error =  $e->getMessage();
	$dbh->rollback();
	$redire = "Location://".$_SERVER['SERVER_NAME']."/madera/ospim/errorSistemas.php?error='".$error."'&page='".$_SERVER['SCRIPT_FILENAME']."'";
	header ($redire);
	exit(0);
}

?>
