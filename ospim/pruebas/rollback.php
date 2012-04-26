<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prueba ROLLBACK</title>
</head>

<body>
<?php
/*** mysql hostname ***/
$hostname = 'cronos';
/*** mysql username ***/
$username = 'sistemas';
$dbname = 'madera';
/*** mysql password ***/
$password = 'blam7326';

try {
    $dbh = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    /*** echo a message saying we have connected ***/
    echo 'Connected to database<br />';
  	
	/*** set the PDO error mode to exception ***/
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /*** begin the transaction ***/
    $dbh->beginTransaction();
	$rowAfectadoas = $dbh->exec("INSERT INTO estadocivil (codestciv, descrip) VALUES ('08', 'PRUEBA')");
	echo $rowAfectadoas;
	$dbh->exec("INSERT INTO estadocedivil (codestciv, descrip) VALUES ('08', 'PRUEBA')");
	$dbh->commit();	
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
	$dbh->rollback();
    }
?>
</body>
</html>
