<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
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
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/lib/funcionControl.js" type="text/javascript"></script>

<body bgcolor="#B2A274" onLoad="logicaHabilitacion()">
<div align="center">
  <p><input type="button" name="volver" value="Volver" onclick="location.href = 'selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>'" /></p>
  <p><?php include($libPath."cabeceraEmpresaConsulta.php"); 
		   include($libPath."cabeceraEmpresa.php"); ?></p>
  <form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="modificarDatosConciliacion.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
     <h3>Acuerdo Número <?php echo $acuerdo ?> Cuota <?php echo $cuota ?> </h3>
	 <table border="1" style="text-align: center; width: 800; margin-bottom: 15px">
			<tr>
   			   	<th>Monto</th>
    			<th>Fecha Vto.</th>
    			<th>Tipo Cancelacion</th>
				<th>Nro Cheque</th>
				<th>Banco</th>
				<th>Fecha Cheque</th>		
			</tr>
		<?php 	$sqltipocan = "select * from tiposcancelaciones where codigo = $rowCuo[tipocancelacion]";
				$restipocan =  mysql_query( $sqltipocan,$db);
				$rowtipocan = mysql_fetch_array($restipocan);?>
			<tr>
			  <td><?php echo $rowCuo['montocuota'] ?></td>
			  <td><?php echo invertirFecha($rowCuo['fechacuota']) ?></td>
			  <td><?php echo $rowtipocan['descripcion'] ?></td>	
		<?php if ($rowCuo['chequenro'] == 0) { ?>
					<td>-</td>
					<td>-</td>
					<td>-</td>
		<?php } else { ?>
					<td><?php echo $rowCuo['chequenro'] ?></td>
					<td><?php echo $rowCuo['chequebanco'] ?></td>
					<td><?php echo invertirFecha($rowCuo['chequefecha']) ?></td>
		<?php } ?>
			</tr>
	</table>
    <table width="400" border="1" style="margin-bottom: 15px">
       <tr>
         <td width="200"><div align="right"><b>Fecha de Pago</b></div></td>
         <td><?php echo invertirFecha($rowCuo['fechacancelacion']); ?></td>
       </tr>
       <tr>
         <td><div align="right"><b>Cuenta de la Boleta</b></div></td>
         <td><?php 
		 		$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaBoleta";
				$rescue = mysql_query($sqlcue,$db);
				$rowcue = mysql_fetch_array($rescue);
				echo $rowcue['descripcioncuenta'];
			?></td>
       </tr>
       <tr>
         <td><div align="right"><b>Fecha Conciliación</b></div></td>
         <td><?php echo invertirFecha($rowConcilia['fechaconciliacion']); ?></td>
       </tr>
     </table>
     <?php if ($quees == "remesa") { ?>
     <table width="400" border="1" style="margin-bottom: 15px">
       <tr>
		 <td colspan="2"><div align="center"><b>REMESA </b></div></td>
       </tr>
       <tr>
		 <td width="200"><div align="right"><b>Cuenta de la Remesa</b></div></td>
         <td>  
		          <?php 
					$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaRemesa";
					$rescue = mysql_query($sqlcue,$db);
					$rowcue = mysql_fetch_array($rescue);
					echo $rowcue['descripcioncuenta']; 
				?>         
		 </td>
	   </tr>
       <tr>
         <td><div align="right"><b>Fecha de la Remesa</b></div></td>
         <td><?php if ($fechaRemesa!="0000-00-00" && $fechaRemesa!="00/00/0000") echo $fechaRemesa; ?></td>
        </tr>
       <tr>
         <td><div align="right"><b>Nro Remesa</b></div></td>
         <td><?php echo $nroremesa; ?></td>
        </tr>
       <tr>
         <td><div align="right"><b>Nro Remito</b></div></td>
         <td><?php echo $nroremito ?> </td>
        </tr>
    </table>
	<?php } 
	 if ($quees == "remito") { ?>
     <table width="400" border="1" style="margin-bottom: 15px">
	   <tr>
         <td colspan="2"><div align="center"><b>REMITO SUELTO </b></div></td>
       </tr>
       <tr>
         <td width="200"><div align="right"><b>Cuenta Reminto Suelto</b></div></td>
         <td>
             <?php 
					$sqlcue = "select * from cuentasusimra where codigocuenta = $cuentaRemito";
					$rescue = mysql_query($sqlcue,$db);
					$rowcue = mysql_fetch_array($rescue);
					echo $rowcue['descripcioncuenta']; 
				?>      
		  </td>
       </tr>
       <tr>
         <td><div align="right"><b>Fecha Remito Suelto</b></div></td>
         <td><?php if ($fechaRemito!="0000-00-00" && $fechaRemito!="00/00/0000") echo $fechaRemito ?> </tr>
       <tr>
         <td><div align="right"><b>Nro Remito Suelto</b></div></td>
         <td><?php echo $nroRemitoSuelto; ?></td>
       </tr>
     </table>
<?php } ?>
	 <table width="800" border="1">
       <tr>
         <td><div align="right"><b>Observacion</b></div></td>
         <td><?php echo  $rowCuo['observaciones'] ?></td>
       </tr>
     </table>
</form>
</div>
</body>
</html>