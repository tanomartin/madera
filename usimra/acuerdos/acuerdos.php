<? session_save_path("sessiones");
session_start();
if($_SESSION['usuario'] == null or $_SESSION['aut'] > 1)
	header ("location:index.htm");
?>

<?php include("../controlSession.php");?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
<!--
A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}
-->

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
.Estilo2 {font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: bold; font-size: 16px; }
</style>

<title>.: Sistema de Acuerdos :.</title>
</head>
<?
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

// maximo por pagina 
$limit = 20; 

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
    <table width="1198" border="1">
      <tr bordercolor="#000000">
        <td width="393" height="126" bordercolor="#000000"><p align="center" class="Estilo1">M&oacute;dulo Actualizaci&oacute;n</p>
            <p align="center">
              <input type="button" name="actualizar" id="actualizar" onClick="location.href='actualiza.php'" value="Actualizar">
        </p>
            <p align="center">
			<?php if ($_SESSION['aut'] == 0) {
				print("<a href='sistemas/index.htm' TARGET=”_blank”><font color='#CD8C34' face='Verdana' size='2'>Cierre Recaudación <br></font></a>");
			}
			?>
			</p></td>
        <td width="789" rowspan="4" valign="top"><div align="center">
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
					$sqlHisto = "select SQL_CALC_FOUND_ROWS * from depositos where fecpro like '$hoy%' order by fecpro DESC LIMIT $offset, $limit";
					$sqlTotal = "SELECT FOUND_ROWS() as total"; 
					$rs = mysql_query($sqlHisto); 
					$rsTotal = mysql_query($sqlTotal); 
				
					$rowTotal = mysql_fetch_assoc($rsTotal); 
					// Total de registros sin limit 
					$total = $rowTotal["total"]; 
					
					while ($rowHisto=mysql_fetch_assoc($rs)) {
						
						$sqlUsuario = "select * from usuarios where id = ".$rowHisto['idusuario'];
						$resultUsua = mysql_db_query("acuerdos",$sqlUsuario,$db);
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
        <p align="center"><?php 
         $totalPag = ceil($total/$limit); 
         $links = array(); 
         for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i\">$i</a>";  
         } 
         echo implode(" - ", $links); 
      ?> </p></p>
        <p align="center">
          <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" />
        </p>
        <p align="center"><a href="cargarCheques.php">Generar Listado de Cheques a Depositar </a></p></td>
      </tr>
      <tr bordercolor="#000000">
        <td height="216"><p align="center" class="Estilo1">Boletas a Depositar </p>
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
        <td height="92" align="center" bordercolor="#000000" class="Estilo1"><p>Historial de Generaci&oacute;n de Boletas </p>
          <p><input type="button" name="historial" id="historial" onClick="location.href='historial.php?orden=nroctr'" value="Entrar"></p>        </td>
      </tr>
      <tr>
        <td height="93" align="center" bordercolor="#000000" class="Estilo2"><p>Eliminaci&oacute;n Boletas Cancelada / No Existe</p>
		  <p><input type="button" name="informeCNE" id="informeCNE" onClick="location.href='informeCNE.php'" value="Entrar"></p>    
      </tr>
    </table>
    <p>
      <input type="button" name="salir" value="SALIR" onClick="location.href='logout.php'" />
    </p>
  </div>
</form>
<p align="center">&nbsp;</p>
</body>
</html>
