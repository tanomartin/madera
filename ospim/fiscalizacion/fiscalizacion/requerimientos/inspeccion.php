<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$fecha = $_GET['fecha'];
$nroreq = $_GET['nroreq'];
$cuit = $_GET['cuit'];
$fechaInspec=date("d-m-Y");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Inspeccion de Requerimientos :.</title>
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

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#fecha").mask("99-99-9999");
});

function validar(formulario) {
	if (formulario.inpector.value == 0) {
		alert("Debe seleccionar un Inspector");
		return false;
	}
	formulario.Submit.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><span style="text-align:center">
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'listarRequerimientos.php?fecha=<?php echo $fecha ?>'" align="center"/>
  </span></p>
  <form name="inspecReq" onSubmit="return validar(this)" method="post" action="guardaInspeccion.php" >
		<input name="fechareq" type="text" value="<?php echo $fecha?>" style="display:none"/>
		<input name="nroreq" type="text" value="<?php echo $nroreq?>" style="display:none"/>
	  <p class="Estilo2">Inpecci&oacute;n  del  Requerimiento Nro. <?php echo $nroreq ?></p>
		<table width="600" border="0">
		  <tr>
			<td><div align="right">Requerimiento a Inspeccionar:</div></td>
			<td><div align="left">
			  <input name="nroreq" type="text" value="<?php echo $nroreq?>" readonly="readonly" style="background-color:#CCCCCC"/>
		    </div></td>
		  </tr>
		  <tr>
			<td><div align="right">Fecha de Inspección:</div></td>
			<td><div align="left">
			  <input name="fechainsp" type="text" value="<?php echo $fechaInspec?>" readonly="readonly" style="background-color:#CCCCCC"/>
		    </div></td>
		  </tr>
		   <tr>
			<td>
			  
	          <div align="right">Inspector	           </div></td>
		    <td><div align="left">
		      <select name="inpector" id="inspector" >
		        <option value=0>Seleccionar Inspector</option>
		        <?php 
				$sqlInspec="select codigo, apeynombre from inspectores i, jurisdiccion j where j.cuit = $cuit and j.codidelega = i.codidelega";
				$resInspec=mysql_query($sqlInspec,$db);
				while ($rowInspec=mysql_fetch_array($resInspec)) { ?>
		        <option value="<?php echo $rowInspec['codigo'] ?>"><?php echo $rowInspec['apeynombre'] ?></option>
		        <?php } ?>
	          </select>
	         </div></td>
	      </tr>
	  </table>

		<p><input type="submit" name="Submit" id="Submit" value="Mandar a Inspección" /></p>
  </form>

</div>
</body>
</html>