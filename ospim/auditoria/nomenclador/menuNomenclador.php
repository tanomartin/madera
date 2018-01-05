<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionOspim.php"); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>.: Modulo Nomencladores :.</title>
<style>
A:link {text-decoration: none;color:#0033FF}
A:visited {text-decoration: none}
A:hover {text-decoration: none;color:#00FFFF }
.Estilo2 {
	font-weight: bold;
	font-size: 18px;
}
</style>
</head>
<body bgcolor="#CCCCCC">
<div align="center">
  <p>
    <input type="reset" name="volver" value="Volver" onclick="location.href = '../menuAuditoria.php'"/>
  </p>
  <p><span class="Estilo2">Men&uacute; Nomencladores </span></p>
  <table width="626" border="3">
    <tr>
	  <td width="200"><p align="center">Nacional / P.M.O.</p>
          <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=1"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td width="200"><p align="center">No Nomenclado </p>
        <p align="center"><a class="enlace" href="nonomenclado/menuNoNomenclado.php?codigo=2"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>
      </td>
      <td width="200"><p align="center">Bioquimico </p>
        <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=3"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>
      </td>
   </tr>
   <tr>
   	 <td width="200"><p align="center">Municipal</p>
        <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=4"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>
      </td>
      <td width="200"><p align="center">Hospitales Publicos </p>
        <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=5"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>
      </td>
       <td width="200"><p align="center">FEMEBA</p>
        <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=6"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
    </tr>
     <tr>
   	 <td width="200"><p align="center">S.U.R.</p>
        <p align="center"><a class="enlace" href="nomenclado/menuNomenclado.php?codigo=7"><img src="img/nomenclador.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p>
      </td>
        <td width="200"><p align="center">Buscador</p>
        <p align="center"><a class="enlace" href="buscador/buscadorPractica.php"><img src="img/buscar.png" width="90" height="90" border="0" alt="enviar"/></a></p>
        <p align="center">&nbsp;</p></td>
      <td></td>
    </tr>
  </table>
</div>
</body>
</html>
