<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 
$cuit= $_POST['cuit'];
if ($cuit == NULL) {
	$cuit = $_GET['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_db_query("madera",$sql,$db); 
$row=mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_db_query("madera",$sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_db_query("madera",$sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlacuerdos =  "select * from cabacuerdosospim where cuit = $cuit";
$resulacuerdos= mysql_db_query("madera",$sqlacuerdos,$db); 

$cant = mysql_num_rows($resulacuerdos); 
if ($cant == 0) {
	header('Location: moduloImpresion.php?err=1');
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
  <p><strong><a href="moduloImpresion.php"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p><strong>Datos de la Empresa </strong></p>
  <table width="43%" height="156" border="2">
    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td width="23%" bordercolor="#0066CC"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT:</font></strong></div></td>
      <td width="77%" bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo  $row['cuit'];?></font></div></td>
    </tr>
    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td bordercolor="#0066CC"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Raz&oacute;n 
        Social:</font></strong></div></td>
      <td bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['nombre'];?></font></div></td>
    </tr>

    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td bordercolor="#0066CC"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Domicilio:</font></strong></div></td>
      <td bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['domilegal'];?></font></div></td>
    </tr>
    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td bordercolor="#0066CC"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Localidad:</font></strong></div></td>
      <td bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $rowlocalidad['nomlocali'];?></font></div></td>
    </tr>
    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td bordercolor="#0066CC"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Provincia</font></strong></div></td>
      <td bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $rowprovi['descrip']; ?></font></div></td>
    </tr>
    <tr bordercolor="#000000" bgcolor="#CCCCCC">
      <td bordercolor="#0066CC"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>C&oacute;digo 
        Postal:</strong></font></div></td>
      <td bordercolor="#0066CC"><div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><? echo $row['numpostal'];?></font></div></td>
    </tr>
  </table>
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="340" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_db_query("madera",$query,$db);
			$rowtipos=mysql_fetch_array($result);
			echo ('<td width=340  align="center"><font face=Verdana size=3><a href="impBoletas.php?acuerdo='.$rowacuerdos['nroacuerdo'].'&cuit='.$cuit.'"> Acuerdo '.$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']."</a></font></td>");
			print ("</tr>");
		}
		
	?>	
  </table>
  <p>
    <?php
  	$acuerdo = $_GET["acuerdo"];
		if ($acuerdo != "") { ?>
  </p>
  <p><strong>Cuotas</strong> <strong>Acuerdo Número </strong> <?php echo $acuerdo ?></p>
  <table border="1" width="935" bordercolorlight="#000099" bordercolordark="#0066FF" bordercolor="#000000" cellpadding="2" cellspacing="0">
				<tr>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cuota</font></strong></div></td>
   					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Vto.</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Tipo Cancelacion</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Estado</font></strong></div></td>
				</tr>
			
			<?php	
			$sqllistado = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
			$reslistado = mysql_db_query("madera",$sqllistado,$db); 
			while ($rowListado = mysql_fetch_array($reslistado)) {
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['nrocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowListado['montocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowListado['fechacuota'])."</font></div></td>");
				
				$sqltipocan = "select * from tiposcancelaciones where codigo = $rowListado[tipocancelacion]";
				$restipocan =  mysql_db_query("madera",$sqltipocan,$db);
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
								$resValorCobro =  mysql_db_query("madera",$sqlValorCobro,$db);
								$cantValor = mysql_num_rows($resValorCobro); 
									if ($cantValor == 1) {
										$rowValorCobro = mysql_fetch_array($resValorCobro);
											if ($rowValorCobro['chequenroospim'] != 0) {
												print ("<td width=168><div align=center><font face=Verdana size=1><a href='acuboleta.php?cuota=".$rowListado['nrocuota']."&acuerdo=".$acuerdo."&cuit=".$cuit."'>".Imprimir."</a></font></div></td>");
											// else si hay info de ospim.
											} else {
												print ("<td width=168><div align=center><font face=Verdana size=1>S/valor O.S.P.I.M.</font></div></td>");
											}
									//else de cantidad de valor al cobro.
									} else {
										print ("<td width=168><div align=center><font face=Verdana size=1>S/valor O.S.P.I.M.</font></div></td>");
									}
							// else del tipo de cancelacion
							} else {
								print ("<td width=168><div align=center><font face=Verdana size=1><a href='acuboleta.php?cuota=".$rowListado['nrocuota']."&acuerdo=".$acuerdo."&cuit=".$cuit."'>".Imprimir."</a></font></div></td>");
							}
						// else de si la boleta ya esta inmpresa
						} else {
							print ("<td width=168><div align=center><font face=Verdana size=1>Boleta Impresa</font></div></td>");
						}
						
					// else de si es imprimible o no (cheque, efectivo, valorAlCobro)
					} else {
						print ("<td width=168><div align=center><font face=Verdana size=1>No Imprimible</font></div></td>");
					}						
				// else de si el monto == 0	
				} else {
					print ("<td width=168><div align=center><font face=Verdana size=1>Cancelada</font></div></td>");
				}
				
				print ("</tr>"); 
			}
			?>
  </table>
<?php	}?>

</div>
</body>
</html>
