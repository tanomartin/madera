<?php 
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
	include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php"); 
	require ($_SERVER['DOCUMENT_ROOT']."/madera/lib/numeros.php");
?>

<html>

<head>

<title></title>
<META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<meta http-equiv="" content="text/html; charset=iso-8859-1"></head>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
<body topmargin="0" leftmargin="0">

<table border="0" width="100%" height="90%">
  <tr>
    <td width="100%" valign="top" align="center"> 
<?php	
	$cuit = $_GET["cuit"];
	$acuerdo = $_GET["acuerdo"];
	$cuota = $_GET["cuota"];	 
 
	$sqlacuerdos =  "select * from cabacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo";
	$resulacuerdos=  mysql_query( $sqlacuerdos,$db); 
	$rowacuerdos = mysql_fetch_array($resulacuerdos);
	
	$sqlcuotas = "select * from cuoacuerdosospim where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
	$rescuotas =  mysql_query( $sqlcuotas,$db); 
	$rowcuotas = mysql_fetch_array($rescuotas);

	$nroact = $rowacuerdos['nroacta'];
	$nroacu = $acuerdo;
	$nrocuo = $cuota;
	$importe = $rowcuotas['montocuota'];
	$tipopago = $rowcuotas['tipocancelacion'];
	$cantbole = $rowcuotas['boletaimpresa'];
	
	if ($tipopago == 3) {
		$sqlvalor = "select * from valoresalcobro where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
		$resvalor =  mysql_query( $sqlvalor,$db); 
		$rowvalor = mysql_fetch_array($resvalor);
		$nrocheque = $rowvalor['chequenroospim'];
		$banco = $rowvalor['chequebancoospim'];
		$fechaChe = invertirFecha($rowvalor['chequefechaospim']);
	} else {
		$nrocheque = $rowcuotas['chequenro'];
		$banco = $rowcuotas['chequebanco'];
		$fechaChe = invertirFecha($rowcuotas['chequefecha']);
	}
	$nrcuit = $cuit;

	$ctrl =  date("YmdHis");
	$ctrlh = substr($ctrl,2,13);
	$h = '99';
	$ctrlh = $h.$ctrlh;
		
	$sql = "select * from empresas where cuit = $nrcuit";
	$result =  mysql_query( $sql,$db); 
	$row=mysql_fetch_array($result); 
	
	$sqllocalidad = "select * from localidades where codlocali = $row[codlocali]";
	$resultlocalidad =  mysql_query( $sqllocalidad,$db); 
	$rowlocalidad = mysql_fetch_array($resultlocalidad); 

//Ejecucion del sql para ingreso del registro en tabla boletasospim
	$sqlgrababoleta = "INSERT INTO boletasospim (cuit,nroacuerdo,nrocuota,importe,nrocontrol,usuarioregistro) VALUES ('$cuit','$acuerdo','$cuota','$importe','$ctrlh','$_SESSION[usuario]')";
	$resulgrababoleta =  mysql_query( $sqlgrababoleta,$db);

