<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['aut'] > 1)
	header ("location:index.htm");
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
</STYLE>


<title>.: Sistmea de Acuerdos - INFORME CNE :.</title>
</head>
<?
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
  <? echo $row['nombre']?></font></strong></p>

<form name="informeCNE" id="informeCNE" method="post" action="moverBoletas.php">
    <table width="906" border="1" align="center">
      <tr>
        <td width="745"><div align="left">
          <h3><strong>Boletas Impresas de Cuotas Canceladas o que no existen</strong></h3>
        </div></td>
        <td width="55"><div align="center"><a href="acuerdos.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a></div></td>
      </tr>
      <tr>
        <td colspan="3">
		<?php 			
			$query="select * from depositos order by fecpro DESC, delcod, empcod";
			$rsTotal = mysql_query($query); 
		?>
		<table width="913" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CD8C34" bordercolorlight="#D08C35" bordercolordark="#D08C35">
            <tr>
              <td width="61"><div align="center"><strong><font size="1" face="Verdana">Delegaci&oacute;n</font></strong></div></td>
              <td width="44"><div align="center"><strong><font size="1" face="Verdana">Empresa</font></strong></div></td>
              <td width="48"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
              <td width="40"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
              <td width="57"><div align="center"><strong><font size="1" face="Verdana">Importe</font></strong></div></td>
              <td width="88"><div align="center"><strong><font size="1" face="Verdana">Nro. Control </font></strong></div></td>
			  <td width="122"><div align="center"><strong><font size="1" face="Verdana">Fecha Imp. </font></strong></div></td>
			  <td width="167"><div align="center"><strong><font size="1" face="Verdana">Usuario </font></strong></div></td>
		      <td width="149"><div align="center"><strong><font size="1" face="Verdana">Estado </font></strong></div></td>
			  <td width="75"><div align="center"><strong><font size="1" face="Verdana">Eliminar </font></strong></div></td>
            </tr>
            <p>
              <?php 
					if ($query <> null) {
						while ($rowHisto=mysql_fetch_assoc($rsTotal)) {	
							$sqlUsuario = "select * from usuarios where id = ".$rowHisto['idusuario'];
							$resultUsua = mysql_db_query("acuerdos",$sqlUsuario,$db);
							$rowUsuar=mysql_fetch_array($resultUsua);
						
							$sqlExiste="select * from cuotas where delcod=".$rowHisto['delcod']." and empcod=".$rowHisto['empcod']." and nroacu=".$rowHisto['nroacu']." and nrocuo=".$rowHisto['nrocuo'];
							$resultExiste = mysql_db_query("acuerdos",$sqlExiste,$db);
							$cant = mysql_num_rows($resultExiste);
							
							if ($cant == 0) {
								print ("<td width=61><font face=Verdana size=1>".$rowHisto['delcod']."</font></td>");
								print ("<td width=44><font face=Verdana size=1>".$rowHisto['empcod']."</font></td>");
								print ("<td width=48><font face=Verdana size=1>".$rowHisto['nroacu']."</font></td>");
								print ("<td width=40><font face=Verdana size=1>".$rowHisto['nrocuo']."</font></td>");
								print ("<td width=57><font face=Verdana size=1>".$rowHisto['importe']."</font></td>");
								print ("<td width=88><font face=Verdana size=1>".$rowHisto['fecpro']."</font></td>");
								
								$fecha= substr($rowHisto['fecpro'],6,2)."/".substr($rowHisto['fecpro'],4,2)."/".substr($rowHisto['fecpro'],2,2);
								$hora= substr($rowHisto['fecpro'],8,2).":".substr($rowHisto['fecpro'],10,2).":".substr($rowHisto['fecpro'],12,2);
								print ("<td width=122><font face=Verdana size=1>".$fecha."-".$hora."</font></td>");
								
								if ($rowUsuar['nombre'] <> "") {
									print ("<td width=167><font face=Verdana size=1>".$rowUsuar['nombre']."</font></td>");
								} else {
									print ("<td width=167><font face=Verdana size=1> Usuario Indefinido </font></td>");
								}
								print ("<td width=149><font face=Verdana size=1>Cancelada / No Existe</font></td>");
								print ("<td width=75><div align='center'><input type=checkbox name=seleccion[] value=".$rowHisto['fecpro']."><br></div></td>");
								print ("</tr>");
							}
						}
					}
			?>
        </table>
		    </p>
		</p>
		<div align="center">
		  <input name="eliminar" id="eliminar" type="submit"  value="ELIMINAR" />		
	    </div></td>
      </tr>
</table>

</form>
</body>
</html>
