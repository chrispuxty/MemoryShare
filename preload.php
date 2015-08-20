<?php include "config.php";
$query = mysql_query("SELECT * FROM patients WHERE id=".$patientID) or die(mysql_error());
$params = mysql_fetch_assoc($query);
$wakeHour = floor($params['wakeTime']/60); $wakeMinute = $params['wakeTime'] % 60;
shell_exec("DISPLAY=:0 xset dpms force off");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height: 100%; width: 100%;">
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<link rel="stylesheet" type="text/css" href="css/main.css"/>-->
<title>Media Preloader</title>
<script>
lastResetTime = Date.now(); var morningCutoff = new Date(); morningCutoff.setHours(<?php echo $wakeHour.",".$wakeMinute;?>,0,0); morningCutoff = morningCutoff.valueOf();  if(morningCutoff<lastResetTime) morningCutoff += 1000 * 60 * 60 * 24;
setTimeout(function(){window.open('index.php','_self');},morningCutoff - lastResetTime);
</script>
</head>

<body onclick="window.open('index.php','_self');" style="background: #000; width: 100%; height: 100%;">
<?php
function getMedia($dir) {
	$media = null;
	$a = opendir($dir);
	$x = 0;
	//if (!$dir) echo "DIR FAULT:<br>".$dir; else
	while (false !== ($temp = readdir($a))) {
		if (substr($temp, 0, 1) != ".") $media[$x++] = $temp;
	}
	if (is_readable($dir."/.customOrder")) $media = file($dir."/.customOrder", FILE_IGNORE_NEW_LINES);
	return $media;}
	
function preloadRecurse($dir){
	$media = getMedia($dir);
	if ($media != null) foreach ($media as $clip)
	if (is_dir($dir."/".$clip)) preloadRecurse($dir."/".$clip);
	else echo "<video hidden preload><source src='".$dir."/".$clip."' type='video/mp4'></video>";
	}
	
preloadRecurse("./media/Old Movies"); preloadRecurse("./media/Family Messages");

?>


</body>
</html>
