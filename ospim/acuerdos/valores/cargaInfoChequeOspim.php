<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 

function desglosar ($dato) {
	$cont = 0;
	$a = "";
	$len_a = strlen($dato);
	$array = array();
	for ($cont = 0; $cont < $len_a; $cont++) {
		if (substr($dato, $cont, 1) != ",") {
			$a .= substr($dato, $cont, 1);
		}else{
			array_push ($array, $a);
			$a = "";
		}
		if ($cont == $len_a) {
			array_push ($array, $a);
		$a = "";
		}
	}
return $array;
}

function array_envia($array) { 
     $tmp = serialize($array); 
     $tmp = urlencode($tmp); 
     return $tmp;
}

$datos = $_POST['elegidos'];
$datosArrayEnvia = array_envia($datos);
$cantidad = sizeof($datos);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaCheque").mask("99-99-9999");
	for (i=0; i<=<?php echo $cantidad ?>; i++) {
		$("#fechaResumen"+i).mask("99-99-9999");
	}
});

function validar(formulario) {
	finfor = <?php echo $cantidad ?>;
	for (i=0; i<finfor; i++) {
		idRes = "idResumen" + i;
		idFec = "fechaResumen" + i;
		resumen = document.getElementById(idRes).value;
		fecha = document.getElementById(idFec).value;
		if (resumen == "") {
			alert("Error identificación de resumen");
			document.getElementById(idRes).focus();
			return false;
		}
		if (!esFechaValida(fecha)){
			alert("La fecha no es valida");
			document.getElementById(idFec).focus();
			return false;
		}
	}
	if (!esEnteroPositivo(formulario.nroCheque.value) || formulario.nroCheque.value == "") {
		alert("Error número de Cheque");
		formulario.nroCheque.focus();
		return false;
	}
	if (!esFechaValida(formulario.fechaCheque.value)){
		alert("La fecha no es valida");
		formulario.fechaCheque.focus();
		return false;
	}
	return true;
}

</script>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Carga Datos Cheque OSPIM :.</title>
<style type="text/css">
<!--
.Estilo2 {	font-weight: bold;
	font-size: 18px;
}
-->
</style>
</head>
<body bgcolor="#CCCCCC">
<p align="center">
<input type="reset" name="volver" value="Volver" onClick="location.href = 'listadoValores.php'" align="center"/>
</p>
<div align="center">
  <form id="form1" name="form1" method="post" onSubmit="return validar(this)" action="guardoValorAlCobro.php">
  	<p>
  	  <input type="hidden" value="<?php echo $datosArrayEnvia  ?>" name="datos" />
    </p>
  	<p><strong>Informaci&oacute;n Cheque OSPIM</strong></p>
  	<table width="660" border="0">
      <tr>
        <td width="334"><label>N&uacute;mero de Cheque
          <input name="nroCheque" type="text" id="nroCheque" />
        </label></td>
        <td width="316"><label>Fecha del Cheque
          <input name="fechaCheque" type="text" id="fechaCheque" size="8" />
        </label></td>
      </tr>
    </table>
  	<p><strong>Informaci&oacute;n Valores al Cobro </strong></p>
  	<table border="1" width="1000" bordercolorlight="#000099" bordercolordark="#0066FF" bordercolor="#000000" cellpadding="2" cellspacing="0">
      <tr>
        <td width="150"><div align="center"><strong><font size="1" face="Verdana">CUIT</font></strong></div></td>
        <td width="400"><div align="center"><strong><font size="1" face="Verdana">Raz&oacute;n Social </font></strong></div></td>
        <td width="50"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
        <td width="50"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
		<td width="168"><div align="center"><strong><font size="1" face="Verdana">Id. Resumen</font></strong></div></td>
		<td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Resumen</font></strong></div></td>
      </tr>
      <?php	
  	$suma = 0;	
	$i = 0;
	foreach ($datos as $array) {
		$info = desglosar($array);
		$cuit = $info[0];
		$nroacu = $info[1];
		$nrocuo = $info[2];
		$sqlCuota = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuo";
		$resCuota = mysql_query( $sqlCuota,$db); 
		$rowCuota = mysql_fetch_array($resCuota); 
		
		$sqlValor= "select * from valoresalcobro where cuit = $cuit and nroacuerdo = $nroacu and nrocuota = $nrocuo";
		$resValor = mysql_query( $sqlValor,$db); 
		$rowValor = mysql_fetch_array($resValor); 

		print ("<td width=150><div align=center><font face=Verdana size=1>".$cuit."</font></div></td>");
		$sqlRazon = "select * from empresas where cuit = $cuit";
		$resRazon = mysql_query( $sqlRazon,$db); 
		$rowRazon = mysql_fetch_array($resRazon); 
				
		print ("<td width=400><div align=center><font face=Verdana size=1>".$rowRazon['nombre']."</font></div></td>");
		print ("<td width=50><div align=center><font face=Verdana size=1>".$nroacu."</font></div></td>");
		print ("<td width=50><div align=center><font face=Verdana size=1>".$nrocuo."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowValor['chequenro']."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowValor['chequebanco']."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowValor['chequefecha'])."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".number_format($rowCuota['montocuota'],2,',','.')."</font></div></td>");
		print ("<td width=168><div align=center><input name='idResumen".$i."' type='text' id='idResumen".$i."'/></td></div></td>");
		print ("<td width=168><div align=center><input name='fechaResumen".$i."' type='text' id='fechaResumen".$i."' size='8'/></td></div></td>");
		print ("</tr>"); 	
		$suma = $suma + $rowCuota['montocuota'];
		$i = $i + 1;
	}
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1><b>TOTAL</b></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1><b>".number_format($suma,2,',','.')."</b></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("</tr>"); 
	
	?>
    </table>
    <p>
      <label>
      <input type="submit" name="Submit" value="Guardar" />
      </label>
    </p>
    <p>
      <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" />
    </p>
  </form>
</div>
</body>
</html>
