<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 
if (isset($_POST['cuit'])) {
	$cuit= $_POST['cuit'];
} else {
	$cuit = $_GET['cuit'];
}

include($libPath."cabeceraEmpresaConsulta.php");
if ($tipo == "noexiste") {
	header('Location: moduloCancelacion.php?err=2');
	exit(0);
} else {	
	$sqlacuerdos =  "select c.*, e.*, t.descripcion as tipo from cabacuerdosospim c, estadosdeacuerdos e, tiposdeacuerdos t where c.cuit = $cuit and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo order by nroacuerdo";
	$resulacuerdos= mysql_query( $sqlacuerdos,$db); 
	$cant = mysql_num_rows($resulacuerdos); 
	if ($cant == 0) {
		header('Location: moduloCancelacion.php?err=1');
		exit(0);
	}
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Seleccion cuata a cancelar :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloCancelacion.php'" /></p>
	 <?php include($libPath."cabeceraEmpresa.php"); ?>
  	<h3>Acuerdos Existentes </h3>
  	<table width="550" border="1" style="text-align: center">
<?php while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) { ?>
		<tr>
			<td><a href="selecCanCuotas.php?acuerdo=<?php echo $rowacuerdos['nroacuerdo']?>&cuit=<?php echo $cuit?>"> Acuerdo <?php echo $rowacuerdos['nroacuerdo']?> - <?php echo $rowacuerdos['tipo'] ?> - Acta: <?php echo $rowacuerdos['nroacta'] ?> - <?php echo $rowacuerdos['descripcion'] ?></a></td>
		</tr>
<?php } ?>
  	</table>
<?php  if (isset($_GET["acuerdo"])) {
  			$acuerdo = $_GET["acuerdo"]; ?>
		  	<p><b>Cuotas Acuerdo Nº <?php echo $acuerdo ?></b> </p>
		  	<table border="1" width="935" style="text-align: center">
				<tr>
    				<th>Nro Cuota</th>
   					<th>Monto</th>
    				<th>Fecha Vto.</th>
    				<th>Tipo Cancelacion</th>
					<th>Nro Cheque</th>
					<th>Banco</th>
					<th>Fecha Cheque</th>
					<th>Estado</th>
				</tr>
	<?php	$sqllistado = "select c.*, t.descripcion, t.imprimible from cuoacuerdosospim c, tiposcancelaciones t where c.cuit = $cuit and c.nroacuerdo = $acuerdo and c.tipocancelacion = t.codigo";
			$reslistado = mysql_query( $sqllistado,$db); 
			while ($rowListado = mysql_fetch_array($reslistado)) { ?>
				<tr>
					<td><?php echo $rowListado['nrocuota']?></td>
					<td><?php echo $rowListado['montocuota']?></td>
					<td><?php echo invertirFecha($rowListado['fechacuota'])?></td>
					<td><?php echo $rowListado['descripcion']?></td>		
		<?php	if ($rowListado['chequenro'] == 0) { ?>
					<td>-</td>
					<td>-</td>
					<td>-</td>
		<?php	} else {  ?>
					<td><?php echo $rowListado['chequenro']?></td>
					<td><?php echo $rowListado['chequebanco']?></td>
					<td><?php echo invertirFecha($rowListado['chequefecha'])?></td>
		<?php	}
				if ($rowListado['tipocancelacion']!=8 && $rowListado['montopagada']==0 && $rowListado['fechapagada']=='0000-00-00') {
					if ($rowListado['boletaimpresa'] == 0) { ?>
						<td><input type="button" value="Cancelar Cuota" onclick="location.href = 'confirmarCancelacion.php?cuota=<?php echo $rowListado['nrocuota']?>&acuerdo=<?php echo $acuerdo?>&cuit=<?php echo $cuit?>'"/></td>
		<?php		} else { ?>
						<td>Boleta Impresa</td>
		<?php		}					
				// else de si el monto == 0	
				} else {
					if ($rowListado['tipocancelacion'] == 8) {  ?>
						<td>No Cancelable</td>
		<?php		} else {  ?>
						<td>Cancelada</td>
		<?php		}
				} ?>
				</tr> 
	<?php	} ?>
  			</table>
<?php	}?>
</div>
</body>
</html>
