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

function deudaAnterior($cuit, $db) {
	//TODO VER LA DEUDA ANTERIOR
	return "S";
}

function liquidar($nroreq, $cuit, $db) {
	global $fechamodif, $usuariomodif;

	$sqlJuris = "SELECT * from empresas e, jurisdiccion j, provincia p where j.cuit = $cuit and j.cuit = e.cuit and j.codprovin = p.codprovin order by j.disgdinero DESC limit 1";
	$resJuris = mysql_query($sqlJuris,$db);
	$rowJuris = mysql_fetch_assoc($resJuris);
	$cuitconguiones = agregaGuiones($cuit);
	$telefono = $rowJuris['ddn'].$rowJuris['telefono'];
	
	$deuda = deudaAnterior($cuit, $db);
	
	$primeraLinea = $rowJuris['codidelega']."|000000|".$rowJuris['nombre']."|".$rowJuris['domireal']."|".$rowJuris['descrip']."|".$cuitconguiones."|".$rowJuris['numpostal']."|".$telefono."|".$deuda;


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
				$pagos[$p] = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$rowRequeDet['cantidadpersonal']."|".number_format((float)$rowRequeDet['remundeclarada'],2,',','')."|".invertirFecha($rowAfipProc['fechapago'])."|".number_format((float)$importe,2,',','');
				if ($p == 0) {	
					$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =".$rowRequeDet['anofiscalizacion']." and mesddjj =".$rowRequeDet['mesfiscalizacion'];
					$resAgrup = mysql_query($sqlAgrup,$db);
					$rowAgrup = mysql_fetch_assoc($resAgrup);
					$pagos[$p] = $pagos[$p]."|".$rowAgrup['cantcuilmayor1000']."|".number_format((float)$rowAgrup['remucuilmayor1000'],2,',','')."|".$rowAgrup['cantadhemayor1000']."|".number_format((float)$rowAgrup['remuadhemayor1000'],2,',','')."|".$rowAgrup['cantcuilmenor1001']."|".number_format((float)$rowAgrup['remucuilmenor1001'],2,',','')."|".$rowAgrup['cantadhemenor1001']."|".number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
				}
				$p++;
			}
		} else {
			if ($rowRequeDet['statusfiscalizacion'] == 'A') {
				$sqlAgrup = "SELECT * from agrufiscalizospim where cuit = $cuit and anoddjj =". $rowRequeDet['anofiscalizacion']." and mesddjj = ".$rowRequeDet['mesfiscalizacion'];
				$resAgrup = mysql_query($sqlAgrup,$db);
				$rowAgrup = mysql_fetch_assoc($resAgrup);
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|".$rowRequeDet['cantidadpersonal']."|".$rowRequeDet['remundeclarada']."|".$rowAgrup['cantcuilmayor1000']."|".number_format((float)$rowAgrup['remucuilmayor1000'],2,',','')."|".$rowAgrup['cantadhemayor1000']."|".number_format((float)$rowAgrup['remuadhemayor1000'],2,',','')."|".$rowAgrup['cantcuilmenor1001']."|".number_format((float)$rowAgrup['remucuilmenor1001'],2,',','')."|".$rowAgrup['cantadhemenor1001']."|".number_format((float)$rowAgrup['remuadhemenor1001'],2,',','');
			} else {
				$linea = "01/".$mes."/".$rowRequeDet['anofiscalizacion']."|0|0|0|0|0|0|0|0|0|0\n";
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
	$ultano = substr ($ultano,2,2);
	$nroreqCompleto = compeltarNroReq($nroreq); 
	$nombreArc = $cuit.$ultmes.$ultano."O".$nroreqCompleto.".txt";
	print("ARCHIVO: ".$nombreArc."<br><br>");
	$direArc = "liqui\\".$nombreArc;
	print($primeraLinea."<br>");
	
	//solo por ahora...
	unlink($direArc);
	//****************
	$ar=fopen($direArc,"x") or die("Hubo un error al generar el archivo de liquidación. Por favor cuminiquese con el dpto. de Sistemas");
	fputs($ar,$primeraLinea."\n");
	for ($i=0; $i < sizeof($cuerpo); $i++) {
		print($cuerpo[$i]."<br>");
		fputs($ar,$cuerpo[$i]."\n");
	}
	fclose($ar);

	$sqlUpdateReque = "UPDATE reqfiscalizospim SET procesoasignado = 1, fechamodificacion = '$fechamodif', usuariomodificacion = '$usuariomodif' WHERE nrorequerimiento = $nroreq";
	print("<br>".$sqlUpdateReque."<br>");
	
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
			$resultado[$resul] = array('nroreq' => $nroreq, 'estado' => "Se encuentra asociada a una inspección que nos se ha cerrado. No se liquidará", 'liquidado' => 0);
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
  	<p class="Estilo2">Resultado del proceso de liquidación los los requerimientos del d&iacute;a <?php echo $fecha ?>  </p>
	  <table width="800" border="1" align="center">
        <tr>
          <th>Req Nro.</th>
          <th>Resolución</th>
		  <th>Acción</th>
        </tr>
  <?php for ($i=0; $i < sizeof($resultado); $i++) {
			print("<tr align='center'>");
			print("<td>".$resultado[$i]['nroreq']."</td>");
			print("<td>".$resultado[$i]['estado']."</td>");   
			if ($resultado[$i]['liquidado'] == 0) {
				$dire = "consultaInspeccion.php?nroreq=".$resultado[$i]['nroreq'];
				print ("<td><a href=javascript:abrirInfo('".$dire."')>Ver Datos Inspección</a></td>");
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