<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php");
include($_SERVER['DOCUMENT_ROOT']."/madera/lib/fechas.php");

$req=$_GET['req'];
$cuit=$_GET['cuit'];
$sql = "select * from aculiquiospim where nrorequerimiento = $req";
$result = mysql_query($sql,$db); 

$sqlEmpre = "select * from empresas where cuit = $cuit";
$resEmpre = mysql_query($sqlEmpre,$db); 
$rowEmpre = mysql_fetch_array($resEmpre); 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script language="javascript">
function abrirInfo(dire) {
	a= window.open(dire,"InfoAcuerdos",
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}
</script>

</head>

<title>.: Ddjj Empresa :.</title>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <table width="774" border="1">
    <tr>
      <td width="242">C.U.I.T.: <b><?php echo $cuit ?></b></td>
      <td width="516">Nombre: <b><?php echo $rowEmpre['nombre'] ?></b></td>
    </tr>
  </table>
  <p><strong>Acuerdos Incluidos en el Requerimiento Nro. <?php echo  $req?> </strong></p>
  <table style="text-align:center" border="1">
    <tr>
      <td><div align="center"><strong>Nro Acuerdo </strong></div></td>
      <td>&nbsp;</td>
    </tr>
	 
	   <?php while ($row = mysql_fetch_array($result)) { 
		   print("<tr>");
		   print("<td>".$row['nroacuerdo']."</td>");
		   print ("<td width=81><a href=javascript:abrirInfo('/madera/ospim/acuerdos/abm/consultaAcuerdo.php?cuit=".$cuit."&nroacu=".$row['nroacuerdo']."&origen=fiscalizacion')>Ver</a></td>"); 
		   print("</tr>");
	 	}
		?>
  </table>
  
</div>
</body>