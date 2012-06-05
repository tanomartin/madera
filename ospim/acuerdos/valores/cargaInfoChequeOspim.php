<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/fechas.php"); 

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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fechaCheque").mask("99-99-9999");
	$("#fechaResumen").mask("99-99-9999");
});

function validar(formulario) {
	if (!esFechaValida(formulario.fechaCheque.value)){
		formulario.fechaCheque.focus();
		return false;
	}
	if (!esEnteroPositivo(formulario.nroCheque.value) || formulario.nroCheque.value == "") {
		alert("Error número de Cheque");
		formulario.nroCheque.focus();
		return false;
	}
	if (!esFechaValida(formulario.fechaResumen.value)){
		formulario.fechaResumen.focus();
		return false;
	}
	if (formulario.idResumen.value == "") {
		alert("Error identificación de resumen");
		formulario.idResumen.focus();
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
<p align="center"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="listadoValores.php">VOLVER</a></strong></font></p>
<p align="center"><span class="Estilo2">Carga de datos Cheque OSPIM </span></p>
<div align="center">
  <form id="form1" name="form1" method="post" onSubmit="return validar(this)" action="guardoValorAlCobro.php">
  	<input type="hidden" value="<?php echo $datosArrayEnvia  ?>" name="datos" />
    <table border="1" width="935" bordercolorlight="#000099" bordercolordark="#0066FF" bordercolor="#000000" cellpadding="2" cellspacing="0">
      <tr>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">CUIT</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Raz&oacute;n Social </font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Banco</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Fecha Cheque</font></strong></div></td>
        <td width="168"><div align="center"><strong><font size="1" face="Verdana">Monto</font></strong></div></td>
      </tr>
      <?php	
  	$suma = 0;	
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

		print ("<td width=168><div align=center><font face=Verdana size=1>".$cuit."</font></div></td>");
		$sqlRazon = "select * from empresas where cuit = $cuit";
		$resRazon = mysql_query( $sqlRazon,$db); 
		$rowRazon = mysql_fetch_array($resRazon); 
				
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowRazon['nombre']."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$nroacu."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$nrocuo."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowValor['chequenro']."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowValor['chequebanco']."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".invertirFecha($rowValor['chequefecha'])."</font></div></td>");
		print ("<td width=168><div align=center><font face=Verdana size=1>".$rowCuota['montocuota']."</font></div></td>");
		print ("</tr>"); 	
		$suma = $suma + $rowCuota['montocuota'];
	}
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1><b>TOTAL</b></font></div></td>");
	print ("<td width=168><div align=center><font face=Verdana size=1><b>".$suma."</b></font></div></td>");
	print ("</tr>"); 
	
	?>
    </table>
    <p><strong>Informaci&oacute;n Cheque OSPIM</strong></p>
    <table width="660" border="0">
      <tr>
        <td width="334"><label>Número de Cheque
            <input name="nroCheque" type="text" id="nroCheque" />
        </label></td>
        <td width="316"><label>Fecha del Cheque
            <input name="fechaCheque" type="text" id="fechaCheque" size="8" />
        </label></td>
      </tr>
    </table>
    <p><strong>Informaci&oacute;n Resumen Bancario </strong></p>
    <table width="655" border="0">
      <tr>
        <td width="332">Identifiacion de Resumen
        <input name="idResumen" type="text" id="idResumen" /></td>
        <td width="313">Fecha de Resumen
        <input name="fechaResumen" type="text" id="fechaResumen" size="8" /></td>
      </tr>
    </table>
    <p>&nbsp;</p>
    <p>
      <label>
      <input type="submit" name="Submit" value="Enviar" />
      </label>
    </p>
  </form>
</div>
</body>
</html>
