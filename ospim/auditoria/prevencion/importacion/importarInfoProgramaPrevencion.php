<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php"); 
set_time_limit(0);
/*****************************************************/
$fecharegistro = date("Y-m-d H:i:s");
$usuarioregistro = $_SESSION['usuario'];
// $hostprevencion = $hostOspim;
// $usuarioaprevencion = $usuarioOspim;
// $claveprevencion = $claveOspim;
$hostprevencion = $hostLocal ;
$usuarioaprevencion = $usuarioLocal; 
$claveprevencion = $claveLocal;

$dbprevencion =  mysql_connect($hostprevencion, $usuarioaprevencion, $claveprevencion);
if (!$dbprevencion) {
    die('No pudo conectarse: ' . mysql_error());
}
$dbnameprevencion = $baseOspimIntranet;
mysql_select_db($dbnameprevencion);
/*****************************************************/

function insertTablaMadera($tabla, $resSql) {
	$sqlInsert = "REPLACE INTO $tabla VALUE (";
	$sqlUpdate = "UPDATE $tabla SET descargado = 1 WHERE id in (";
	$c = 0;
	while($row = mysql_fetch_assoc($resSql)) {
		foreach($row as $campo) {
			$sqlInsert .= "'".$campo."',";
		}
		$sqlInsert = substr($sqlInsert, 0, -1);
		$sqlInsert .= "),(";
		$sqlUpdate .= "'".$row['id']."',";
		$ingresados[$c] = array('delcod' => $row['delcod'], 'profesional' => $row['profesional'], 'nrafil' => $row['nrafil'], 'nombre' =>  $row['nombre'],'fecharegistro' => $row['fecharegistro']);
		$c++;
	}
	$sqlInsert = substr($sqlInsert, 0, -2);
	$sqlInsert .= ";";
	$sqlUpdate = substr($sqlUpdate, 0, -1);
	$sqlUpdate .= ")";

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		//print($sqlInsert."<br>");
		$dbh->exec($sqlInsert);
		
		global $hostprevencion,$usuarioaprevencion,$claveprevencion,$dbnameprevencion;

		$dbhInternet = new PDO("mysql:host=$hostprevencion;dbname=$dbnameprevencion",$usuarioaprevencion,$claveprevencion);
		$dbhInternet->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbhInternet->beginTransaction();
		//print($sqlUpdate."<br>");
		$dbhInternet->exec($sqlUpdate);
		
		$dbh->commit();
		$dbhInternet->commit();
		
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
		$dbhInternet->rollback();
		return (0);
	}
	return ($ingresados);
}

$arrayTablas = array(0 => "cancermama", 1 => "canceruterino", 2 => "diabetes", 3 => "hipertension", 4 => "maternoinfantil", 5 => "odontologica", 6 => "prenatal", 7 => "saludsexual");
$r=0;
foreach($arrayTablas as $tabla) {
	$sqlDescarga = "SELECT * FROM $tabla WHERE descargado = 0 order by fecharegistro";
	$resDescarga = mysql_query($sqlDescarga,$dbprevencion); 
	$canDescarga = mysql_num_rows($resDescarga); 
	if ($canDescarga > 0) {
		$ingresdas = insertTablaMadera($tabla,$resDescarga);
		$resultados[$r] = array('tabla' => $tabla, 'ingreso' => $ingresdas); 
	} else {
		$resultados[$r] = array('tabla' => $tabla, 'ingreso' => 0); 
	}
	$r++;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Empresas dasdas de alta :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css" />

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
	.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" class="nover" name="volver" value="Volver" onclick="location.href = '../menuProgramaPrevencion.php'" />
  </span></p>
  	<p class="Estilo2">Resultado del proceso de descarga de información de programa de prevención <?php echo date("m/d/Y");?>  </p>
<?php 
foreach($resultados as $resultado) { ?>
	<p class="Estilo2"><?php echo $resultado['tabla'] ?></p>
<?php if  ($resultado['ingreso'] != 0) { ?>
	 <div class="grilla">
	 <table width="800" border="1" align="center">
		<thead>
		<tr>
		  <th>Delegación</th>
	      <th>Profesional</th>
		  <th>Nro. Afiliado</th>
		  <th>Nombre</th>
		  <th>Fecha Registro</th>
	    </tr>
		</thead>
		<tbody>
	  <?php foreach ($resultado['ingreso'] as $reg) {
			print("<tr align='center'>");
				print("<td>".$reg['delcod']."</td>");
				print("<td>".$reg['profesional']."</td>");
				print("<td>".$reg['nrafil']."</td>");   
				print("<td>".$reg['nombre']."</td>");   
				print("<td>".$reg['fecharegistro']."</td>");   
			print("</tr>");
		} ?>
		</tbody>
	  </table>
	  </div>
	<?php } else {
		print("<div align='center' style='color:#006699'><b> NO SE DESCARGO NINGÚN REGISTRO</b></div>");
	} 
}
?>
      <p><input type="button" class="nover" name="imprimir" value="Imprimir" onclick="window.print();" /></p>
</div>
</body>
</html>