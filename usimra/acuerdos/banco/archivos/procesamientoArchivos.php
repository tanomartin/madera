<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");

function UltimoDia($anho,$mes){ 
    print($anho."".$mes);
   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
       $dias_febrero = 29; 
   } else { 
       $dias_febrero = 28; 
   } 
   switch($mes) { 
       case 1: return 31; break; 
       case 2: return $dias_febrero; break; 
       case 3: return 31; break; 
       case 4: return 30; break; 
       case 5: return 31; break; 
       case 6: return 30; break; 
       case 7: return 31; break; 
       case 8: return 31; break; 
       case 9: return 30; break; 
       case 10: return 31; break; 
       case 11: return 30; break; 
       case 12: return 31; break; 
   } 
}

$fechaRegistro = date("Y-m-d H:i:s");
$usuarioRegistro = $_SESSION['usuario'];
$sqlInsertDia = array();
$sqlDias = "SELECT ano,mes,dia FROM diasbancousimra WHERE procesado = 0 and exceptuado = 0 ORDER BY ano, mes, dia limit 1";
$resDias = mysql_query($sqlDias,$db); 
$canDias = mysql_num_rows($resDias);
//print("Cantida dias: ".$canDias."<br>");
if ($canDias == 0) {
	$sqlPeriodos = "SELECT mes, ano from diasbancousimra GROUP BY ano, mes ORDER BY ano DESC, mes DESC limit 1";
	$resPeriodos = mysql_query($sqlPeriodos,$db); 
	$rowPeriodos = mysql_fetch_assoc($resPeriodos);
	if ($rowPeriodos['mes'] == 12) {
		$proxMes = 1;
		$proxAno = $rowPeriodos['ano'] + 1;
	} else {
		$proxMes = $rowPeriodos['mes'] + 1;
		$proxAno = $rowPeriodos['ano'];
	}
	$proxPeriodo = $proxMes."-".$proxAno;
	$ultimoDiaMes = UltimoDia($proxAno,$proxMes);
	$c = 0;
	
	/*print("Mes Actual: ".$rowPeriodos['mes']."<br>");
	print("Prox mes: ".$proxMes."<br>");
	print("Prox ano: ".$proxAno."<br>");
	print("Prox periodo: ".$proxPeriodo."<br>");
	print("Ultimo dia mes: ".$ultimoDiaMes."<br>");*/
	
	for ($i = 1; $i <= $ultimoDiaMes; $i++) {
		$fechaAInsertar = $proxAno."-".$proxMes."-".$i;
		$fechaAInsertar = strtotime($fechaAInsertar);
		$diaSemana = date ('N',$fechaAInsertar);
		if ($diaSemana < 6) {
			$sqlInsertDia[$c] = "INSERT INTO diasbancousimra VALUE($proxAno,$proxMes,$i,DEFAULT,DEFAULT,DEFAULT,'$fechaRegistro','$usuarioRegistro',DEFAULT,DEFAULT)";
			$c++;
		}
	}

	try {
		$hostname = $_SESSION['host'];
		$dbname = $_SESSION['dbname'];
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		for ($f = 0; $f < sizeof($sqlInsertDia); $f++) {
			//print($sqlInsertDia[$f]."<br>");
			$dbh->exec($sqlInsertDia[$f]);
		}
		$dbh->commit();
		$pagina = "procesamientoArchivos.php";
		Header("Location: $pagina"); 
	} catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
	
} else {
	while($rowDias = mysql_fetch_assoc($resDias)) {
		$dia = str_pad( $rowDias['dia'],2,'0',STR_PAD_LEFT);
		$mes = str_pad( $rowDias['mes'],2,'0',STR_PAD_LEFT);
		$diaProcesar = $dia."-".$mes."-".$rowDias['ano'];
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<body bgcolor="#B2A274">
<form id="form1" name="form1" method="post" action="verificacionArchivo.php">
  <div align="center">
    <input type="reset" name="volver" value="Volver" onClick="location.href = '../moduloBanco.php'" align="center"/>
  </div>
  <p align="center" class="Estilo1">Procesamiento de Archivos Transferidos</p>
  <p align="center">
    <label>Fecha del Archivo del Banco:
    <input readonly="readonly" style="background-color:#CCCCCC" id="fechaarchivo" name="fechaarchivo" type="text" value="<?php echo $diaProcesar ?>" size="10" />
    </label>
  </p>
  <?php  if(isset($_GET['err'])) { ?>
  <div align='center' style='color:#CC3333'><b>Error en Archivo - El Archivo solicitado no existe.</b></div>
  <div align='center'> <b>Verifique si el banco no envió el archivo o el día debe ser exceptuado de procesamiento</b><br>
    <br>
    <input type="button" name="exceptuar" value="Exceptuar" onclick="location.href = 'exceptuarDia.php?dia=<?php echo $diaProcesar ?>'"/>
  </div>
  <?php  } else {
	   		print("<div align='center'><input type='submit' name='Submit' value='Procesar' /></div>");
	   }	
?>
</form>
</body>
</html>
