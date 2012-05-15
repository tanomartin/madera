<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 
$cuit = $_GET['cuit'];
$nroacu = $_GET['nroacu'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: Consulta Acuerdo :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form name="verificador">
  <label>
  <div align="center"><strong><a href="acuerdos.php?cuit=<?php echo $cuit?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong>
  </div>
  <div align="center">
    <?php 
	//PARA LA CABECERA
	$sql = "select * from empresas where cuit = $cuit";
	$result = mysql_query($sql,$db); 
	$row = mysql_fetch_array($result); 
	
	$sqlDelEmp = "select * from delegaempresa where cuit = $cuit";
	$resDelEmp = mysql_query($sqlDelEmp,$db);
	$rowDelEmp = mysql_fetch_array($resDelEmp); 
	
	$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
	$resultlocalidad = mysql_query($sqllocalidad,$db); 
	$rowlocalidad = mysql_fetch_array($resultlocalidad); 
	
	$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
	$resultprovi = mysql_query($sqlprovi,$db); 
	$rowprovi = mysql_fetch_array($resultprovi);
	//FIN CABECERA
	include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/cabeceraEmpresa.php"); 
	
	$sqlCabecera = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
	$resCabecera = mysql_query($sqlCabecera,$db); 
	$canCabecera = mysql_num_rows($resCabecera); 
	if ($canCabecera == 1) {
		$rowCebecera = mysql_fetch_array($resCabecera); 
	} else {
		echo ("<div align='center'> Error en la lectura de la cabecera de acuerdo cargada </div>");
	}	
	
	?> 
    <p><strong>Acuerdo Cargado </strong><strong> NUMERO <?php echo $rowCebecera['nroacuerdo'] ?></strong>	</p>
    <p><strong>ESTADO </strong>
	<?php 
		$sqlEstado = "select * from estadosdeacuerdos where codigo = $rowCebecera[estadoacuerdo]";
		$resEstado= mysql_query($sqlEstado,$db); 
		$rowEstado = mysql_fetch_array($resEstado);
		echo $rowEstado['descripcion'];
	?>
	</p>
    <p><strong>Cabecera</strong></p>
    <table width="954" border="1">
      <tr>
        <td width="126" valign="bottom"><div align="left"><b>Tipo de Acuerdo</b></div></td>
        <td width="225" valign="bottom"><div align="left">
		<?php 
			$sqlTipoAcuerdo = "select * from tiposdeacuerdos where codigo = ".$rowCebecera['tipoacuerdo'];
			$resTipoAcuerdo = mysql_query($sqlTipoAcuerdo,$db);
			$rowTipoAcuerdo = mysql_fetch_array($resTipoAcuerdo);	
			echo $rowTipoAcuerdo['descripcion'];
		?>
		</div></td>
        <td width="106" valign="bottom"><div align="left"><b>Fecha Acuerdo</b></div></td>
        <td width="144" valign="bottom"><div align="left"><?php echo $rowCebecera['fechaacuerdo'] ?></div></td>
        <td width="158" valign="bottom"><div align="left"><b>N&uacute;mero de Acta</b></div></td>
        <td valign="bottom"><div align="left"><?php echo $rowCebecera['nroacta'] ?></div></td>
      </tr>
      <tr>
        <td valign="bottom"><div align="left"><b>Gestor</b></div></td>
        <td valign="bottom"><div align="left">
		<?php 
			$sqlGestor = "select * from gestoresdeacuerdos where codigo =". $rowCebecera['gestoracuerdo'];
			$resGestor = mysql_query($sqlGestor,$db);
			$rowGestor = mysql_fetch_array($resGestor);	
			echo $rowGestor['apeynombre'];
		?>
		</div></td>
		<td valign="bottom"><div align="left"><b>Inspector</b></div></td>
        <td valign="bottom"><div align="left">
		<?php 
			if ($rowCebecera['inspectorinterviene'] == 0) {
				echo "No Especificado";
			} else {
				$sqlInspec = "select * from inspectores where codigo = ".$rowCebecera['inspectorinterviene'];
				$resInspec = mysql_query($sqlInspec,$db);
				$rowInspec = mysql_fetch_array($resInspec);	
				echo $rowInspec['apeynombre'];
			}
		?></div></td>
        <td valign="bottom"><div align="left"><b>Requerimiento de Origen</b></div></td>
        <td valign="bottom"><div align="left"><?php if ($rowCebecera['requerimientoorigen'] == 0) { echo "-"; } else { echo $rowCebecera['requerimientoorigen']; }  ?></div></td>
      </tr>
      <tr>
        <td valign="bottom"><div align="left"><b>Liquidacion Origen</b></div></td>
        <td valign="bottom"><div align="left">
		<?php 
			if ($rowCebecera['requerimientoorigen'] == 0) {
				echo "-";
			} else {
				echo $rowCebecera['liquidacionorigen'];
			}
		?>
		</div></td>
        <td valign="bottom"><div align="left"><b>Monto Acuerdo</b> </div></td>
        <td valign="bottom"><div align="left"><?php echo $rowCebecera['montoacuerdo'] ?></div></td>
        <td valign="bottom"><div align="left"><b>Gastos Administrativos</b> </div></td>
        <td valign="bottom"><div align="left"><?php echo $rowCebecera['porcengastoadmin']."%" ?></div></td>
      </tr>
      <tr>
        <td height="23" valign="bottom"><div align="left"><b>Observaciones</b> </div></td>
        <td colspan="5" valign="bottom"><div align="left"><?php echo $rowCebecera['observaciones'] ?></div></td>
      </tr>
    </table>
    <p><strong>Per&iacute;odos</strong></p>
    <?php 
		$sqlPeriodos = "select * from detacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
		$resPeriodos = mysql_query($sqlPeriodos,$db); 
		$canPeriodos = mysql_num_rows($resPeriodos); 
		if ($canPeriodos != 0 ) { ?>
			<table width="431" height="32" border="1">
      			<tr>
        			<td width="107"><div align="center"><b>Mes</b></div></td>
					<td width="140"><div align="center"><b>A&ntilde;o</b></div></td>
					<td width="170"><div align="center"><b>Concepto de deuda </b></div></td>
      			</tr>
    	
		
		<?php 
			while ($rowPeriodos = mysql_fetch_array($resPeriodos)) {
				print ("<td width=107 align='center'><font face=Verdana size=2>".$rowPeriodos['mesacuerdo']."</font></td>");
				print ("<td width=140 align='center'><font face=Verdana size=2>".$rowPeriodos['anoacuerdo']."</font></td>");
				$sqlConcep = "select * from conceptosdeudas where codigo = '".$rowPeriodos['conceptodeuda']."'";
				$resConcep = mysql_query($sqlConcep,$db);
				$rowConcep = mysql_fetch_array($resConcep);	
				print ("<td width=170 align='center'><font face=Verdana size=2>".$rowConcep['descripcion']."</font></td>");
				print ("</tr>");
			} 
		?>
			</table>
		<?php 
		} else {
			echo ("<div align='center'>No hay periódos cargados relacionados con este acuerdo</div>");
		}	
	?>
	
	
    <p><strong>Cuotas</strong></p>
    <table width="1100" border="1">
      <tr>
        <td width="41"><div align="center"><b>Cuota </b></div></td>
        <td width="83"><div align="center"><b>Monto </b></div></td>
        <td width="98"><div align="center"><b>Fecha </b></div></td>
        <td width="117"><div align="center"><b>Cancelacion</b></div></td>
        <td width="108"><div align="center"><b>Nro Cheque</b> </div></td>
        <td width="105"><div align="center"><b>Banco </b></div></td>
        <td width="118"><div align="center"><b>Fecha Cheque </b></div></td>
		<td width="249"><div align="center"><b>Observaciones</b> </div></td>
		<td width="123"><div align="center"><b>Estado</b> </div></td>
      </tr>
	<?php 
		$sqlCuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
		$resCuotas = mysql_query($sqlCuotas,$db); 
		$canCuotas = mysql_num_rows($resCuotas); 
		if ($canCuotas != 0) {
			while ($rowCuotas = mysql_fetch_array($resCuotas)) {
				print ("<td width=41 align='center'><font face=Verdana size=2>".$rowCuotas['nrocuota']."</font></td>");
				print ("<td width=83 align='center'><font face=Verdana size=2>".$rowCuotas['montocuota']."</font></td>");
				print ("<td width=98 align='center'><font face=Verdana size=2>".invertirFecha($rowCuotas['fechacuota'])."</font></td>");
				print ("<td width=117 align='center'><font face=Verdana size=2>".$rowCuotas['tipocancelacion']."</font></td>");
				if ($rowCuotas['chequenro'] != 0) {
					print ("<td width=108 align='center'><font face=Verdana size=2>".$rowCuotas['chequenro']."</font></td>");
					print ("<td width=105 align='center'><font face=Verdana size=2>".$rowCuotas['chequebanco']."</font></td>");
					print ("<td width=118 align='center'><font face=Verdana size=2>".invertirFecha($rowCuotas['chequefecha'])."</font></td>");
				} else {
					print ("<td width=108 align='center'><font face=Verdana size=2>-</font></td>");
					print ("<td width=105 align='center'><font face=Verdana size=2>-</font></td>");
					print ("<td width=118 align='center'><font face=Verdana size=2>-</font></td>");
				}
				print ("<td width=249 align='center'><font face=Verdana size=2>".$rowCuotas['observaciones']."</font></td>");
				if ($rowCuotas['montopagada'] != 0) {
					print ("<td width=123 align='center'><font face=Verdana size=2>CANCELADA</font></td>");
				} else {
					if ($rowCuotas['boletaimpresa'] != 0) {
						print ("<td width=123 align='center'><font face=Verdana size=2>BOLETA IMPRESA</font></td>");
					} else {
						print ("<td width=123 align='center'><font face=Verdana size=2>A PAGAR</font></td>");
					}
				}
				
				print ("</tr>");
			}
		} else {
			echo ("<div align='center'>Error al leer las cuotas recien cargadas.</div>");
		}
	?>
	</table>
    <p>&nbsp;</p>
    <p>
      <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left">
    </p>
  </div>
</form>
</body>
</html>