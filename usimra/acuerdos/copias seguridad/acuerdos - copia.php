<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['usuario'] == 3)
	header ("Location: http://www.usimra.com.ar/acuerdos/prueba");
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
<!--
A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}
-->
</style>
<STYLE>
BODY {SCROLLBAR-FACE-COLOR: #E4C192; 
SCROLLBAR-HIGHLIGHT-COLOR: #CD8C34; 
SCROLLBAR-SHADOW-COLOR: #CD8C34; 
SCROLLBAR-3DLIGHT-COLOR: #CD8C34; 
SCROLLBAR-ARROW-COLOR: #CD8C34; 
SCROLLBAR-TRACK-COLOR: #CD8C34; 
SCROLLBAR-DARKSHADOW-COLOR: #CD8C34
}
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
</STYLE>

<script language=Javascript>
function ventanaNueva(documento){	
	window.open(documento,'Historial');
}
</script>

<title>.: Sistema de Acuerdos :.</title>
</head>
<?
$host = "localhost";
$user = "uv0472";
$pass = "trozo299tabea";
$db = mysql_connect($host,$user,$pass);

$sql = "select * from usuarios where id = '$usuario'";
$result = mysql_db_query("uv0472_acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

// maximo por pagina 
$limit = 30; 

// pagina pedida 
$pag = (int) $_GET["pag"]; 
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 

?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  <? echo $row['nombre']?></font></strong></p>
<form name="form1" method="post" action="acuerdos2.php">
  <div align="center">
    <p align="right"><strong></strong></p>
    <table width="1137" border="1">
      <tr bordercolor="#000000">
        <td width="340" height="80" bordercolor="#000000"><p align="center" class="Estilo1">M&oacute;dulo Actualizaci&oacute;n</p>
            <p align="center">
              <input type="button" name="actualizar" id="actualizar" onClick="location.href='actualiza.php'" value="Actualizar">
        </p></td>
        <td width="781" rowspan="4" valign="top"><div align="center">
          <p class="Estilo1">Historial de Generaci&oacute;n del d&iacute;a <?php echo $today = date("j/n/Y"); ?></p>
        </div>
          <table width="723" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CD8C34" bordercolorlight="#D08C35" bordercolordark="#D08C35">
            <tr>
              <td width="54"><div align="center"><strong><font size="1" face="Verdana">Delegaci&oacute;n</font></strong></div></td>
              <td width="60"><div align="center"><strong><font size="1" face="Verdana">Empresa</font></strong></div></td>
              <td width="61"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
              <td width="43"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
              <td width="77"><div align="center"><strong><font size="1" face="Verdana">Importe</font></strong></div></td>
              <td width="154"><div align="center"><strong><font size="1" face="Verdana">Nro. Control </font></strong></div></td>
			  <td width="230"><div align="center"><strong><font size="1" face="Verdana">Usuario </font></strong></div></td>
            </tr>
            <p>
              <?php 
			  		$hoy = "99".date("ymd");
					$sqlHisto = "select * from depositos where fecpro like '$hoy%' order by fecpro DESC";
					$resultHisto = mysql_db_query("uv0472_acuerdos",$sqlHisto,$db);
					while ($rowHisto=mysql_fetch_array($resultHisto)) {
						
						$sqlUsuario = "select * from usuarios where id = ".$rowHisto['idusuario'];
						$resultUsua = mysql_db_query("uv0472_acuerdos",$sqlUsuario,$db);
						$rowUsuar=mysql_fetch_array($resultUsua);
						
						print ("<td width=54><font face=Verdana size=1>".$rowHisto['delcod']."</font></td>");
						print ("<td width=60><font face=Verdana size=1>".$rowHisto['empcod']."</font></div></td>");
						print ("<td width=61><font face=Verdana size=1>".$rowHisto['nroacu']."</font></td>");
						print ("<td width=43><font face=Verdana size=1>".$rowHisto['nrocuo']."</font></div></td>");
						print ("<td width=77><font face=Verdana size=1>".$rowHisto['importe']."</font></div></td>");
						print ("<td width=154><font face=Verdana size=1>".$rowHisto['fecpro']."</font></div></td>");
						print ("<td width=230><font face=Verdana size=1>".$rowUsuar['nombre']."</font></div></td>");
						print ("</tr>");
					}
			
			?>
            </p>
        </table>
        <p align="center">
            <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" />
        </p></td>
      </tr>
      <tr bordercolor="#000000">
        <td height="190"><p align="center" class="Estilo1">Boletas a Depositar </p>
            <table width="96%" height="80" border="0" align="center">
              <tr>
                <td width="50%" height="24"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT 
                  :</font></strong></div></td>
                <td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
                  <input type="text" name="textfield">
                </strong></font></td>
              </tr>
              <tr>
                <td height="24"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n 
                  :</font></strong></div></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
                  <input type="text" name="textfield2">
                </strong></font></td>
              </tr>
              <tr>
                <td height="24"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Empresa 
                  :</font></strong></div></td>
                <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
                  <input type="text" name="textfield3">
                </strong></font></td>
              </tr>
            </table>
          <div align="center">
              <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
                <input type="submit" name="Submit" value="Enviar">
              </font></strong></p>
        </div></td>
      </tr>
      <tr>
        <td height="101" align="center" bordercolor="#000000" class="Estilo1"><p>Historial de Generaci&oacute;n de Boletas </p>
          <p><input type="button" name="historial" id="historial" onClick="location.href='historial.php?orden=nroctr'" value="Entrar"></p>
        </td>
      </tr>
      <tr>
        <td height="192">&nbsp;</td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
