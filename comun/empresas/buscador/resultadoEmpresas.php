<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php");

$criterio = $_POST['criterio'];
$dato = $_POST['dato'];

if ($criterio == "cuit") {
	$sqlbusqueda = "select * from empresas where cuit = $dato";
	$sqlbusquedabaja = "select * from empresasdebaja where cuit = $dato";
}
if ($criterio == "razonsocial") {
	$sqlbusqueda = "select * from empresas where nombre like '%$dato%'";
	$sqlbusquedabaja = "select * from empresasdebaja where nombre like '%$dato%'";
}
if ($criterio == "domicilio") {
	$sqlbusqueda = "select * from empresas where domilegal like '%$dato%'"; 
	$sqlbusquedabaja = "select * from empresasdebaja where domilegal like '%$dato%'";
}

$resbusqueda = mysql_query($sqlbusqueda,$db);
$canbusqueda = mysql_num_rows($resbusqueda); 
$resbusquedabaja = mysql_query($sqlbusquedabaja,$db);
$canbusquedabaja = mysql_num_rows($resbusquedabaja); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultado Busqueda Empresa :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>

<body style="background-color: <?php echo $bgcolor ?>">
<p align="center">
<input type="reset" name="volver" value="Volver" onclick="location.href = 'buscador.php?origen=<?php echo $origen ?>'"/> 
</p>
<p align="center" class="Estilo2">Resultado de la Busqueda</p>
<div align="center">
	<p><span class="Estilo2">Empresas Activas </span></p>
	<table border="1" >
  <tr>
    <td width="104"><div align="center"><strong>C.U.I.T.</strong></div></td>
    <td width="349"><div align="center"><strong>Razón Social</strong></div></td>
    <td width="351"><div align="center"><strong>Dirección</strong></div></td>
    <td width="74"><div align="center"></div></td>
  </tr>

<?php 
if ($canbusqueda > 0) {
	while ($rowbusqueda = mysql_fetch_array($resbusqueda)) {  
		$cuit = $rowbusqueda['cuit'];
		print ("<td><div align=center>".$rowbusqueda['cuit']."</div></td>");
		print ("<td><div align=center>".$rowbusqueda['nombre']."</div></td>");
		print ("<td><div align=center>".$rowbusqueda['domilegal']."</div></td>");
		print ("<td><div align=center><a href=../abm/empresa.php?origen=$origen&cuit=$cuit>+ INFO</a></div></td>");
		print ("</tr>"); 	

	}
} else {
		print ("<td colspan='4'><div align=center>No hay empresas activas para esta busqueda</div></td>");
}?>
</table>
    <p><strong>Empresas de Baja </strong></p>
    <table border="1" >
      <tr>
        <td width="104"><div align="center"><strong>C.U.I.T.</strong></div></td>
        <td width="349"><div align="center"><strong>Raz&oacute;n Social</strong></div></td>
        <td width="351"><div align="center"><strong>Direcci&oacute;n</strong></div></td>
        <td width="74"><div align="center"></div></td>
      </tr>
      <?php 
			if ($canbusquedabaja > 0) {	  
				  while ($rowbusquedabaja = mysql_fetch_array($resbusquedabaja)) {  
					$cuit = $rowbusquedabaja['cuit'];
					print ("<td><div align=center>".$rowbusquedabaja['cuit']."</div></td>");
					print ("<td><div align=center>".$rowbusquedabaja['nombre']."</div></td>");
					print ("<td><div align=center>".$rowbusquedabaja['domilegal']."</div></td>");
					print ("<td><div align=center><a href=../abm/empresaBaja.php?origen=$origen&cuit=$cuit>+ INFO</a></div></td>");
					print ("</tr>"); 	
			
				}
			} else {
					print ("<td colspan='4'><div align=center>No hay empresas de baja para esta busqueda</div></td>");
			}
		?>
    </table>
    </div>

</body>
</html>
