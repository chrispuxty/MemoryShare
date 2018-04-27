<?php
shell_exec("DISPLAY=:0 xset dpms force on");
/*
$cfgUpdateDisableAutoplay = isset($_GET['disableAutoplay']);
if($cfgUpdateSevereMode||$cfgUpdateDisableAutoplay){
	 //DEPRECIATED
	$configFile = file("config.php",FILE_IGNORE_NEW_LINES||FILE_SKIP_EMPTY_LINES);
	foreach($configFile as &$line){
	if($cfgUpdateSevereMode&&strpos($line,"severeMode") === 1)
	$line = "$"."severeMode = ".(($_GET['severeMode']=="true")?"true":"false").";\n";
	//echo strpos($line,"severeMode");
	if($cfgUpdateDisableAutoplay&&strpos($line,"disableAutoplay") === 1)
	$line = "$"."disableAutoplay = ".(($_GET['disableAutoplay']=="true")?"true":"false").";\n";
	}
	//echo print_r($configFile);
	file_put_contents("config.php",$configFile);}
	*/
include "config.php"; include "functions.php";
if (isset($_GET['severeMode'])) mysqli_query($db,"UPDATE patients SET severeMode=".((($_GET['severeMode']==="true"))?1:0)." WHERE id=".$patientID) or die(mysqli_error($db));
if (isset($_GET['disableAutoplay'])) mysqli_query($db,"UPDATE patients SET disableAutoplay=".((($_GET['disableAutoplay']==="true"))?1:0)." WHERE id=".$patientID) or die(mysqli_error($db));
$query = mysqli_query($db,"SELECT * FROM patients WHERE id=".$patientID) or die(mysqli_error($db)); $row = mysqli_fetch_assoc($query);
$severeMode = (($row['severeMode']==1)?true:false); $disableAutoplay = (($row['disableAutoplay']==1)?true:false);

$query = mysqli_query($db,"SELECT * FROM patients WHERE id=".$patientID) or die(mysqli_error($db));
$params = mysqli_fetch_assoc($query);
$wakeHour = floor($params['wakeTime']/60); $wakeMinute = $params['wakeTime'] % 60;
$sleepHour = floor($params['sleepTime']/60); $sleepMinute = $params['sleepTime'] % 60;
$randomAutoplayTime = $params['randomAutoPlayDelay'] * 60000;
$inhibitAutoplay = ($params['voicePrompts']!=1);
$sensorsActivated = ($params['motionAndAudioSensing']==1);

//echo $_SERVER['SCRIPT_FILENAME']."<br />".$_SERVER['REMOTE_ADDR']; //TESTING - COMMENT OUT
//if ($_SERVER['REMOTE_ADDR'] == "::1" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER['REMOTE_ADDR'] == "localhost") $localPath = "file:///".$_SERVER['SCRIPT_FILENAME']."/../"; else
$localPath = ""; //Broken by Chrome same-origin policy
//echo $_SERVER['REMOTE_ADDR'];

