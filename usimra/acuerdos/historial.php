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


<title>.: Sistmea de Acuerdos - Historial :.</title>
</head>
<?
include("conexion.php");
$sql = "select * from usuarios where id = '$_SESSION[usuario]'";
$result = mysql_db_query("acuerdos",$sql,$db);
$row=mysql_fetch_array($result);

// maximo por pagina 
$limit = 50; 

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

<form name="historial" id="historial" method="post" action="historial.php">
    <table width="906" border="1" align="center">
      <tr>
        <td width="474">
		  <p>Seleccione el orden: 
			 <select name="orden" id="orden">
			  <option value="nroctr">Numero de Contro - Fecha Imp.</option>
			  <option value="delemp">Delegacion Empresa</option>
			  <option value="acucuo">Acuerdo Cuota</option>
			  <option value="usuario">Usuario</option>
			</select>
	    </p>		  </td>
        <td width="410" rowspan="2"><input name="listar" id="listar" type="submit"  value="LISTAR / BUSCAR"  /></td>
        <td width="48" rowspan="2"><a href="acuerdos.php"><font color="#CD8C34" face="Verdana" size="2"><b>Volver</b></font></a></td>
      </tr>
      <tr>
        <td>Dato  
          <label>
          <input name="nrocheque" type="text" id="nrocheque">
          Bucar por: 
          <input name="tipo" type="radio" value="nrocheque" checked>Nro Cheque  
		  <input name="tipo" type="radio" value="importe">Importe       
		  </label></td>
      </tr>
      <tr>
        <td colspan="3">
		<?php 
			$orden=$_GET['orden'];
			if ($orden == "") {
				$datos=array_values($_POST);
				$orden=$datos[0];
			}
			if ($orden == "delemp") {
				$query="select SQL_CALC_FOUND_ROWS * from depositos order by delcod, empcod, nroacu, nrocuo LIMIT $offset, $limit";
			}
			if ($orden == "acucuo") {
				$query="select SQL_CALC_FOUND_ROWS * from depositos order by nroacu, nrocuo, delcod, empcod LIMIT $offset, $limit";
			}
			if ($orden == "nroctr") {
				$query="select SQL_CALC_FOUND_ROWS * from depositos order by fecpro DESC, delcod, empcod LIMIT $offset, $limit";
			}
			if ($orden == "usuario") {
				$query="select SQL_CALC_FOUND_ROWS * from depositos order by idusuario LIMIT $offset, $limit";
			}
			$dato=$_GET['dato'];
			$tipo=$_GET['tipo'];
			if ($dato == "") {
				$dato=$_POST['nrocheque'];
				$tipo = $_POST['tipo'];
			}
			if ($dato <> "") {
				$query="select SQL_CALC_FOUND_ROWS * from depositos where $tipo = $dato order by fecpro DESC LIMIT $offset, $limit";
			} 
			
			$sqlTotal = "SELECT FOUND_ROWS() as total"; 
			$rs = mysql_query($query); 
			$rsTotal = mysql_query($sqlTotal); 
			$rowTotal = mysql_fetch_assoc($rsTotal); 
			// Total de registros sin limit 
			$total = $rowTotal["total"]; 
		?>
          <table width="944" border="1" align="center" cellpadding="2" cellspacing="0" bordercolor="#CD8C34" bordercolorlight="#D08C35" bordercolordark="#D08C35">
            <tr>
              <td width="60"><div align="center"><strong><font size="1" face="Verdana">Delegaci&oacute;n</font></strong></div></td>
              <td width="43"><div align="center"><strong><font size="1" face="Verdana">Empresa</font></strong></div></td>
              <td width="49"><div align="center"><strong><font size="1" face="Verdana">Acuerdo</font></strong></div></td>
              <td width="44"><div align="center"><strong><font size="1" face="Verdana">Cuota</font></strong></div></td>
              <td width="59"><div align="center"><strong><font size="1" face="Verdana">Importe</font></strong></div></td>
              <td width="105"><div align="center"><strong><font size="1" face="Verdana">Nro. Control </font></strong></div></td>
              <td width="126"><div align="center"><strong><font size="1" face="Verdana">Fecha Imp. </font></strong></div></td>
              <td width="100"><div align="center"><strong><font size="1" face="Verdana">Nro Cheque </font></strong></div></td>
			  <td width="166"><div align="center"><strong><font size="1" face="Verdana">Usuario </font></strong></div></td>
              <td width="136"><div align="center"><strong><font size="1" face="Verdana">Estado </font></strong></div></td>
            </tr>
            <p>
              <?php 
					if ($query <> null) {
						while ($rowHisto=mysql_fetch_assoc($rs)) {	
							$sqlUsuario = "select * from usuarios where id = ".$rowHisto['idusuario'];
							$resultUsua = mysql_db_query("acuerdos",$sqlUsuario,$db);
							$rowUsuar=mysql_fetch_array($resultUsua);
						
		$sqlExiste="select * from cuotas where delcod=".$rowHisto['delcod']." and empcod=".$rowHisto['empcod']." and nroacu=".$rowHisto['nroacu']." and nrocuo=".$rowHisto['nrocuo'];
							$resultExiste = mysql_db_query("acuerdos",$sqlExiste,$db);
							$cant = mysql_num_rows($resultExiste);
							
		$sqlExBoleta = "select * from boletas where delcod=".$rowHisto['delcod']." and empcod=".$rowHisto['empcod']." and nroacu=".$rowHisto['nroacu'];" and nrocuo=".$rowHisto['nrocuo'];
							$resultExBoleta = mysql_db_query("acuerdos",$sqlExBoleta,$db);
							$cantBoleta = mysql_num_rows($resultExBoleta);				
							
							print ("<td width=60><font face=Verdana size=1>".$rowHisto['delcod']."</font></td>");
							print ("<td width=43><font face=Verdana size=1>".$rowHisto['empcod']."</font></div></td>");
							print ("<td width=49><font face=Verdana size=1>".$rowHisto['nroacu']."</font></td>");
							print ("<td width=44><font face=Verdana size=1>".$rowHisto['nrocuo']."</font></div></td>");
							print ("<td width=59><font face=Verdana size=1>".$rowHisto['importe']."</font></div></td>");
							print ("<td width=105><font face=Verdana size=1>".$rowHisto['fecpro']."</font></div></td>");
							
							$fecha= substr($rowHisto['fecpro'],6,2)."/".substr($rowHisto['fecpro'],4,2)."/".substr($rowHisto['fecpro'],2,2);
							$hora= substr($rowHisto['fecpro'],8,2).":".substr($rowHisto['fecpro'],10,2).":".substr($rowHisto['fecpro'],12,2);
							print ("<td width=126><font face=Verdana size=1>".$fecha."-".$hora."</font></div></td>");
							
							if ($rowHisto['nrocheque'] <> "") {
								print ("<td width=100><font face=Verdana size=1>".$rowHisto['nrocheque']."</font></div></td>");
							} else {
								print ("<td width=100><font face=Verdana size=1> - </font></div></td>");
							}
							
							if ($rowUsuar['nombre'] <> "") {
								print ("<td width=166><font face=Verdana size=1>".$rowUsuar['nombre']."</font></div></td>");
							} else {
								print ("<td width=166><font face=Verdana size=1> Usuario Indefinido </font></div></td>");
							}
							
							if ($cant > 0) {
								if ($cantBoleta > 0) {
									print ("<td width=136><font face=Verdana size=1 color=#0000FF><a href=reimprimir.php?cuota=".$rowHisto['nrocuo']."&acuerdo=".$rowHisto['nroacu']."&empcod=".$rowHisto['empcod']."&delcod=".$rowHisto['delcod'].">Rehabilitar Cuota</font></div></td>");
								} else {
									print ("<td width=136><font face=Verdana size=1>Boleta Impresa</font></div></td>");
								}
							} else {
								print ("<td width=136><font face=Verdana size=1>Cancelada / No Existe</font></div></td>");
							}
							print ("</tr>");
						
						
						}
					}
			
			?>
                    </table>
          <p></p>
          <?php  $totalPag = ceil($total/$limit);  ?>
          <p align="center">P&aacute;gina <?php echo $pag ?> de <?php echo $totalPag?> </p>
          <p align="center">
            <?php 
         $links = array(); 
         for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i&orden=$orden&dato=$dato&tipo=$tipo\">$i</a>";  
         } 
         echo implode(" - ", $links); 
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
