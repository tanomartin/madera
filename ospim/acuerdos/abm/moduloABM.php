<?php include($_SERVER['DOCUMENT_ROOT']."/madera/lib/controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: M�dulo ABM Acuerdos :.</title>
<script src="/madera/lib/jquery.js" type="text/javascript"></script>
<script src="/madera/lib/jquery.maskedinput.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

jQuery(function($){
	$("#cuit").mask("99999999999");
});

</script>
</head>

<body bgcolor="#CCCCCC">
<form id="form1" name="form1" method="post" action="acuerdos.php">
	<div align="center">
		<p><input type="button" name="volver" value="Volver" onclick="location.href = '../menuAcuerdos.php'" /></p>	 
	  	<h3>M�dulo De ABM de Acuerdos</h3>
	  	<?php 
	  		if (isset($_GET['err'])) {
				$err = $_GET['err'];
				if ($err == 1) {
					print("<div align='center' style='color:#FF0000'><b> CUIT NO ENCONTRADO </b></div>");
				}
	  		}
	  	?>
	  	<p> C.U.I.T.: <input name="cuit" type="text" id="cuit" size="10" /></p>
	  	<p>	<input type="submit" name="Submit" value="Buscar" /></p>
	</div>
</form>
</body>
</html>
