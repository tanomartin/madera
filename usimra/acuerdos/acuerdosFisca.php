<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null)
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
<STYLE>BODY {SCROLLBAR-FACE-COLOR: #E4C192; 
SCROLLBAR-HIGHLIGHT-COLOR: #CD8C34; 
SCROLLBAR-SHADOW-COLOR: #CD8C34; 
SCROLLBAR-3DLIGHT-COLOR: #CD8C34; 
SCROLLBAR-ARROW-COLOR: #CD8C34; 
SCROLLBAR-TRACK-COLOR: #CD8C34; 
SCROLLBAR-DARKSHADOW-COLOR: #CD8C34
}
</STYLE>
<title>.: U.S.I.M.R.A. :.</title>
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
<form name="form1" method="post" action="acuerdos2Fisca.php">
  <div align="center">
    <p align="right"><strong></strong></p>
    <table width="100%" border="0">
      <tr> 
        <td width="50%"><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">CUIT 
            :</font></strong></div></td>
        <td width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
          <input type="text" name="textfield">
          </strong></font></td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Delegaci&oacute;n 
            :</font></strong></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
          <input type="text" name="textfield2">
          </strong></font></td>
      </tr>
      <tr> 
        <td><div align="right"><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Empresa 
            :</font></strong></div></td>
        <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong> 
          <input type="text" name="textfield3">
          </strong></font></td>
      </tr>
    </table>
    <p><strong><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
      <input type="submit" name="Submit" value="Enviar">
      </font></strong></p>
    <p>
      <input type="button" name="salir" value="SALIR" onClick="location.href='index.htm'" />
    </p>
  </div>
</form>
<p align="center">&nbsp;</p>
  


</body>
</html>
