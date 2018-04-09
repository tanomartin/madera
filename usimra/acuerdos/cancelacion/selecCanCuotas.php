<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
include($libPath."fechas.php");

if (isset($_POST['cuit'])) {
	$cuit = $_POST['cuit'];
} else {
	$cuit = $_GET['cuit'];
}

$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result = mysql_query( $sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header('Location: moduloCancelacion.php?err=2');
} else {
	$row=mysql_fetch_array($result); 
	$sqlacuerdos =  "select c.*, t.descripcion from cabacuerdosusimra c, tiposdeacuerdos t where c.cuit = $cuit and c.tipoacuerdo = t.codigo";
	$resulacuerdos= mysql_query( $sqlacuerdos,$db); 
	$cant = mysql_num_rows($resulacuerdos); 
	if ($cant == 0) {
		header('Location: moduloCancelacion.php?err=1');
	}
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>.: Seleccion cuata a cancelar :.</title>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
</head>
<body bgcolor="#B2A274">
<div align="center">
  <input type="button" name="volver" value="Volver" onclick="location.href = 'moduloCancelacion.php'" /> 
  <?php 	
  		include($libPath."cabeceraEmpresaConsulta.php"); 
		include($libPath."cabeceraEmpresa.php"); 
	?>
  <h3>Acuerdos Existentes </h3>
  <table width="400" border="1" style="text-align: center">
<?php while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) { ?>
		<tr>
			<td><a href="selecCanCuotas.php?acuerdo=<?php echo $rowacuerdos['nroacuerdo'] ?>&cuit=<?php echo $cuit?>"> Acuerdo <?php echo $rowacuerdos['nroacuerdo'] ?> - <?php echo $rowacuerdos['descripcion'] ?></a></td>
		</tr>
<?php } ?>	
  </table>
<?php if (isset($_GET["acuerdo"])) {
  	  	$acuerdo = $_GET["acuerdo"];
		if ($acuerdo != 0) { ?>
		  <h3>Cuotas Acuerdo Número <?php echo $acuerdo ?></h3>
		  <div class="grilla">
			 <table>
			  	<thead>
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
				</thead>
				<tbody>
					<?php	
						$sqllistado = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo";
						$reslistado = mysql_query( $sqllistado,$db); 
						while ($rowListado = mysql_fetch_array($reslistado)) { 
							$sqltipocan = "select * from tiposcancelaciones where codigo = $rowListado[tipocancelacion]";
							$restipocan =  mysql_query( $sqltipocan,$db);
							$rowtipocan = mysql_fetch_array($restipocan); ?>
							<tr>
								<td><?php echo $rowListado['nrocuota'] ?></td>
								<td><?php echo $rowListado['montocuota'] ?></td>
								<td><?php echo invertirFecha($rowListado['fechacuota']) ?></td>
								<td><?php echo $rowtipocan['descripcion'] ?></td>
							
					<?php   if ($rowListado['chequenro'] == 0) { ?>		
								<td>-</td>
								<td>-</td>
								<td>-</td>
					<?php	} else { ?>
								<td><?php echo $rowListado['chequenro'] ?></td>
								<td><?php echo $rowListado['chequebanco'] ?></td>
								<td><?php echo invertirFecha($rowListado['chequefecha']) ?></td>
					<?php	}
							if ($rowListado['tipocancelacion']!=8 && $rowListado['montopagada']==0 && $rowListado['fechapagada']=='0000-00-00') {
								if ($rowListado['boletaimpresa'] == 0) { ?>
									<td><input type="button" value="Cancelar" onclick="location.href='confirmarCancelacion.php?cuota=<?php echo $rowListado['nrocuota']?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit?>'" /></td>
					<?php		} else { ?>
									<td>Boleta Impresa</td>
					<?php		}			
							// else de si el monto == 0	
							} else { 	
								if ($rowListado['tipocancelacion'] == 8) { ?>	
									<td>No Cancelable</td>
				<?php			} else {
									if ($rowListado['sistemacancelacion'] == 'M') { 
										$cuota = $rowListado['nrocuota'];
										$sqlConcilia = "select * from conciliacuotasusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
										$resConcilia = mysql_query($sqlConcilia,$db); 
										$rowConcilia = mysql_fetch_array($resConcilia);							
										if ($rowConcilia['estadoconciliacion'] == 0) { ?>
											<td>
												<input type="button" value="Cancelada - Modificar Datos Banco" onclick="location.href='datosConciliacion.php?cuota=<?php echo $rowListado['nrocuota'] ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>'"/>
											</td>
								<?php	} else { ?>
											<td><input type="button" value="Cancelada - Ver Datos Banco" onclick="location.href='verDatosConciliacion.php?cuota=<?php echo $rowListado['nrocuota'] ?>&acuerdo=<?php echo $acuerdo ?>&cuit=<?php echo $cuit ?>'" /></td>
								<?php	}
									} else { ?>
										<td>Cancelada</td>
							<?php	} 
								}
							} ?>
							</tr>
				<?php 	} ?>
				  </tbody>
			  </table>
		  </div>
<?php	}
	} ?>
</div>
</body>
</html>
