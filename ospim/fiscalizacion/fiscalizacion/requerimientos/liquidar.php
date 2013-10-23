<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 
$fechamodif = date("Y-m-d H:m:s");
$usuariomodif = $_SESSION['usuario'];
$fecha = $_GET['fecha'];
/**********************************************************************************/

function agregaGuiones($cuit) {
	$primero = substr ($cuit,0,2);
	$segundo = substr ($cuit,2,8);
	$tercero = substr ($cuit,10,1);
	$conguiones = $primero."-".$segundo."-".$tercero;
	return $conguiones;
}

function compeltarNroReq($nroreq) {
	if ($nroreq<10) {
		$nrocompleto = "0000000".$nroreq;
	} else {
		if ($nroreq<100) {
			$nrocompleto = "000000".$nroreq;
		} else {
			if ($nroreq<1000) {
				$nrocompleto = "00000".$nroreq;
			} else {
				if ($nroreq<10000) {
					$nrocompleto = "0000".$nroreq;
				} else {
					if ($nroreq<100000) {
						$nrocompleto = "000".$nroreq;
					} else {
						if ($nroreq<1000000) {
							$nrocompleto = "00".$nroreq;
						} else {
							if ($nroreq<10000000) {
								$nrocompleto = "0".$nroreq;
							} else {
								$nrocompleto = $nroreq;
							}
						}
					}
				}
			}
		} 
	} 
	return($nrocompleto);
}

function encuentroPagos($cuit, $anoInicioActivida, $mesInicioActividad, $anoInicioDeuda, $mesInicioDeuda, $db) {
	if ($anoInicioActivida == $anoInicioDeuda) {
		$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda and mespago >= $mesInicioActividad) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
	} else {
		$sqlPagos = "select anopago, mespago, fechapago, debitocredito, sum(importe) from afipprocesadas where cuit = $cuit and concepto != 'REM' and ((anopago > $anoInicioActivida and anopago < $anoInicioDeuda) or (anopago = $anoInicioDeuda and mespago < $mesInicioDeuda) or (anopago = $anoInicioActivida and mespago >= $mesInicioActividad)) group by anopago, mespago, debitocredito, fechapago order by anopago, mespago, fechapago";
	}
	$resPagos = mysql_query($sqlPagos,$db);
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		while ($rowPagos = mysql_fetch_assoc($resPagos)) { 
			$id=$rowPagos['anopago'].$rowPagos['mespago'];		
			$arrayPagos[$id] = array('anio' => (int)$rowPagos['anopago'], 'mes' => (int)$rowPagos['mespago'], 'estado' => 'PAGO');
		}
		return($arrayPagos);
	} else {
		return(0);
	}
}

function encuentroDeuda($ano, $me, $cuit, $anoinicio, $mesinicio, $anofin, $mesfin, $db) {
	if ($ano == $anoinicio) {
		if ($me < $mesinicio) {
			return(0);
		}
	}
	if ($ano == $anofin) {
		if ($me >= $mesfin) {
			return(0);
		}
	}
	
	// VEO LOS PERIODOS ABARCADOS POR ACUERDO
	$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from cabacuerdosospim c, detacuerdosospim d where c.cuit = $cuit and c.cuit = d.cuit and c.nroacuerdo = d.nroacuerdo and d.anoacuerdo = $ano and d.mesacuerdo = $me";
	$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
	$CantAcuerdos = mysql_num_rows($resAcuerdos); 
	if($CantAcuerdos > 0) {
		return(0);
	} else {
		//VEO LOS JUICIOS
		$sqlJuicio = "select c.nroorden, c.statusdeuda, c.nrocertificado from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
		$resJuicio = mysql_query($sqlJuicio,$db); 
		$CantJuicio = mysql_num_rows($resJuicio); 
		if ($CantJuicio > 0) {
			return(0);
		} else {
			// VEO LOS REQ DE FISC
			$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.requerimientoanulado = 0 and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
			$resReq = mysql_query($sqlReq,$db); 
			$CantReq = mysql_num_rows($resReq); 
			if($CantReq > 0) {
				return(0);
			} // IF REQUERMINETOS
		} // ELSE JUICIOS
	} // ELSE ACUERDOS
	return (1);
}

