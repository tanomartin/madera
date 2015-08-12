<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 
include($libPath."fechas.php"); 
$cuit= $_POST['cuit'];
if ($cuit == NULL) {
	$cuit = $_GET['cuit'];
}

$sql = "select e.*, l.nomlocali, p.descrip as nomprovin from empresas e, localidades l, provincia p where e.cuit = $cuit and e.codlocali = l.codlocali and e.codprovin = p.codprovin";
$result = mysql_query( $sql,$db); 
$cant = mysql_num_rows($result); 
if ($cant != 1) {
	header ("Location: fiscalizacionImpresion.php?err=2");
} else {
	$row=mysql_fetch_array($result); 
	
	$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
	$resultlocalidad = mysql_query( $sqllocalidad,$db); 
	$rowlocalidad = mysql_fetch_array($resultlocalidad); 
	
	$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
	$resultprovi = mysql_query( $sqlprovi,$db); 
	$rowprovi = mysql_fetch_array($resultprovi);
	
	$sqlacuerdos =  "select * from cabacuerdosospim c, estadosdeacuerdos e where c.cuit = $cuit and c.estadoacuerdo = e.codigo order by nroacuerdo";
	$resulacuerdos= mysql_query( $sqlacuerdos,$db); 
	
	$cant = mysql_num_rows($resulacuerdos); 
	if ($cant == 0) {
		header('Location: fiscalizacionImpresion.php?err=1');
	}
}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>

<title>.: Sistema de Acuerdos OSPIM :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p>
  <input type="reset" name="volver" value="Volver" onclick="location.href = 'fiscalizacionImpresion.php'" />
  </p>
	 <?php 	
	    include($libPath."cabeceraEmpresa.php"); 
	?>
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="550" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_query( $query,$db);
			$rowtipos=mysql_fetch_array($result);
			if ($rowacuerdos['estadoacuerdo'] == 1) {
				echo ('<td align="center"><font face=Verdana size=3><a href="fiscalizacionImpBoletas.php?acuerdo='.$rowacuerdos['nroacuerdo'].'&cuit='.$cuit.'"> Acuerdo '.$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']." - Acta :".$rowacuerdos['nroacta']." - ".$rowacuerdos['descripcion']."</a></font></td>");
			} else {
				echo ('<td align="center"><font face=Verdana size=3>Acuerdo '.$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']." - Acta: ".$rowacuerdos['nroacta']." - ".$rowacuerdos['descripcion']."</font></td>");
			}
			print ("</tr>");
		}
		
	?>	
  </table>
  <p>
    <?php
  	$acuerdo = $_GET["acuerdo"];
		if ($acuerdo != 0) { ?>
  </p>
<form id="FIBOlettas" name="FIBoletas" method="post" action="acuboletapdf.php?acuerdo=<?php echo $acuerdo?>&cuit=<?php echo $cuit?>">
  <p><strong>Cuotas</strong> <strong>Acuerdo Número </strong> <?php echo $acuerdo ?></p>
  <table border="1" width="935" cellpadding="2" cellspacing="0">
				<tr>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cuota</font></strong></div></td>
   					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Vto.</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Tipo Cancelacion</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Estado</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Envia Boleta</font></strong></div></td>
				</tr>
			
			<?php	
			$hayboleta = 0;
			$sqllistado = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
			$reslistado = mysql_query( $sqllistado,$db); 
			while ($rowListado = mysql_fetch_array($reslistado)) {
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['nrocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['montocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowListado['fechacuota'])."</font></div></td>");
				
				$sqltipocan = "select * from tiposcancelaciones where codigo = $rowListado[tipocancelacion]";
				$restipocan =  mysql_query( $sqltipocan,$db);
				$rowtipocan = mysql_fetch_array($restipocan);
				
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowtipocan['descripcion']."</font></div></td>");
				
				if ($rowListado['chequenro'] == 0) {
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
				} else {
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['chequenro']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['chequebanco']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowListado['chequefecha'])."</font></div></td>");
				}
				if ($rowListado['montopagada'] == 0) {
					if ($rowtipocan['imprimible']) {
						if ($rowListado['boletaimpresa'] == 0) {
							if ($rowListado['tipocancelacion'] == 3) {
								$nrocuota = $rowListado['nrocuota'];
								$sqlValorCobro = "select * from valoresalcobro where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $nrocuota";
								$resValorCobro =  mysql_query( $sqlValorCobro,$db);
								$cantValor = mysql_num_rows($resValorCobro); 
									if ($cantValor == 1) {
										$rowValorCobro = mysql_fetch_array($resValorCobro);
											if ($rowValorCobro['chequenroospim'] != 0) {
												$hayboleta=1;
												print ("<td width=168><div align=center><font face=Verdana size=1><a href='fiscalizacionAcuBoleta.php?cuota=".$rowListado['nrocuota']."&acuerdo=".$acuerdo."&cuit=".$cuit."'>".Imprimir."</a></font></div></td>");
												print("<td width=168><div align=center><input type='checkbox' name='seleccion[]' value=".$rowListado['nrocuota']."></div></td>");
											// else si hay info de ospim.
											} else {
												print ("<td width=168><div align=center><font face=Verdana size=1>S/valor O.S.P.I.M.</font></div></td>");
												print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
											}
									//else de cantidad de valor al cobro.
									} else {
										print ("<td width=168><div align=center><font face=Verdana size=1>S/valor O.S.P.I.M.</font></div></td>");
										print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
									}
							// else del tipo de cancelacion
							} else {
								$hayboleta=1;
								print ("<td width=168><div align=center><font face=Verdana size=1><a href='fiscalizacionAcuBoleta.php?cuota=".$rowListado['nrocuota']."&acuerdo=".$acuerdo."&cuit=".$cuit."'>".Imprimir."</a></font></div></td>");
								print("<td width=168><div align=center><input type='checkbox' name='seleccion[]' value=".$rowListado['nrocuota']."></div></td>");
							}
						// else de si la boleta ya esta inmpresa
						} else {
							print ("<td width=168><div align=center><font face=Verdana size=1>Boleta Impresa</font></div></td>");
							print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
						}
						
					// else de si es imprimible o no (cheque, efectivo, valorAlCobro)
					} else {
						if ($rowListado['tipocancelacion'] == 0 && $rowListado['boletaimpresa'] == 0) {
							$hayboleta=1;						
							print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
							print("<td width=168><div align=center><input type='checkbox' name='seleccion[]' value=".$rowListado['nrocuota']."></div></td>");						
						}
						else {
							print ("<td width=168><div align=center><font face=Verdana size=1>No Imprimible</font></div></td>");
							print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
						}
					}						
				// else de si el monto == 0	
				} else {
					print ("<td width=168><div align=center><font face=Verdana size=1>Cancelada</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
				}
				
				print ("</tr>"); 
			}
			?>
  </table>
  <p>
    <?php
	if($hayboleta==1) { ?>

  </p>
  <p>
    <input type="submit" name="enviar" value="Enviar Archivos" />
   <?php
	} 
}?>
  </p>
  </form>
</div>
</body>
</html>
