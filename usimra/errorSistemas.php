<?php $libPath = $_SERVER['DOCUMENT_ROOT']."/madera/lib/";
include($libPath."controlSessionUsimra.php"); 
?>
<html>
<head>
<style>

A:link {text-decoration: none}
A:visited {text-decoration: none}
A:hover {text-decoration:underline; color:FCF63C}

.Estilo1 {
	font-size: 24px;
	font-weight: bold;
	color: #FF0000;
}
.Estilo2 {font-size: 24px; font-weight: bold; color: #000000; }
</style>

<title>.: U.S.I.M.R.A. :.</title>
</head>

<body bgcolor="#B2A274" link="#D5913A" vlink="#CF8B34" alink="#D18C35">
<form method="POST" action="verificador.php">
  <div align="center">
    <p class="Estilo1">&iexcl;&iexcl;ERROR de Sistema!!</p>
    <p class="Estilo2">Por favor imprimir esta pantalla y llevarla al Dpto. de Sistemas</p>
    <p><img src="img/stop.png" width="128" height="128"> </p>
    <table width="200" border="1">
      <tr>
        <td><strong>P&aacute;gina: </strong></td>
        <td><?php echo $_GET['error']; ?></td>
      </tr>
      <tr>
        <td><strong>ERROR:</strong></td>
        <td><?php echo $_GET['page']; ?></td>
      </tr>
    </table>
    <p><strong>
      <input type="button" name="imprimir" value="Imprimir" onClick="window.print();" align="center"/>
    </strong></p>
  </div>
</form>

</body>

</html>