//PREPARATION CODE
if (isset($_GET['path'])) $path = $_GET['path']; else {$path = "media"; resetSequentialAutoplay($path);}
$file = false; $autoplay = false;
if (!is_dir($path)) {$temp = explode('/',$path); $file = array_pop($temp); 
$path = ""; foreach ($temp as $x) $path .= "/" . $x;}
if (substr($path,0,1) == "/") $path = substr($path,1,strlen($path));
if(!$file&&(isset($_GET['sequentialOverride']))) $file = sequentialAutoplay($path."/".$_GET['sequentialOverride']);
if (isset($_GET['recurse'])) $file = severeModeAutoplay($path."/".$_GET['recurse'],((isset($_GET['exclude']))?$_GET['exclude']:""));
$recurseCount = count(getMedia($path.((isset($_GET['recurse']))?$_GET['recurse']:"")));
if($file == ""&&!$disableAutoplay) $file = recursiveAutoplay($path);
if($file == "") $file = false; else {$array = explode($path,$file); $file = array_pop($array); $autoplay = true;}
if (is_readable($path."/.autoplay")&&!isset($_GET['recurse'])) $autoplayFile = file($path."/.autoplay", FILE_IGNORE_NEW_LINES);
if (isset($autoplayFile)) $autoplayFile = $autoplayFile[0];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Kanvar Nayar - Dementia Project</title>
<link rel="stylesheet" type="text/css" href="css/main.css"/>
<script type="text/javascript" src="./js/date_time.js"></script>
<script type="text/javascript" src="./js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="./config.php"></script>
<script type="text/javascript" src="./js/screensaver.php"></script>
<script type="text/javascript" src="./js/alarm.js"></script>
<script type="text/javascript">
window.oncontextmenu = function(event) {
    event.preventDefault();
    event.stopPropagation();
    return false;
};
function randomAutoplay(){<?php $tempArray = getMedia("media"); shuffle($tempArray);echo "window.open('index.php?path=media&recurse=/".$tempArray[0]."','_self');"?>}
</script>
<script type="text/javascript">//window.unload = alert("unload fired");
<?php
echo "function autoplayAnimation() {";
if($path=="media"	) echo '$(".button:eq(0)").delay(7087).animate({opacity: 0},250,function(){$(".button:eq(0)").animate({opacity: 1},250);});
$(".button:eq(1)").delay(11117).animate({opacity: 0},250,function(){$(".button:eq(1)").animate({opacity: 1},250);});
$(".button:eq(2)").delay(14673).animate({opacity: 0},250,function(){$(".button:eq(2)").animate({opacity: 1},250);});
$(".button:eq(3)").delay(17508).animate({opacity: 0},250,function(){$(".button:eq(3)").animate({opacity: 1},250);});
var x = setTimeout(function(){
$(".button:eq(0)").delay(7087).animate({opacity: 0},250,function(){$(".button:eq(0)").animate({opacity: 1},250);});
$(".button:eq(1)").delay(11117).animate({opacity: 0},250,function(){$(".button:eq(1)").animate({opacity: 1},250);});
$(".button:eq(2)").delay(14673).animate({opacity: 0},250,function(){$(".button:eq(2)").animate({opacity: 1},250);});
$(".button:eq(3)").delay(17508).animate({opacity: 0},250,function(){$(".button:eq(3)").animate({opacity: 1},250);});
},26434);';else echo '$(".button:eq(0)").delay(6483).animate({opacity: 0},250,function(){$(".button:eq(0)").animate({opacity: 1},250);});
$(".button:eq(1)").delay(7925).animate({opacity: 0},250,function(){$(".button:eq(1)").animate({opacity: 1},250);});
$(".button:eq(2)").delay(9000).animate({opacity: 0},250,function(){$(".button:eq(2)").animate({opacity: 1},250);});
$(".button:eq(3)").delay(9902).animate({opacity: 0},250,function(){$(".button:eq(3)").animate({opacity: 1},250);});
var x = setTimeout(function(){
$(".button:eq(0)").delay(2483).animate({opacity: 0},250,function(){$(".button:eq(0)").animate({opacity: 1},250);});
$(".button:eq(1)").delay(3925).animate({opacity: 0},250,function(){$(".button:eq(1)").animate({opacity: 1},250);});
$(".button:eq(2)").delay(5000).animate({opacity: 0},250,function(){$(".button:eq(2)").animate({opacity: 1},250);});
$(".button:eq(3)").delay(5902).animate({opacity: 0},250,function(){$(".button:eq(3)").animate({opacity: 1},250);});
},22620);
';
echo "document.getElementById('autoplayer').play();}";
?>
function triggerClick(){document.getElementById("clicker").play();}
function navigate(dest)
{
	//alert(dest);
	var destinations = Array();
	var matchedOnClickPlays = Array();
	<?php
//echo $path . "<br>";
//echo $file;
$media = getMedia($path.((isset($_GET['recurse']))?"/".$_GET['recurse']:""));
$mediaCount = count($media);
//.onClickPlay Handler
$onClickPlayFile = null;
if (is_readable($path."/.onClickPlay")) {$onClickPlayFile = file($path."/.onClickPlay",FILE_IGNORE_NEW_LINES);
$destinations = Array();
$matchedOnClickPlays = Array();
$temp = array_pop($onClickPlayFile);
while ($temp !== null){
array_push($destinations,array_pop($onClickPlayFile));
array_push($matchedOnClickPlays,$temp);
$temp = array_pop($onClickPlayFile);}
//echo print_r($destinations);
//echo print_r($matchedOnClickPlays);
$c1 = 0;
$c2 = 0;
foreach($destinations as $element) echo "destinations[".$c1++."] = '".$element."';
";
foreach($matchedOnClickPlays as $element) echo "matchedOnClickPlays[".$c2++."] = '".$path."/".$element."'; 
";

echo "if (destinations.indexOf(dest)!=-1) {
	document.getElementById('clicker').src = matchedOnClickPlays[destinations.indexOf(dest)];
	//alert(document.getElementById('clicker').src);
	document.getElementById('clicker').play();
	document.getElementById('clicker').addEventListener('ended',function(){".(($severeMode)?"setTimeout(function(){":"")."window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."'+dest,'_self');".(($severeMode)?"},2000);":"")."},false);
	}
else window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."'+dest,'_self');

	}";} 
	else echo "window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."/'+dest,'_self');}";?>
$(document).ready(function(){
//alert("onload fired");
<?php if($file == false && $path != "media") echo "setTimeout(function(){window.open('index.php','_self');},60000);";?>
triggerClick();
<?php if(($file==false && $path!="media") || isset($_GET['timerCarry'])) 
echo "setScreenSaver(".((isset($_GET['timerCarry']))?$_GET['timerCarry']:0).");";
?>
autoplayAnimation();
<?php if($path=="media"&&!isset($_GET['recurse'])&&$sensorsActivated) echo "setTimeout(function(){checkAlarm();},60000);";
if($path=="media"&&!isset($_GET['recurse'])&&$randomAutoplayTime!=0){echo "setInterval(function(){randomAutoplay();},".$randomAutoplayTime.");";}
?>
});

<?php if($mediaCount==0) echo "window.open('index.php','_self');";?> //Fix for this machine only, patch to others if needed //HACK

</script>
<?php
//$patientID = 24601;
//echo "insert into observations (patient, path) values (" . $patientID . ", " . $path . "/" . $file . ")";
mysqli_query($db,"insert into observations (patient, path) values (" . $patientID . ", '" . addslashes($path . "/" . (($autoplay)?" [AUTOPLAY] ":""). $file) ."')") or die(mysqli_error($db));
//RENDERING CODE
//if($severeMode && strlen($file) > 0) echo "<script type='text/javascript'>setTimeout(function(){window.onclick = function(){window.open('index.php','_self');}},500);</script>";
//if($path == "media") echo "<script type='text/javascript'>var screensaver;
//function resetScreenSaver(){clearTimeout(screensaver); //document.getElementById(\"clicker\").play();
//screensaver = setTimeout(function(){window.open('preload.php','_self');
//document.getElementById(\"clicker\").pause(); //document.getElementById(\"clicker\").load();
//}, 600000);}
//resetScreenSaver();
//window.onmousemove = resetScreenSaver();
//</script>";

$enablePopup = false;
$thumbs = getThumbs($path);
 if ($path == "media" && $file == ""){echo '<style type="text/css">body {font-size: 24pt; letter-spacing: 2px;}
.button {font-size: 24pt;}
</style>';
echo '</head> <body>';
echo '<div id="topBar"><span id="date_time"></span><script type="text/javascript">date_time("date_time");</script>';
echo '</div>';} else echo '</head> <body>';
//echo $path."####".$file;
//if ($file != "") echo $path . "/" . recurseToThumb($path."/".$file);
echo '<audio id="clicker" hidden preload src="click.mp3"></audio><div id="content"';
if ($path != "media" || $file != "") echo ' style="height: 55%; 
margin-bottom: 0.5%;"'; echo ">";
//if ($path == "media") {preloadRecurse("media/Videos"); preloadRecurse("media/Talk");}
$extension = trimExt($file,true);
echo ((!$inhibitAutoplay&&is_readable($path."/.autoplay.mp3")&&strlen($extension) < 1)?"<audio id='autoplayer' autoplay src='".$path."/.autoplay.mp3'></audio>":"");
if ($extension == strtolower("mp3") || $extension == strtolower("wav") || $extension == strtolower("ogg") || $extension == strtolower("flac") || $extension == strtolower("wma"))
{echo "<audio id='player' hidden autoplay>
<source  src='".$localPath.$path."/".$file."' type='audio/mp3'>
</audio>"
.(($severeMode
||true //HACK
)?"
<style type='text/css'>
#content {background-image: url('" . recurseToThumb($path."/".$file)."');
		background-size: contain;
		background-repeat: no-repeat;
		background-position: center;
</style>":""); $enablePopup = true;
if ($severeMode) echo "<script type='text/javascript' src='js/fullscreenSevere.js'></script>";}
elseif ($extension == strtolower("avi") || $extension == strtolower("mov") || $extension == strtolower("wmv") || $extension == strtolower("mpg") || $extension == strtolower("mp4")){
echo "<video id='player' autoplay poster='loading.gif' name='media'><source src='".$localPath.$path."/".$file."' type='video/".$extension."'>
</video>
<div id='playbackControl' style='display: none;'><span id='playbackControlText'>Press PLAY to continue</br>your ".((strpos($path,"Old Movies") > 0)?"movie":"message")."</span></div>
".((!$severeMode
//||!isset($_GET['recurse'])
)?"<div id='pauseButton' onclick='disableFullScreen();'></div><div id='pauseButtonText'>PAUSE</div>":"")."
<script type='text/javascript' src='".
(($severeMode)?"js/fullscreenSevere.js":"js/fullscreen.js")."'></script>";
//preloadRecurse("media/Videos");
//preloadRecurse("media/Talk");
}
elseif ($extension == strtolower("lee")) {
$musicFolder = file_get_contents($path."/".$file);
	$musicPath = dirname($path."/".$file)."/".$musicFolder;
echo "<audio id='slideShowPlayer' autoplay hidden></audio><script type='text/javascript'>";
echo "audioArray = new Array(); audioCount = 0;";
	$audioArray = array_flatten(recursiveMap('media/Music'));
	shuffle($audioArray);
	for($i = 0; $i < count($audioArray); $i++) echo "audioArray[".$i."] = '".$audioArray[$i]."';
		";
	echo "document.getElementById('slideShowPlayer').addEventListener('ended',function(){
audioCount += 1; if(audioCount>=audioArray.length) audioCount = 0;
document.getElementById('slideShowPlayer').src = audioArray[audioCount];
document.getElementById('slideShowPlayer').play();
});
document.getElementById('slideShowPlayer').src = audioArray[audioCount];
document.getElementById('slideShowPlayer').play();

</script>";

}
else if (strtolower($extension) == "kanvar") {
	$picFolder = file_get_contents($path."/".$file);
	$picPath = dirname($path."/".$file)."/".$picFolder;
	$slideshow = getMedia($picPath); shuffle($slideshow);
	echo "<audio id='slideShowPlayer' autoplay hidden></audio>
	<img id='slideshow'><script type='text/javascript'>
	//document.getElementById('content').onclick = function(){window.timer = setTimeout(function(){enableFullScreen();},transitionTime); disableFullScreen();};
	var picturesCount = 0;
	var pictures = new Array();";
	$count = 0;
	foreach ($slideshow as $picture) {
		echo "pictures[".$count++."] = '".$picPath."/".$picture."';
		";
		}
		echo "audioArray = new Array(); audioCount = 0;";
	$audioArray = array_flatten(recursiveMap('media/Music'));
	shuffle($audioArray);	
	for($i = 0; $i < count($audioArray); $i++) echo "audioArray[".$i."] = '".$audioArray[$i]."';
		";

		echo "document.getElementById('slideShowPlayer').addEventListener('ended',function(){
audioCount += 1; if(audioCount>=audioArray.length) audioCount = 0;
document.getElementById('slideShowPlayer').src = audioArray[audioCount];
document.getElementById('slideShowPlayer').play();
});
document.getElementById('slideShowPlayer').src = audioArray[audioCount];
document.getElementById('slideShowPlayer').play();

