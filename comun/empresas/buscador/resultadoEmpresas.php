<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 

$criterio = $_POST['criterio'];
$dato = $_POST['dato'];
if ($criterio == "cuit") {
	$sqlbusqueda = "select * from empresas where cuit = $dato";
}
if ($criterio == "razonsocial") {
	$sqlbusqueda = "select * from empresas where nombre like '%$dato%'";
}
if ($criterio == "domicilio") {
	$sqlbusqueda = "select * from empresas where domilegal like '%$dato%'"; 
}
$resbusqueda = mysql_query($sqlbusqueda,$db);
$canbusqueda = mysql_num_rows($resbusqueda); 
if ($canbusqueda == 0) {
	//Aca hay que buscar en empresa de baja y mandar a la pantalla de consulta
	header ("Location: buscador.php?origen=$origen&err=1");
} 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Resultado Busqueda Empresa :.</title>
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
<body bgcolor=<?php echo $bgcolor ?>>
<p align="center" class="Estilo2"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="buscador.php?origen=<?php echo $origen ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></font></p>
<p align="center" class="Estilo2">Resultado de la Busqueda</p>
<div align="center">
	<table border="1" >
  <tr bordercolor="#000000">
    <td width="104"><div align="center"><strong>C.U.I.T.</strong></div></td>
    <td width="349"><div align="center"><strong>Razón Social</strong></div></td>
    <td width="351"><div align="center"><strong>Dirección</strong></div></td>
    <td width="74"><div align="center"></div></td>
  </tr>

<?php while ($rowbusqueda = mysql_fetch_array($resbusqueda)) {  
		$cuit = $rowbusqueda['cuit'];
		print ("<td><div align=center>".$rowbusqueda['cuit']."</div></td>");
		print ("<td><div align=center>".$rowbusqueda['nombre']."</div></td>");
		print ("<td><div align=center>".$rowbusqueda['domilegal']."</div></td>");
		print ("<td><div align=center><a href=../abm/empresa.php?origen=$origen&cuit=$cuit>+ INFO</a></div></td>");
		print ("</tr>"); 	

}?>
</table>
</div>

</body>
</html>
