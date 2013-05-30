<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/ospim/lib/";
include($libPath."controlSession.php");
include($libPath."fechas.php");

$nroafiliado=$_GET['nroAfi'];
$estafiliado=$_GET['estAfi'];

//echo $nroafiliado; echo "<br>";
//echo $estafiliado; echo "<br>";

if ($estafiliado == 1)
	$sqlTitular = "select * from titulares where nroafiliado = $nroafiliado";

if ($estafiliado == 0)
	$sqlTitular = "select * from titularesdebaja where nroafiliado = $nroafiliado";

//echo $sqlTitular; echo "<br>";

$resTitular = mysql_query($sqlTitular,$db);
$rowTitular = mysql_fetch_array($resTitular);

$cuitempresa = $rowTitular['cuitempresa'];
$delegacion = $rowTitular['codidelega'];

$sqlEmpresa = "select * from empresas where cuit = $cuitempresa";
$resEmpresa = mysql_query($sqlEmpresa,$db);
if (mysql_num_rows($resEmpresa)== 0) {
	$sqlEmpresa = "select * from empresasdebaja where cuit = $cuitempresa";
	$resEmpresa = mysql_query($sqlEmpresa,$db);
	$rowEmpresa = mysql_fetch_array($resEmpresa);
}
else
	$rowEmpresa = mysql_fetch_array($resEmpresa);

$sqlDelegacion = "select * from delegaciones where codidelega = $delegacion";
$resDelegacion = mysql_query($sqlDelegacion,$db);
$rowDelegacion = mysql_fetch_array($resDelegacion);

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
<title>.: Afiliado :.</title>
</head>
<body bgcolor="#CCCCCC" >
<form id="formAfiliado" name="formAfiliado" method="post" action="guardaModificacionAfiliado.php">
<table width="1205" border="0">
	<tr align="center" valign="top">
      <td width="1205" valign="middle"><div align="center">
        <input type="reset" name="volver" value="Volver" onClick="location.href = 'buscaAfiliado.php'" align="center"/> 
        </div></td>
	</tr>
</table>
<table width="1205" border="0">
	<tr>
      <td width="1205" valign="middle"><div align="center" class="Estilo4"><?php if ($estafiliado == 1) echo "Titular Activo"; else echo "Titular Inactivo";?></div></td>
	</tr>
</table>
<table width="1205" height="100" border="0">
  <tr>
	<td width="212" align="left" valign="middle"><?php echo "<img src='mostrarFoto.php?nroAfi=".$nroafiliado."&estAfi=".$estafiliado."' alt='Foto'>" ?></td>
    <td width="983" align="left" valign="middle"><div align="left"><span class="Estilo4"><strong>Numero Afiliado</strong></span><strong>  
    <input name="nroafiliado" type="text" id="nroafiliado" value="<?php echo $rowTitular['nroafiliado'] ?>" size="9" readonly="true" style="background-color:#CCCCCC" /></strong></div></td>
  </tr>
</table>

