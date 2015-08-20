<?php include "../config.php"; $updated = false;
if(isset($_GET['wakeHour'])&&isset($_GET['wakeMinute'])&&isset($_GET['sleepHour'])&&isset($_GET['sleepMinute'])){
		$wakeTime = 60 * $_GET['wakeHour'] + $_GET['wakeMinute'];
		$sleepTime = 60 * $_GET['sleepHour'] + $_GET['sleepMinute'];
		mysql_query("UPDATE patients SET wakeTime=".$wakeTime.",sleepTime=".$sleepTime." WHERE id=".$patientID) or die(mysql_error());
		$updated = true;}
		
		$query = mysql_query("SELECT wakeTime,sleepTime,voicePrompts FROM patients WHERE id=".$patientID) or die(mysql_query()); $row = mysql_fetch_assoc($query);
		$wakeHour = floor($row['wakeTime']/60); $wakeMinute = $row['wakeTime']%60; $sleepHour = floor($row['sleepTime']/60); $sleepMinute = $row['sleepTime']%60;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<style type="text/css">
body,input,select {font-size: 31px; line-height: 2em;}
select {border-radius: 21px; background: rgba(255,255,255,0.1); border: none; color: #fff;}
option {background: #191919;}
option:hover,select:hover, option:selected {background: #00CC00;}
input[type="submit"]{background-color: #FFFF00; border: none; color: #000; padding: 6px 27px; border-radius: 35px; font-size: 31px;}
</style>
<script type="text/javascript" src="form.js"></script>
<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dementia Project - Edit Configuration</title>
</head>

<body>
<?php if($row['voicePrompts']==1) echo "<audio autoplay hidden src='".((!$updated)?"audio/select_wake_sleep_times.mp3":"audio/changes_saved.mp3")."'></audio>";?>
<div id="content" style="height: 54.75%; padding-top: 9%; box-sizing:
border-box; font-size: 1.2em; color: yellow;">
<div style="margin: 0 auto; width: 22.75%;"><form action="setTime.php" method="get">

<div style="float: left; margin-right: 15%;">Wake Time<br />
<select name="wakeHour" onchange="document.getElementById('submitButton').style.display = 'inline';">
<?php for($i = 0; $i < 12; $i++) echo "<option".(($i==$wakeHour)?" selected":"").">".sprintf("%02s", $i)."</option>";?>
</select><span style="color: #FFF; margin: 0 5px 0 5px;">:</span><select name="wakeMinute" onchange="document.getElementById('submitButton').style.display = 'inline';">
<?php for($i = 0; $i < 60; $i+=15) echo "<option".(($i==$wakeMinute)?" selected":"").">".sprintf("%02s", $i)."</option>";?>
</select></div>
<div style="float: left;" onchange="document.getElementById('submitButton').style.display = 'inline';">Sleep Time<br />
<select name="sleepHour">
<?php for($i = 12; $i < 24; $i++) echo "<option".(($i==$sleepHour)?" selected":"").">".sprintf("%02s", $i)."</option>";?>
</option>
</select><span style="color: #FFF; margin: 0 5px 0 5px;">:</span><select name="sleepMinute" onchange="document.getElementById('submitButton').style.display = 'inline';">
<?php for($i = 0; $i < 60; $i+=15) echo "<option".(($i==$sleepMinute)?" selected":"").">".sprintf("%02s", $i)."</option>";?>
</select></div>
<input style="margin-top: 5%; <?php echo (($updated)?"":"display: none;");?>" type="submit" name="submit" value="<?php echo (($updated)?"Changes Saved":"Save Changes");?>" id="submitButton"/>
</form>
</div>
<div id="buttonDiv"></div>
<div id="stopButtonText" onclick="window.open('user_settings.php','_self');">BACK</div>
<div id="stopButton" onclick="window.open('user_settings.php','_self');"></div>
<?php if(isset($_GET['submit'])) echo "<script type='text/javascript'>setTimeout(function(){window.open('user_settings.php','_self');},4000);</script>";?>
</div>
</body>
</html>
