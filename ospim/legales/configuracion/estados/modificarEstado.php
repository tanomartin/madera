<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlEstado = "select * from estadosprocesales where codigo = $codigo";
$resEstado = mysql_query($sqlEstado,$db); 
$rowEstado = mysql_fetch_array($resEstado);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Estado Procesal :.</title>
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
	if (formulario.descri.value == "") {
		alert("Debe completar la Denominación del Juzgado");
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'estados.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Estado Procesal </span></p>
  <form id="modifEstado" name="modifEstado" method="post" action="guardarModifEstado.php?codigo=<?php echo $codigo ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b> <?php echo $rowEstado['codigo']; ?> </b></label>
				</p>
				<p>
				  <label>Denominación
				  <input name="descri" type="text" id="descri" value="<?php echo $rowEstado['descripcion'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<table border="0">
                  <tr>
                    
                      <?php
					  $sqlTraJuicios = "select * from trajuiciosospim where estadoprocesal = $codigo";
					  $resTraJuicios = mysql_query($sqlTraJuicios,$db); 
					  $canTraJuicios = mysql_num_rows($resTraJuicios); 
					 			  
					  if ($canTraJuicios == 0) { ?>
					  <td><div align="center">
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarEstado.php?codigo=<?php echo $codigo ?>'" value="Eliminar" />
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
