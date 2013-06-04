<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];
$ordafiliado=$_GET['nroOrd'];

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";
//echo $ordafiliado; echo "<br>";

if ($estafiliado == 1)
	$sqlFamilia = "select * from familiares where nroafiliado = $nroafiliado and nroorden = $ordafiliado";

if ($estafiliado == 0)
	$sqlFamilia = "select * from familiaresdebaja where nroafiliado = $nroafiliado and nroorden = $ordafiliado";

//echo $sqlFamilia; echo "<br>";

$resFamilia = mysql_query($sqlFamilia,$db);
$rowFamilia = mysql_fetch_array($resFamilia);

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo4 {
	font-size: 18px;
	font-weight: bold;
}
</style>
<title>.: Familiar :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form id="formFichaFamiliar" name="formFichaFamiliar" method="post" action="guardaModificacionFamiliar.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'afiliado.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>'" align="center"/> 
        </div></td>
	</tr>
</table>
<table width="1205" border="0">
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4"><?php if ($estafiliado == 1) echo "Familiar Activo"; else echo "Familiar Inactivo";?></div></td>
	</tr>
</table>
<table width="1205" height="100" border="0">
  <tr>
	<td width="212" align="left" valign="middle"><?php echo "<img src='mostrarFotoFamiliar.php?nroAfi=".$nroafiliado."&estAfi=".$estafiliado."&nroOrd=".$ordafiliado."' alt='Foto' width='115' height='115'>" ?></td>
    <td width="983" align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span><strong>  
    <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowFamilia['nroafiliado'] ?>" size="9" readonly="true" style="background-color:#CCCCCC" /></strong></div></td>
  </tr>
