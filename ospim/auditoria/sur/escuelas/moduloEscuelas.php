<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); 

$busqueda = 0;
if (isset($_POST['dato']) && isset($_POST['filtro'])) {
	$busqueda = 1;
	$dato = $_POST['dato'];
	$filtro = $_POST['filtro'];
	if ($filtro == "codigo") {
		$cartel = "Resultados de Busqueda por Código de Escuela <b>'".$dato."'</b>";
	}
	if ($filtro == "nombre") {
		$cartel = "Resultados de Busqueda por Nombre <b>'".$dato."'</b>";
	}
	if ($filtro == "cue") {
		$cartel = "Resultados de Busqueda por C.U.E. <b>'".$dato."'</b>";
	}
	if (isset($dato)) {
		$sqlEscuelas = "";
		if ($filtro == "codigo") { $sqlEscuelas = "SELECT * FROM escuelas WHERE id = $dato"; }
		if ($filtro == "nombre") { $sqlEscuelas = "SELECT * FROM escuelas WHERE nombre like '%$dato%' order by id DESC"; }
		if ($filtro == "cue") { $sqlEscuelas = "SELECT * FROM escuelas WHERE cue = $dato order by id DESC"; }
		$resEscuelas = mysql_query($sqlEscuelas,$db);
		$canEscuelas = mysql_num_rows($resEscuelas);
		if ($canEscuelas == 0) {
			$noExiste = 1;
		}
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Escuelas :.</title>
<script src="/madera/lib/jquery.js"></script>
<script src="/madera/lib/jquery-ui.min.js"></script>
<script src="/madera/lib/jquery.blockUI.js" type="text/javascript"></script>
<script src="/madera/lib/funcionControl.js" type="text/javascript"></script>
<link rel="stylesheet" href="/madera/lib/tablas.css"/>
<script type="text/javascript">

function validar(formulario) {
	if(formulario.dato.value == "") {
		alert("Debe colocar un dato de busqueda");
		return false;
	}
	if (formulario.filtro[0].checked || formulario.filtro[2].checked) {
		if (!esEnteroPositivo(formulario.dato.value)) {
			alert("El Código de la escuala y su C.U.E. debe ser un numero entero positivo");
			return false;
		} 
	}
	$.blockUI({ message: "<h1>Generando Busqueda... <br>Esto puede tardar unos segundos.<br> Aguarde por favor</h1>" });
	return true;
}

function abrirPantalla(dire) {
	a= window.open(dire,'',
	"toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=800, height=500, top=10, left=10");
}

</script>

<style type="text/css" media="print">
.nover {display:none}
</style>
</head>

<body bgcolor="#CCCCCC">
<div align="center">
	<form id="moduloEscuelas" name="moduloEscuelas" method="post" onsubmit="return validar(this)" action="moduloEscuelas.php">
		<input class="nover" type="reset" name="volver" value="Volver" onclick="location.href = '../menuSur.php'" /> 
		<h2>Modulo Escuelas </h2>
		<p><input type="button" name="nuevo" value="Nueva Escuela" onclick="abrirPantalla('nuevaEscuela.php')" /></p>
		<table width="100" border="0">
			  <tr>
				<td width="23"><input name="filtro" type="radio" value="codigo" checked="checked"/></td>
				<td width="104"><div align="left">Codigo</div></td>
			  </tr>
			  <tr>
				<td><input name="filtro" type="radio" value="nombre" /></td>
				<td><div align="left">Nombre</div></td>
			  </tr>
			  <tr>
				<td><input name="filtro" type="radio" value="cue" /></td>
				<td><div align="left">C.U.E.</div></td>
			  </tr>
		</table>
		<p><input name="dato" id="dato" type="text" size="11" /></p>
 		<p><input class="nover" type="submit" name="buscar" value="Buscar" /></p>
	</form>
<?php  if ($busqueda == 1) { ?>
			<h3><?php echo $cartel ?></h3>		
<?php		if($canEscuelas == 0) { 
		  		print("<h3><font color='#0000FF'><b> No Existen Escuelas con la busqueda realizada </b></font></h3>");
		  	} else { ?>
				<div class="grilla">
					 <table style="text-align:center; width:1100px" id="tablaEscuelas" class="tablaEscuelas" >
	         		 <thead>
	            		<tr>
				  			<th>Codigo</th>
				  			<th>Nombre</th>
							<th>C.U.E.</th>
							<th>Email</th>
							<th>Telefono</th>
							<th></th>
						</tr>
	          		</thead>
	        		<tbody> 
			<?php 	while ($rowEscuelas = mysql_fetch_assoc($resEscuelas)) {?>
						<tr>
							<td><?php echo $rowEscuelas['id'] ?></td>
							<td><?php echo $rowEscuelas['nombre']?> </td>
							<td><?php echo $rowEscuelas['cue'] ?> </td>
							<td><?php echo $rowEscuelas['email']?> </td>
							<td><?php echo $rowEscuelas['telefono']?> </td>
							<td><input type="button" value="Ficha" onclick="abrirPantalla('escuela.php?id=<?php echo  $rowEscuelas['id'] ?>')"/> | <input type="button" value="Modificar" onclick="abrirPantalla('modificarEscuela.php?id=<?php echo  $rowEscuelas['id'] ?>')"/></td>
						</tr>
			  <?php } ?>
			  		</tbody> 
					</table>
				</div>
	<?php	}
	} ?>
</div>
</body>
</html>