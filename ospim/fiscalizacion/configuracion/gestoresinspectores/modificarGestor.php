<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); 
$codigo = $_GET['codigo'];

$sqlGestor = "select * from gestoresdeacuerdos where codigo = $codigo";
$resGestor = mysql_query($sqlGestor,$db); 
$rowGestor = mysql_fetch_array($resGestor);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Gestores :.</title>
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
    <input type="reset" name="volver" value="Volver" onclick="location.href = 'gestores.php'" align="center"/>
 </p>
  <p><span class="Estilo2">Modificar Gestor de Acuerdos </span></p>
  <form id="modifGestor" name="modifGestor" method="post" action="guardarModifGestor.php?codigo=<?php echo $codigo ?>" onSubmit="return validar(this)">
				
				<p>
				  <label>Codigo: <b> <?php echo $rowGestor['codigo']; ?> </b></label>
				</p>
				<p>
				  <label>Apellido y Nombre 
				  <input name="apeynombre" type="text" id="apeynombre" value="<?php echo $rowGestor['apeynombre'];?>" size="100" maxlength="100"/>
				  </label>
				</p>
				<table width="528" border="0">
                  <tr>
                    <td width="172"><div align="center">
                      <?php
					  $sqlAcuerdosOspim = "select * from cabacuerdosospim where gestoracuerdo = $codigo";
					  $resAcuerdosOspim = mysql_query($sqlAcuerdosOspim,$db); 
					  $ospimCant = mysql_num_rows($resAcuerdosOspim); 
					  
					  $sqlAcuerdosUsimra = "select * from cabacuerdosusimra where gestoracuerdo = $codigo";
					  $resAcuerdosUsimra = mysql_query($sqlAcuerdosUsimra,$db); 
					  $usimraCant = mysql_num_rows($resAcuerdosUsimra); 
					  
					  $controlAcu = $ospimCant + $usimraCant;
					  
					  if ($controlAcu == 0) { ?>
					  		<input type="button" name="eliminar" onclick="location.href = 'eliminarGestor.php?codigo=<?php echo $codigo ?>'" value="Eliminar" />
			   <?php } ?>
                    </div></td>
                    <td width="167"><div align="center">
                      <input type="submit" name="guardar" value="Guardar Cambios" sub/>
                    </div></td>
                    <td width="167">
					  <div align="center">
					    <?php
					  $sqlAcuerdosOspim = "select * from cabacuerdosospim where gestoracuerdo = $codigo and estadoacuerdo = 0";
					  $resAcuerdosOspim = mysql_query($sqlAcuerdosOspim,$db); 
					  $ospimCant = mysql_num_rows($resAcuerdosOspim); 
					  
					  $sqlAcuerdosUsimra = "select * from cabacuerdosusimra where gestoracuerdo = $codigo and estadoacuerdo = 0";
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
