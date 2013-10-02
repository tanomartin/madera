<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");
$cuit=$_GET['cuit'];
include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresaConsulta.php");
$fechaInicio= $row['iniobliosp'];
include($_SERVER['DOCUMENT_ROOT']."/lib/limitesTemporalesEmpresas.php");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>

<style type="text/css">
<!--
.Estilo6 {
	font-size: 12px;
	font-weight: bold;
}
.Estilo7 {font-size: 14px}
-->
</style>
<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoPeriodoCuentaCorrienteEmpresa",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>
</head>
<?php

function estaVencido($resFechasPagos, $me, $ano) {
	if ($me == 12) {
		$mesvto = 1;
		$anovto = $ano + 1;
	} else {
		$mesvto = $me + 1;
		$anovto = $ano;
	}
	if ($mesvto < 10) {
		$mesvto = "0".$mesvto;
	}
	$diavto = 15;
	$fechaStr = $anovto.'-'.$mesvto.'-'.$diavto;
	while ($rowPagos = mysql_fetch_array($resFechasPagos)) {
		$fechaPago = $rowPagos['fechapago'];
		if (strcmp($fechaPago,$fechaStr) > 0) {
			return(1);
		}
	}
	return(0);
}

function estado($ano, $me, $db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
	//VEO QUE EL MES Y EL A�O ESTEND DENTRO DE LOS PERIODOS A MOSTRAR
	if ($ano == $anoinicio) {
		if ($me < $mesinicio) {
			$des = "-";
			print ("<td width=81>".$des."</td>");
			return($des);
		}
	}
	if ($ano == $anofin) {
		if ($me > $mesfin) {
			$des = "-";
			print ("<td width=81>".$des."</td>");
			return($des);
		}
	}
	
	//VEO LOS PAGOS DE AFIP
	$sqlPagos = "select fechapago from afiptransferencias where cuit = $cuit and anopago = $ano and mespago = $me group by fechapago";
	$resPagos = mysql_query($sqlPagos,$db); 
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		if (estaVencido($resPagos, $me, $ano)) {
			$des = "P.F.T.";
		} else {
			$des = "PAGO";
		}
		print ("<td width=81><a href=javascript:abrirInfo('pagosOspim.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$des."</a></td>");
	} else { 
		// VEO LOS PERIODOS ABARCADOS POR ACUERDO
		$sqlAcuerdos = "select c.nroacuerdo, c.estadoacuerdo from detacuerdosospim d, cabacuerdosospim c where c.cuit = $cuit and c.cuit = d.cuit and d.anoacuerdo = $ano and d.mesacuerdo = $me";
		$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
		$CantAcuerdos = mysql_num_rows($resAcuerdos); 
		if($CantAcuerdos > 0) {
			$rowAcuerdos = mysql_fetch_array($resAcuerdos); 
			if ($rowAcuerdos['estadoacuerdo'] == 0 ) {
				$des = "P. ACUER.";
			} else {
				$des = "ACUER.";
			}
			print ("<td width=81><a href=javascript:abrirInfo('/ospim/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$rowAcuerdos['nroacuerdo']."&origen=empresa')>".$des."</a></td>");
		} else {
			//VEO LOS JUICIOS
			$sqlJuicio = "select c.nroorden, c.statusdeuda from cabjuiciosospim c, detjuiciosospim d where c.cuit = $cuit and c.nroorden = d.nroorden and d.anojuicio = $ano and d.mesjuicio = $me";
			$resJuicio = mysql_query($sqlJuicio,$db); 
			$CantJuicio = mysql_num_rows($resJuicio); 
			if ($CantJuicio > 0) {
				$rowJuicio = mysql_fetch_array($resJuicio); 
				$statusDeuda = $rowJuicio['statusdeuda'];
				if ($statusDeuda == 1) {
					$des = "J.EJEC";
				}
				if ($statusDeuda == 2) {
					$des = "J.CONV";
				}
				if ($statusDeuda == 3) {
					$des = "J.QUIEB";
				}
				print ("<td width=81>".$des."</td>");
			} else {
				// VEO LOS REQ DE FISC
				$sqlReq = "select r.nrorequerimiento from reqfiscalizospim r, detfiscalizospim d where r.cuit = $cuit and r.nrorequerimiento = d.nrorequerimiento and d.anofiscalizacion = $ano and d.mesfiscalizacion = $me";
				$resReq = mysql_query($sqlReq,$db); 
				$CantReq = mysql_num_rows($resReq); 
				if($CantReq > 0) {
					$des = "REQ.";
					print ("<td width=81>".$des."</td>");
				} else {
					// VEO LAS DDJJ REALIZADAS SIN PAGOS
					$sqlDDJJ = "select * from cabddjjospim where cuit = $cuit and anoddjj = $ano and mesddjj = $me" ;
					$resDDJJ = mysql_query($sqlDDJJ,$db); 
					$CantDDJJ = mysql_num_rows($resDDJJ); 
					if($CantDDJJ > 0) {
						$des = "NO PAGO";
						print ("<td width=81><a href=javascript:abrirInfo('ddjjOspim.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$des."</a></td>");
					} else {
						// NO HAY DDJJ SIN PAGOS
						$des = "S.DJ.";
						print ("<td width=81>".$des."</td>");
					} //else DDJJ
				}//else REQ
			} //else JUICIOS
		}//else ACUERDOS
	} //else PAGOS 
	return $des;
} //function

?>
<title>.: Cuenta Corriente Empresa :.</title>
<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
	<?php if ($tipo == "activa") { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 
	<?php } else { ?>
			<input type="reset" class="nover" name="volver" value="Volver" onClick="location.href = '../empresaBaja.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>'" align="center"/> 
	<?php } ?>
	 <p>
    <?php 
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  </p>
   <p><strong>Cuenta Corriente </strong></p>
   <p><strong>Inicio Actividad: <?php echo invertirFecha($fechaInicio) ?></strong></p>
  	<?php if ($tipo == "baja") {?>
   		<p><strong>Fecha Baja Empresa: <?php echo invertirFecha($fechaBaja) ?></strong></p>
	<?php } ?>	
	
   <table width="1024" border="1" bordercolor="#000000" style="text-align:center; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:10px">
  <tr>
    <td width="52" rowspan="2"><span class="Estilo6">A&Ntilde;OS</span></td>
    <td colspan="12"><span class="Estilo6">MESES</span></td>
  </tr>
  <tr> 
	<td width="81" class="Estilo6">Enero</td>
    <td width="81" class="Estilo6">Febrero</td>
    <td width="81" class="Estilo6">Marzo</td>
    <td width="81" class="Estilo6">Abril</td>
    <td width="81" class="Estilo6">Mayo</td>
    <td width="81" class="Estilo6">Junio</td>
    <td width="81" class="Estilo6">Julio</td>
    <td width="81" class="Estilo6">Agosto</td>
    <td width="81" class="Estilo6">Setiembre</td>
    <td width="81" class="Estilo6">Octubre</td>
    <td width="81" class="Estilo6">Noviembre</td>
    <td width="81" class="Estilo6">Diciembre</td>
  </tr>
<?php
while($ano<=$anofin) {
  	print("<tr>");
  	print("<td width='52'><strong>".$ano."</strong></td>");
	for ($i=1;$i<13;$i++){
		$descri = estado($ano,$i, $db);
	}
	print("</tr>");
	$ano++;
}
?>
</table>
<br>
<table width="1024" border="0" style="font-size:12px">
  <tr>
  	<td>*PAGO =  PAGO CON DDJJ</td>
	<td>*P. ACUER. =  PAGO POR ACUERDO </td>
    <td>*P.F.T. = PAGO FUERA DE TERMINO </td>
	<td>*ACUER. =  EN ACUERDO</td>
  </tr>
  <tr>
    <td>*NO PAGO =  NO PAGO CON DDJJ</td>
	<td>*S. DJ.=  NO PAGO SIN DDJJ</td>
	<td>*REQ. = FISCALIZADO</td>
    <td>*J.EJEC = EN JUICIO EJECUI&Oacute;N </td>
  </tr>
  <tr>
    <td>*J.CONV = EN JUICIO CONVOCATORIA </td>
    <td>*J.QUIEB = EN JUICIO QUIEBRA </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br>
<input type="button" class="nover" name="imprimir" value="Imprimir" onClick="window.print();" />
</div>
</body>
</html>
