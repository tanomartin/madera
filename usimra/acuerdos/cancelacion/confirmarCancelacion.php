<?php include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/fechas.php"); 
$cuit = $_GET["cuit"];
$acuerdo = $_GET["acuerdo"];
$cuota = $_GET["cuota"];	

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
<title>.: Confirmar Cancelacion :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none;color:#0033FF}
A:hover {text-decoration: none;color:#33CCFF }
</style>

<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(function($){
	$("#fechapagada").mask("99-99-9999");
});

function validar(formulario) {
	var fecha = formulario.fechapagada.value;
	if (!esFechaValida(fecha)) {
		formulario.fechapagada.focus = true;
		return false;
	}
	return true;
}

</script>

<body bgcolor="#B2A274">
<div align="center">
  <p><strong><a href="selecCanCuotas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
	 <?php 	
		include($_SERVER['DOCUMENT_ROOT']."/usimra/lib/cabeceraEmpresa.php"); 
	?>
<form id="formularioSeleCuotas" name="formularioSeleCuotas" method="post" action="cancelarCuota.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>&cuota=<?php echo $cuota ?>"  onSubmit="return validar(this)">
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
	
     <p>Fecha de Pago 
       <label>
       <input name="fechapagada" type="text" id="fechapagada" size="8">
       </label>
     </p>
     <p>Cuenta de la Boleta
       <label>
       <select name="select">
       </select>
       </label>
     </p>
     <label></label>
     <table width="663" border="0">
       <tr>
         <td colspan="2"><div align="center"><strong>REMESA </strong></div></td>
         <td colspan="2"><div align="center"><strong>REMITO SUELTO </strong></div></td>
       </tr>
       <tr>
         <td width="206"><div align="right">Cuenta de la Remesa
           
         </div></td>
         <td width="126"><select name="select2">
         </select></td>
         <td width="161"><label>
          <div align="right">Cuenta Reminto Suelto</div>
         </label></td>
         <td width="152"><select name="select6">
         </select></td>
       </tr>
       <tr>
         <td><label>
           <div align="right">Fecha de la Remesa           </div>
         </label></td>
         <td><select name="select3">
         </select></td>
         <td><label>
          <div align="right">Fecha Remito Suelto</div>
         </label></td>
         <td><select name="select7">
         </select></td>
       </tr>
       <tr>
         <td><label>
          <div align="right">Nro Remesa</div>
         </label></td>
         <td><select name="select4">
         </select></td>
         <td><label>
          <div align="right">Nro Remito Suelto</div>
         </label></td>
         <td><select name="select8">
         </select></td>
       </tr>
       <tr>
         <td><label>
          <div align="right">Nro Remito</div>
         </label></td>
         <td><select name="select5">
         </select></td>
         <td colspan="2">&nbsp;</td>
       </tr>
     </table>
     <p>
       <label></label>
       <label></label><label>Observacion
	   <textarea name="textarea" cols="50" rows="4"></textarea>
       </label>
     </p>
     <p>
       <label>
       <input type="submit" name="Submit" value="Cancelar Cuota">
       </label>
     </p>
  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
