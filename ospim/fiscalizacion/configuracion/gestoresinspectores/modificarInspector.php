<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlInspector = "select * from inspectores where codigo = $codigo";
$resInspector = mysql_query($sqlInspector,$db); 
$rowInspector = mysql_fetch_array($resInspector);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Inspector :.</title>

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
</head>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="button" name="volver" value="Volver" onclick="location.href = 'inspectores.php'" />
 </p>
  <p><span class="Estilo2">Modificar Inspector </span></p>
  <form id="modifInspector" name="modifInspector" method="post" action="guardarModifInspector.php?codigo=<?php echo $codigo ?>" onsubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b> <?php echo $codigo ?> </b></label>
				</p>
				<p>
				  <label>Apellido y Nombre 
				  <input name="apeynombre" type="text" id="apeynombre" value="<?php echo $rowInspector['apeynombre'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<p>
					<label>
					<?php 
						$error = $_GET['error'];
						if ($error == 1) {
							print("<div align='center' style='color:#FF0000'><b> Debe elegir una o varias delegaciones </b></div>");
						}
					?>
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
						$sqlExiste = "select * from inspectores where codigo = $codigo and codidelega = $codigoDelega";
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
				<table width="528" border="0">
                  <tr>
                    <td width="172"><div align="center">
                      <?php
					  $sqlAcuerdosOspim = "select * from cabacuerdosospim where inspectorinterviene = $codigo";
					  $resAcuerdosOspim = mysql_query($sqlAcuerdosOspim,$db); 
					  $ospimCant = mysql_num_rows($resAcuerdosOspim); 
					  
					  $sqlAcuerdosUsimra = "select * from cabacuerdosusimra where inspectorinterviene = $codigo";
					  $resAcuerdosUsimra = mysql_query($sqlAcuerdosUsimra,$db); 
					  $usimraCant = mysql_num_rows($resAcuerdosUsimra); 
					  
					  $sqlCabJuicios = "select * from cabjuiciosospim where codinspector = $codigo";
					  $resCabJuicios = mysql_query($sqlCabJuicios,$db); 
					  $canCabJuicios = mysql_num_rows($resCabJuicios); 
					  
					  $controlAcuYJuicios = $ospimCant + $usimraCant + canCabJuicios;
					  if ($controlAcuYJuicios == 0) { ?>
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarInspector.php?codigo=<?php echo $codigo ?>'" value="Eliminar" />
			   <?php } ?>
                    </div></td>
                    <td width="167"><div align="center">
                      <input type="submit" name="guardar" value="Guardar Cambios" />
                    </div></td>
                    <td width="167">
					  <div align="center">
					    <?php
					  $sqlAcuerdosOspim = "select * from cabacuerdosospim where inspectorinterviene = $codigo and estadoacuerdo = 0";
					  $resAcuerdosOspim = mysql_query($sqlAcuerdosOspim,$db); 
					  $ospimCant = mysql_num_rows($resAcuerdosOspim); 
					  
					  $sqlAcuerdosUsimra = "select * from cabacuerdosusimra where inspectorinterviene = $codigo and estadoacuerdo = 0";
					  $resAcuerdosUsimra = mysql_query($sqlAcuerdosUsimra,$db); 
					  $usimraCant = mysql_num_rows($resAcuerdosUsimra); 
					  
					  $sqlCabJuicios = "select * from cabjuiciosospim where codinspector = $codigo";
					  $resCabJuicios = mysql_query($sqlCabJuicios,$db); 
					  $canCabJuicios = mysql_num_rows($resCabJuicios); 
					  
					  $controlAcuYJuicios = $ospimCant + $usimraCant + canCabJuicios;
					  
				 	  if ($controlAcuYJuicios == 0) { ?>
					    <input type="button" name="desactivar" value="Desactivar"/>
	            <?php } ?>
			          </div></td>
                  </tr>
                </table>
  </form>
</div>
</body>
</html>
