<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechaarchivo'];

if(strcmp("localhost",$maquina)==0)
	$archivo_name="00005866".substr($fechacargada, 0, 2).substr($fechacargada, 3, 2).substr($fechacargada, 6, 4).".TXT";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/UsimraxxBanco/00005866".substr($fechacargada, 0, 2).substr($fechacargada, 3, 2).substr($fechacargada, 6, 4).".TXT";

$hayErrores=0;
$totregi=0;
$validas=0;
$deposit=0;
$efectiv=0;
$cheques=0;
$rechazo=0;

if (!file_exists($archivo_name)) 
	$hayErrores=1;
else{
	$impoapro=0.00;
	$imporech=0.00;
	$registros = file($archivo_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	for($i=0; $i < count($registros); $i++){
		$fechanio=substr($registros[$i], 26, 4);
		$fechames=substr($registros[$i], 30, 2);
		$fechadia=substr($registros[$i], 32, 2);

		if(strcmp("localhost",$maquina)==0)
			$fechaarc=substr($archivo_name, 8,8);
		else
			$fechaarc=substr($archivo_name, 60,8);

		$fechaenarchivo=$fechadia.$fechames.$fechanio;

		if(strcmp($fechaenarchivo, $fechaarc)!=0)
			$hayErrores=2;

		$convearc=substr($registros[$i], 0, 10);
		if(strcmp("0000005866", $convearc)!=0)
			$hayErrores=3;

		$tipopago=substr($registros[$i], 73, 2);
		if(strcmp("20", $tipopago)==0) {
			$totregi++;
			$impoente=substr($registros[$i], 42, 13);
			$impodeci=substr($registros[$i], 55, 2);
			$impodepo=$impoente.".".$impodeci;

			$codimovi=substr($registros[$i], 34, 2);
			if(strcmp("50", $codimovi)==0 || strcmp("52", $codimovi)==0){
				$validas++;
				$deposit++;
				$efectiv++;
				$impoapro=$impoapro+$impodepo;
			}
			if(strcmp("54", $codimovi)==0){
				$estamovi=substr($registros[$i], 154, 1);
				if(strcmp("P", $estamovi)==0)
					$validas++;
				if(strcmp("L", $estamovi)==0){
					$deposit++;
					$cheques++;
					$impoapro=$impoapro+$impodepo;
				}
				if(strcmp("R", $estamovi)==0){
					$rechazo++;
					$imporech=$imporech+$impodepo;
				}
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Banco USIMRA :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {font-weight: bold}
</style>
</head>
<body bgcolor="#B2A274">
  <table width="762"  border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="3"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Control de Archivo del Banco </font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td width="157" height="23"><div align="left"><strong>Nombre del Archivo</strong></div></td>
    <td colspan="2"><div align="left"><?php	if(strcmp("localhost",$maquina)==0) print($archivo_name); else print(substr($archivo_name,52,20)); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Total de Registros </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print($totregi); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left" class="Estilo2">Boletas Presentadas </div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print($validas); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Aprobados </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print("$deposit: $efectiv en Efectivo - $cheques en Cheques por un Total de $$impoapro "); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Rechazados</strong> </div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print("$rechazo por un Total de $$imporech"); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27"><div align="left"><strong>Control de Errores </strong></div></td>
    <td colspan="2"><div align="left"><?php 
		if ($hayErrores == 0)
			print("No se ha encontrado ningun error en los registros.");
		else
		{
			if ($hayErrores == 1)
				//print("Error en Archivo -- El Archivo solicitado no existe.<br/>\n");
				$pagina = "procesamientoArchivosExtraordinarias.php?err=1";
				Header("Location: $pagina"); 
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
    <td height="27"><div align="left">
      <input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoArchivosExtraordinarias.php'"/>
    </div></td>
    <td width="426" height="27"><div align="center">
<?php 
	if ($hayErrores == 0) { ?>
	  <input type="submit" name="ingresar" value="Ingresar" onclick="location.href = 'procesarArchivoExtraordinarias.php?nombreArc=<?php echo $archivo_name ?> '"/>
<?php 
	} ?>
    </div></td>
    <td width="157"><div align="right">
        <input type="button" name="imprimir" value="Imprimir" onclick="window.print();"/>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
