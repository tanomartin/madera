<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
set_time_limit(0);
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php"); 
$fechamodif = date("Y-m-d H:i:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = $_GET['fecha'];
/**********************************************************************************/

function obtenerMesRelacion($mes, $anio, $db) {
	$sqlExtra = "SELECT relacionmes FROM extraordinariosusimra WHERE anio = $anio and mes = $mes and tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	return $rowExtra['relacionmes'];
}

function obtenerMesNoRem($mesrelacion, $anio, $db) {
	$sqlExtra = "SELECT mes FROM extraordinariosusimra WHERE anio = $anio and relacionmes = $mesrelacion and tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	return $rowExtra['mes'];
}

function incluyeNoRem($mesNoRem, $anio, $nroreque, $db) {
	$sqlRequeDet = "SELECT * from detfiscalizusimra where nrorequerimiento = $nroreque and anofiscalizacion = $anio and mesfiscalizacion = $mesNoRem";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	$canRequeDet = mysql_num_rows($resRequeDet);
	if ($canRequeDet > 0) {
		return true;
	} 
	return false;
}

function calculoBaseCalculoNR($remu, $mes, $anio, $db) {
	$sqlExtra = "SELECT tipo, valor FROM extraordinariosusimra WHERE anio = $anio and mes = $mes and tipo != 2";
	$resExtra = mysql_query($sqlExtra,$db);
	$rowExtra = mysql_fetch_assoc($resExtra);
	$baseCalculoNR = 0;
	if ($rowExtra['tipo'] == 0) {
		$baseCalculoNR = $rowExtra['valor'];
	}
	if ($rowExtra['tipo'] == 1) {
		$baseCalculoNR = $remu * $rowExtra['valor'];
	}
	return $baseCalculoNR;
}

function agregaGuiones($cuit) {
	$primero = substr ($cuit,0,2);
	$segundo = substr ($cuit,2,8);
	$tercero = substr ($cuit,10,1);
	$conguiones = $primero."-".$segundo."-".$tercero;
	return $conguiones;
}

function creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $cuerpo, $nroreqArc) {	
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/limitesTemporalesEmpresasUsimra.php");
		
	//DDJJ VALIDAS
	$sqlDDJJ = "select anoddjj, mesddjj, cuil, sum(remuneraciones) as remuneraciones from detddjjusimra 
					where cuit = $cuit and cuil != '99999999999' and
					((anoddjj > $anoinicio and anoddjj < $ultano) or 
	   				 (anoddjj = $ultano and mesddjj <= $ultmes) or 
	  				 (anoddjj = $anoinicio and mesddjj >= $mesinicio)) 
					group by anoddjj, mesddjj, cuil";
	$arrayDDJJ = array();
	$resDDJJ = mysql_query($sqlDDJJ,$db);
	while ($rowDDJJ = mysql_fetch_assoc($resDDJJ)) {
		$mes = str_pad($rowDDJJ['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJ['anoddjj'].$mes;
		$idArray = $rowDDJJ['anoddjj'].$mes.$rowDDJJ['cuil'];
		$arrayDDJJ[$idArray] = array ('origen' =>  1, 'datos' => $rowDDJJ, 'id' => $id);
		$idArray++;
	}
	
	//DDJJ TEMPORALES
	$sqlDDJJNroControl = "select perano, permes, nrctrl from ddjjusimra 
					   where nrcuit = $cuit and nrcuil = '99999999999' and
					   ((perano > $anoinicio and perano < $ultano) or 
	   				 	(perano = $ultano and permes <= $ultmes) or 
	  				 	(perano = $anoinicio and permes >= $mesinicio)) order by nrctrl, nrcuil DESC";
	$arrayNroControl = array();
	$resDDJJNroControl = mysql_query($sqlDDJJNroControl,$db);
	while ($rowDDJJNroControl = mysql_fetch_assoc($resDDJJNroControl)) {
		$idNrocontrol = $rowDDJJNroControl['perano'].$rowDDJJNroControl['permes'];
		$arrayNroControl[$idNrocontrol] =  $rowDDJJNroControl['nrctrl'];
	}
	foreach($arrayNroControl as $nrocontrol) {
		$wherein = $wherein."'".$nrocontrol."',";
	}
	$wherein = substr($wherein, 0, -1);
	$wherein = "(".$wherein.")";
	if ($wherein != "()") {
		$sqlDDJJTemp = "select perano as anoddjj, permes as mesddjj, nrcuil as cuil, remune as remuneraciones from ddjjusimra 
							where nrcuit = $cuit and nrcuil != '99999999999' and nrctrl in $wherein";
		$resDDJJTemp = mysql_query($sqlDDJJTemp,$db);
		$canDDJJTemp = mysql_num_rows($resDDJJTemp);
		if ($canDDJJTemp != 0) {
			while ($rowDDJJTemp = mysql_fetch_assoc($resDDJJTemp)) {
				$mes = str_pad($rowDDJJTemp['mesddjj'],2,'0',STR_PAD_LEFT);
				$id = $rowDDJJTemp['anoddjj'].$mes;
				$idArray = $rowDDJJTemp['anoddjj'].$mes.$rowDDJJTemp['cuil'];
				if (!array_key_exists($idArray, $arrayDDJJ)) {
					$arrayDDJJ[$idArray] = array ('origen' =>  1, 'datos' => $rowDDJJTemp, 'id' => $id);
				}
			}
		}
	}
	
	//DDJJ OSPIM			 	
	$sqlDDJJOspim = "select anoddjj, mesddjj, cuil, sum(remundeclarada) as remuneraciones from detddjjospim 
						where cuit = $cuit and cuil != '99999999999' and
							((anoddjj > $anoinicio and anoddjj < $ultano) or 
							 (anoddjj = $ultano and mesddjj <= $ultmes) or 
							 (anoddjj = $anoinicio and mesddjj >= $mesinicio))
						group by anoddjj, mesddjj, cuil";
	$resDDJJOspim = mysql_query($sqlDDJJOspim,$db);
	while ($rowDDJJOspim = mysql_fetch_assoc($resDDJJOspim)) {
		$mes = str_pad($rowDDJJOspim['mesddjj'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJOspim['anoddjj'].$mes;
		$idArray = $rowDDJJOspim['anoddjj'].$mes.$rowDDJJOspim['cuil'];
		if (!array_key_exists($idArray, $arrayDDJJ)) {
			$arrayDDJJ[$idArray] = array ('origen' =>  2, 'datos' => $rowDDJJOspim, 'id' => $id);
		}
	}
	
	
	ksort($arrayDDJJ);
	
	//NO REMUNERATIVO
	$sqlDDJJNR = "select d.anoddjj, d.mesddjj, e.relacionmes, d.cuil, sum(d.remuneraciones) as remuneraciones
						from detddjjusimra d, extraordinariosusimra e
						where d.cuit = $cuit and d.anoddjj > $anoinicio and 
							d.anoddjj <= $ultano and 
							d.mesddjj > 12 and 
							d.anoddjj = e.anio and 
							d.mesddjj = e.mes
						group by d.anoddjj, d.mesddjj, d.cuil"; 
	print($sqlDDJJNR);
	$resDDJJNR = mysql_query($sqlDDJJNR,$db);
	$arrayNR = array();
	while ($rowDDJJNR = mysql_fetch_assoc($resDDJJNR)) {
		$mes = str_pad($rowDDJJNR['relacionmes'],2,'0',STR_PAD_LEFT);
		$id = $rowDDJJNR['cuil'].$rowDDJJNR['anoddjj'].$mes;
		$arrayNR[$id] =  array ('datos' => $rowDDJJNR);
	}
	
	foreach ($cuerpo as $lineaCuerpo) {
		$linea = explode("|",$lineaCuerpo);
		$fechaArray =  explode("/",$linea[0]);
		$id = $fechaArray[2].$fechaArray[1];	
		$tipoPeriodo = $linea[5];
		if ($tipoPeriodo == 0) {
			$idBuscar[$id]['remun'] = 1; 
		}
		if ($tipoPeriodo == 1) {
			$idBuscar[$id]['norem'] = 1; 
		}
	}
	
	$c = 0;
	$cuerpoCUIL = array();
	foreach($arrayDDJJ as $ddjj) {
		$id = $ddjj['id'];
		if (array_key_exists($id, $idBuscar)) {
			$mes = str_pad($ddjj['datos']['mesddjj'],2,'0',STR_PAD_LEFT);
			$ano = $ddjj['datos']['anoddjj'];
			
			//veo si tengo que incluir el remunerativo
			if ($idBuscar[$id]['remun'] == 1) {
				$remuDecl = number_format((float)$ddjj['datos']['remuneraciones'],2,',','');	
			} else {
				$remuDecl = 0;
			}
			$remuDecl = str_pad($remuDecl,12,'0',STR_PAD_LEFT);
				
			//veo si tengo que incluir el no remunerativo
			if ($idBuscar[$id]['norem'] == 1) {
				$idNR = $ddjj['datos']['cuil'].$ano.$mes;
				$mesNoRem = obtenerMesNoRem($mes, $ano, $db);
				if (array_key_exists($idNR, $arrayNR)) {
					$norem = $arrayNR[$idNR]['datos']['remuneraciones'];
				} else {
					$norem = calculoBaseCalculoNR($ddjj['datos']['remuneraciones'], $mesNoRem, $ano, $db);
				}
			} else {
				$norem = 0;
			}
			$norem = number_format($norem,2,',','');
			$norem = str_pad($norem,12,'0',STR_PAD_LEFT);
			
			$indexCuerpo = $ano.$mes.$ddjj['origen'].$c;
			$cuerpoCUIL[$indexCuerpo] = "01/".$mes."/".$ano."|".agregaGuiones($ddjj['datos']['cuil'])."|".$remuDecl."|".$norem."|".$ddjj['origen'];
			$c++;
		}
	}
	
	//CREAMOS EL ARCHIVO
	$ultanoArch = substr ($ultano,2,2);
	$ultmesArch = str_pad($ultmes,2,'0',STR_PAD_LEFT);
	$nombreArcCUIL = $cuit.$ultmesArch.$ultanoArch.'S'.$nroreqArc.".txt";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArcCUIL;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArcCUIL;
	}

	//**********************************//
	ksort($cuerpoCUIL);
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de detalle de CUILES en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	foreach ($cuerpoCUIL as $lineaCuil) {
		fputs($ar,$lineaCuil."\r\n");
	}
	fclose($ar);
	//**********************************//
}

function cambioEstadoReq($nroreq) {
	global $fechamodif, $usuariomodif;
	$sqlUpdateReque = "UPDATE reqfiscalizusimra SET procesoasignado = 1, fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlUpdateReque);
		$dbh->commit();
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function grabarCabLiquidacion($nroreq, $nombreArcExc, $db) {
	$fechaLiqui = date("Ymd");
	$horaLiqui = date("His");
	$sqlCabeLiqui = "INSERT INTO cabliquiusimra VALUE($nroreq, $fechaLiqui, $horaLiqui, '$nombreArcExc', DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,DEFAULT, DEFAULT, DEFAULT, DEFAULT)";
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlCabeLiqui);
		$dbh->commit();
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function liquidar($nroreq, $cuit, $codidelega, $db) {
	//CREAMOS PRIMERA LINEA DEL ARCHIVO
	$sqlJuris = "SELECT e.*, j.*, p.descrip as provincia, l.nomlocali as localidad from empresas e, jurisdiccion j, provincia p, localidades l where j.cuit = $cuit and j.cuit = e.cuit and j.codidelega = $codidelega and j.codprovin = p.codprovin and j.codlocali = l.codlocali";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$cuitconguiones = agregaGuiones($cuit);
	
	//Coloco los tamaños fijos.
	$delcod = str_pad($rowJuris['codidelega'],4,'0',STR_PAD_RIGHT);
	$nombre = str_pad($rowJuris['nombre'],30,' ',STR_PAD_RIGHT);
	$domireal = str_pad($rowJuris['domireal'],30,' ',STR_PAD_RIGHT);
	$locaDescr = str_pad($rowJuris['localidad'],30,' ',STR_PAD_RIGHT);
	$provDescr = str_pad($rowJuris['provincia'],20,' ',STR_PAD_RIGHT);
	$numpostal = str_pad($rowJuris['numpostal'],4,'0',STR_PAD_RIGHT);
	
	if ($rowJuris['telefono'] == "" or $rowJuris['telefono'] == 0) {
		$telefono = $rowJuris['ddn1'].$rowJuris['telefono1'];
		$telefono = str_pad($telefono,14,' ',STR_PAD_RIGHT);
	} else {
		$telefono = $rowJuris['ddn'].$rowJuris['telefono'];
		$telefono = str_pad($telefono,14,' ',STR_PAD_RIGHT);
	}
	
	$primeraLinea = $delcod."|000000|".$nombre."|".$domireal."|".$locaDescr."|".$provDescr."|".$cuitconguiones."|".$numpostal."|".$telefono;
	
	//CREAMOS EL CUERPO DEL ARCHIVO CON LA DEUDA
	$cuerpo = array();
	$pagos = array();
	$l = 0;
	$sqlRequeDet = "SELECT * from detfiscalizusimra where nrorequerimiento = $nroreq";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	while ($rowRequeDet = mysql_fetch_assoc($resRequeDet)) {
		$mes = $rowRequeDet['mesfiscalizacion'];
		$tipoPeriodo = 0;
		if ($mes > 12) {
			$mes = obtenerMesRelacion($mes,$rowRequeDet['anofiscalizacion'],$db);
			$tipoPeriodo = 1;
		}
		$mes = str_pad($mes,2,'0',STR_PAD_LEFT);
		if ($rowRequeDet['statusfiscalizacion'] == 'F' || $rowRequeDet['statusfiscalizacion'] == 'M') {
			//ACA PODEMOS PONER lA LINEA 0 ya que la data viene del detalle y de la consulta de agrupfisca linea de información.
			unset($pagos);		
			$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
			$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
			$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
			$pOrd = 0;
			//PAGOS ORDINARIOS//
			$sqlAfipProc = "select fechapago, sum(montopagado) from seguvidausimra where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." group by fechapago order by fechapago";
			$resAfipProc = mysql_query($sqlAfipProc,$db);
			while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
				$fechaOrdinario = $rowAfipProc['fechapago'];
				$importeOrdinario = $rowAfipProc['sum(montopagado)'];
				$importeOrdinario = number_format((float)$importeOrdinario,2,',','');
				$importeOrdinario = str_pad($importeOrdinario,12,'0',STR_PAD_LEFT);
				$pagos[$pOrd] = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|".invertirFecha($fechaOrdinario)."|".$importeOrdinario."|".$tipoPeriodo;
				$personal = "0000";
				$remunDec = "000000000,00";
				$pOrd++;
			} 
			//****************//
		} else {
			unset($pagos);	
			if ($rowRequeDet['statusfiscalizacion'] == 'A' || $rowRequeDet['statusfiscalizacion'] == 'O') {	
				$personal = str_pad($rowRequeDet['cantidadpersonal'],4,'0',STR_PAD_LEFT);
				$remunDec = number_format((float)$rowRequeDet['remundeclarada'],2,',','');
				$remunDec = str_pad($remunDec,12,'0',STR_PAD_LEFT);
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$personal."|".$remunDec."|          |            |".$tipoPeriodo;
			} else {
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0000|000000000,00|          |            |".$tipoPeriodo;
			}
		}
		$indexFecha = $rowRequeDet['anofiscalizacion'].$mes;
		$l = 0;
		if (sizeof($pagos) > 0) {
			for ($n = 0; $n < sizeof($pagos); $n++) {
				$indexCuerpo = $indexFecha.$l.$tipoPeriodo;
				$cuerpo[$indexCuerpo] = $pagos[$n];
				$l++;
			}
		} else  {
			$indexCuerpo = $indexFecha.$l.$tipoPeriodo;
			$cuerpo[$indexCuerpo] = $linea;
		}
		
	}
	
	$sqlUltimoMes = "SELECT d.anofiscalizacion,  IFNULL(e.relacionmes,d.mesfiscalizacion) as mesorden
						FROM detfiscalizusimra d LEFT JOIN extraordinariosusimra e on d.mesfiscalizacion = e.mes and d.anofiscalizacion = e.anio
						where d.nrorequerimiento = $nroreq
						order by d.anofiscalizacion DESC, mesorden DESC limit 1";
	$resUltimoMes = mysql_query($sqlUltimoMes,$db);
	$rowUltimoMes = mysql_fetch_assoc($resUltimoMes);
	$ultano = $rowUltimoMes['anofiscalizacion'];
	$ultmes = $rowUltimoMes['mesorden'];
	
	//CREAMOS EL ARCHIVO DE DEUDA
	$ultanoArch = substr ($ultano,2,2);
	$ultmesArch = str_pad($ultmes,2,'0',STR_PAD_LEFT);
	$nroreqCompleto = str_pad($nroreq,8,'0',STR_PAD_LEFT);
	$nombreArc = $cuit.$ultmesArch.$ultanoArch."U".$nroreqCompleto.".txt";
	$nombreArcExc = $cuit.$ultmesArch.$ultanoArch."U".$nroreqCompleto.".xls";
	$maquina = $_SERVER['SERVER_NAME'];
	if(strcmp("localhost",$maquina) == 0) {
		$direArc = "liqui/".$nombreArc;
	} else {
		$direArc="/home/sistemas/Documentos/Liquidaciones/Preliquidaciones/".$nombreArc;
	}
	
	ksort($cuerpo);
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación de Deuda en $direArc. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\r\n");
	foreach ($cuerpo as $linea) {
		fputs($ar,$linea."\r\n");
	}
	fclose($ar);
	
	//**********************************
	creacionArchivoCuiles($cuit, $ultano, $ultmes, $db, $cuerpo, $nroreqCompleto);
	
	grabarCabLiquidacion($nroreq, $nombreArcExc, $db);
	
	cambioEstadoReq($nroreq);
	//**********************************
	
	return $nombreArc;
}

/***********************************************************************************/


$datos = array_values($_POST);
$reqALiquidar = array();
$resultado = array();
$req = 0;
$resul = 0;

for ($i=0; $i < sizeof($datos); $i++) {
	$nroreq = $datos[$i];
	$sqlRequeCab = "SELECT * from reqfiscalizusimra where nrorequerimiento = $nroreq";
	$resRequeCab = mysql_query($sqlRequeCab,$db);
	$rowRequeCab = mysql_fetch_assoc($resRequeCab);
	if ($rowRequeCab['procesoasignado'] == 0) {
		$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit'], 'codidelega' => $rowRequeCab['codidelega']);
		$req++;
	} else {
		$sqlRequeInsp = "SELECT * from inspecfiscalizusimra where nrorequerimiento = $nroreq";
		$resRequeInsp = mysql_query($sqlRequeInsp,$db);
		$rowRequeInsp= mysql_fetch_assoc($resRequeInsp);
		if ($rowRequeInsp['inspeccionefectuada'] == 1) {
			$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit'], 'codidelega' => $rowRequeCab['codidelega']);
			$req++;
		} else {
			$resultado[$resul] = array('nroreq' => $nroreq, 'estado' => "Se encuentra asociada a una inspección que nos se ha cerrado. No se liquidará", 'liquidado' => 0);
			$resul++;
		}
	}
}	

if (sizeof($reqALiquidar) != 0) {
	for ($i=0; $i < sizeof($reqALiquidar); $i++) {
		$nombreArc = liquidar($reqALiquidar[$i]['req'],$reqALiquidar[$i]['cuit'], $reqALiquidar[$i]['codidelega'], $db);
		$resultado[$resul] =  array('nroreq' => $reqALiquidar[$i]['req'], 'estado' => "Se ha liquidado en el archivo con nombre '".$nombreArc."'", 'liquidado' => 1);
		$resul++;
	}
} 

//cambio la hora de secion por ahora para no perder la misma
$ahora = date("Y-n-j H:i:s"); 
$_SESSION["ultimoAcceso"] = $ahora;

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos Liquidados:.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script>
function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=400, top=10, left=10");
}

</script>
</head>

<body bgcolor="#B2A274">
<div align="center">
  <p><span style="text-align:center">
    <input type="button" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" />
  </span></p>
  	<p class="Estilo2">Resultado del proceso de liquidación los los requerimientos del d&iacute;a <?php echo $fecha ?>  </p>
	  <table style="width: 800; text-align: center;" border=1>
        <tr>
          <th>Req Nro.</th>
          <th>Resolución</th>
		  <th>Acción</th>
        </tr>
<?php 	for ($i=0; $i < sizeof($resultado); $i++) { ?>
			<tr align='center'>
			<td><?php echo $resultado[$i]['nroreq'] ?></td>
			<td><?php echo $resultado[$i]['estado'] ?></td>  
<?php		if ($resultado[$i]['liquidado'] == 0) {
				$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq']; ?>
				<td><a href="javascript:abrirInfo('<?php echo $dire ?>')">Ver Datos Inspección</a></td>
<?php		} else {  ?>
				<td>-</td>   
<?php		} ?>
			</tr>
<?php	} ?>
      </table>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" />
  </p>
</div>
</body>
</html>