function deudaAnterior($cuit, $db) {
	$tipo = 'activa';
	$sqlEmpresasInicioActividad = "select iniobliosp from empresas where cuit = $cuit ";
	$resEmpresasInicioActividad = mysql_query($sqlEmpresasInicioActividad,$db);
	$rowEmpresasInicioActividad = mysql_fetch_assoc($resEmpresasInicioActividad);
	$fechaInicio = $rowEmpresasInicioActividad['iniobliosp'];
	$anioInicioActi = substr($fechaInicio,0,4);
	$mesInicioActi = substr($fechaInicio,5,2);
	include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
	
	//print("ANO INICIO ACTIVIDAD: ".$anioInicioActi."<br>");
	//print("MES INICIO ACTIVIDAD: ".$mesInicioActi."<br>");
	//print("ANO INICIO CALCULO DEUDA: ".$anoinicio."<br>");
	//print("MES INICIO CALCULO DEUDA: ".$mesinicio."<br>");
	
	if ($anioInicioActi == $anoinicio) {
		if ($mesInicioActi == $mesinicio) {
			return "N";
		} else { //ES MENOR EL MES DE INICIO DE ACTIVIDAD
			$pagos = encuentroPagos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
		}
	} else { //ES MENOR EL A�O DE INICIO DE ACTIVIDAD � 0000
		$pagos = encuentroPagos($cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
	} 

	if ($anioInicioActi == '0000' && $pagos == 0) {
		return "N";
	} else {
		if ($anioInicioActi == '0000') {
			foreach($pagos as $pago) {
				$anioInicioActi = $pago['anio'];
				$mesInicioActi = $pago['mes'];
				//print("ANO INICIO ACTIVIDAD: ".$anioInicioActi."<br>");
				//print("MES INICIO ACTIVIDAD: ".$mesInicioActi."<br>");
				break;
			}
		}
	}
	//var_dump($pagos);
	$deuda = 0;
	$ano = $anioInicioActi;
	while($ano<=$anoinicio && $deuda <= 2) {
		for ($i=1;$i<13;$i++){
			$idArray = $ano.$i;
			if (!array_key_exists($idArray, $pagos)) {
				$deuda = $deuda + encuentroDeuda($ano, $i, $cuit, $anioInicioActi, $mesInicioActi, $anoinicio, $mesinicio, $db);
				//print("DEUDA ACUMULA: ".$deuda."<br>");
			}
		}
		$ano++;
	}
	
	if($deuda > 2) {
		return 'S';
	} else {
		return 'N';
	}
}

function cambioEstadoReq($sqlUpdateReq) {
	$hostname = $_SESSION['host'];
	$dbname = $_SESSION['dbname'];
	try {
		$dbh = new PDO("mysql:host=$hostname;dbname=$dbname",$_SESSION['usuario'],$_SESSION['clave']);
		$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$dbh->beginTransaction();
		$dbh->exec($sqlUpdateReq);
		$dbh->commit();
	}catch (PDOException $e) {
		echo $e->getMessage();
		$dbh->rollback();
	}
}

function liquidar($nroreq, $cuit, $db) {
	global $fechamodif, $usuariomodif;

	//CREAMOS PRIMERA LINEA DEL ARCHIVO
	$sqlJuris = "SELECT * from empresas e, jurisdiccion j, provincia p where j.cuit = $cuit and j.cuit = e.cuit and j.codprovin = p.codprovin order by j.disgdinero DESC limit 1";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$cuitconguiones = agregaGuiones($cuit);
	$telefono = $rowJuris['ddn'].$rowJuris['telefono'];
	$deuda = deudaAnterior($cuit, $db);
	$primeraLinea = $rowJuris['codidelega']."|000000|".$rowJuris['nombre']."|".$rowJuris['domireal']."|".$rowJuris['descrip']."|".$cuitconguiones."|".$rowJuris['numpostal']."|".$telefono."|".$deuda;
	//**********************************
	
	//CREAMOS EL CUERPO DEL ARCHIVO CON LA DEUDA ************************************************************************
	$cuerpo = array();
	$pagos = array();
	$l = 0;
	$sqlRequeDet = "SELECT * from detfiscalizospim where nrorequerimiento = $nroreq";
	$resRequeDet = mysql_query($sqlRequeDet,$db);
	while ($rowRequeDet = mysql_fetch_assoc($resRequeDet)) {
		if ($rowRequeDet['mesfiscalizacion'] < 10) {
			$mes = "0".$rowRequeDet['mesfiscalizacion'];
		} else {
			$mes = $rowRequeDet['mesfiscalizacion'];
		}
		if ($rowRequeDet['statusfiscalizacion'] == 'F' || $rowRequeDet['statusfiscalizacion'] == 'M') {
			$sqlAfipProc = "select concepto, fechapago, sum(importe), debitocredito from afipprocesadas where cuit = $cuit and anopago = ".$rowRequeDet['anofiscalizacion']." and  mespago = ".$rowRequeDet['mesfiscalizacion']." and concepto != 'REM' group by concepto, fechapago, debitocredito order by fechapago, concepto, debitocredito";
			$resAfipProc = mysql_query($sqlAfipProc,$db);
			$p = 0;
			unset($pagos);
			while ($rowAfipProc = mysql_fetch_assoc($resAfipProc)) {
				$importe = "";
				if ($rowAfipProc['debitocredito'] == 'D') {
					$importe = "-".$rowAfipProc['sum(importe)'];
				} else {
					$importe = $rowAfipProc['sum(importe)'];
				}
				$pagos[$p] = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".number_format((float)$rowRequeDet['cantidadpersonal'],0,'','')."|".number_format((float)$rowRequeDet['remundeclarada'],2,',','')."|".invertirFecha($rowAfipProc['fechapago'])."|".number_format((float)$importe,2,',','');
				if ($p == 0) {	
					$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =".$rowRequeDet['anofiscalizacion']." and mesddjj =".$rowRequeDet['mesfiscalizacion'];
					$resAgrup = mysql_query($sqlAgrup,$db);
					$rowAgrup = mysql_fetch_assoc($resAgrup);
					$pagos[$p] = $pagos[$p]."|".number_format((float)$rowAgrup['cantcuilmayor1000'],0,'','')."|".number_format((float)$rowAgrup['remucuilmayor1000'],2,',','')."|".number_format((float)$rowAgrup['cantadhemayor1000'],0,'','')."|".number_format((float)$rowAgrup['remuadhemayor1000'],2,',','')."|".number_format((float)$rowAgrup['cantcuilmenor1001'],0,'','')."|".number_format((float)$rowAgrup['remucuilmenor1001'],2,',','')."|".number_format((float)$rowAgrup['cantadhemenor1001'],0,'','')."|".number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
				}
				$p++;
			}
		} else {
			unset($pagos);
			if ($rowRequeDet['statusfiscalizacion'] == 'A') {
				$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =". $rowRequeDet['anofiscalizacion']." and mesddjj = ".$rowRequeDet['mesfiscalizacion'];
				$resAgrup = mysql_query($sqlAgrup,$db);
				$rowAgrup = mysql_fetch_assoc($resAgrup);
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".number_format((float)$rowRequeDet['cantidadpersonal'],0,'','')."|".number_format((float)$rowRequeDet['remundeclarada'],2,',','')."|".number_format((float)$rowAgrup['cantcuilmayor1000'],0,'','')."|".number_format((float)$rowAgrup['remucuilmayor1000'],2,',','')."|".number_format((float)$rowAgrup['cantadhemayor1000'],0,'','')."|".number_format((float)$rowAgrup['remuadhemayor1000'],2,',','')."|".number_format((float)$rowAgrup['cantcuilmenor1001'],0,'','')."|".number_format((float)$rowAgrup['remucuilmenor1001'],2,',','')."|".number_format((float)$rowAgrup['cantadhemenor1001'],0,'','')."|".number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
			} else {
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0|0|0|0|0|0|0|0|0|0";
			}
		}
		
		
		if (sizeof($pagos) > 0) {
			for ($n = 0; $n < sizeof($pagos); $n++) {
				$cuerpo[$l] = $pagos[$n];
				$l++;
			}
		} else  {
			$cuerpo[$l] = $linea;
			$l++;
		}
		$ultmes = $mes;
		$ultano = $rowRequeDet['anofiscalizacion'];
	}
	//************************************************************************************************************************
	
	//CREAMOS EL ARCHIVO
	$ultano = substr ($ultano,2,2);
	$nroreqCompleto = compeltarNroReq($nroreq); 
	$nombreArc = $cuit.$ultmes.$ultano."O".$nroreqCompleto.".txt";
	//print("ARCHIVO: ".$nombreArc."<br><br>");
	$direArc = "liqui\\".$nombreArc;
	//print($primeraLinea."<br>");
	//solo por ahora...
	unlink($direArc);
	//****************
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidaci�n. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\n");
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		//print($cuerpo[$i]."<br>");
		fputs($ar,$cuerpo[$i]."\n");
	}
	fclose($ar);
	//**********************************
	
	//ACTULIZAMOS EL ESTADO DEL REQUERIMIENTO A 1.
	$sqlUpdateReque = "UPDATE reqfiscalizospim SET procesoasignado = 1, fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
	cambioEstadoReq($sqlUpdateReque);
	//print("<br>".$sqlUpdateReque."<br>");
	//**********************************
	
	return $nombreArc;
}

