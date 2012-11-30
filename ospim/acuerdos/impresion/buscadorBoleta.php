<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php"); 

$nrocontrol = $_POST['nrocontrol'];
$tipoBoletas = "";
$noExiste = 0;
if ($nrocontrol != "") {
	$sqlBoletas = "SELECT * from boletasospim where nrocontrol = $nrocontrol";
	$resBoletas = mysql_query($sqlBoletas,$db); 
	$canBoletas = mysql_num_rows($resBoletas); 
	if ($canBoletas != 0) {
		$rowAMostrar = mysql_fetch_array($resBoletas); 
		$tipoBoletas = "Generada";
	} else {
		$sqlBoletasValidas = "SELECT * from validasospim where nrocontrol = $nrocontrol";
		$resBoletasValidas = mysql_query($sqlBoletasValidas,$db); 
		$canBoletasValidas = mysql_num_rows($resBoletasValidas); 
		if ($canBoletasValidas != 0) {
			$tipoBoletas = "Validada";
			$rowAMostrar = mysql_fetch_array($resBoletasValidas); 
		} else {
			$sqlBoletasAnuladas = "SELECT * from anuladasospim where nrocontrol = $nrocontrol";
			$resBoletasAnuladas = mysql_query($sqlBoletasAnuladas,$db); 
			$canBoletasAnuladas = mysql_num_rows($resBoletasAnuladas); 
			if ($canBoletasAnuladas != 0) {
				$tipoBoletas = "Anulada";
				$rowAMostrar = mysql_fetch_array($resBoletasAnuladas); 
			} else {
				$noExiste = 1;
			}
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Acuerdo OSPIM :.</title>
<style type="text/css">
<!--
.Estilo1 {
	font-size: 18px;
	font-weight: bold;
}
-->
</style>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#nrocontrol").mask("99999999999999");
});

</script>
<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="buscadorBoleta.php">
  <p align="center" class="Estilo1"><font color="#000000" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong><a href="menuBoletas.php">VOLVER</a></strong></font></p>
  <p align="center" class="Estilo1">M&oacute;dulo Buscador de Bolestas</p>
   <?php 
		if ($noExiste == 1) {
			print("<div align='center' style='color:#FF0000'><b> NO EXISTE LA BOLETA CON EL NUMERO DE CONTROL ".$nrocontrol."</b></div><br>");
		}
  ?>
  <label> 
  <div align="center"> Nro. Control <input name="nrocontrol" type="text" id="nrocontrol" size="14" /></div>
  </label>
  <p align="center">
    <label>
    <input type="submit" name="Buscar" value="Buscar" />
    </label>
  </p>
  <p align="center">
 <?php if ($tipoBoletas != "") {
			 print("<div align='center'><b> BOLETA ".$tipoBoletas."</b></div>");  ?>
  </p>
  <div align="center">
    <table width="543">
      <tr>
        <td width="160"><div align="right">CUIT</div></td>
        <td width="371" style="border:groove"><div align="left"><strong><?php echo $rowAMostrar['cuit']; ?></strong></div></td>
      </tr>
      <tr>
        <td><div align="right">Raz&oacute;n Social </div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong>
            <?php 
			$sqlEmp = "select * from empresas where cuit = ".$rowAMostrar['cuit'];
			$resEmp = mysql_query($sqlEmp,$db); 
			$rowEmp = mysql_fetch_array($resEmp); 
			echo $rowEmp['nombre']; 
		?>
          </strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Acuerdo </div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong><?php echo $rowAMostrar['nroacuerdo']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Nro Cuota</div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong><?php echo $rowAMostrar['nrocuota']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Importe</div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong><?php echo $rowAMostrar['importe']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Identificacion Boleta </div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong><?php echo $rowAMostrar['nrocontrol']; ?></strong></div>
        </div></td>
      </tr>
      <tr>
        <td><div align="right">Usuario Emisi&oacute;n </div></td>
        <td><div align="left" style="border:groove">
          <div align="left"><strong><?php echo $rowAMostrar['usuarioregistro']; ?></strong></div>
        </div></td>
      </tr>
      </table>
    <p>
      <input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left" />
    </p>
  </div>
  <?php } ?>
</form>
</body>
</html>
