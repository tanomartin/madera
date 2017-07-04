<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$sqlMesPadron = "SELECT * FROM padronssscabecera c WHERE fechacierre is null ORDER BY c.id DESC LIMIT 1"; 
$resMesPadron = mysql_query ( $sqlMesPadron, $db );
$canMesPadron = mysql_num_rows($resMesPadron);
if ($canMesPadron == 0) {
	header ("Location: sinPadronSSS.php");
} else {
	$rowMesPadron = mysql_fetch_assoc ($resMesPadron);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Afiliados - Curce SSS - OSPIM :.</title>

<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
</style>

<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script>
function mostrar(dire) {
	$.blockUI({ message: "<h1>Realizando Proceso Pedido... <br>Esto puede tardar unos minutos.<br> Aguarde por favor</h1>" });
	window.location = dire;
}
</script>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<input type="button" name="volver" value="Volver" onclick="location.href = '../menuCruceSSS.php'" /> 
</div>
<div align="center">
	<h2>Men&uacute; Descarga Informacion de Titulares desde la S.S.S.</h2>
	<h2>Padrón SSS Periodo "<?php echo $rowMesPadron['mes'].'-'.$rowMesPadron['anio']?>" </h2>
</div>
<div align="center">
  <table width="600" border="3">
    <tr>
       <td width="200"><p align="center">Descarga Titulares</p>
        <p align="center"><a class="enlace" href="javascript:mostrar('alta/altaTitularesSSS.php')"><img src="../img/descargaTitulares.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
      <td width="200"><p align="center">Reactivacion Titulares</p>
          <p align="center"><a class="enlace" href="javascript:mostrar('reactivacion/reactivaTitularesSSS.php')"><img src="../img/reactivacion.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
         <td width="200"><p align="center">Inconsistencias Titulares</p>
        <p align="center"><a class="enlace" href="javascript:mostrar('inconsistencia/incoTitularesSSS.php')"><img src="../img/listado.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p>&nbsp;</p></td>
    </tr>
  </table>
</div>
</body>
</html>