/***********************************************************************************/


$datos = array_values($_POST);
$reqALiquidar = array();
$resultado = array();
$req = 0;
$resul = 0;

for ($i=0; $i < sizeof($datos) - 1; $i++) {
	$nroreq = $datos[$i];
	$sqlRequeCab = "SELECT * from reqfiscalizospim where nrorequerimiento = $nroreq";
	$resRequeCab = mysql_query($sqlRequeCab,$db);
	$rowRequeCab = mysql_fetch_assoc($resRequeCab);
	if ($rowRequeCab['procesoasignado'] == 0) {
		$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit']);
		$req++;
	} else {
		$sqlRequeInsp = "SELECT * from inspecfiscalizospim where nrorequerimiento = $nroreq";
		$resRequeInsp = mysql_query($sqlRequeInsp,$db);
		$rowRequeInsp= mysql_fetch_assoc($resRequeInsp);
		if ($rowRequeInsp['inspeccionefectuada'] == 1) {
			$reqALiquidar[$req] = array ('req' => $nroreq, 'cuit' => $rowRequeCab['cuit']);
			$req++;
		} else {
			$resultado[$resul] = array('nroreq' => $nroreq, 'estado' => "Se encuentra asociada a una inspecci�n que nos se ha cerrado. No se liquidar�", 'liquidado' => 0);
			$resul++;
		}
	}
}	

