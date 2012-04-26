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
.Estilo1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
</STYLE>

<title>.: Sistmea de Acuerdos - Actualizacion :.</title>
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

<form name="actualiza" id="actualiza" method="post" action="">
    <table width="689" border="1" align="center">
      <tr>
        <td width="679" height="28">
		    <div align="center">
		      <?php 
		$error=0;
		if (!file_exists("actualizacion/cabeacue.txt")) {
			$error=1;
			echo "El archivo de cabeceras de acuerdos no se ha generado";
			echo '<img src="nook.png" width="20" height="20"><br>';
		} else {
			echo "El archivo de cabeceras de acuerdos se ha generado correctamente";
			echo '<img src="ok.png" width="20" height="20"><br>';
		}
		
		if (!file_exists("actualizacion/cuotacue.txt")) {
			$error=1;
			echo "El archivo de cuotas de acuerdos no se ha generado";
			echo '<img src="nook.png" width="20" height="20"><br>'; 
		} else {
			echo "El archivo de cuotas de acuerdos se ha generado correctamente";
			echo '<img src="ok.png" width="20" height="20"><br>';
		}
		
		if (!file_exists("actualizacion/empracue.txt")) {
			$error=1;
			echo "El archivo de empresas no se ha generado";
			echo '<img src="nook.png" width="20" height="20"><br>'; 
		} else {
			echo "El archivo de empresas se ha generado correctamente";
			echo '<img src="ok.png" width="20" height="20"><br>';
		}
		
		if ($error == 0) {
		?>	
	        </div>
		    <p align="center"><input type="button" name="actualizar" id="actualizar" onClick="location.href='subida.php'" value="Actualizar">
		    </p>
		    <p align="center"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="acuerdos.php">VOLVER</a></strong></font></p>
		    <?php }else{ ?>
		  <p align="center"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="acuerdos.php">VOLVER</a></strong></font></p>
            <?php	
			} 
			?>		
		</td>
      </tr>
</table>

</form>
</body>
</html>
