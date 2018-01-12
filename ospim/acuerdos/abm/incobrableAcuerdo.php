<?php $libPath = $_SERVER ['DOCUMENT_ROOT'] . "/madera/lib/";
include($libPath."controlSessionOspim.php");
include($libPath."fechas.php"); 
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
<body bgcolor="#CCCCCC">
<form name="verificador">
  <div align="center">
	<input type="reset" name="volver" value="Volver" onClick="location.href = 'acuerdos.php?cuit=<?php echo $cuit ?>'" />
<?php include($libPath."cabeceraEmpresaConsulta.php"); 
	 include($libPath."cabeceraEmpresa.php"); 
	
	$sqlCabecera = "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu";
	$resCabecera = mysql_query($sqlCabecera,$db); 
	$canCabecera = mysql_num_rows($resCabecera); 
	if ($canCabecera == 1) {
		$rowCebecera = mysql_fetch_array($resCabecera); 
	} else {
		echo ("<div align='center'> Error en la lectura de la cabecera de acuerdo cargada </div>");
	}	
	
	?> 
    <p><strong>O.S.P.I.M. - Acuerdo Cargado </strong><strong> NUMERO <?php echo $rowCebecera['nroacuerdo'] ?></strong>	</p>
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
        <td width="144" valign="bottom"><div align="left"><?php echo invertirFecha($rowCebecera['fechaacuerdo']) ?></div></td>
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
		</div>
		</td>
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
 	<p><input type="button" name="incobrable" value="Confirmar INCOBRABLE" onclick="window.location = 'incobrableAcuerdoGuardar.php?cuit=<?php echo $cuit ?>&nroacu=<?php echo $nroacu ?>'"/> </p>
  </div>
</form>
</body>
</html>