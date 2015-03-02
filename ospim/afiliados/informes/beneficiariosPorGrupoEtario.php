<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Listado De Beneficiarios por Delegacion :.</title>
</head>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
<style type="text/css" media="print">
.nover {display:none}
</style>

<script src="/lib/jquery.js" type="text/javascript"></script>
<script src="/lib/jquery.blockUI.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">

function checkall(seleccion, formulario) {
	grupo = formulario.provincias;
	var total = grupo.length;
	if (total == null) {
		if (seleccion.checked) {
			grupo.checked = 1;
		} else {
			grupo.checked = 0;
		}
	}
	if (seleccion.checked) {
		 for (i=0;i< grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=1;  
	} else {
		 for (i=0;i<grupo.length;i++) 
			 if(grupo[i].type == "checkbox")	
				 grupo[i].checked=0;  
	}
} 

function validar(formulario) {
	var delegaCheck = 0;
	delegaciones = formulario.provincias;
	if (delegaciones != null) {
		for (x=0;x<delegaciones.length;x++) {
			if(delegaciones[x].checked) {
				delegaCheck = 1;
			}
		}
	}
	if (delegaCheck == 0) {
		alert("Debe elegir como mínimo una Provincia para generar el Informe");
		return false;
	}
	formulario.checkAll.disabled = true;
	formulario.Submit.disabled = true;
	$.blockUI({ message: "<h1>Generando Archivo<br>Aguarde por favor...</h1>" });
	return true;
}

</script>

<body bgcolor="#CCCCCC">
<div align="center">
  <p><input type="reset" name="volver" value="Volver" class="nover" onclick="location.href = 'moduloInformes.php?origen=<?php echo $origen ?>'" align="center"/></p>
	<form  name="listadoEmpresa" id="listadoEmpresa" method="post" onSubmit="return validar(this)" action="beneficiariosPorGrupoEtarioExcel.php">
  	<p><span class="Estilo2">Beneficiarios por Grupo Etario </span></p>
  	<p>
  	  <?php if (isset($_GET['error'])) { 
			if ($_GET['error'] == 0) {
				print("<p><font color='#0000FF'><b> Se generó correctamente el informe.<br>Lo encontrara en la carpeta correspondiente </b></font></p>");
		 	} 
			if ($_GET['error'] == 1) {
				$descerror = $_GET['mensaje'];
				print("<p><font color='#FF0000'><b> Hubo un error. $descerror.<br> Comuníquese con el Dpto. de Sistemas </b></font></p>");
			}
  	 } ?>
	</p>
  	<table width="600" border="0">
      <tr><td colspan="2"><div align="center" class="Estilo2"><strong>Delegaciones (Seleccionar todo <input type="checkbox" name="checkAll" id="checkAll" onchange="checkall(this, this.form)" /> )</strong></div></td></tr>
      <tr>
        <td><div align="left">
            <?php 
				$query="select * from provincia where codprovin > 0 and codprovin <= 12";
				$result=mysql_query($query,$db);
				$i = 0;
				while ($rowtipos=mysql_fetch_array($result)) { ?>
            		<input type="checkbox" name="<?php echo "provincia".$i ?>" id="provincias" value="<?php echo $rowtipos['codprovin'] ?>" /> <?php echo $rowtipos['descrip']."<br>"; $i++;
			 	} ?>
        </div></td>
        <td><div align="left">
            <?php 
				$query="select * from provincia where codprovin > 12 and codprovin < 99";
				$result=mysql_query($query,$db);
				while ($rowtipos=mysql_fetch_array($result)) { ?>
           		  <input type="checkbox" name="<?php echo "provincia".$i  ?>" id="provincias" value="<?php echo $rowtipos['codprovin'] ?>" /><?php echo $rowtipos['descrip']."<br>"; $i++;
				} ?>
        </div></td>
      </tr>
    </table>
  	<p><input type="submit" name="Submit" id="Submit" value="Generar Archivo" class="nover"/></p>
</form>
</div>
</body>
</html>
