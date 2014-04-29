<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlAsesor = "select * from asesoreslegales where codigo = $codigo";
$resAsesor = mysql_query($sqlAsesor,$db); 
$rowAsesor = mysql_fetch_array($resAsesor);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Asesores :.</title>
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
	if (formulario.apeynombre.value == "") {
		alert("Debe completar en Nombre y el Apellido");
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'asesores.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Asesores Legales </span></p>
  <form id="modifGestor" name="modifGestor" method="POST" action="guardarModifAsesor.php?codigo=<?php echo $codigo ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b> <?php echo $rowAsesor['codigo']; ?> </b></label>
				</p>
				<p>
				  <label>Apellido y Nombre 
				  <input name="apeynombre" type="text" id="apeynombre" value="<?php echo $rowAsesor['apeynombre'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<p>
                  <?php 
						$error = $_GET['error'];
						if ($error == 1) {
							print("<div align='center' style='color:#FF0000'><b> Debe elegir una o varias delegaciones </b></div>");
						}
					?>
                  <br/>
                  </label>
</p>
				<table width="300" border="1">
                  <tr>
                    <td>&nbsp;</td>
                    <td>Delegaciones</td>
                  </tr>
                  <?php 
					$i = 0;
					$resDelega= mysql_query("SELECT * FROM delegaciones where codidelega > 1001 and codidelega < 3500", $db);
					while($rowDelega= mysql_fetch_array($resDelega)) { 
						echo '<tr>';
						$codigoDelega = $rowDelega['codidelega'];
						$sqlExiste = "select * from asesoreslegales where codigo = $codigo and codidelega = $codigoDelega";
						$resExiste = mysql_query($sqlExiste,$db); 
						$numExiste = mysql_num_rows($resExiste);
						if ($numExiste == 1) {
							echo '<td><input type="checkbox" id="delega'.$i.'" name="delega'.$i.'" value='.$codigoDelega.' checked></td>';
						} else {
							echo '<td><input type="checkbox" id="delega'.$i.'" name="delega'.$i.'" value='.$codigoDelega.'></td>';
						}
						echo '<td><span class="Estilo1">'.$rowDelega["nombre"].'</span><br></td>'; 
						$i = $i + 1;
						echo '</tr>';
					} 
					?>
                </table>
				<p>&nbsp;</p>
				<table border="0">
                  <tr>
                    
                      <?php
					  $sqlCabJuicios = "select * from cabjuiciosospim where codasesorlegal = $codigo";
					  $resCabJuicios = mysql_query($sqlCabJuicios,$db); 
					  $canCabJuicios = mysql_num_rows($resCabJuicios); 
					 			  
					  if ($canCabJuicios == 0) { ?>
					  <td><div align="center">
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarAsesor.php?codigo=<?php echo $codigo ?>'" value="Eliminar" />
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
