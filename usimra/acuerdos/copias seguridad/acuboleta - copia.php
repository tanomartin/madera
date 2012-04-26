<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null)
	header ("Location: http://www.usimra.com.ar/acuerdos/prueba");
?>

<html>

<head>

<title>.: U.S.I.M.R.A. :.</title>
<META HTTP-EQUIV="Expires" CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">

<meta http-equiv="" content="text/html; charset=iso-8859-1"></head>
<p><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
<body topmargin="0" leftmargin="0">

<table border="0" width="100%" height="100%">
  <tr>
    <td width="100%" valign="top" align="center"> 
<?
require ("numeros.php");			  
$datos = array_values($HTTP_POST_VARS);

if ($datos [1] == -1) {
	$nroact = $datos [0];
	$nroacu = $datos [2];
	$nrocuo = $datos [4];
	$importe = $datos [5];
	$tipopago = $datos [6];
	$nrocheque = $datos [7];
	$banco = $datos [8];
	$nrcuit = $datos [9];
	$usuario = $datos [10];
	$delcod = $datos [11];
	$empcod = $datos [12];
} else {
	$nroact = $datos [0];
	$nroacu = $datos [1];
	$nrocuo = $datos [2];
	$importe = $datos [3];
	$tipopago = $datos [4];
	$nrocheque = $datos [5];
	$banco = $datos [6];
	$nrcuit = $datos [7];
	$usuario = $datos [8];
	$delcod = $datos [9];
	$empcod = $datos [10];
}


$ctrl =  date("YmdHis");
$ctrlh = substr($ctrl,2,13);
$h = '99';
$ctrlh = $h.$ctrlh;

$host = "localhost";
$user = "uv0472";
$pass = "trozo299tabea";
$db = mysql_connect($host,$user,$pass);

//Ejecucion de la sentencia SQL para ingresar registro en boletas impresas.
$sqlBoletas = "INSERT INTO boletas VALUES ('$delcod','$empcod','$nroacu','$nrocuo','$ctrlh')";
$resultBole = mysql_db_query("uv0472_acuerdos",$sqlBoletas,$db);

