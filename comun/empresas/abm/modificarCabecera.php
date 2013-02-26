<?php 
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); 
include($_SERVER['DOCUMENT_ROOT']."/comun/lib/fechas.php");
$cuit=$_GET['cuit'];

$sql = "select * from empresas where cuit = $cuit";
$result = mysql_query($sql,$db); 
$cant = mysql_num_rows($result);
$row = mysql_fetch_array($result); 

$numpostal=$_GET['numpostal'];
if ($numpostal == "") {
	$numpostal = $row['numpostal'];
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modificar Cabecera Empresa :.</title>
</head>
<script src="../../lib/jquery.js" type="text/javascript"></script>
<script src="../../lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="../../lib/funcionControl.js" type="text/javascript"></script>
<script type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99-99999999-9");
	$("#fechaInicioOspim").mask("99-99-9999");
	$("#fechaInicioUsimra").mask("99-99-9999");
});

function cambioProvincia(locali) {
	<?php 
		$sqlLocali = "select codlocali, codprovin from localidades";
		$resLocali = mysql_query($sqlLocali,$db);
		while($rowLocali = mysql_fetch_array($resLocali)) { ?>
			if (locali == <?php echo $rowLocali['codlocali'] ?>)  {
				<?php	
					$codprovin =  $rowLocali['codprovin'];
					$sqlProvin = "select * from provincia where codprovin = $codprovin";
					$resProvin = mysql_query($sqlProvin,$db);
					$rowProvin = mysql_fetch_array($resProvin)
				?>
				document.forms.modifCabeEmpresa.provincia.value = "<?php echo $rowProvin['descrip'] ?>";
				document.forms.modifCabeEmpresa.indpostal.value = "<?php echo $rowProvin['indpostal'] ?>";
			}
<?php } ?>
}

</script>

