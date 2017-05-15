<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php");
$maquina = $_SERVER['SERVER_NAME'];
$fechacargada=$_POST['fechaarchivo'];
if(strcmp("localhost",$maquina)==0)
	$archivo_name="0XO0".substr($fechacargada, 6, 4).substr($fechacargada, 3, 2).substr($fechacargada, 0, 2).".csv";
else
	$archivo_name="/home/sistemas/Documentos/Repositorio/UsimraxxBanco/0XO0".substr($fechacargada, 6, 4).substr($fechacargada, 3, 2).substr($fechacargada, 0, 2).".csv";

$hayErrores=0;
$hayPagos=0;
$totregi=0;
$impocobr=0.00;
$impodepo=0.00;
if(!file_exists($archivo_name)) 
	$hayErrores=1;
else {
	$registros = file($archivo_name, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$totregi = count($registros);
	if($totregi > 1) {
		$hayPagos=1;
		for($i=1; $i < count($registros); $i++){
			$deposito=substr($registros[$i], 32, 10).".".substr($registros[$i], 42, 2);
			//print("deposito: ".$deposito."<br>");
			$impodepo=$deposito;
			$impocobr=$impocobr+$impodepo;
		}
		$totregi=$totregi-1;
	} else {
		$totregi=0;
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
    <td height="23" colspan="3"><div align="center"><em><strong><font face="Arial, Helvetica, sans-serif">Control de Archivo de Link Pagos </font></strong></em></div></td>
  </tr>
  <tr align="center" valign="top">
    <td width="157" height="23"><div align="left"><strong>Nombre del Archivo</strong></div></td>
    <td colspan="2"><div align="left"><?php	if(strcmp("localhost",$maquina)==0) print($archivo_name); else print(substr($archivo_name,52,16)); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Total de Registros </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print($totregi); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="23"><div align="left"><strong>Total Cobrado </strong></div></td>
    <td colspan="2"><div align="left"><?php if ($hayErrores == 0) print("$ ".$impocobr." "); ?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27"><div align="left"><strong>Control de Errores </strong></div></td>
    <td colspan="2"><div align="left"><?php 
		if($hayErrores == 0) {
			if($hayPagos == 1) {
				print("No se ha encontrado ningun error en los registros. Puede Efectuar el Ingreso de los mismos.");
			}
			else {
				$diaProcesado = substr($fechacargada, 0, 2)."-".substr($fechacargada, 3, 2)."-".substr($fechacargada, 6, 4);
				print("No se han registrado pagos para el dia ".$diaProcesado.". Debe registrar el dia para que quede procesado.");
			}
		}
		else {
			if ($hayErrores == 1) {
				//print("Error en Archivo -- El Archivo solicitado no existe.<br/>\n");
				$pagina = "procesamientoArchivosLinkpagos.php?err=1";
				Header("Location: $pagina"); 
			}
		}
	?></div></td>
  </tr>
  <tr align="center" valign="top">
    <td height="27" colspan="3">&nbsp;</td>
  </tr>
  <tr align="center" valign="top">
    <td height="27"><div align="left">
      <input type="reset" name="volver" value="Volver" onclick="location.href = 'procesamientoArchivosLinkpagos.php'"/>
    </div></td>
    <td width="426" height="27"><div align="center">
<?php 
	if($hayErrores == 0) {
		if($hayPagos == 1) { ?>
	  		<input type="submit" name="ingresar" value="Ingresar" onclick="location.href = 'procesarArchivoLinkpagos.php?nombreArc=<?php echo $archivo_name ?> '"/>
<?php 
		}
		else {  ?>
	  		<input type="submit" name="registrardia" value="Registrar Dia" onclick="location.href = 'registrarDiaLinkpagos.php?nombreArc=<?php echo $archivo_name ?> '"/>
<?php 
		}
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