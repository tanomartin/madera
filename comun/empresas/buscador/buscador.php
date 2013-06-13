<?php include($_SERVER['DOCUMENT_ROOT']."/comun/lib/controlSession.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Buscador de Empresas :.</title>
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
<body bgcolor=<?php echo $bgcolor ?>>
<p align="center">
<input type="reset" name="volver" value="Volver" onClick="location.href = '../menuEmpresa.php?origen=<?php echo $origen ?>'" align="center"/> 
</p>
<p align="center" class="Estilo2">Buscador de Empresas </p>
 <?php 
		$err = $_GET['err'];
		if ($err == 1) {
			print("<div align='center' style='color:#FF0000'><b> EMPRESA NO ENCONTRADA </b></div>");
		}

  ?>
<form id="ordena" name="ordena" method="post" action="resultadoEmpresas.php?origen=<?php echo $origen ?>">
  <div align="center">
    <table width="336" border="0">
    <tr>
      <td width="152" rowspan="3"><strong>Criterio de Busqueda </strong></td>
      <td width="20"><div align="center">
        <input name="criterio"  id="orden" type="radio" value="cuit" checked/>
      </div></td>
      <td width="150"><div align="left">CUIT</div></td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="criterio"  id="orden" type="radio" value="razonsocial" />
      </div></td>
      <td><div align="left">Razon Social </div></td>
    </tr>
    <tr>
      <td><div align="center">
        <input name="criterio"  id="radio" type="radio" value="domicilio" />
      </div></td>
      <td><div align="left">Domicilio</div></td>
    </tr>
  </table>
    <table width="297" border="0">
      <tr>
        <td width="131"><strong>Dato de Busqueda</strong></td>
        <td width="156"><input name="dato" type="text" id="dato" /></td>
      </tr>
    </table>
    <p>
      <label></label>
      <input type="submit" name="Submit" value="Buscar" />
      <br />
    </p>
  </div>
</form>
</body>
</html>
