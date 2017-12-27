<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspimSistemas.php"); 
include($libPath."claves.php");

$maquina = $_SERVER['SERVER_NAME'];
if(strcmp("localhost",$maquina)==0) {
	$hostUsimra = "localhost"; //para las pruebas...
}
$dbInternet =  mysql_connect($hostUsimra,$usuarioUsimra,$claveUsimra );
if (!$dbInternet) {
	die('No pudo conectarse a la base de OSPIM.COM.AR: ' . mysql_error());
}
mysql_select_db($baseUsimraIntranet);
$sqlUltimaActua = "select fechaactualizacion from usuarios where delcod = '3200'";
$resUltimaActua = mysql_query($sqlUltimaActua,$dbInternet);
$rowUltimaActua = mysql_fetch_array($resUltimaActua);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
.Estilo7 {font-weight: bold}
</style>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(formulario) {
	var mensaje = "<h1>Actualizando Intranet U.S.I.M.R.A.<br>Aguarde por favor...</h1>"
	$.blockUI({ message: mensaje });
	return true;
}

</script>
</head>


<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'menuActualizacionUsimra.php'" />
  </span></p>
  <p><span class="Estilo2">Men&uacute; Actualizacion Intranet U.S.I.M.R.A. </span></p>
  <p><span class="Estilo2">Fecha última actualizacion "<?php echo $rowUltimaActua['fechaactualizacion'] ?>" </span></p>
  <form action="actualizarIntraUsimra.php" method="post" enctype="multipart/form-data" name="form1" id="form1" onsubmit="return validar(this)">
    <p>
      <label></label>  
      <label>
      <input type="submit" name="Submit" value="Actualizar" />
      </label>
    </p>
  </form>
</div>
</body>
</html>