<table width="1205" border="0">
  <tr>
    <td colspan="4"><div align="center">
      <p align="left" class="Estilo4">Datos Identificatorios</p>
      </div></td>
  </tr>
  <tr>
    <td width="238">Apellido y Nombre:</td>
    <td colspan="3"><input name="apellidoynombre" type="text" id="apellidoynombre" value="<?php echo $rowTitular['apellidoynombre'] ?>" size="100" />	</td>
  </tr>
  <tr>
    <td>Documento:</td>
    <td width="316"><input name="tipodocumento" type="text" id="tipodocumento" value="<?php echo $rowTitular['tipodocumento'] ?>" size="2" />
					<input name="nrodocumento" type="text" id="nrodocumento" value="<?php echo $rowTitular['nrodocumento'] ?>" size="10" />	</td>
    <td width="173">Fecha de Nacimiento:</td>
    <td width="460"><input name="fechanacimiento" type="text" id="fechanacimiento" value="<?php echo invertirFecha($rowTitular['fechanacimiento']) ?>" size="10" />	</td>
  </tr>
  <tr>
    <td>Nacionalidad:</td>
    <td><input name="nacionalidad" type="text" id="nacionalidad" value="<?php echo $rowTitular['nacionalidad'] ?>" size="3" />	</td>
    <td>Sexo:</td>
    <td><input name="sexo" type="text" id="sexo" value="<?php echo $rowTitular['sexo'] ?>" size="1" />	</td>
  </tr>
  <tr>
    <td>Estado Civil: </td>
    <td colspan="3"><input name="estadocivil" type="text" id="estadocivil" value="<?php echo $rowTitular['estadocivil'] ?>" size="2" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Domiciliarios</div>
    </div></td>
  </tr>
  <tr>
    <td>Domicilio:</td>
    <td><input name="domicilio" type="text" id="domicilio" value="<?php echo $rowTitular['domicilio'] ?>" size="50" />	</td>
    <td>C.P.</td>
    <td><input name="indpostal" type="text" id="indpostal" value="<?php echo $rowTitular['indpostal'] ?>" size="1" />
		<input name="numpostal" type="text" id="numpostal" value="<?php echo $rowTitular['numpostal'] ?>" size="4" />
		<input name="alfapostal" type="text" id="alfapostal" value="<?php echo $rowTitular['alfapostal'] ?>" size="3" />	</td>
  </tr>
  <tr>
    <td>Localidad:</td>
    <td><input name="codlocali" type="text" id="codlocali" value="<?php echo $rowTitular['codlocali'] ?>" size="6" />	</td>
    <td>Provincia:</td>
    <td><input name="codprovin" type="text" id="codprovin" value="<?php echo $rowTitular['codprovin'] ?>" size="2" />	</td>
  </tr>
  <tr>
    <td>Telefono:</td>
    <td><input name="ddn" type="text" id="ddn" value="<?php echo $rowTitular['ddn'] ?>" size="5" />
		<input name="telefono" type="text" id="telefono" value="<?php echo $rowTitular['telefono'] ?>" size="10" />	</td>
    <td>Email:</td>
    <td><input name="email" type="text" id="email" value="<?php echo $rowTitular['email'] ?>" size="60" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Afiliatorios</div>
    </div></td>
  </tr>
  <tr>
    <td>Fecha Ingreso O.S.: </td>
    <td>
	<input name="fechaobrasocial" type="text" id="fechaobrasocial" value="<?php echo invertirFecha($rowTitular['fechaobrasocial']) ?>" size="10" />	</td>
    <td>Tipo Afiliado: </td>
    <td><input name="tipoafiliado" type="text" id="tipoafiliado" value="<?php echo $rowTitular['tipoafiliado'] ?>" size="1" />
		<input name="solicitudopcion" type="text" id="solicitudopcion" value="<?php echo $rowTitular['solicitudopcion'] ?>" size="8" />	</td>
  </tr>
  <tr>
    <td>Tipo Titularidad: </td>
    <td><input name="situaciontitularidad" type="text" id="situaciontitularidad" value="<?php echo $rowTitular['situaciontitularidad'] ?>" size="2" />	</td>
    <td>Discapacidad:</td>
    <td><input name="discapacidad" type="text" id="discapacidad" value="<?php echo $rowTitular['discapacidad'] ?>" size="2" />
		<input name="certificadodiscapacidad" type="text" id="certificadodiscapacidad" value="<?php echo $rowTitular['certificadodiscapacidad'] ?>" size="1" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Laborales </div>
    </div></td>
  </tr>
  <tr>
    <td>C.U.I.L.:</td>
    <td><input name="cuil" type="text" id="cuil" value="<?php echo $rowTitular['cuil'] ?>" size="11" />	</td>
    <td>Empresa:</td>
    <td><input name="cuitempresa" type="text" id="cuitempresa" value="<?php echo $rowTitular['cuitempresa'] ?>" size="11" />
    <input name="nombreempresa" type="text" id="nombreempresa" value="<?php echo $rowEmpresa['nombre'] ?>" size="50" readonly="true" style="background-color:#CCCCCC" />    </td>
  </tr>
  <tr>
    <td>Fecha  Ingreso Empresa:</td>
    <td><input name="fechaempresa" type="text" id="fechaempresa" value="<?php echo invertirFecha($rowTitular['fechaempresa']) ?>" size="10" />	</td>
    <td>Jurisdiccion del Titular:</td>
    <td><input name="codidelega" type="text" id="codidelega" value="<?php echo $rowTitular['codidelega'] ?>" size="4" /> </td>
  </tr>
  <tr>
    <td>Categoria:</td>
    <td colspan="3"><input name="categoria" type="text" id="categoria" value="<?php echo $rowTitular['categoria'] ?>" size="100" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left">Datos Credencial </div>
    </div></td>
  </tr>
  <tr>
    <td>Emision:</td>
    <td><input name="emitecarnet" type="text" id="emitecarnet" value="<?php echo $rowTitular['emitecarnet'] ?>" size="1" />	</td>
    <td>Cantidad Emitida:</td>
    <td><input name="cantidadcarnet" type="text" id="cantidadcarnet" value="<?php echo $rowTitular['cantidadcarnet'] ?>" size="4" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Fecha Ultima Emision:</td>
    <td><input name="fechacarnet" type="text" id="fechacarnet" value="<?php echo invertirFecha($rowTitular['fechacarnet']) ?>" size="10" readonly="true" style="background-color:#CCCCCC" />	</td>
    <td>Tipo Credencial:</td>
    <td><input name="tipocarnet" type="text" id="tipocarnet" value="<?php echo $rowTitular['tipocarnet'] ?>" size="1" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td>Vencimiento:</td>
    <td colspan="3"><input name="vencimientocarnet" type="text" id="vencimientocarnet" value="<?php echo invertirFecha($rowTitular['vencimientocarnet']) ?>" size="10" readonly="true" style="background-color:#CCCCCC" />	</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center" class="Estilo4">
      <div align="left"><?php if ($estafiliado == 0)  echo "Datos Inactividad"; ?> </div>
    </div></td>
  </tr>
  <tr>
    <td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Fecha de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
				echo "<input name='fechabaja' type='text' id='fechabaja' value='".$rowTitular['fechabaja']."' size='10' readonly='true' style='background-color:#CCCCCC' />";
		  	  }?> </td>
	<td align="left" valign="top"><?php if ($estafiliado == 0)  echo "Motivo de Baja:"; ?> </td>
    <td align="left" valign="top"><?php if ($estafiliado == 0) {
          		echo "<textarea name='motivobaja' cols='60' rows='5' id='motivobaja' readonly='readonly' style='background-color:#CCCCCC'>".$rowTitular['motivobaja']."</textarea>";
		  	  }?> </td>
  </tr>