<body bgcolor=<?php echo $bgcolor ?>>
<div align="center">
  <p><strong><a href="empresa.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>"><font face="Verdana" size="2"><b>VOLVER</b></font></a></strong></p>
  <p><strong>Modificacion Cabecera de Empresa</strong>
  <form name="modifCabeEmpresa" id="modifCabeEmpresa" method="post" action="modificarEmpresa.php?origen=<?php echo $origen ?>">
    <table width="723" border="1">
      <tr>
        <td width="167"><div align="left">C.U.I.T. </div></td>
        <td width="540"><div align="left">
			<input style="background-color:#CCCCCC" name="cuit" type="text" id="cuit" size="12" value="<?php echo $row['cuit'];?>"  readonly="readonly"/>                
          </div></td>
      </tr>
      <tr>
        <td><div align="left">Raz&oacute;n Social</div></td>
        <td><div align="left">
          <input name="nombre" type="text" id="nombre" value="<?php echo $row['nombre'];?>" size="90" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Domicilio</div></td>
        <td><div align="left">
          <input name="domicilio" type="text" id="domicilio" value="<?php echo $row['domilegal'];?>" size="90" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Codigo Postal</div></td>
        <td><div align="left">
          <label>
          <input style="background-color:#CCCCCC" name="indpostal" type="text" size="1" value="<?php echo $row['indpostal'];?>"/>
          </label>
          -
          <input name="codPos" type="text" id="codPos" value="<?php echo $numpostal ?>" size="7" onchange='location.href="modificarCabecera.php?origen=<?php echo $origen ?>&cuit=<?php echo $cuit ?>&numpostal="+ document.forms.modifCabeEmpresa.codPos.value'  />
		  -        
		  <label>
		  <input name="alfapostal" type="text" size="3" value="<?php echo $row['alfapostal'];?>"/>
		  </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Localidad</div></td>
        <td><div align="left">
            <select name="selectLocali" id="selectLocali" onchange="cambioProvincia(document.forms.modifCabeEmpresa.selectLocali[selectedIndex].value)">
              <option value="0">Seleccione un valor </option>
              <?php 
			  		
					$sqlLaca="select * from localidades where numpostal = $numpostal";
					$resLoca= mysql_query($sqlLaca,$db);
					while ($rowLoca=mysql_fetch_array($resLoca)) { 	
						if ($rowLoca['codlocali'] == $row['codlocali']) {?>
              				<option value="<?php echo $rowLoca['codlocali'] ?>" selected="selected"><?php echo $rowLoca['nomlocali']  ?></option>
              	 <?php } else { ?>
              				<option value="<?php echo $rowLoca['codlocali'] ?>"><?php echo $rowLoca['nomlocali']  ?></option>
             	 <?php } ?>
             <?php } ?>
            </select>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Provincia</div></td>
        <td><div align="left">
			<?php	
				$codProvi = $row['codprovin'];
				$sqlProvi = "select * from provincia where codprovin = $codProvi ";
				$resProvi = mysql_query($sqlProvi,$db);
				$rowProvi = mysql_fetch_array($resProvi);
			?>
             <input readonly="readonly" style="background-color:#CCCCCC" name="provincia" type="text" id="provincia" value="<?php echo $rowProvi['descrip'];?>" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Telefono 1 </div></td>
        <td>
          <div align="left">
            <input name="ddn1" type="text" id="ddn1" value="<?php echo $row['ddn1'];?>" size="5" />
            - 
            <input name="telefono1" type="text" id="telefono1" value="<?php echo $row['telefono1'];?>" size="10" />
            </div>        </td>
      </tr>
      <tr>
        <td><div align="left">Contacto 1 </div></td>
        <td>
          <div align="left">
            <input name="contacto1" type="text" id="contacto1" value="<?php echo $row['contactel1'];?>" size="50" />
            </div>			</td>
      </tr>
      <tr>
        <td><div align="left">Telefono 2 </div></td>
        <td><div align="left">
          <input name="ddn2" type="text" id="ddn2" value="<?php echo $row['ddn2'];?>" size="5" />
          -
          <input name="telefono2" type="text" id="telefono2" value="<?php echo $row['telefono2'];?>" size="10" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Contacto 2 </div></td>
        <td><div align="left">
          <input name="contacto2" type="text" id="contacto2" value="<?php echo $row['contactel2'];?>" size="50" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Actividad</div></td>
        <td><div align="left">
          <label>
          <input name="actividad" id="actividad" type="text" value="<?php echo $row['actividad'];?>" size="80" />
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Obs. OSPIM </div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<textarea name='obsospim' cols='60' rows='2' id='obsospim'>".$row['obsospim']."</textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsospim' cols='60' rows='2' id='obsospim'>".$row['obsospim']."</textarea>";
				} 
			?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Obs. USIMRA </div></td>
        <td><div align="left">
            <?php if ($origen == "usimra") {
         	 		echo "<textarea name='obsusimra' cols='60' rows='2' id='obsusimra'>".$row['obsusimra']."</textarea>";
				  } else {
					echo "<textarea readonly='readonly' style='background-color:#CCCCCC' name='obsusimra' cols='60' rows='2' id='obsusimra'>".$row['obsusimra']."</textarea>";
				} 
			?>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Inicio Obl. OSPIM </div></td>
        <td><div align="left">
          <label>
		  	<?php if ($origen == "ospim") {
         	 		echo "<input name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10' value='".invertirFecha($row['iniobliosp'])."'/>";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioOspim' type='text' id='fechaInicioOspim' size='10' value='".invertirFecha($row['iniobliosp'])."'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Inicio Obl. USIMRA </div></td>
        <td><div align="left">
          <label>
          	<?php if ($origen == "usimra") {
         	 		echo "<input name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10' value='".invertirFecha($row['iniobliusi'])."'/>";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='fechaInicioUsimra' type='text' id='fechaInicioUsimra' size='10' value='".invertirFecha($row['iniobliusi'])."'/>";
				} ?>
          </label>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Email</div></td>
        <td><div align="left">
          <input name="email" type="text" id="email" value="<?php echo $row['email'];?>" size="60" />
        </div></td>
      </tr>
      <tr>
        <td><div align="left">Carpeta Archivo </div></td>
        <td><div align="left">
          <?php if ($origen == "ospim") {
         	 		echo "<input name='carpetaArc' type='text' id='carpetaArc' value='".$row['carpetaenarchivo']."' size='10' />";
				  } else {
					echo "<input readonly='readonly' style='background-color:#CCCCCC' name='carpetaArc' type='text' id='carpetaArc' value='".$row['carpetaenarchivo']."' size='10' />";
				} ?>
		  
        </div></td>
      </tr>
    </table>
    <p>
      <label>
      <input type="submit" name="Submit" value="Guardar">
      </label>
    </p>
  </form>
  </p>
</div>
</body>
</html>
