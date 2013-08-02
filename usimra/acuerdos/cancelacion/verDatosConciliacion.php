<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionUsimra.php");
include($libPath."fechas.php");
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

$sqlConcilia = "select * from conciliacuotasusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
$resConcilia = mysql_query($sqlConcilia,$db); 
$rowConcilia = mysql_fetch_array($resConcilia);
$cuentaBoleta = $rowConcilia['cuentaboleta'];
$cuentaRemesa=$rowConcilia['cuentaremesa'];
$fechaRemesa =$rowConcilia['fecharemesa'];
$nroremesa=$rowConcilia['nroremesa'];	
$nroremito=$rowConcilia['nroremitoremesa'];
$cuentaRemito=$rowConcilia['cuentaremitosuelto'];
$fechaRemito=$rowConcilia['fecharemitosuelto'];
$nroRemitoSuelto=$rowConcilia['nroremitosuelto'];
if ($rowConcilia['cuentaremesa'] != 0) {
	$quees="remesa";
	$sqlRemesa="select * from remesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaRemesa'";
	$resRemesa=mysql_query($sqlRemesa,$db);	
	if ($nroremesa!=0) {
		$sqlRem="select * from remitosremesasusimra where codigocuenta = $cuentaRemesa and sistemaremesa = 'M' and fecharemesa = '$fechaRemesa' and nroremesa = $nroremesa";
		$resRem=mysql_query($sqlRem,$db);
	}
} else {
	$quees="remito";
	$sqlRemitoSuelto = "select * from remitossueltosusimra where codigocuenta = $cuentaRemito and sistemaremito = 'M' and fecharemito = '$fechaRemito'";
	$resRemitoSuelto=mysql_query($sqlRemitoSuelto,$db);
}
$fechaRemesa = invertirFecha($fechaRemesa);
$fechaRemito = invertirFecha($fechaRemito);

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query( $sql,$db); 
$row=mysql_fetch_array($result); 

$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
$resultlocalidad = mysql_query( $sqllocalidad,$db); 
$rowlocalidad = mysql_fetch_array($resultlocalidad); 

$sqlprovi =  "select * from provincia where codprovin = $row[codprovin]";
$resultprovi = mysql_query( $sqlprovi,$db); 
$rowprovi = mysql_fetch_array($resultprovi);

$sqlCab = "select * from cabacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo";
$resCab = mysql_query($sqlCab,$db); 
$rowCab = mysql_fetch_array($resCab);

$sqlCuo = "select * from cuoacuerdosusimra where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
$resCuo = mysql_query($sqlCuo,$db); 
$rowCuo = mysql_fetch_array($resCuo);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.: Ver Datos Banco:.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>

<body bgcolor="#B2A274" onLoad="logicaHabilitacion()">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onClick="location.href = 'selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>'" align="center"/>
  </p>
  <p>
    <?php 	
		include($libPath."cabeceraEmpresa.php"); 
	?>
  </p>
  <form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="modificarDatosConciliacion.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
  <div align="center">
    <p><strong>Acuerdo N&uacute;mero </strong> <?php echo $acuerdo ?> <strong>Cuota</strong> <?php echo $cuota ?> </p>
	 <table border="1" width="935" bordercolorlight="#000000" bordercolordark="#000000" bordercolor="#000000" cellpadding="2" cellspacing="0">
				<tr>
   					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Vto.</font></strong></div></td>
    				<td width="168"><div align="center"><strong><font size="1" face="Verdana">Tipo Cancelacion</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
					<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
				</tr>
				<?php
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['montocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowCuo['fechacuota'])."</font></div></td>");
				
				$sqltipocan = "select * from tiposcancelaciones where codigo = $rowCuo[tipocancelacion]";
				$restipocan =  mysql_query( $sqltipocan,$db);
				$rowtipocan = mysql_fetch_array($restipocan);
				
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowtipocan['descripcion']."</font></div></td>");
				
				if ($rowCuo['chequenro'] == 0) {
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>-</font></div></td>");
				} else {
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['chequenro']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuo['chequebanco']."</font></div></td>");
					print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowCuo['chequefecha'])."</font></div></td>");
				}
				print ("</tr>"); 
				?>
	</table>
     <p>&nbsp;</p>
     <table width="415" border="1">
       <tr>
         <td width="197"><div align="right">Fecha de Pago</div></td>
         <td width="202"><?php echo invertirFecha($rowCuo['fechacancelacion']); ?></td>
       </tr>
       <tr>
         <td><div align="right">Cuenta de la Boleta</div></td>
         <td><?php 
		 		$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaBoleta";
				$rescue = mysql_query($sqlcue,$db);
				$rowcue = mysql_fetch_array($rescue);
				echo $rowcue['descripcioncuenta'];
			?></td>
       </tr>
       <tr>
         <td><div align="right">Fecha Conciliaci&oacute;n </div></td>
         <td><?php echo invertirFecha($rowConcilia['fechaconciliacion']); ?></td>
       </tr>
     </table>
     <p>
       <?php if ($quees == "remesa") { ?>
     </p>
     <table width="415" border="1">
       <tr>
		 <td colspan="2"><div align="center"><strong>REMESA </strong></div></td>
       </tr>
       <tr>
		 <td width="199"><div align="right">Cuenta de la Remesa</div></td>
         <td width="200">  
		          <?php 
					$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaRemesa";
					$rescue = mysql_query($sqlcue,$db);
					$rowcue = mysql_fetch_array($rescue);
					echo $rowcue['descripcioncuenta']; 
				?>         </td>
	    </tr>
       <tr>
         <td>
           <div align="right">Fecha de la Remesa</div></td>
         <td><?php if ($fechaRemesa!="0000-00-00" && $fechaRemesa!="00/00/0000") echo $fechaRemesa; ?></td>
        </tr>
       <tr>
         <td>
          <div align="right">Nro Remesa</div></td>
         <td><?php echo $nroremesa; ?></td>
        </tr>
       <tr>
         <td>
          <div align="right">Nro Remito</div></td>
         <td> <?php echo $nroremito ?> </td>
        </tr>
    </table>
	<?php } ?>
<?php if ($quees == "remito") { ?>
     <table width="415" border="1">
	   <tr>
         <td colspan="2"><div align="center"><strong>REMITO SUELTO </strong></div></td>
       </tr>
       <tr>
         <td width="200"><div align="right">Cuenta Reminto Suelto</div></td>
         <td width="199">
             <?php 
					$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaRemito";
					$rescue = mysql_query($sqlcue,$db);
					$rowcue = mysql_fetch_array($rescue);
					echo $rowcue['descripcioncuenta']; 
			?>       </td>
       </tr>
       <tr>
         <td><div align="right">Fecha Remito Suelto</div></td>
         <td><?php if ($fechaRemito!="0000-00-00" && $fechaRemito!="00/00/0000") echo $fechaRemito ?> </tr>
       <tr>
         <td><div align="right">Nro Remito Suelto</div></td>
         <td><?php echo $nroRemitoSuelto; ?></td>
       </tr>
     </table>
<p>
<?php } ?>
</p>
	 <table width="701" border="1">
       <tr>
         <td width="101"><div align="right">Observacion</div></td>
         <td width="584"><?php echo  $rowCuo['observaciones'] ?></td>
       </tr>
     </table>
    </div>
</form>
</div>
</body>
</html>