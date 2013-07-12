<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/lib/fechas.php"); 

$orden = $_POST['orden'];

if ($orden == "") {
 $orden = "cuit";
}

if ($orden == "cuit") {
	$titulo = "NRO CUIT";
} else {
	$titulo = "FECHA VALOR AL COBRO";
}

$sqlLista = "select * from valoresalcobro where chequenroospim = '' order by $orden";
$resLista = mysql_query( $sqlLista,$db); 

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado Valores :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>


<body bgcolor="#CCCCCC">
<p align="center" class="Estilo2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="moduloValores.php">VOLVER</a></strong></font></p>
<p align="center" class="Estilo2">Listado Valores al Cobro ordenado por <?php echo $titulo ?></p>
<div align="center">
  <form id="listado" name="listado" method="post" action="cargaInfoChequeOspim.php">
    <table width="935" border="0">
      <tr>
        <td width="462"><label>
          <input type="submit" name="Submit" value="Valor de Depósito" />
        </label></td>
        <td width="463"><div align="right">
            <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" />
        </div></td>
      </tr>
    </table>
    <table border="1" width="935" bordercolorlight="#000099" bordercolordark="#0066FF" bordercolor="#000000" cellpadding="2" cellspacing="0">
      <tr>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">CUIT</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Raz&oacute;n Social </font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
		<td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Seleccionar</font></strong></div></td>
      </tr>
      <?php	
			while ($rowLista = mysql_fetch_array($resLista)) {
				
				$cuit = $rowLista['cuit'];
				print ("<td width=168><div align=center><font face=Verdana size=1>".$cuit."</font></div></td>");
				$sqlRazon = "select * from empresas where cuit = $cuit";
				$resRazon = mysql_query( $sqlRazon,$db); 
				$rowRazon = mysql_fetch_array($resRazon); 
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowRazon['nombre']."</font></div></td>");
				
						
				$nroacuerdo = $rowLista['nroacuerdo'];
				$nrocuota = $rowLista['nrocuota'];
				print ("<td width=168><div align=center><font face=Verdana size=1>".$nroacuerdo."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".$nrocuota."</font></div></td>");
				
				$sqlCuota = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacuerdo and nrocuota = $nrocuota";
				$resCuota = mysql_query($sqlCuota,$db); 
				$rowCuota = mysql_fetch_array($resCuota); 
				
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuota['montocuota']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowLista['chequenro']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".$rowLista['chequebanco']."</font></div></td>");
				print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowLista['chequefecha'])."</font></div></td>");
				$valor = $cuit.",".$rowLista['nroacuerdo'].",".$rowLista['nrocuota'].",";
				print ("<td width=168><div align=center><font face=Verdana size=1><input type='checkbox' name='elegidos[]' value='".$valor."' /></font></div></td>");
				print ("</tr>"); 	
			}
			?>
    </table>
    <p>&nbsp;</p>
  </form>
  </div>
</body>
</html>