</script><script type='text/javascript' src='".
(($severeMode)?"js/fullscreenSevere.js":"js/fullscreen.js")."'></script>";
	}


$pathString = "index.php"; $mediaCarry = 0;
if($severeMode) $pathString .= "?path=".$path.((isset($_GET['recurse']))?"&recurse=".$_GET['recurse']:"");
else {
//$autoReturnFile = file_get_contents($path."/.autoReturn");
//if($autoReturnFile != false) $pathString .= "?path=".$autoReturnFile[0].'\'';
//else

//if(isset($_GET['mediaCarry'])&&$_GET['mediaCarry']>0) $mediaCarry = $_GET['mediaCarry'];
$mediaCarry = ((isset($_GET['mediaCarry']))?$_GET['mediaCarry']:0);
$pathString .= "?path=".$path.((isset($_GET['recurse']))?"&recurse=".$_GET['recurse']:"")."&mediaCarry=".$mediaCarry."&timerCarry=+getScreensaverTimeRemaining()";
if($disableAutoplay&&file_exists($path."/.autoplayOnFinish")&&strlen($file)>0) $pathString .= "+'&sequentialOverride=".$file."'";} 
if($severeMode) $pathString .= "&exclude=".$file."'";
if(($mediaCarry+1)==$recurseCount) $pathString = "index.php?path=".$path;
//echo $pathString;
//echo $recurseCount;

