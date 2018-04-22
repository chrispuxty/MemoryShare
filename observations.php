<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Results</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>Observations Viewer</h1>
<form action='observations.php' method='get'>
<select name='id'>
<?php include "config.php";
$query = mysqli_query($db,"select id,name from patients order by name") or die(mysqli_error($db));
$patients = Array(); $count = 0; $name = null; $temp = null;
while ($temp !== false) {$temp = mysqli_fetch_assoc($query); if ($temp !== false) $patients[$count++]=$temp;}


if (count($patients) > 0) foreach ($patients as $patient)
{echo "<option value='".$patient['id']."'";
if (isset($_GET['id']) && $patient['id'] == $_GET['id']) {echo " selected "; $name = $patient['name'];}
echo ">".$patient['name']."</option>";}
?>
</select>
<input type='submit' name='View Log' />
</form><br />
<?php if (isset($_GET['id'])) {
echo "<h2>All observations logged for ".$name."</h2><table>";
$query = mysqli_query($db,"select path,time from observations where patient='".addslashes($_GET['id'])."'") or die(mysqli_error($db));
$records = null; $count = 0; $temp = null;
while ($temp !== false) {$temp = mysqli_fetch_assoc($query); if ($temp !== false) $records[$count++]=$temp;}
$count = 0;
if ($records != null) foreach ($records as $record)
{if ($count++ % 15 == 0) echo "<tr><td><b>Timestamp</b></td><td><b>Path Logged</b></td></tr>";
echo "<tr><td>".$record['time']."</td><td>".$record['path']."</td></tr>";
}}
?>
</table>
</body>
</html>