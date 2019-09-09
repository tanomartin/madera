<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSession.php"); 
//Para que se vea el blockUI
print("<br>");
//*************************

$modulo = $_GET['modulo'];
$nombreModulo = "id-$modulo";
$idmodulo = $_POST[$nombreModulo];
$origen = $_GET['origen'];

$nombreDato1 = "dato1-$modulo";
$dato1 = "";
if (isset($_POST[$nombreDato1])) {
	$dato1 = $_POST[$nombreDato1];
}
$nombreDato2 = "dato2-$modulo";
$dato2 = "";
if (isset($_POST[$nombreDato2])) {
	$dato2 = $_POST[$nombreDato2];
}
$nombreDato3 = "dato3-$modulo";
$dato3 = "";
if (isset($_POST[$nombreDato3])) {
	$dato3 = $_POST[$nombreDato3];
}
$nombreDato4 = "dato4-$modulo";
$dato4 = "";
if (isset($_POST[$nombreDato4])) {
	$dato4 = $_POST[$nombreDato4];
}
$nombreMotivo = "motivo-$modulo";
$idMotivo = $_POST[$nombreMotivo];
$nombreObs = "obs-$modulo";
$observacion = $_POST[$nombreObs];

$arrayExistencia = array();
if ($modulo == "ACUERDOS") {
	$arrayExistencia[0] = "SELECT * FROM cabacuerdos$origen WHERE cuit = $dato1 and nroacuerdo = $dato2";
	$error = "NO EXISTE ACUERDO NUMERO '$dato2' PARA EL C.U.I.T. '$dato1'";
}
if ($modulo == "APORTES" || $modulo == "EMPRESAS" || $modulo == "FACTURACION / LIQUIDACION") {
	$arrayExistencia[0] = "SELECT * FROM empresas WHERE cuit = $dato1";
	$arrayExistencia[1] = "SELECT * FROM empresasdebaja WHERE cuit = $dato1";
	$error = "NO EXISTE EMPRESA CON EL C.U.I.T. '$dato1'";
}
if ($modulo == "AFILIADOS") {
	$arrayExistencia[0] = "SELECT * FROM titulares WHERE cuil = $dato1";
	$arrayExistencia[1] = "SELECT * FROM titularesdebaja WHERE cuil = $dato1";
	$arrayExistencia[2] = "SELECT * FROM familiares WHERE cuil = $dato1";
	$arrayExistencia[3] = "SELECT * FROM familiaresdebaja WHERE cuil = $dato1";
	$error = "NO EXISTE AFILIADO CON EL C.U.I.L. '$dato1'";
}
if ($modulo == "JUICIOS") {
	$arrayExistencia[0] = "SELECT * FROM cabjuicios$origen WHERE cuit = $dato1 and nroorden = $dato2";
	$error = "NO EXISTE JUICIO CON NRO DE ORDEN '$dato2' EN EL C.U.I.T. '$dato1' ";
}
if ($modulo == "AUDITORIA MEDICA") {
	$arrayExistencia[1] = "SELECT * FROM prestadores WHERE cuit = $dato1 or codigoprestador = $dato2";
	$error = "NO EXISTE PRESATDOR CON CODIGO '$dato2' O C.U.I.T. '$dato1' ";
}

$numExistencia = 0;
foreach($arrayExistencia as $sqlExistencia) {
	$resExistencia = mysql_query($sqlExistencia,$db);
	$numExistencia += mysql_num_rows($resExistencia);
}

if ($numExistencia == 0) {
	Header("Location: nuevaCorreccion.php?origen=$origen&error=$error");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Módulo Fiscalizador OSPIM :.</title>

<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$.blockUI({ message: "<h1>Guardando datos de la Corrección...<br> Aguarde por favor</h1>" });
	function formSubmit() {
		document.getElementById("correccion").submit();
	}
</script>
</head>
<body onload="formSubmit();" style="background-color: <?php echo $bgcolor ?>">
<form action="guardarCorreccion.php?origen=<?php echo $origen ?>&modulo=<?php echo $modulo ?>" id="correccion" method="post" style="display: none"> 
	<input type="text" name="id-<?php echo $modulo ?>" id="id-<?php echo $modulo ?>" value="<?php echo $idmodulo ?>"/>
<?php if ($dato1 != "") {?>
		<input type="text" name="dato1-<?php echo $modulo ?>" id="dato1-<?php echo $modulo ?>" value="<?php echo $dato1 ?>"/>
<?php } 
	  if ($dato2 != "") {?>
		<input type="text" name="dato2-<?php echo $modulo ?>" id="dato2-<?php echo $modulo ?>" value="<?php echo $dato2 ?>"/>
<?php } 
	  if ($dato3 != "") { ?>
		<input type="text" name="dato3-<?php echo $modulo ?>" id="dato3-<?php echo $modulo ?>" value="<?php echo $dato3 ?>"/>
<?php }
	  if ($dato4 != "") { ?>
		<input type="text" name="dato4-<?php echo $modulo ?>" id="dato4-<?php echo $modulo ?>" value="<?php echo $dato4 ?>"/>
<?php } ?>
	<input type="text" name="motivo-<?php echo $modulo ?>" id="motivo-<?php echo $modulo ?>" value="<?php echo $idMotivo ?>"/>
	<textarea rows="6" cols="100" name="obs-<?php echo $modulo ?>" id="obs-<?php echo $modulo ?>"><?php echo $observacion ?></textarea>
</form> 
</body>
</html>