if (sizeof($reqALiquidar) != 0) {
	for ($i=0; $i < sizeof($reqALiquidar); $i++) {
		$nombreArc = liquidar($reqALiquidar[$i]['req'],$reqALiquidar[$i]['cuit'], $db);
		$resultado[$resul] =  array('nroreq' => $reqALiquidar[$i]['req'], 'estado' => "Se ha liquidado en el archivo con nombre '".$nombreArc."'", 'liquidado' => 1);
		$resul++;
	}
} 

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado de Requerimientos Liquidados:.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoInspeccion",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, width=700, height=300, top=10, left=10");
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuFiscalizaciones.php'" align="center"/>
  </span></p>
  	<p class="Estilo2">Resultado del proceso de liquidaci�n los los requerimientos del d&iacute;a <?php echo $fecha ?>  </p>
	  <table width="800" border="1" align="center">
        <tr>
          <th>Req Nro.</th>
          <th>Resoluci�n</th>
		  <th>Acci�n</th>
        </tr>
  <?php for ($i=0; $i < sizeof($resultado); $i++) {
			print("<tr align='center'>");
			print("<td>".$resultado[$i]['nroreq']."</td>");
			print("<td>".$resultado[$i]['estado']."</td>");   
			if ($resultado[$i]['liquidado'] == 0) {
				$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq'];
				print ("<td><a href=javascript:abrirInfo('".$dire."')>Ver Datos Inspecci�n</a></td>");
			} else {
				print("<td>-</td>");   
			}
			print("</tr>");
		} ?>
      </table>
      <p>
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
  </p>
</div>
</body>
</html>