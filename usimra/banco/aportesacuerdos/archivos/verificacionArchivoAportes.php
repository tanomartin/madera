<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechaarchivo'];

if(strcmp("localhost",$maquina)==0)
	$archivo_name="00003617".substr($fechacargada, 0, 2).substr($fechacargada, 3, 2).substr($fechacargada, 6, 4).".TXT";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/UsimraxxBanco/00003617".substr($fechacargada, 0, 2).substr($fechacargada, 3, 2).substr($fechacargada, 6, 4).".TXT";

$hayErrores=0;
$totregic=0;
$validasc=0;
$depositc=0;
$efectivc=0;
$chequesc=0;
$rechazoc=0;
$totregip=0;
$validasp=0;
$depositp=0;
$efectivp=0;
$chequesp=0;
$rechazop=0;

if(!file_exists($archivo_name)) 
	$hayErrores=1;
else {
	$impoaproc=0.00;
	$imporechc=0.00;
	$impoaprop=0.00;
	$imporechp=0.00;
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

		if(strcmp("0000003617", $convearc)!=0)
			$hayErrores=3;

		$tipopago=substr($registros[$i], 73, 2);

		if(strcmp("99", $tipopago)==0) {
			$totregic++;
			$impoentec=substr($registros[$i], 42, 13);
			$impodecic=substr($registros[$i], 55, 2);
			$impodepoc=$impoentec.".".$impodecic;

			$codimovic=substr($registros[$i], 34, 2);

			if(strcmp("50", $codimovic)==0 || strcmp("52", $codimovic)==0) {
				$validasc++;
				$depositc++;
				$efectivc++;
				$impoaproc=$impoaproc+$impodepoc;
			}

			if(strcmp("54", $codimovic)==0) {
				$estamovic=substr($registros[$i], 154, 1);

				if(strcmp("P", $estamovic)==0) {
					$validasc++;
				}

				if(strcmp("L", $estamovic)==0) {
					$depositc++;
					$chequesc++;
					$impoaproc=$impoaproc+$impodepoc;
				}

				if(strcmp("R", $estamovic)==0) {
					$rechazoc++;
					$imporechc=$imporechc+$impodepoc;
				}
			}
		}

		if(strcmp("20", $tipopago)==0) {
			$totregip++;
			$impoentep=substr($registros[$i], 42, 13);
			$impodecip=substr($registros[$i], 55, 2);
			$impodepop=$impoentep.".".$impodecip;

			$codimovip=substr($registros[$i], 34, 2);

			if(strcmp("50", $codimovip)==0 || strcmp("52", $codimovip)==0) {
				$validasp++;
				$depositp++;
				$efectivp++;
				$impoaprop=$impoaprop+$impodepop;
			}

			if(strcmp("54", $codimovip)==0) {
				$estamovip=substr($registros[$i], 154, 1);

				if(strcmp("P", $estamovip)==0) {
					$validasp++;
				}

				if(strcmp("L", $estamovip)==0) {
					$depositp++;
					$chequesp++;
					$impoaprop=$impoaprop+$impodepop;
				}

				if(strcmp("R", $estamovip)==0) {
					$rechazop++;
					$imporechp=$imporechp+$impodepop;
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
  <table style="height: 237; width: 762" border="1" align="center">
  <tr align="center" valign="top">
    <td height="23" colspan="3"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Control de Archivo del Banco </font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td width="202" height="23"><div align="left"><strong>Nombre del Archivo</strong></div></td>
    <td colspan="2"><div align="left"><?php	if(strcmp("localhost",$maquina)==0) print($archivo_name); else print(substr($archivo_name,52,20)); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Tipo Registros </strong></div></td>
    <td width="269"><strong>Aportes</strong></td>
    <td width="269"><strong>Acuerdos</strong></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Total de Registros </strong></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print($totregip); ?></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print($totregic); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left" class="Estilo2">Boletas Presentadas </div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print($validasp); ?></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print($validasc); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Aprobados </strong></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print("$depositp: $efectivp en Efectivo - $chequesp en Cheques por un Total de $ $impoaprop"); ?></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print("$depositc: $efectivc en Efectivo - $chequesc en Cheques por un Total de $ $impoaproc"); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Depositos Rechazados</strong> </div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print("$rechazop por un Total de $ $imporechp"); ?></div></td>
    <td width="269"><div align="left"><?php if ($hayErrores == 0) print("$rechazoc por un Total de $ $imporechc"); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Control de Errores</strong></div></td>
    <td colspan="2"><div align="left"><?php 
		if ($hayErrores == 0)
			print("No se ha encontrado ningun error en los registros.");
		else
		{
			if ($hayErrores == 1)
				//print("Error en Archivo -- El Archivo solicitado no existe.<br/>\n");
				$pagina = "procesamientoArchivosAportes.php?err=1";
				Header("Location: $pagina"); 
			if ($hayErrores == 2)
				print("Error en Archivo -- Las fechas en el contenido del archivo son incorrectas.<br/>\n");
			if ($hayErrores == 3)
				print("Error en Archivo -- El Nro. de Convenio en el contenido del archivo es incorrecto.<br/>\n");
		}
	?></div></td>
    </tr>
</table>
<table style="width: 762; height: 23" border="1" align="center">
  <tr align="center" valign="top">
    <td height="27"><div align="left">
      <input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoArchivosAportes.php'" align="left" />
    </div></td>
    <td width="426" height="27"><div align="center">
<?php 
	if ($hayErrores == 0) { ?>
	  <input type="submit" name="ingresar" value="Ingresar" onclick="location.href = 'procesarArchivoAportes.php?nombreArc=<?php echo $archivo_name ?> '" align="left" />
<?php 
	} ?>
    </div></td>
    <td width="157">
    	<div align="right">
        	<input type="button" name="imprimir" value="Imprimir" onclick="window.print();" align="left"/>
    	</div>
    </td>
  </tr>
</table>
</body>
</html>
