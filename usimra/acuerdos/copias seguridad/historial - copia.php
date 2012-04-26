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


<title>.: Sistmea de Acuerdos - Historial :.</title>
</head>
<?
$host = "localhost";
$user = "uv0472";
$pass = "trozo299tabea";
$db = mysql_connect($host,$user,$pass);

$sql = "select * from usuarios where id = '$usuario'";
$result = mysql_db_query("uv0472_acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  <? echo $row['nombre']?></font></strong></p>

<form name="historial" id="historial" method="post" action="historial.php">
    <table width="906" border="1" align="center">
      <tr>
        <td width="298">
		Seleccione el orden: 
			<select name="orden" id="orden">
        		<option value="delemp" selected="selected">Delegacion Empresa</option>
        		<option value="acucuo">Acuerdo Cuota</option>
        		<option value="nroctr">Numero de Control</option>
				<option value="usuario">Usuario</option>
    		</select>		</td>
        <td width="533"> 	
			<input name="listar" id="listar" type="submit"  value="LISTAR"  /></td>
        <td width="53"><a href="acuerdos.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a></td>
      </tr>
      <tr>
        <td colspan="3">
		<?php 
			if ($orden == "delemp") {
				$query="select * from depositos order by delcod, empcod, nroacu, nrocuo";
			}
			if ($orden == "acucuo") {
				$query="select * from depositos order by nroacu, nrocuo, delcod, empcod";
			}
			if ($orden == "nroctr") {
				$query="select * from depositos order by fecpro DESC, delcod, empcod";
			}
			if ($orden == "usuario") {
				$query="select * from depositos order by idusuario";
			}
		?>
		<table width="844" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CD8C34" bordercolorlight="#D08C35" bordercolordark="#D08C35">
            <tr>
              <td width="54"><div align="center"><strong><font size="1" face="Verdana">Delegaci&oacute;n</font></strong></div></td>
              <td width="52"><div align="center"><strong><font size="1" face="Verdana">Empresa</font></strong></div></td>
              <td width="52"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
              <td width="37"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
              <td width="62"><div align="center"><strong><font size="1" face="Verdana">Importe</font></strong></div></td>
              <td width="124"><div align="center"><strong><font size="1" face="Verdana">Nro. Control </font></strong></div></td>
			  <td width="251"><div align="center"><strong><font size="1" face="Verdana">Usuario </font></strong></div></td>
		      <td width="162"><div align="center"><strong><font size="1" face="Verdana">Estado </font></strong></div></td>
            </tr>
            <p>
              <?php 
					if ($query <> null) {
						$resultHisto = mysql_db_query("uv0472_acuerdos",$query,$db);
						while ($rowHisto=mysql_fetch_array($resultHisto)) {	
							$sqlUsuario = "select * from usuarios where id = ".$rowHisto['idusuario'];
							$resultUsua = mysql_db_query("uv0472_acuerdos",$sqlUsuario,$db);
							$rowUsuar=mysql_fetch_array($resultUsua);
						
		$sqlExiste="select * from cuotas where delcod=".$rowHisto['delcod']." and empcod=".$rowHisto['empcod']." and nroacu=".$rowHisto['nroacu']." and nrocuo=".$rowHisto['nrocuo'];
							$resultExiste = mysql_db_query("uv0472_acuerdos",$sqlExiste,$db);
							$cant = mysql_num_rows($resultExiste);
							
		$sqlExBoleta = "select * from boletas where delcod=".$rowHisto['delcod']." and empcod=".$rowHisto['empcod']." and nroacu=".$rowHisto['nroacu'];" and nrocuo=".$rowHisto['nrocuo'];
							$resultExBoleta = mysql_db_query("uv0472_acuerdos",$sqlExBoleta,$db);
							$cantBoleta = mysql_num_rows($resultExBoleta);				
							
							print ("<td width=54><font face=Verdana size=1>".$rowHisto['delcod']."</font></td>");
							print ("<td width=60><font face=Verdana size=1>".$rowHisto['empcod']."</font></div></td>");
							print ("<td width=61><font face=Verdana size=1>".$rowHisto['nroacu']."</font></td>");
							print ("<td width=43><font face=Verdana size=1>".$rowHisto['nrocuo']."</font></div></td>");
							print ("<td width=77><font face=Verdana size=1>".$rowHisto['importe']."</font></div></td>");
							print ("<td width=154><font face=Verdana size=1>".$rowHisto['fecpro']."</font></div></td>");
							print ("<td width=230><font face=Verdana size=1>".$rowUsuar['nombre']."</font></div></td>");
							if ($cant > 0) {
								if ($cantBoleta > 0) {
									print ("<td width=230><font face=Verdana size=1 color=#0000FF><a href=reimprimir.php?cuota=".$rowHisto['nrocuo']."&acuerdo=".$rowHisto['nroacu']."&empcod=".$rowHisto['empcod']."&delcod=".$rowHisto['delcod'].">Rehabilitar Cuota</font></div></td>");
								} else {
									print ("<td width=230><font face=Verdana size=1>Boleta Impresa</font></div></td>");
								}
							} else {
								print ("<td width=230><font face=Verdana size=1>Cancelada / No Existe</font></div></td>");
							}
							print ("</tr>");
						
						
						}
					}
			
			?>
            </p>
        </table>
		<p align="center">
		  <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" />
		</p></td>
      </tr>
</table>

</form>
</body>
</html>
