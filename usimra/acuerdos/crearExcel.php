<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['aut'] > 1)
	header ("location:index.htm");
?>
<?php include("../controlSession.php");?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Generador Listado</title>
</head>

<?php
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);
?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<div align="center">
  <p>
<?php
include("excelwriter.inc.php");  
$nombre = "InformeCheques/listaCheques".date('hisjmy').".xls";

$excel=new ExcelWriter($nombre);
    
if($excel==false) {   
	echo $excel->error;
}
//Escribimos la primera fecha de hoy
$today = date("j/n/Y");
$myArr=array($today);
$excel->writeLine($myArr);
$excel->writeRow();

//Escribimos la primera fila con las cabeceras
$myArr=array("Cod. Del.","Cod. Emp.","Razon Social","Nro. Cheque","Banco","Importe","Nro. Acuerdo","Nro. Cuota");
$excel->writeLine($myArr);

$cheques = $_POST['cheques'];
$lista = explode("\n",$cheques);
$cantidad = sizeof($lista);
$resultados = 0;
$boletas = 0;

for ( $i = 0; $i <= $cantidad - 1 ; $i++) {
	$sql = "select depositos.delcod, depositos.empcod, empresas.nombre, depositos.nrocheque, depositos.banco, depositos.importe, depositos.nroacu, depositos.nrocuo
	from 
	depositos, empresas 
	where 
	depositos.nrocheque = $lista[$i] and
	depositos.nrcuit = empresas.nrcuit and
	depositos.delcod = empresas.delcod and
	depositos.empcod = empresas.empcod";
	$result = mysql_db_query("acuerdos",$sql,$db);
	$cant = mysql_num_rows($result);
	if ($cant > 0) {;
		$resultados = $resultados + 1;
		while ($row = mysql_fetch_array($result)) {
			$boletas = $boletas + 1;
			$myArr=array($row['delcod'],$row['empcod'],$row['nombre'],$row['nrocheque'],$row['banco'],$row['importe'],$row['nroacu'],$row['nrocuo']);
			$excel->writeLine($myArr);
		}
	} else {
		echo $lista[$i]." NO EXISTE <br>";
	}
}
$excel->writeRow();
$total="TOTAL";
$myArr=array($total, $boletas);
$excel->writeLine($myArr);
$excel->close();
if ($resultados == 0) {
	$resultados = "ninguno";
}
echo "<br>";
echo "<b>Se buscaron $cantidad y se encontraron $resultados</b>";
?> 
</p>
  <p>&nbsp;    </p>
  <div align="center"><a href="cargarCheques.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a></div>
</body>
</html>