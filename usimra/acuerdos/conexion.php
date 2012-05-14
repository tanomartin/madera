<?php
$host = "localhost";
$user = "sistemas";
$pass = "blam7326";
$dbname = "acuerdos";
$db = mysql_connect($host,$user,$pass);
if (!$db) {
    die('No pudo conectarse: ' . mysql_error());
}
mysql_select_db($dbname);
?>