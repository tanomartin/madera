<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");

if (isset($_POST['cuit'])) {
	$cuit= $_POST['cuit'];
} else {
	$cuit = $_GET['cuit'];
}
include($libPath."cabeceraEmpresaConsulta.php");
if ($tipo == "noexiste") {
	header ("Location: moduloImpresion.php?err=2");
	exit(0);
} else {
	$sqlacuerdos =  "select c.*, e.*, t.descripcion as tipo from cabacuerdosusimra c, estadosdeacuerdos e, tiposdeacuerdos t where c.cuit = $cuit and c.estadoacuerdo = e.codigo and c.tipoacuerdo = t.codigo order by nroacuerdo";
	$resulacuerdos= mysql_query( $sqlacuerdos,$db); 
	$cant = mysql_num_rows($resulacuerdos); 
	if ($cant == 0) {
		header('Location: moduloImpresion.php?err=1');
		exit(0);
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<script>

function imprimir(button,cuota,acuerdo,cuit) {
	button.disabled = true;
	var pagina = "acuboleta.php?cuota="+cuota+"&acuerdo="+acuerdo+"&cuit="+cuit;
	location.href = pagina;
}

</script>

<title>.: Sistema de Acuerdos USIMRA :.</title>
</head>
<body bgcolor="#B2A274">
	<div align="center">
	<p><input type="button" name="volver" value="Volver" onclick="location.href = 'moduloImpresion.php'" /></p>
	<?php 	include($libPath."cabeceraEmpresa.php"); ?>
	<h3>Acuerdos Existentes </h3>
	<table width="550" border="1" style="text-align: center">
		<?php while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) { ?>
				<tr>
			<?php 	if ($rowacuerdos['estadoacuerdo'] == 1) { ?>
						<td><a href="impBoletas.php?acuerdo=<?php echo $rowacuerdos['nroacuerdo']?>&cuit=<?php echo $cuit?>"> Acuerdo <?php echo $rowacuerdos['nroacuerdo']?> - <?php echo $rowacuerdos['tipo'] ?> - Acta: <?php echo $rowacuerdos['nroacta'] ?> - <?php echo $rowacuerdos['descripcion'] ?></a></td>
			 <?php 	} else { ?>
						<td>Acuerdo <?php echo $rowacuerdos['nroacuerdo']?> - <?php echo $rowacuerdos['tipo'] ?> - Acta: <?php echo $rowacuerdos['nroacta'] ?> - <?php echo $rowacuerdos['descripcion'] ?></td>
			 <?php	} ?>
				</tr>
	  <?php	} ?>	
	</table>
<?php if (isset($_GET["acuerdo"])) {
		  $acuerdo = $_GET["acuerdo"];
		  if ($acuerdo != 0) { ?>
		 		 <p><b>Cuotas Acuerdo Número <?php echo $acuerdo ?></b></p>
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
			<?php	$sqllistado = "select c.*, t.descripcion, t.imprimible from cuoacuerdosusimra c, tiposcancelaciones t where c.cuit = $cuit and c.nroacuerdo = $acuerdo and c.tipocancelacion = t.codigo";
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
			<?php		} 
			
						if ($rowListado['montopagada'] == 0) {
							if ($rowListado['imprimible']) {
								if ($rowListado['boletaimpresa'] == 0) {
									if ($rowListado['tipocancelacion'] == 3) {
										$nrocuota = $rowListado['nrocuota'];
										$sqlValorCobro = "select * from valoresalcobrousimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $nrocuota";
										$resValorCobro =  mysql_query( $sqlValorCobro,$db);
										$cantValor = mysql_num_rows($resValorCobro); 
											if ($cantValor == 1) {
												$rowValorCobro = mysql_fetch_array($resValorCobro);
													if ($rowValorCobro['chequenrousimra'] != 0) { ?>
														<td><input type="button" onclick="imprimir(this,'<?php echo $rowListado['nrocuota']?>','<?php echo $acuerdo ?>','<?php echo $cuit?>')" value="Imprimir"/></td>	
									
									<?php			} else { ?>
														<td>S/valor U.S.I.M.R.A</td>
									<?php			}
											//else de cantidad de valor al cobro.
											} else {  ?>
												<td>S/valor U.S.I.M.R.A</td>
								<?php		}
									// else del tipo de cancelacion
									} else { ?>
											<td><input type="button" onclick="imprimir(this,'<?php echo $rowListado['nrocuota']?>','<?php echo $acuerdo ?>','<?php echo $cuit?>')" value="Imprimir"/></td>	
						<?php		}
								// else de si la boleta ya esta inmpresa
								} else { ?>
									<td>Boleta Impresa</td>
					<?php		}	
							// else de si es imprimible o no (cheque, efectivo, valorAlCobro)
							} else { ?>
								<td>No Imprimible</td>
				<?php		}						
						// else de si el monto == 0	
						} else { ?>
							<td>Cancelada</td>
			<?php		}	?>
						</tr>
		<?php		}	?>
		  </table>
	<?php		}
			}?>
	</div>
</body>
</html>
