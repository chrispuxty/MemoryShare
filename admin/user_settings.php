<?php include "../config.php"; include "../functions.php";

$randomAutoplayOptions = [0,15,30,45];

$query = mysqli_query($db,"SELECT * FROM patients WHERE id=".$patientID) or die(mysqli_error($db)); $row = mysqli_fetch_assoc($db,$query);


if(isset($_GET['action'])) {
if ($_GET['action']=="timeout"){
$temp = array_search($row['randomAutoPlayDelay'],$randomAutoplayOptions)+1; if($temp>=count($randomAutoplayOptions)) $temp = 0;
mysqli_query($db,"UPDATE patients SET randomAutoPlayDelay=".$randomAutoplayOptions[$temp]." where id=".$patientID) or die(mysqli_error($db));}

elseif ($_GET['action']=="voiceprompts") {mysqli_query($db,"UPDATE patients SET voicePrompts=".(($row['voicePrompts']==1)?0:1)." where id=".$patientID) or die(mysqli_error($db));}
elseif ($_GET['action']=="sensors") {mysqli_query($db,"UPDATE patients SET motionAndAudioSensing=".(($row['motionAndAudioSensing']==1)?0:1)." where id=".$patientID) or die(mysqli_error($db));}

$query = mysqli_query($db,"SELECT * FROM patients WHERE id=".$patientID) or die(mysqli_error($db)); $row = mysqli_fetch_assoc($db,$query);
}
?>
<html>
<head>
<link rel="stylesheet" src="../css/main.css">
<style>
body {font-size: 38px; color: yellow; font-family: Planar; text-align: center; background: url('user-settings.jpg') #000 center center no-repeat; background-size: cover;}
.area, .button, .textbox {position: absolute; width: 14%; text-transform: uppercase;}
.textbox > span {position: absolute; bottom: 0px; width: 100%; left: 0px; line-height: 44px;}
.button {bottom: 0; height: 55%;}
.textbox {bottom: 56%; height: 6%;}
.button:nth-child(1), .textbox:nth-child(7) {left: 0; margin-left: 13px; text-transform: none;}
.button:nth-child(2), .textbox:nth-child(8) {left: 20.5%;}
.button:nth-child(3), .textbox:nth-child(9) {left: 36%;}
.button:nth-child(4), .textbox:nth-child(10) {left: 51%;}
.button:nth-child(5), .textbox:nth-child(11) {left: 66%;}
.button:nth-child(6), .textbox:nth-child(12) {right: 0;}
</style>
</head>
<body>
<div id="btnUsername" class="button" onclick="window.open('editConfig.php','_self');"></div>
<div id="btnSleeptime" class="button" onclick="window.open('setTime.php','_self');"></div>
<div id="btnVoiceprompts" class="button" onclick="window.open('user_settings.php?action=voiceprompts','_self');"></div>
<div id="btnTimeout" class="button" onclick="window.open('user_settings.php?action=timeout','_self');"></div>
<div id="btnSensors" class="button" onclick="window.open('user_settings.php?action=sensors','_self');"></div>
<div id="btnStop" class="button" onclick="window.open('/closekiosk','_self');"></div>
<div id="username" class="textbox"><span><?php echo $row['name']; ?></span></div>
<div id="sleeptime" class="textbox"><span><?php echo sprintf("%04d",(floor($row['wakeTime'] / 60) * 100 + $row['wakeTime'] % 60))
. " - " . sprintf("%04d",(floor($row['sleepTime'] / 60) * 100 + $row['sleepTime'] % 60));?></span></div>
<div id="voiceprompts" class="textbox"><span><?php echo (($row['voicePrompts']==1)?"On":"Off");?></span></div>
<div id="timeout" class="textbox"><span><?php echo (($row['randomAutoPlayDelay']==0)?"Off":$row['randomAutoPlayDelay']." Min");?></span></div>
<div id="sensors" class="textbox"><span><?php echo (($row['motionAndAudioSensing']==1)?"On":"Off");?></span></div>
<div id="stopbutton" class="textbox"></div>
<?php if ($row['voicePrompts']==1) {
$voicePromptPath = "audio/";
if(isset($_GET['action'])&&$_GET['action']=="timeout") $voicePromptPath .= (($row['randomAutoPlayDelay']!=0)?$row['randomAutoPlayDelay']."min.mp3":"rmp_disabled.mp3");
elseif (isset($_GET['action'])&&$_GET['action']=="voiceprompts") $voicePromptPath .= "voice_prompts_on.mp3";
else $voicePromptPath .= "homepage_2.mp3";
echo "<audio autoplay hidden style='display: none;' src='".$voicePromptPath."'></audio>
";
} ?>
</body>
</html>