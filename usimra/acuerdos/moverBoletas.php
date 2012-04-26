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

<form name="mover" id="mover" method="post" action="">
    <table width="906" border="1" align="center">
      <tr>
        <td width="716"><div align="left">
          <h3><strong>Informe de Eliminación</strong></h3>
        </div></td>
        <td width="111"> <div align="center"><a href="acuerdos.php"><font color="#CD8C34" face="Verdana" size="2"><b>Menú Principal</b></font></a></div></td>
        <td width="57"><div align="center"><a href="informeCNE.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a></div></td>
      </tr>
      <tr>
        <td colspan="3">
		  <p>
		    <?php 
		if (count($_POST['seleccion']) != 0) {
			foreach ($_POST['seleccion'] as $fecpro){ 
				 $sql = "INSERT INTO eliminadas SELECT * FROM depositos WHERE fecpro =".$fecpro;
				 $result = mysql_db_query("acuerdos",$sql,$db);
				 if ($result == true) {
				 	$sqlElim = "DELETE FROM depositos WHERE fecpro =".$fecpro;
					$resultElim = mysql_db_query("acuerdos",$sqlElim,$db);
					if ($resultElim == true) {
						print ("Se eliminó la boleta número: ".$fecpro);
						echo '<img src="ok.png" width="20" height="20"><br>'; 
					} else {
						print ("NO se pudo eliminar la boleta número: ".$fecpro);
						echo '<img src="nook.png" width="20" height="20"><br>'; 
					}
				 }  else {
				  	print ("NO se pudo mover la boleta número: ".$fecpro."<br>");
					echo '<img src="nook.png" width="20" height="20"><br>'; 
				 }
			}  
		} else {
			print ("NO se seleccionó ninguna boleta para eliminar");
			echo '<img src="nook.png" width="20" height="20"><br>'; 
		}
			
		?>
        </p>
	    <p align="center">
	      <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" />
</p></td>
      </tr>
</table>

</form>
</body>
</html>
