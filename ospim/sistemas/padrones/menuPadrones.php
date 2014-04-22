<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspimSistemas.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Stock :.</title>
</head>

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
<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(formulario) {
	if(	!document.getElementById("001").checked && !document.getElementById("002").checked && 
		!document.getElementById("003").checked && !document.getElementById("004").checked &&
		!document.getElementById("005").checked && !document.getElementById("006").checked && 
		!document.getElementById("007").checked && !document.getElementById("008").checked) {
		alert("Debe Seleccionar por lo menos un Prestador");
		return(false);
	}
	if (formulario.periodo.value == 0) {
		alert("Debe Seleccionar un Período");
		return(false);
	}
	$.blockUI({ message: "<h1>Generando Padrones<br>Aguarde por favor...</h1>" });
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuSistemas.php'" align="center"/>
  </span></p>
  <p><span class="Estilo2">Men&uacute; Padrones</span></p>
  <form id="form1" name="form1" method="post" action="generarPadrones.php" onsubmit="return validar(this)">
    <p><strong>Seleccionar Prestadore</strong>s</p>
    <table width="400" border="1">
	<?php 
		$resPresta = mysql_query("SELECT * FROM prestadores", $db);
		while($rowPresta = mysql_fetch_array($resPresta)) { 
			echo "<tr>";
			$codigo = $rowPresta['codigo'];
			$nombre = $rowPresta['nombre'];
			echo "<td><div align='left'>$codigo - $nombre</div></td>";
			echo "<td><input type='checkbox' id=$codigo name=$codigo value=$codigo></td>";
			echo "</tr>";
		} 
	?>  
    </table>  
    <p class="Estilo7">Periodo 
      <label>
		  <?php 
		  $mes = date("n");
		  $anio = date("Y");
		  if ($mes == 1) {
			$anio =  $anio - 1;
			$mes = 12;
		  } else {
			$mes = $mes - 1;
		  }
		  ?>
		  <input name="periodo" readonly="readonly" style="background-color:#CCCCCC; width:60px; text-align:center"  value="<?php echo $mes."-".$anio  ?>" type="text" />
      </label>
    </p>
    <p>
      <label>
      <input type="submit" name="Submit" value="Generar Padrones" />
      </label>
    </p>
  </form>
  </div>
</body>
</html>