</table> 
<table width="1205" border="0">
  <tr>
    <td colspan="7"><div align="center"><span class="Estilo4">Grupo Familiar</span> </div></td>
  </tr>
  <tr>
    <td width="291"><div align="center">Parentesco</div></td>
    <td width="464"><div align="center">Apellido y Nombre </div></td>
    <td width="109"><div align="center">Nacimiento</div></td>
    <td width="90"><div align="center">Documento</div></td>
    <td width="88"><div align="center">C.U.I.L.</div></td>
    <td width="80"><div align="center">Estado</div></td>
    <td width="83"><div align="center"></div></td>
  </tr>
</table>
<?php
$canfamilia = 0;
$sqlFamilia = "select * from familiares where nroafiliado = $nroafiliado order by nroafiliado, nroorden";
$resFamilia = mysql_query($sqlFamilia,$db);
while($rowFamilia = mysql_fetch_array($resFamilia)) {
	print("<table width=1205 border=0>");
	print ("<tr>");
	$parentesco = $rowFamilia['tipoparentesco'];
	$sqlParentesco = "select * from parentesco where codparent = $parentesco";
	$resParentesco = mysql_query($sqlParentesco,$db);
	$rowParentesco = mysql_fetch_array($resParentesco);
	print ("<td width=291><div align=center><font face=Verdana size=1>".$rowParentesco['descrip']."</font></div></td>");
	print ("<td width=464><div align=center><font face=Verdana size=1>".$rowFamilia['apellidoynombre']."</font></div></td>");
	print ("<td width=109><div align=center><font face=Verdana size=1>".invertirFecha($rowFamilia['fechanacimiento'])."</font></div></td>");
	print ("<td width=90><div align=center><font face=Verdana size=1>".$rowFamilia['nrodocumento']."</font></div></td>");
	print ("<td width=88><div align=center><font face=Verdana size=1>".$rowFamilia['cuil']."</font></div></td>");
	print ("<td width=80><div align=center><font face=Verdana size=1>ACTIVO</font></div></td>");
	print ("<td width=83><div align=center><font face=Verdana size=1><input type=button name=ficha value=Ficha onClick=location.href='fichaFamiliar.php?nroAfi=".$nroafiliado."&estAfi=1&nroOrd=".$rowFamilia['nroorden']."' align=center/></font></div></td>");
	print ("</tr>");
	print ("</table>");
	$canfamilia++;
}
$sqlFamBaja = "select * from familiaresdebaja where nroafiliado = $nroafiliado order by nroafiliado, nroorden";
$resFamBaja = mysql_query($sqlFamBaja,$db);
while($rowFamBaja = mysql_fetch_array($resFamBaja)) {
	print("<table width=1205 border=0>");
	print ("<tr>");
	$parentesco = $rowFamBaja['tipoparentesco'];
	$sqlParentesco = "select * from parentesco where codparent = $parentesco";
	$resParentesco = mysql_query($sqlParentesco,$db);
	$rowParentesco = mysql_fetch_array($resParentesco);
	print ("<td width=291><div align=center><font face=Verdana size=1>".$rowParentesco['descrip']."</font></div></td>");
	print ("<td width=464><div align=center><font face=Verdana size=1>".$rowFamBaja['apellidoynombre']."</font></div></td>");
	print ("<td width=109><div align=center><font face=Verdana size=1>".invertirFecha($rowFamBaja['fechanacimiento'])."</font></div></td>");
	print ("<td width=90><div align=center><font face=Verdana size=1>".$rowFamBaja['nrodocumento']."</font></div></td>");
	print ("<td width=88><div align=center><font face=Verdana size=1>".$rowFamBaja['cuil']."</font></div></td>");
	print ("<td width=80><div align=center><font face=Verdana size=1>INACTIVO</font></div></td>");
	print ("<td width=83><div align=center><font face=Verdana size=1><input type=button name=ficha value=Ficha onClick=location.href='fichaFamiliar.php?nroAfi=".$nroafiliado."&estAfi=0&nroOrd=".$rowFamBaja['nroorden']."' align=center/></font></div></td>");
	print ("</tr>");
	print ("</table>");
	$canfamilia++;
}

if($estafiliado == 1) { 
?>
<table width="1205" border="0">
  <tr>
    <td width="241" valign="middle"><div align="center">
        <input type="submit" name="guardar" value="Guardar Cambios" align="center"/> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input type="button" name="familia" value="Agregar Familiar" onClick="location.href = 'agregaFamiliar.php?nroAfi=<?php echo $nroafiliado?>&nueOrd=<?php echo $canfamilia?>'" align="center"/> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input type="button" name="foto" value="Cargar Foto" onClick="location.href = 'agregaFotoAfiliado.php'" align="center"/> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input type="button" name="aportes" value="Aportes / DDJJ" onClick="location.href = 'aportesAfiliado.php'" align="center"/> 
        </div></td>
    <td width="241" valign="middle"><div align="center">
        <input type="button" name="bajar" value="Dar de Baja" onClick="location.href = 'bajaAfiliado.php'" align="center"/> 
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
        <input type="button" name="reactiva" value="Reactivar" onClick="location.href = 'reactivaAfiliado.php'" align="center"/> 
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
