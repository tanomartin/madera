<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 
$cuit = $_GET['cuit'];
$nroorden = $_GET['nroorden'];
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<title>.: Consulta Juicio :.</title>
</head>
<body bgcolor="#CCCCCC">
<form name="verificador">
  <div align="center"><input type="reset" name="volver" value="Volver" onClick="location.href = 'juicios.php?cuit=<?php echo $cuit ?>'" align="center"/> </div>
  <div align="center">
    <?php 
	include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresaConsulta.php"); 
	include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	
	$sqlCabecera = "select c.*, e.*, a.apeynombre as asesor, i.apeynombre as inspector from cabjuiciosospim c, estadosdeacuerdos e, asesoreslegales a, inspectores i where cuit = $cuit and nroorden = $nroorden and c.statusdeuda = e.codigo and c.codasesorlegal = a.codigo and c.codinspector = i.codigo";
	$resCabecera = mysql_query($sqlCabecera,$db); 
	$canCabecera = mysql_num_rows($resCabecera); 
	if ($canCabecera == 1) {
		$rowCebecera = mysql_fetch_array($resCabecera); 
	} else {
		echo ("<div align='center'> Error en la lectura de la cabecera del juicio cargado </div>");
	}	
	
	?> 
    <p><strong>O.S.P.I.M. - Juicio </strong><strong> Nro. Orden <?php echo $rowCebecera['nroorden'] ?></strong>	</p>
    <p><strong>ESTADO DE DEUDA </strong><?php echo $rowCebecera['descripcion']; ?></p>
    <p><strong>Cabecera</strong></p>
    <table width="954" border="1" style="text-align:left">
      <tr>
        <td><b>Certificado</b></td>
        <td><?php echo $rowCebecera['nrocertificado']; ?></td>
        <td><b>Fecha Expedición</b></td>
        <td><?php echo invertirFecha($rowCebecera['fechaexpedicion']) ?></td>
        <td><b>Incluye Acuerdo</b></td>
        <td><?php if ($rowCebecera['acuerdorelacionado'] == 0) { echo "NO"; } else { echo "SI"; } ?></td>
      </tr>
      <tr>
        <td><b>Nro. Acuerdo</b></td>
        <td><?php echo $rowCebecera['nroacuerdo'];?></td>
		<td><b>Deuda Historica</b></td>
        <td><?php echo $rowCebecera['deudahistorica']?></td>
        <td><b>Intereses</b></td>
        <td><?php echo $rowCebecera['intereses'] ?></td>
      </tr>
      <tr>
        <td><b>Deuda Actualizada</b></td>
        <td><?php echo $rowCebecera['deudaactualizada'];?></td>
        <td><b>Asesor Legal</b></td>
        <td><?php echo $rowCebecera['asesor']; ?></td>
        <td><b>Inspector</b></td>
        <td><?php echo $rowCebecera['inspector']; ?></td>
      </tr>
    </table>
    <p><strong>Per&iacute;odos</strong></p>
    <?php 
		$sqlPeriodos = "select * from detjuiciosospim where nroorden = $nroorden order by anojuicio ASC, mesjuicio ASC";
		$resPeriodos = mysql_query($sqlPeriodos,$db); 
		$canPeriodos = mysql_num_rows($resPeriodos); 
		if ($canPeriodos != 0 ) { ?>
			<table width="200" height="32" border="1">
      			<tr>
        			<td><div align="center"><b>Mes</b></div></td>
					<td><div align="center"><b>A&ntilde;o</b></div></td>
      			</tr>
    	
		
		<?php 
			while ($rowPeriodos = mysql_fetch_array($resPeriodos)) {
				print ("<td width=107 align='center'><font face=Verdana size=2>".$rowPeriodos['mesjuicio']."</font></td>");
				print ("<td width=140 align='center'><font face=Verdana size=2>".$rowPeriodos['anojuicio']."</font></td>");
				print ("</tr>");
			} 
		?>
			</table>
		<?php 
		} else {
			echo ("<div align='center'>No hay periódos cargados relacionados con este juicio</div>");
		}	
	?>
	  <p><strong>Tramite Judicial</strong></p>
    <?php 
		$sqlTramite = "select t.*, j.denominacion as juzgado, s.denominacion as secretaria, e.descripcion as estadoprocesal from trajuiciosospim t, juzgados j, secretarias s, estadosprocesales e where nroorden = $nroorden and t.codigojuzgado = j.codigojuzgado and t.codigojuzgado = s.codigojuzgado and t.codigosecretaria = s.codigosecretaria and t.estadoprocesal = e.codigo";
		$resTramite = mysql_query($sqlTramite,$db); 
		$canTramite = mysql_num_rows($resTramite); 
		if ($canTramite != 0 ) { 
			$rowTramite = mysql_fetch_array($resTramite)
			?>
			<table width="954" border="1" style="text-align:left">
			  <tr>
				<td><b>Fecha Inicio</b></td>
				<td><?php echo invertirFecha($rowTramite['fechainicio']); ?></td>
				<td><b>Autos</b></td>
				<td><?php echo $rowTramite['autoscaso'] ?></td>
				<td><b>Juzgado</b></td>
				<td><?php echo $rowTramite['juzgado'] ?></td>
			  </tr>
			  <tr>
				<td><b>Secretaria</b></td>
				<td><?php echo $rowTramite['secretaria'];?></td>
				<td><b>Expediente</b></td>
				<td><?php echo $rowTramite['nroexpediente']?></td>
				<td><b>Bienes Embargados</b></td>
				<td><?php echo $rowTramite['bienesembargados'] ?></td>
			  </tr>
			  <tr>
				<td><b>Estado Procesal</b></td>
				<td><?php echo $rowTramite['estadoprocesal'];?></td>
				<td><b>Fecha Finalizacion</b></td>
				<td><?php echo invertirFecha($rowTramite['fechafinalizacion']) ?></td>
				<td><b>Monto Cobrado</b></td>
				<td><?php echo $rowTramite['montocobrado']; ?></td>
			  </tr>
			</table>
		<?php 
		} else {
			echo ("<div align='center'>No hay tramite para este Juicio</div>");
		}	
	?>
	
  </div>
  <div align="center"><p><input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/> </p></div>
</form>
</body>
</html>