//Ejecucion del sql para incrementar la cantidad de boletas impresas en tabla cuoacuerdosospim
	$sqlactcuotas = "update cuoacuerdosospim set boletaimpresa = ($cantbole+1) where cuit = $cuit and nroacuerdo = $acuerdo and nrocuota = $cuota";
	$resulactcuotas =  mysql_query( $sqlactcuotas,$db); 

	$nota[0] = ("1 - Original: Para el BANCO como comprobante de Caja");
	$nota[1] = ("1 - Duplicado: Para el DEPOSITANTE");
	$nota[2] = ("3 - Triplicado: Para O.S.P.I.M. como comprobante de Control");
	for ($w = 0; $w <3; $w++) {			  
	
	print ("<table border=1 width=650 bordercolor=#000000");
	print ("  <tr>");
	print ("    <td width=650><p align=center><font face=Arial size=3><b>OBRA SOCIAL DEL PERSONAL DE LA INDUSTRIA MADERERA - O.S.P.I.M.</b></font></td>");
	print ("  </tr>");
	print ("</table>");
	print ("<table border=1 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
	print ("  <tr>");
	print ("    <td width=650><p align=center><font size=2 face=Arial Narrow>Cta. Cte. <b>Nº 39.750/12</b> (O.S.P.I.M.) BANCO NACION - SUCURSAL PLAZA DE MAYO</b></font></td>");
	print ("  </tr>");
	print ("</table>");
	print ("<br>");
	print ("<table border=1 width=350 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0><p align=center>");
	print ("  <tr>");
	print ("    <td width=350><p align=center><font size=1 face=Arial Narrow>BANCO DE LA NACION ARGENTINA - Sucursal Plaza de Mayo Bartolomé Mitre 326 - C.A.B.A.</b></font></td>");
	print ("  </tr>");
	print ("</table>");
	print ("<br>");
	print ("<table border=0 width=650>");
	print ("  <tr>");
	print ("    <td width=100><font face=Verdana size=1>Empleador:</font></td>");
	print ("    <td width=300><font face=Verdana size=1>".$row['nombre']."</font></td>");
	print ("    <td width=100><font face=Verdana size=1>CUIT:</font></td>");
	print ("    <td width=150><font face=Verdana size=1>".$nrcuit."</font></td>");
	print ("  </tr>");
	print ("  <tr>");
	print ("    <td width=100><font face=Verdana size=1>Domicilio:</font></td>");
	print ("    <td width=300><font face=Verdana size=1>".$row['domilegal']."</font></td>");
	print ("    <td width=100><font face=Verdana size=1>Localidad:</font></td>");
	print ("    <td width=150><font face=Verdana size=1>".$rowlocalidad['nomlocali']."</font></td>");
	print ("  </tr>");
	print ("</table>");
	
	$nume = $importe;
	$pepe = cfgValorEnLetras($nume);
	
	print ("<table border=1 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
	
	print ("  <tr>");
		print ("    <td width=650 colspan=3 align=center ><font size=1 face=Arial Narrow>CONCEPTOS DEPOSTIADOS</font></td>");
	print ("  </tr>");
	
	
	print ("  <tr>");
		print ("    <td width=300 align=center><font size=1 face=Arial Narrow>Acta - N°Acuerdo - N°Cuota </font></td>");
		print ("    <td width=155 align=center><font size=1 face=Arial Narrow>Vencimiento</font></td>");
		print ("    <td width=155 align=center><font size=1 face=Arial Narrow>Importe</font></td>");
	print ("  </tr>");
	print ("  <tr>");
		print ("    <td width=300 align=center><font face=Arial Narrow size=1>".$nroact." - ".$nroacu." - ".$nrocuo."</font></td>");
		print ("    <td width=155 align=center><font face=Arial Narrow size=1>".invertirFecha($rowcuotas['fechacuota'])."</font></td>");
		print ("    <td width=155 align=center><font face=Arial Narrow size=1><b>".number_format($importe, 2, ",", ".")."</b></font></td>");
	print ("  </tr>");
	print ("</table>");
	
	print ("<table border=0 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
	print ("  <tr>");
		print(" <td width=650 align=left ><font size=1> O.S.P.I.M. formula expresa reserva de intereses por pagos fuera de término </font></td>");
	print ("  </tr>");
	
	//Tabla de tipo de pagos y datos del mismo
	print ("<table border=1 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
	print ("  <tr>");
		print ("   <td width=54 align=center><font face=Arial Narrow size=1><b>Efectivo</b></font></td>");
		print ("   <td width=12 align=center bordercolor='#000000'><font face=Arial Narrow size=1><b>");
		if ($tipopago == 2) {echo 'X';} else {echo ' ';}
		print("</b></font></td>");
		print ("    <td width=54 align=center><font face=Arial Narrow size=1><b>Cheque</b></font></td>");
		print ("    <td width=12 align=center bordercolor='#000000'><font face=Arial Narrow size=1><b>");
		if ($tipopago == 1 or $tipopago == 3) {echo 'X';} else {echo ' ';}
		print ("</b></font></td>");
		print ("    <td width=491 align=center colspan=7><font face=Arial Narrow size=1><b>");
		if ($tipopago == 1 or $tipopago == 3) {echo 'Fecha: '.$fechaChe.' - Nro.: '.$nrocheque.' - Banco: '.$banco;} else {echo ' ';}
		print ("</b></font></td>");
	print ("  </tr>");
	print ("  <tr>");
		print ("    <td width=70 align=center colspan=2><font face=Arial Narrow size=1><b>Son Pesos:</b></font></td>");
		print ("    <td width=560 align=left colspan=9><font face=Arial Narrow size=1>&nbsp;".strtoupper($pepe)."-</td>");
	print ("  </tr>");
	print ("</table>");
	
	print ("<br>");
	
	$nconvenio = 5734;
	$ncuasifinal = $nconvenio.$nrcuit.$ctrlh;
	
	
	//PONDERADOR 31 MÓDULO 10 (Dígito Verificador)
	$npart3total = 0;
	$npart1total = 0;
	for ($i=0; $i < 29; $i++) {
		$npor3 = substr($ncuasifinal,$i,1);
		$npor33 = $npor3 * 3;
		$npart3total = $npart3total + $npor33;
		$i = $i + 1;
		$npor1 = substr($ncuasifinal,$i,1);
		$npart1total = $npart1total + $npor1;
	}
	
	//Suma de Productos
	$npartot = $npart1total + $npart3total;
	
	//Calculo del resto
	$largonpar = strlen($npartot);
	$ndigito = $largonpar -1;
	$nverifi01 = substr($npartot,$ndigito,1);
	
	//Si es 0 se toma 0, sino 10 - resto
	if ($nverifi01 == 0) {
		$dverifi = 0;
	} else {
		$dverifi = 10 - $nverifi01;
	} 
	
	//impresion de codigo de barra
	print ("<img border=0 src=jpg/x.jpg width=10 height=28>");
	for ($i=0; $i < 29; $i++) {
		$poscuit = substr($ncuasifinal,$i,1);
		print ("<img border=0 src=jpg/".$poscuit.".jpg width=10 height=28>");
	}
	print ("<img border=0 src=jpg/".$dverifi.".jpg width=10 height=28>");
	print ("<img border=0 src=jpg/x.jpg width=10 height=28>");
	print ("<br>");
	print $ncuasifinal.$dverifi;
	
	print ("<table border=0 width=650>");
	print ("  <tr>");
	print ("    <td width=650><p align=left><font size=1 face=Arial Narrow>".$nota[$w]."</font></td>");
	
	print ("  </tr>");
	print ("    <td width=650><p align=center><font size=1 face=Arial Narrow><img border=0 src=jpg/tijera.jpg width=30 height=17>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </font></td>");
	print ("  <tr>");
	print ("  </tr>");
	
	print ("</table>");
	
	
	}
	mysql_close();
	
	?>
    </td> 
  </tr>
</table>
    <table width="719" border="0">
      <tr>
          <td width="596"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="fiscalizacionImpBoletas.php?cuit=<?php echo $cuit ?>&acuerdo=<?php echo $acuerdo ?>">VOLVER</a></strong></font></td>
          <td width="113"><div align="right">
            <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left">
        </div></td>
      </tr>
</table>
	  <p align="right">&nbsp; </p>
    <p>&nbsp;</p>
</body>
</html>