<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigosecre = $_GET['secre'];
$codigoJuzga = $_GET['juz'];

$sqlJuzgado = "select * from juzgados where codigojuzgado = $codigoJuzga";
$resJuzgado = mysql_query($sqlJuzgado,$db); 
$rowJuzgado = mysql_fetch_assoc($resJuzgado);


$sqlSecretaria = "select * from secretarias where codigojuzgado = $codigoJuzga and codigosecretaria = $codigosecre";
$resSecretaria = mysql_query($sqlSecretaria,$db); 
$rowSecretaria = mysql_fetch_array($resSecretaria);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Secretaria :.</title>
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

<script type="text/javascript">

function validar(formulario) {
	if (formulario.denominacion.value == "") {
		alert("Debe completar la Denominación de la Secretaria");
		return(false);
	}
	formulario.guardar.disabled = true;
	formulario.eliminar.disabled = true;
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'secretarias.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Juzgado </span></p>
  <form id="modifSecre" name="modifSecre" method="post" action="guardarModifSecretaria.php?codsecre=<?php echo $codigosecre ?>&codjuz=<?php echo $codigoJuzga ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo Secretaría: <b> <?php echo $codigosecre ?> </b></label>
				</p>
				<p>
				  <label>Juzgado: <b> <?php echo $codigoJuzga ?> - <?php echo $rowJuzgado['denominacion'] ?></b></label>
				</p>
				<p>
				  <label>Denominación
				  <input name="denominacion" type="text" id="denominacion" value="<?php echo $rowSecretaria['denominacion'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<table border="0">
                  <tr>
                    
                      <?php
					  $sqlTraJuicios = "select * from trajuiciosospim where codigojuzgado = $codigoJuzga and codigosecretaria = $codigosecre";
					  $resTraJuicios = mysql_query($sqlTraJuicios,$db); 
					  $canTraJuicios = mysql_num_rows($resTraJuicios); 
					 			  
					  if ($canTraJuicios == 0) { ?>
					  <td><div align="center">
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarSecretaria.php?codsecre=<?php echo $codigosecre ?>&codjuz=<?php echo $codigoJuzga ?>'" value="Eliminar" />
						</div></td>
			   <?php } ?>
                    
                    <td><div align="center">
                      <input type="submit" name="guardar" value="Guardar Cambios" sub/>
                    </div></td>
                  </tr>
                </table>
    </form>
</div>
</body>
</html>
