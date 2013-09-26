<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php");
$cuit=$_GET['cuit'];
include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresaConsulta.php");
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

function estado($ano, $me, $db) {
	global $cuit, $anoinicio, $mesinicio, $anofin, $mesfin;
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
	$sqlPagos = "select * from afiptransferencias where cuit = $cuit and anopago = $ano and mespago = $me";
	$resPagos = mysql_query($sqlPagos,$db); 
	$CantPagos = mysql_num_rows($resPagos); 
	if($CantPagos > 0) {
		$des = "PAGO";
		print ("<td width=81><a href=javascript:abrirInfo('pagosOspim.php?origen=".$_GET['origen']."&cuit=".$cuit."&anio=".$ano."&mes=".$me."')>".$des."</a></td>");
		} else { 
			// VEO LOS PERIODOS ABARCADOS POR ACUERDO
			$sqlAcuerdos = "select * from detacuerdosospim where cuit = $cuit and anoacuerdo = $ano and mesacuerdo = $me" ;
			$resAcuerdos = mysql_query($sqlAcuerdos,$db); 
			$CantAcuerdos = mysql_num_rows($resAcuerdos); 
			if($CantAcuerdos > 0) {
				$rowAcuerdos = mysql_fetch_array($resAcuerdos); 
				$des = "ACUER.";
				print ("<td width=81><a href=javascript:abrirInfo('/ospim/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$rowAcuerdos['nroacuerdo']."&origen=empresa')>".$des."</a></td>");
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
				} //else
			} //else 
		} //else
		return $des;
	} //if
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
    <td width="254">*ACUER. = PERIODO EN ACUERDO</td>
    <td width="212">*PAGO = PERIODO PAGO CON DDJJ</td>
	<td width="544">*S. DJ.= PERIODO SIN DDJJ</td>
  </tr>
  <tr>
    <td>*NO PAGO = PERIODO NO PAGO CON DDJJ</td>
	<td>*JUICI.= PERIODO EN JUICIO </td>
    <td>*REQ = FISCALIZADO </td>
  </tr>
</table>
<br>
<input type="button" class="nover" name="imprimir" value="Imprimir" onClick="window.print();" />
</div>
</body>
</html>