echo '<script type="text/javascript">document.getElementById("player").addEventListener(\'ended\',function(){'.(($severeMode)?"setTimeout(function(){":"").'window.open(\''.$pathString.'\',\'_self\');'.(($severeMode)?"},2000);":"").'},false);</script>';

echo '</div>';
//echo $path."/".$_GET['recurse'];
if(isset($_GET['recurse'])) $thumbs = getThumbs($path."/".$_GET['recurse']);
//echo print_r($thumbs);

if (!isset($_GET['recurse'])
||true
) {echo '<div id="buttonDiv"'.(($path=="media")?" style='margin-top: 1%;'":"").'>';

//Offsetter
$mediaOffset = 0; $colourCount = 0;
if(isset($_GET['mediaOffset'])) {$mediaOffset = $_GET['mediaOffset'];
if($mediaOffset >= $mediaCount) $mediaOffset = 0;}

if ($mediaCount > 0)
for ($i = $mediaOffset; (($i < $mediaCount) && (($mediaCount == 4)?(true):($i < $mediaOffset + 3))); $i++) {echo '<div class="button'.((!$severeMode&&strpos($path."/".$file,$media[$i])!==false&&$enablePopup)?" buttonActivated":"").'" style="background: '.$colours[$colourCount].';" onclick="navigate(\''.((isset($_GET['recurse']))?$_GET['recurse']."/":"").$media[$i].'&mediaOffset='.$mediaOffset.'\');"><div class="buttonBack1"><div class="thumbnail" style="'.((/*$path != "media"||*/isset($_GET['recurse']))?"background-repeat: repeat; ":"").'background-colour: '.$colours[$colourCount++].'; background-image: url(\''.$path."/".((isset($_GET['recurse']))?$_GET['recurse']."/":"").$thumbs[$i].'\');'.(($path == "media"&&!isset($_GET['recurse']))?" border: none; width: 70%; background-size: contain; top: 8%;":"").'"></div>'.((strpos($file,'/') !== false && !isset($_GET['recurse']))?"<span class='buttonMoreText'>(More)</span>":"").'<span class="buttonText"'.(($path == "media"&&!isset($_GET['recurse']))?" style='top: 61%;'":"").'>'
	.($path == "media" ? "My<br/>":"") //Adds 'My' to front
	.trimExt($media[$i],false).'</span></div><div class="buttonBack2"></div><div class="buttonBack3"></div></div>';
}
$moreButton = "";
$tempPathString = $path.((isset($_GET['recurse']))?$_GET['recurse']:"");
if (strpos($tempPathString,"Music") > 0) $moreButton = "./admin/more/Music.png";
if (strpos($tempPathString,"Movies") > 0) $moreButton = "./admin/more/Old Movies.png";
if (strpos($tempPathString,"Photos") > 0) $moreButton = "./admin/more/Photos.png";
if (strpos($tempPathString,"Family") > 0) $moreButton = "./admin/more/Family Messages.png";

if($mediaCount > 4) echo '<div class="button" style="background: '.$colours[$colourCount].';" onclick="window.open(\'index.php?path='.$path.((isset($_GET['recurse']))?$_GET['recurse']:"").'&mediaOffset='.($mediaOffset+3).'\',\'_self\');"><div class="buttonBack1"><div class="thumbnail" style="border: none; background-colour: '.$colours[$colourCount++].'; background-image: url(\''.$moreButton.'\');"></div><span class="buttonText">More</br>'.array_pop(explode("/",$path)).'</span></div><div class="buttonBack2"></div><div class="buttonBack3"></div></div>';

//if ($mediaCount == 1) echo '<div class="button"></div>';
//if ($i == 2 || $i == 1 && $mediaCount == 1
//echo ($path != "media");) echo '<div class="button" onclick="window.open(\'index.php\',\'_self\');">Stop</div>';

echo '</div>';}
if ($file != "" || $path !="media") echo '<div id="stopButtonText" >'.(($file != "")?'STOP':'BACK').'</div>
<div id="stopButton" onclick="window.open(\'index.php\',\'_self\');"></div>';
echo '<div id="buttonDisabler"></div><div id="altPauseButton" onclick="'.(($severeMode)?"window.open('index.php','_self');":"toggleFullScreen();").'" style="'.(($file != ""&&$severeMode)?"display: block;":"").'"></div>';
echo '<div id="pauseButtonReturner" onclick="window.open(\'index.php\',\'_self\');" style="'.(($file != "" && $severeMode
//&& !($extension == strtolower("avi") || $extension == strtolower("mov") || $extension == strtolower("wmv") || $extension == strtolower("mpg") || $extension == strtolower("mp4"))
)?"display: block;":"").'"></div>';
?>
</body>
</html>
