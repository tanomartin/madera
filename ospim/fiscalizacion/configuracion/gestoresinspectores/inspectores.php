<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Inspectores :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuConfiguracionFiscalizacion.php'" align="center"/>
</p>
  <p><span class="Estilo2">Inspectores</span></p>
  <table border="1" width="732" bordercolorlight="#000099" bordercolordark="#0066FF" bordercolor="#000000" cellpadding="2" cellspacing="0">
    <tr>
      <td width="49"><div align="center"><strong><font size="1" face="Verdana">Codigo</font></strong></div></td>
      <td width="320"><div align="center"><strong><font size="1" face="Verdana">Apellido y Nombre</font></strong></div></td>
	  <td width="126"></td>
	  <td width="93"><div align="center"><strong><font size="1" face="Verdana">Acuerdos</font></strong></div></td>
	  <td width="112"><div align="center"><strong><font size="1" face="Verdana">Acuerdos Activos</font></strong></div></td>
    </tr>
    <?php	
		$sqlInspectores = "select * from inspectores group by codigo order by apeynombre";
		$resInspectores = mysql_query($sqlInspectores,$db); 
		while ($rowInspectores = mysql_fetch_array($resInspectores)) { 
				print("<tr>");
				print ("<td width=49><div align=center><font face=Verdana size=1>".$rowInspectores['codigo']."</font></div></td>");
				print ("<td width=320><div align=center><font face=Verdana size=1>".$rowInspectores['apeynombre']."</font></div></td>");
				print ("<td width=126><div align=center><font face=Verdana size=1><a href='modificarInspector.php?codigo=".$rowInspectores['codigo']."'>Modificar</a></font></div></td>");
				
				$sqlAcuerdosOspim = "select * from cabacuerdosospim where inspectorinterviene = $rowInspectores[codigo]";
				$resAcuerdosOspim = mysql_query($sqlAcuerdosOspim,$db); 
				$ospimCant = mysql_num_rows($resAcuerdosOspim); 
				
				$sqlAcuerdosUsimra = "select * from cabacuerdosusimra where inspectorinterviene = $rowInspectores[codigo]";
				$resAcuerdosUsimra = mysql_query($sqlAcuerdosUsimra,$db); 
				$usimraCant = mysql_num_rows($resAcuerdosUsimra); 
				
				$controlAcu = $ospimCant + $usimraCant;
				
				if ($controlAcu == 0) {
					print ("<td width=93><div align=center><font face=Verdana size=1>Eliminar</font></div></td>");
				} else {
					print ("<td width=93><div align=center><font face=Verdana size=1>".$controlAcu."</font></div></td>");
				}
				
				$sqlActivoOspim = "select * from cabacuerdosospim where inspectorinterviene = $rowInspectores[codigo] and estadoacuerdo = 0";
				$resActivoOspim = mysql_query($sqlActivoOspim,$db); 
				$ospimCantActivo = mysql_num_rows($resActivoOspim); 
				
				$sqlActivoUsimra = "select * from cabacuerdosusimra where inspectorinterviene = $rowInspectores[codigo] and estadoacuerdo = 0";
				$resActivoUsimra = mysql_query($sqlActivoUsimra,$db); 
				$usimraCantActivo = mysql_num_rows($resActivoUsimra); 
				
				$controlAcuActivos = $ospimCantActivo + $usimraCantActivo;
				
				if ($controlAcuActivos == 0) {
					print ("<td width=112><div align=center><font face=Verdana size=1>Desactivar</font></div></td>");
				} else {
					print ("<td width=112><div align=center><font face=Verdana size=1>".$controlAcuActivos."</font></div></td>");
				}
				print ("</tr>"); 
			}
	  ?>
  </table>
  <table width="732" border="0">
    <tr>
      <td width="360"><div align="left">
          <input name="nuevo" type="button" id="nuevo" onclick="location.href = 'nuevoInspector.php'"  value="Nuevo" />
      </div></td>
      <td width="362"><div align="right">
          <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="center"/>
      </div></td>
    </tr>
  </table>
</div>
</body>
</html>
