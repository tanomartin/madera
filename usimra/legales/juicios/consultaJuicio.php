<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php"); 
$cuit = $_GET['cuit'];
$nroorden = $_GET['nroorden'];

$sqlCabecera = "select c.*, a.apeynombre as asesor, i.apeynombre as inspector from cabjuiciosusimra c, asesoreslegales a, inspectores i where c.cuit = $cuit and c.nroorden = $nroorden and c.codasesorlegal = a.codigo and c.codinspector = i.codigo limit 1";
$resCabecera = mysql_query($sqlCabecera,$db);
$canCabecera = mysql_num_rows($resCabecera);

$sqlPeriodos = "select * from detjuiciosusimra where nroorden = $nroorden order by anojuicio ASC, mesjuicio ASC";
$resPeriodos = mysql_query($sqlPeriodos,$db);
$canPeriodos = mysql_num_rows($resPeriodos);

$sqlTramite = "select t.*, e.descripcion as estadoprocesaldescri from trajuiciosusimra t, estadosprocesales e where t.nroorden = $nroorden and t.estadoprocesal = e.codigo";
$resTramite = mysql_query($sqlTramite,$db);
$canTramite = mysql_num_rows($resTramite);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Consulta Juicio :.</title>
</head>
<body bgcolor="#B2A274">
<form name="verificador">
	<div align="center">