//Ejecucion de la sentencia SQL
$sql = "select * from empresas where nrcuit = '$nrcuit' and delcod = '$delcod' and empcod = '$empcod'";
$result = mysql_db_query("uv0472_acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

$delcod = $row['delcod'];
$empcod = $row['empcod'];
//echo $delcod.' '.$empcod.' '.$nroact.' '.$nroacu.' '.$nrocuo.' '.$importe.' '.$nrcuit;



$sql = "INSERT INTO depositos (nrcuit,delcod,empcod,nroacu,nrocuo,nroact,importe,fecpro,idusuario,tipopago,nrocheque,banco)
VALUES ('$nrcuit','$delcod','$empcod','$nroacu','$nrocuo','$nroact','$importe','$ctrlh','$usuario','$tipopago','$nrocheque','$banco')";
$result = mysql_db_query("uv0472_acuerdos",$sql,$db);			  

$nota[0] = ("1 - Original: Para el DEPOSITANTE");
$nota[1] = ("1 - Duplicado: Para el BANCO como comprobante de Caja");
$nota[2] = ("3 - Triplicado: XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
for ($w = 0; $w <2; $w++) {			  

print ("<table border=1 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
print ("  <tr>");
print ("    <td width=650><p align=center><font size=2 face=Arial Narrow>Cta. Cte. <b>Nº 900004/93</b> (F.A.I.M.A. - U.S.I.M.R.A.) BANCO NACION - SUCURSAL CABALLITO</b></font></td>");
print ("  </tr>");
print ("</table>");

print ("<table border=0 width=650");
print ("  <tr>");
print ("    <td width=650><p align=center><font face=Verdana size=2>NOTA DE CREDITO para la Cuenta de Unión de Sindicatos de la Industria Maderera de la República Argentina (U.S.I.M.R.A.) y Federación Argentina de la Industria Maderera y Afines (F.A.I.M.A.) - CCT 335/75 Artículos 32 y 32 bis.</font></td>");
print ("  </tr>");
print ("</table>");


print ("<table border=1 width=300 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0><p align=center>");
print ("  <tr>");
print ("    <td width=400><p align=center><font size=1 face=Arial Narrow>BANCO DE LA NACION ARGENTINA - Sucursal Caballito - Rivadavia 5199 - C.A.B.A.</b></font></td>");
print ("  </tr>");
print ("</table>");

print ("<table border=0 width=650>");
print ("  <tr>");
print ("    <td width=100><font face=Verdana size=1>Empleador:</font></td>");
print ("    <td width=300><font face=Verdana size=1>".$row['nombre']." (".$row['delcod']."-".$row['empcod'].")</font></td>");
print ("    <td width=100><font face=Verdana size=1>CUIT:</font></td>");
print ("    <td width=150><font face=Verdana size=1>".$nrcuit."</font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=100><font face=Verdana size=1>Domicilio:</font></td>");
print ("    <td width=300><font face=Verdana size=1>".$row['domici']."</font></td>");
print ("    <td width=100><font face=Verdana size=1>Localidad:</font></td>");
print ("    <td width=150><font face=Verdana size=1>".$row['locali']."</font></td>");
print ("  </tr>");
print ("</table>");

$sql = "select * from depositos where nrcuit = '$nrcuit' and delcod = '$delcod' and empcod = '$empcod' and fecpro = '$ctrlh'";
$result = mysql_db_query("uv0472_acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

$nume = $row['importe'];
$pepe = cfgValorEnLetras($nume);



print ("<table border=1 width=650 bordercolor=#000000 bordercolorlight=#000000 bordercolordark=#000000 cellspacing=0 cellpadding=0>");
print ("  <tr>");
print ("    <td width=141 colspan=4 align=center rowspan=2><font size=1 face=Arial Narrow>Período Liquidado</font></td>");
print ("    <td width=69 rowspan=3 align=center><font size=1 face=Arial Narrow>Cantidad de Personal</font></td>");
print ("    <td width=69 rowspan=3 align=center><font size=1 face=Arial Narrow>Total Salarios</font></td>");
print ("    <td width=69 align=center><font size=1 face=Arial Narrow>Otros Conceptos</font></td>");
print ("    <td width=212 colspan=3 align=center><font face=Arial Narrow size=1>Contribuciones y Aportes CCT 335/75</font></td>");
print ("    <td width=76 rowspan=3 align=center><font face=Arial Narrow size=1>Total del Depósito</font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=69 rowspan=2 align=center><font face=Arial Narrow size=1>Recargos - Intereses - Otros</font></td>");
print ("    <td width=141 colspan=2 align=center><font face=Arial Narrow size=1>Contribuciones Patronales</font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1>Aporte</font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=70 align=center colspan=2><font face=Arial Narrow size=1>Mes/Acta</font></td>");
print ("    <td width=69 align=center colspan=2><font face=Arial Narrow size=1>Año/Cuota</font></td>");
print ("    <td width=70 align=center><font face=Arial Narrow size=1>Art.32 0,6%</font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1>Art.32 bis 1%</font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1>Art.32 bis 1,5%</font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=70 align=center colspan=2><font face=Arial Narrow size=1>".$row['nroact']."-".$row['nroacu']."</font></td>");
print ("    <td width=69 align=center colspan=2><font face=Arial Narrow size=1>".$row['nrocuo']."</font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=70 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=69 align=center><font face=Arial Narrow size=1> --- </font></td>");
print ("    <td width=76 align=center><font face=Arial Narrow size=1><b>".number_format($row['importe'], 2, ",", ".")."</b></font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=56 align=center>");
print ("      <p align=center><font face=Arial Narrow size=1><b>Efectivo</b></font></td>");
print ("    <td width=12 align=center><font face=Arial Narrow size=1><b>");
if ($row['tipopago'] == 2) {echo 'X';} else {echo ' ';}
print("</b></font></td>");
print ("    <td width=54 align=center><font face=Arial Narrow size=1><b>Cheque</b></font></td>");
print ("    <td width=13 align=center>");
if ($row['tipopago'] == 1) {echo 'X';}  else {echo ' ';}
print ("</td>");
print ("    <td width=491 align=center colspan=7><font face=Arial Narrow size=1><b>");
if ($row['tipopago'] == 1) {echo 'Nro.: '.$row['nrocheque'].' '.$banco;} else {echo ' ';}
print ("</b></font></td>");
print ("  </tr>");
print ("  <tr>");
print ("    <td width=70 align=center colspan=2><font face=Arial Narrow size=1><b>Son Pesos:</b></font></td>");
print ("    <td width=560 align=left colspan=9><font face=Arial Narrow size=1>&nbsp;".strtoupper($pepe)."-</td>");
print ("  </tr>");
print ("</table>");
print ("<br>");



$nconvenio = 3617;
$ncuasifinal = $nconvenio.$nrcuit.$row['fecpro'];
print ("<br>");
//print $ncuasifinal;
print ("<br>");

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
//print $npart3total;
//print ("<br>");
//print $npart1total;
//print ("<br>");
$npartot = $npart1total + $npart3total;
//print $npartot;

$largonpar = strlen($npartot);

//print $largonpar;
$ndigito = $largonpar -1;
$nverifi01 = substr($npartot,$ndigito,1);
print ("<br>");

if ($nverifi01 == 0) {
$dverifi = 0;} else {
$dverifi = 10 - $nverifi01;
}
//print $dverifi;
//print ("<br>");








//impresion de codigo de barra
print ("<table border=0 width=650>");
print ("  <tr><td width=100%><p align=center>");


print ("<img border=0 src=x.jpg width=10 height=28>");
for ($i=0; $i < 29; $i++) {
$poscuit = substr($ncuasifinal,$i,1);
print ("<img border=0 src=".$poscuit.".jpg width=10 height=28>");
}
print ("<img border=0 src=".$dverifi.".jpg width=10 height=28>");
print ("<img border=0 src=x.jpg width=10 height=28>");
print ("<br>");
print $ncuasifinal.$dverifi;



print ("	</td></tr>");
print ("</table>");

print ("<table border=0 width=650>");
print ("  <tr>");
print ("    <td width=650><p align=left><font size=1 face=Arial Narrow>".$nota[$w]."</font></td>");
print ("<br>");

print ("  </tr>");
print ("    <td width=650><p align=center><font size=1 face=Arial Narrow><img border=0 src=tijera.jpg width=30 height=17>- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </font></td>");
print ("<br>");
print ("<br>");
print ("<br>");
print ("  <tr>");
print ("  </tr>");

print ("</table>");

print ("<br>");



}
mysql_close();





?>
   </td> 
  </tr>
</table>


</p></font>


</p>

<p>
  <input type="button" name="imprimir" value="Imprimir" onClick="window.print();">
</p>
<?php 
if($_SESSION['usuario'] == 3) {
?>
<p><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="acuerdosFisca.php">VOLVER</a></strong></font></p>
<?php
} else {
?>
<p><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="acuerdos.php">VOLVER</a></strong></font></p>
<?php } ?>
</body>

</html>