</table>
<table width="1205" border="0">
  <tr>
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="142">Apellido y Nombre:</td>
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="<?php echo $rowFamilia['apellidoynombre'] ?>" size="100" />	</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="412"><input name="tipodocumento" type="text" id="tipodocumento" value="<?php echo $rowFamilia['tipodocumento'] ?>" size="2" />
	  <input name="nrodocumento" type="text" id="nrodocumento" value="<?php echo $rowFamilia['nrodocumento'] ?>" size="10" />	</td>
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="<?php echo invertirFecha($rowFamilia['fechanacimiento']) ?>" size="10" />	</td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><input name="nacionalidad" type="text" id="nacionalidad" value="<?php echo $rowFamilia['nacionalidad'] ?>" size="3" />	</td>
    <td>Sexo:</td>
    <td><input name="sexo" type="text" id="sexo" value="<?php echo $rowFamilia['sexo'] ?>" size="1" />	</td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="<?php echo $rowFamilia['ddn'] ?>" size="5" />
		<input name="telefono" type="text" id="telefono" value="<?php echo $rowFamilia['telefono'] ?>" size="10" />	</td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="<?php echo $rowFamilia['email'] ?>" size="60" />	</td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td colspan="3"><input name="cuil" type="text" id="cuil" value="<?php echo $rowFamilia['cuil'] ?>" size="11" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Parentesco:</td>
    <td><input name="tipoparentesco" type="text" id="tipoparentesco" value="<?php echo $rowFamilia['tipoparentesco'] ?>" size="2" />	</td>
    <td>Fecha Ingreso O.S.:</td>
    <td><input name="fechaobrasocial" type="text" id="fechaobrasocial" value="<?php echo invertirFecha($rowFamilia['fechaobrasocial']) ?>" size="10" />	</td>
  </tr>
  <tr>
    <td>Discapacidad:</td>
    <td><input name="discapacidad" type="text" id="discapacidad" value="<?php echo $rowFamilia['discapacidad'] ?>" size="2"  readonly="true" style="background-color:#CCCCCC" />
		 Certif:
		   <input name="certificadodiscapacidad" type="text" id="certificadodiscapacidad" value="<?php echo $rowFamilia['certificadodiscapacidad'] ?>" size="2" readonly="true" style="background-color:#CCCCCC" /> Emision:
		<input name="emisiondiscapacidad" type="text" id="emisiondiscapacidad" value="" size="10" readonly="true" style="background-color:#CCCCCC" /> 
		Vto:
		<input name="vencimientodiscapacidad" type="text" id="vencimientodiscapacidad" value="" size="10" readonly="true" style="background-color:#CCCCCC" />	</td>
    <td>Estudia:</td>
    <td><input name="estudia" type="text" id="estudia" value="<?php echo $rowFamilia['estudia'] ?>" size="2" />
		<input name="certificadoestudio" type="text" id="certificadoestudio" value="<?php echo $rowFamilia['certificadoestudio'] ?>" size="1" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial </div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td><input name="emitecarnet" type="text" id="emitecarnet" value="<?php echo $rowFamilia['emitecarnet'] ?>" size="1" />	</td>
    <td>Cantidad Emitida:</td>
    <td><input name="cantidadcarnet" type="text" id="cantidadcarnet" value="<?php echo $rowFamilia['cantidadcarnet'] ?>" size="4" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Fecha Ultima Emision:</td>
    <td><input name="fechacarnet" type="text" id="fechacarnet" value="<?php echo invertirFecha($rowFamilia['fechacarnet']) ?>" size="10" readonly="true" style="background-color:#CCCCCC" />	</td>
    <td>Tipo Credencial:</td>
    <td><input name="tipocarnet" type="text" id="tipocarnet" value="<?php echo $rowFamilia['tipocarnet'] ?>" size="1" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Vencimiento:</td>
    <td colspan="3"><input name="vencimientocarnet" type="text" id="vencimientocarnet" value="<?php echo invertirFecha($rowFamilia['vencimientocarnet']) ?>" size="10" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left"><?php if ($estafiliado == 0)  echo "Datos Inactividad"; ?> </div>
    </div></td>
  </tr>
  <tr>
    <td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Fecha de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
				echo "<input name='fechabaja' type='text' id='fechabaja' value='".$rowFamilia['fechabaja']."' size='10' readonly='true' style='background-color:#CCCCCC' />";
		  	  }?> </td>
	<td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Motivo de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
          		echo "<textarea name='motivobaja' cols='60' rows='5' id='motivobaja' readonly='readonly' style='background-color:#CCCCCC'>".$rowFamilia['motivobaja']."</textarea>";
		  	  }?> </td>
  </tr>
</table>
<?php

if($estafiliado == 1) { 
?>
<table width="1205" border="0">
  <tr>
    <td width="402" valign="middle"><div align="center">
        <input type="submit" name="guardar" value="Guardar Cambios" align="center"/> 
        </div></td>
    <td width="402" valign="middle"><div align="center">
        <input type="button" name="foto" value="Cargar Foto" onClick="location.href = 'agregaFoto.php?nroAfi=<?php echo $nroafiliado?>&estAfi=<?php echo $estafiliado?>&tipAfi=2&nroOrd=<?php echo $ordafiliado?>&fotAfi=0'" align="center"/>
        </div></td>
    <td width="401" valign="middle"><div align="center">
        <input type="button" name="bajar" value="Dar de Baja" onClick="location.href = 'bajaFamiliar.php'" align="center"/> 
        </div></td>
  </tr>
</table>
<?php
}

if($estafiliado == 0) { 
?>
<table width="1205" border="0">
  <tr>
    <td width="1205" valign="middle"><div align="center">
        <input type="button" name="reactiva" value="Reactivar" onClick="location.href = 'reactivaFamiliar.php'" align="center"/> 
        </div></td>
  </tr>
</table>
<?php
}
?>
<table width="1205" border="0">
  <tr>
    <td width="1205" valign="middle"><div align="center">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/> 
        </div></td>
  </tr>
</table>
</form>
</body>
</html>