<?php if (!isset($_GET['origen'])) { ?>
		<input type="button" name="volver" value="Volver" onClick="location.href = 'juicios.php?cuit=<?php echo $cuit ?>'" /> 
<?php } 
		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); 
		if ($canCabecera == 1) {
			$rowCebecera = mysql_fetch_array($resCabecera); ?>
		    <h3>U.S.I.M.R.A. - Juicio Nro. Orden <?php echo $rowCebecera['nroorden'] ?></h3>
		    <p>Situacion Deuda Judicial: 
			
			<?php   $statusDeuda = $rowCebecera['statusdeuda'];
					if ($statusDeuda == 1) {
						$des = "EJECUCION";
					}
					if ($statusDeuda == 2) {
						$des = "CONVOCATORIA";
					}
					if ($statusDeuda == 3) {
						$des = "QUIEBRA";
					}?>
					<b><?php echo $des ?></b>
			</p>
		    <p><b>Cabecera</b></p>
		    <table width="954" border="1" style="text-align:left">
		      <tr>
		        <td><b>Certificado</b></td>
		        <td><?php echo $rowCebecera['nrocertificado']; ?></td>
		        <td><b>Fecha Expedici�n</b></td>
		        <td><?php echo invertirFecha($rowCebecera['fechaexpedicion']) ?></td>
		        <td><b>Incluye Acuerdo</b></td>
		        <td><?php if ($rowCebecera['acuerdorelacionado'] == 0) { echo "NO"; } else { echo "SI (Nro: <b>".$rowCebecera['nroacuerdo']."</b>)"; } ?></td>
		      </tr>
		      <tr>
				<td><b>Deuda Historica</b></td>
		        <td><?php echo $rowCebecera['deudahistorica']?></td>
		        <td><b>Intereses</b></td>
		        <td><?php echo $rowCebecera['intereses'] ?></td>
				<td><b>Deuda Actualizada</b></td>
		        <td><?php echo $rowCebecera['deudaactualizada'];?></td>
		      </tr>
		      <tr>
		        <td><b>Asesor Legal</b></td>
		        <td><?php echo $rowCebecera['asesor']; ?></td>
		        <td><b>Inspector</b></td>
		        <td><?php echo $rowCebecera['inspector']; ?></td>
				<td><b>Ejecutor</b></td>
		        <td><?php echo $rowCebecera['usuarioejecutor'];?></td>
		      </tr>
		    </table> 
<?php   } else { ?>
			<p style="color: red"><b> Error en la lectura de la cabecera del juicio cargado </b></p>
<?php	} ?> 
    <p><b>Per&iacute;odos</b></p>
    <?php 
		if ($canPeriodos != 0 ) { ?>
			<table width="300" height="32" border="1" style="text-align: center">
      			<tr>
        			<td><b>Mes</b></td>
					<td><b>A�o</b></td>
			<?php 	if ($rowCebecera['acuerdorelacionado'] == 1) { ?><td><b>+ Info</b></td> <?php } ?>
      			</tr>
		<?php   while ($rowPeriodos = mysql_fetch_array($resPeriodos)) { ?>
					<tr>
						<td><?php echo $rowPeriodos['mesjuicio'] ?></td>
						<td><?php echo $rowPeriodos['anojuicio'] ?></td>
				  <?php if ($rowCebecera['acuerdorelacionado'] == 1) { ?>
							<td><?php if ($rowPeriodos['nroacuerdo'] != 0) { 
							        echo "Abs A. Nro: ".$rowPeriodos ['nroacuerdo']; 
								  } ?>
							</td>
			      <?php } ?>
					</tr>
		<?php	} ?>
			</table>
		<?php 
		} else { ?>
			<p style="color: blue"><b>No hay peri�dos cargados relacionados con este juicio</b></p>
<?php	}	?>
	  <p><b>Tramite Judicial</b></p>
       <?php 
		if ($canTramite != 0 ) { 
			$rowTramite = mysql_fetch_array($resTramite);
			if ($rowTramite['estadoprocesal'] != 3) {
				$sqlJuzgadoSecretaria = "select j.denominacion as juzgado, s.denominacion as secretaria from juzgados j, secretarias s where j.codigojuzgado = ".$rowTramite['codigojuzgado']." and s.codigojuzgado = ".$rowTramite['codigojuzgado']." and s.codigosecretaria = ".$rowTramite['codigosecretaria'];
				$resJuzgadoSecretaria = mysql_query($sqlJuzgadoSecretaria,$db); 
				$canJuzgadoSecretaria = mysql_num_rows($resJuzgadoSecretaria); 
				if ($canJuzgadoSecretaria != 0) {
					$rowJuzgadoSecretaria = mysql_fetch_array($resJuzgadoSecretaria);
					$juzgado = $rowJuzgadoSecretaria['juzgado'];
					$secretaria = $rowJuzgadoSecretaria['secretaria'];
				} else {
					$juzgado = "No se encontr� Juzgado";
					$secretaria = "No se encontr� Secretar�a";
				}
			} else {
				$juzgado = "-";
				$secretaria = "-";
			}
			?>
			<table width="954" border="1" style="text-align:left">
			  <tr>
				<td><b>Fecha Inicio</b></td>
				<td><?php echo invertirFecha($rowTramite['fechainicio']); ?></td>
				<td><b>Expediente</b></td>
				<td><?php echo $rowTramite['nroexpediente']?></td>
			  </tr>
			  <tr>
				<td><b>Juzgado</b></td>
				<td colspan="3"><?php echo $juzgado ?></td>
			  </tr>
			  <tr>
			    <td><b>Secretaria</b></td>
			    <td><?php echo $secretaria ?></td>
			    <td><b>Estado Procesal</b></td>
			    <td><?php echo $rowTramite['estadoprocesaldescri'];?></td>
		      </tr>
			  <tr>
			    <td><b>Auto Caso </b></td>
			    <td colspan="3"><?php echo $rowTramite['autoscaso'] ?></td>
		      </tr>
			  <tr>
				<td><b>Bienes Embargados</b></td>
				<td colspan="3"><?php echo $rowTramite['bienesembargados'] ?></td>
			  </tr>
			  <tr>
				<td><b>Observacion</b></td>
				<td colspan="3"><?php echo $rowTramite['observacion'] ?></td>
			  </tr>
			  
			  <tr>
			    <td colspan="4"><div align="center">
			      <p><strong>Datos Finalizaci�n de Tr�mite</strong></p>
			    </div></td>
	          </tr>
			  <tr>
			    <td><b>Fecha Finalizacion</b></td>
			    <?php 
			    	$fechaTra = "";
			    	if ($rowTramite['fechafinalizacion'] != "0000-00-00") { $fechaTra = invertirFecha($rowTramite['fechafinalizacion']); } ?>
			    <td><?php echo $fechaTra ?></td>
			    <td><b>Monto Cobrado</b></td>
			    <td><?php if ( $rowTramite['montocobrado']!=0) { echo $rowTramite['montocobrado']; } ?></td>
		      </tr>
			</table>
		<?php 
		} else { ?>
			<p style="color: blue"><b>No hay tramite para este Juicio</b></p>
<?php	} ?>
  		<p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" /> </p>
	</div>
</form>
</body>
</html>