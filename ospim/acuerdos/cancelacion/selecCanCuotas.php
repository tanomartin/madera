<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 
$cuit= $_POST['cuit'];
if ($cuit == NULL) {
	$cuit = $_GET['cuit'];
}

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query( $sql,$db); 
$cantEmp = mysql_num_rows($result); 
if ($cantEmp == 0) {
	header('Location: moduloCancelacion.php?err=2');
}

$row=mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query( $sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query( $sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlacuerdos =  "select * from cabacuerdosospim where cuit = $cuit";
$resulacuerdos= mysql_query( $sqlacuerdos,$db); 

$cant = mysql_num_rows($resulacuerdos); 
if ($cant == 0) {
	header('Location: moduloCancelacion.php?err=1');
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

<title>.: Seleccion cuata a cancelar :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p>
  <input type="reset" name="volver" value="Volver" onClick="location.href = 'moduloCancelacion.php'" align="center"/>
  </p>
	 <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/lib/cabeceraEmpresa.php"); 
	?>
  <p><strong>Acuerdos Existentes </strong></p>
  <table width="340" border="1">
     <?php 
		while ($rowacuerdos = mysql_fetch_array($resulacuerdos)) {
			$query = "select * from tiposdeacuerdos where codigo = $rowacuerdos[tipoacuerdo]";
			$result=mysql_query( $query,$db);
			$rowtipos=mysql_fetch_array($result);
			echo ('<td width=340  align="center"><font face=Verdana size=3><a href="selecCanCuotas.php?acuerdo='.$rowacuerdos['nroacuerdo'].'&cuit='.$cuit.'"> Acuerdo '.$rowacuerdos['nroacuerdo']." - ".$rowtipos['descripcion']."</a></font></td>");
			print ("</tr>");
		}
		
	?>	
  </table>
  <p>
    <?php
  	$acuerdo = $_GET["acuerdo"];
		if ($acuerdo != 0) { ?>
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
				if ($rowListado['tipocancelacion']!=8 && $rowListado['montopagada']==0 && $rowListado['fechapagada']=='0000-00-00') {
					if ($rowListado['boletaimpresa'] == 0) {
						print ("<td width=168><div align=center><font face=Verdana size=1><a href='confirmarCancelacion.php?cuota=".$rowListado['nrocuota']."&acuerdo=".$acuerdo."&cuit=".$cuit."'>Cancelar</a></font></div></td>");
						// else de si la boleta ya esta inmpresa
					} else {
						print ("<td width=168><div align=center><font face=Verdana size=1>Boleta Impresa</font></div></td>");
					}					
				// else de si el monto == 0	
				} else {
					if ($rowListado['tipocancelacion'] == 8) {
						print ("<td width=168><div align=center><font face=Verdana size=1>No Cancelable</font></div></td>");
					} else {
						print ("<td width=168><div align=center><font face=Verdana size=1>Cancelada</font></div></td>");
					}
				}
				
				print ("</tr>"); 
			}
			?>
  </table>
<?php	}?>

</div>
</body>
</html>
