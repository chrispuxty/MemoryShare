<?php
$db = mysqli_connect("127.0.0.1","kanvar","eVBLeUTKqRm8qxsQ") or die(mysqli_error($db));
mysqli_select_db($db,"kanvar_dementia") or die(mysqli_error($db));
$patientID = mysqli_fetch_assoc($db,mysqli_query($db,"SELECT id FROM options LIMIT 1;")) or die(mysqli_error($db));
$patientID = $patientID['id'];
?>

