<?php include($_SERVER['DOCUMENT_ROOT']."/ospim/lib/controlSession.php");
$fechacargada=$_POST['fechaarchivo'];
$archivo_name="00005734".$fechacargada.".TXT";
$hayErrores=0;
$validas=0;
$deposit=0;
$efectiv=0;
$cheques=0;
$rechazo=0;
if (!file_exists($archivo_name)) 
	$hayErrores=1;
	else{
		$registros = file($archivo_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		for($i=0; $i < count($registros); $i++){
			$fechanio=substr($registros[$i], 26, 4);
			$fechames=substr($registros[$i], 30, 2);
			$fechadia=substr($registros[$i], 32, 2);
			$fechaarc=substr($archivo_name, 8,8);
			$fechaenarchivo=$fechadia.$fechames.$fechanio;
			if(strcmp($fechaenarchivo, $fechaarc)!=0)
				$hayErrores=2;

			$convearc=substr($registros[$i], 0, 10);
			if(strcmp("0000005734", $convearc)!=0)
				$hayErrores=3;

			$codimovi=substr($registros[$i], 34, 2);
			if(strcmp("50", $codimovi)==0){
				$validas++;
				$deposit++;
				$efectiv++;
			}
			if(strcmp("54", $codimovi)==0){
				$estamovi=substr($registros[$i], 154, 1);
				if(strcmp("P", $estamovi)==0)
					$validas++;
				if(strcmp("L", $estamovi)==0){
					$deposit++;
					$cheques++;
				}
				if(strcmp("R", $estamovi)==0)
					$rechazo++;
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M&oacute;dulo Recaudaci&oacute;n Bancaria :.</title></head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>

<body bgcolor="#CCCCCC">
  <table width="762" height="241" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="3"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Control de Archivo del Banco </font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td width="157" height="23"><div align="left"><strong>Nombre del Archivo</strong></div></td>
    <td colspan="2"><div align="left"><?php print($archivo_name); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Total de Registros </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print(count($registros)); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left" class="Estilo2">Boletas Presentadas </div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print($validas); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Aprobados </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print("$deposit : $efectiv En Efectivo - $cheques En Cheques"); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Rechazados</strong> </div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print($rechazo); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27"><div align="left"><strong>Control de Errores </strong></div></td>
    <td colspan="2"><div align="left"><?php 
		if ($hayErrores == 0)
			print("No se ha encontrado ningun error en los registros.");
		else
		{
			if ($hayErrores == 1)
				print("Error en Archivo -- El Archivo solicitado no existe.<br/>\n");
			if ($hayErrores == 2)
				print("Error en Archivo -- Las fechas en el contenido del archivo son incorrectas.<br/>\n");
			if ($hayErrores == 3)
				print("Error en Archivo -- El Nro. de Convenio en el contenido del archivo es incorrecto.<br/>\n");
		}
	?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27" colspan="3">&nbsp;</td>
  </tr>
  <tr align="center" valign="top">
    <td height="27"><div align="left"><?php print("<a href=moduloBanco.php>".VOLVER);?></div></td>
    <td width="426" height="27"><div align="center"><?php 
		if ($hayErrores == 0)
			print("<a href=procesarArchivo.php?nombreArc=".$archivo_name.">".INGRESAR);
	?></div></td>
    <td width="157"><div align="right">
        <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="left">
      </div>
    </label></td>
  </tr>
</table>
</body>
</html>
