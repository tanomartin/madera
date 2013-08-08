<?php include($_SERVER['DOCUMENT_ROOT']."/lib/controlSessionOspim.php"); 
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
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'inspectores.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Inspector </span></p>
  <form id="modifInspector" name="modifInspector" method="POST" action="guardarModifInspector.php?codigo=<?php echo $codigo ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b> <?php echo $codigo ?> </b></label>
				</p>
				<p>
				  <label>Apellido y Nombre 
				  <input name="apeynombre" type="text" id="apeynombre" value="<?php echo $rowInspector['apeynombre'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<p>
				<label>Delegaciones<br/>
					<?php 
						$error = $_GET['error'];
						if ($error == 1) {
							print("<div align='center' style='color:#FF0000'><b> Debe elegir una o varias delegaciones </b></div>");
						}
					?>
					 <br/>
					<?php 
					$i = 0;
					$resDelega= mysql_query("SELECT * FROM delegaciones", $db);
					while($rowDelega= mysql_fetch_array($resDelega)) { 
						$codigoDelega = $rowDelega['codidelega'];
						$sqlExiste = "select * from inspectores where codigo = $codigo and codidelega = $codigoDelega";
						$resExiste = mysql_query($sqlExiste,$db); 
						$numExiste = mysql_num_rows($resExiste);
						if ($numExiste == 1) {
							echo '<input type="checkbox" id="delega'.$i.'" name="delega'.$i.'" value='.$codigoDelega.' checked>';
						} else {
							echo '<input type="checkbox" id="delega'.$i.'" name="delega'.$i.'" value='.$codigoDelega.'>';
						}
						echo '<span class="Estilo1">'.$rowDelega["nombre"].'</span><br>'; 
						$i = $i + 1;
					} 
					?>
					</label>
					</p>
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
					  
					  $controlAcu = $ospimCant + $usimraCant;
					  if ($controlAcu == 0) { ?>
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarInspector.php?codigo=<?php echo $codigo ?>'" value="Eliminar" />
			   <?php } ?>
                    </div></td>
                    <td width="167"><div align="center">
                      <input type="submit" name="guardar" value="Guardar Cambios" sub/>
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
					  
					  $controlAcu = $ospimCant + $usimraCant;
					  
				 	  if ($controlAcu == 0) { ?>
					    <input type="button" name="desactivar" value="Desactivar"/>
	            <?php } ?>
			          </div></td>
                  </tr>
                </table>
  </form>
</div>
</body>
</html>
