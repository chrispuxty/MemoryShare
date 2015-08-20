<?php
$db = mysql_connect("127.0.0.1","kanvar","eVBLeUTKqRm8qxsQ") or die(mysql_error());
mysql_select_db("kanvar_dementia") or die(mysql_error());
$patientID = mysql_fetch_assoc(mysql_query("SELECT id FROM options LIMIT 1;")) or die(mysql_error());
$patientID = $patientID['id'];
?>

