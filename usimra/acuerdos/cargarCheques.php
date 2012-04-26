<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['aut'] > 1)
	header ("location:index.htm");
?>

<?php include("../controlSession.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Cargar Cheques</title>
</head>
<?php
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);
?>
<body bgcolor="#E4C192" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<p align="center"><img border="0" src="top.jpg" width="700" height="120"></p>
<p align="center"><strong><font color="#990000" size="1" face="Verdana, Arial, Helvetica, sans-serif">Bienvenid@</font><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> 
<? echo $row['nombre']?></font></strong></p>
<form id="form1" name="form1" method="post" action="crearExcel.php">
  <label>
  <div align="center"><strong>INGRESAR N&Uacute;MEROS DE CHQUES (Separados por ENTER) </strong><br />
    <br />
    <textarea name="cheques" rows="10" id="cheques"></textarea>
  </div>
  </label>
  <p>
    <label>
    <div align="center">
      	<input type="submit" name="Submit" value="Enviar" />
    </div>
    </label>
  </p>
</form>
<div align="center"><a href="acuerdos.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a>
</div>
</body>
</html>
