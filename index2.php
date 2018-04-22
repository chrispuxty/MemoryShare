<?php
$cfgUpdateSevereMode = isset($_GET['severeMode']); $cfgUpdateDisableAutoplay = isset($_GET['disableAutoplay']);
if($cfgUpdateSevereMode||isset($cfgUpdateDisableAutoplay))
{
	$configFile = file("config.php",FILE_IGNORE_NEW_LINES||FILE_SKIP_EMPTY_LINES);
	foreach($configFile as &$line){
	if($cfgUpdateSevereMode&&strpos($line,"severeMode") === 1)
	$line = "$"."severeMode = ".(($_GET['severeMode']=="true")?"true":"false").";\n";
	//echo strpos($line,"severeMode");
	if($cfgUpdateDisableAutoplay&&strpos($line,"disableAutoplay") === 1)
	$line = "$"."disableAutoplay = ".(($_GET['disableAutoplay']=="true")?"true":"false").";\n";
	}
	//echo print_r($configFile);
	file_put_contents("config.php",$configFile);
	}


include "config.php";
//echo $_SERVER['SCRIPT_FILENAME']."<br />".$_SERVER['REMOTE_ADDR']; //TESTING - COMMENT OUT
//if ($_SERVER['REMOTE_ADDR'] == "::1" || $_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER['REMOTE_ADDR'] == "localhost") $localPath = "file:///".$_SERVER['SCRIPT_FILENAME']."/../"; else
$localPath = ""; //Broken by Chrome same-origin policy
//echo $_SERVER['REMOTE_ADDR'];
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
<script type="text/javascript">window.onload = function(){triggerClick(); document.onmousemove = resetScreenSaver(); resetScreenSaver();};
function triggerClick(){document.getElementById("clicker").play();}
function navigate(dest)
{
	//alert(dest);
	var destinations = Array();
	var matchedOnClickPlays = Array();
	<?php
$colours = array(
0 => "#1B75BB", //#2C24C9
1 => "#F16521", //#FF5500
2 => "#38B449", //#0D992D
3 => "#EC008B"  //#ED2D45
);

//echo "lol";

function getMedia($dir) {
	$media = null;
	$a = opendir($dir);
	$x = 0;
	//if (!$dir) echo "DIR FAULT:<br>".$dir; else
	while (false !== ($temp = readdir($a))) {
		if (substr($temp, 0, 1) != ".") $media[$x++] = $temp;
	}
	sort($media);
	if (is_readable($dir."/.customOrder")) $media = file($dir."/.customOrder", FILE_IGNORE_NEW_LINES);
	return $media;}
	
function trimExt($filename,$returnExtensionInstead) {
	$temp = explode(".",$filename);
	if (count($temp) >= 2) {
	$x = array_pop($temp);
	$y = implode($temp);
	if ($returnExtensionInstead == true) $z = $x; else $z = $y;}
	else $z = $filename;
	return $z;}

function preloadRecurse($dir){
	$media = getMedia($dir);
	if ($media != null) foreach ($media as $clip)
	if (is_dir($dir."/".$clip)) preloadRecurse($dir."/".$clip);
	else echo "<video hidden preload><source src='".$dir."/".$clip."' type='video/mp4'></video>";
	}
	
function getThumbs($dir){$retValue = Array();
	if (is_dir($dir."/.thumbs")) {$media = getMedia($dir."/.thumbs");
	foreach ($media as $clip) array_push($retValue,".thumbs/".$clip);}
	else {$media = getMedia($dir);
		foreach ($media as $clip) if (trimExt($clip,true) == "kanvar")
		{	$clipFile = file_get_contents($dir."/".$clip);
			$clipDir = getMedia($dir."/".$clipFile);
			array_push($retValue,$clipFile."/".$clipDir[0]);}
		}
	return $retValue;
	}
	
function recursiveAutoplay($path){$retValue = "";
	if(is_dir($path) && file_exists($path."/.recursiveAutoplay")) {
	$media = getMedia($path);
	$mediaCount = count($media);
	if ($mediaCount-- > 1) shuffle($media);
	while($retValue == "" && $mediaCount > -1) $retValue = recursiveAutoplay($path."/".$media[$mediaCount--]);
		}
	else if (file_exists($path) && !is_dir($path)) $retValue = $path;
	return $retValue;}
	
	function recursiveAutoplayWithExclude($path,$exclude){$retValue = "";
	if(is_dir($path) && file_exists($path."/.recursiveAutoplay")) {
	$media = getMedia($path);
	$mediaCount = count($media);
	if ($mediaCount-- > 1) shuffle($media);
	while($retValue == "" && $mediaCount > -1) $retValue = recursiveAutoplay($path."/".$media[$mediaCount--]);
		}
	else if (file_exists($path) && !is_dir($path)) $retValue = $path;
	if ($retValue == $exclude) $retValue = recursiveAutoplay($path,$exclude);
	return $retValue;}
	
function sequentialAutoplay($path){$retValue = ""; $fileStr = ""; $oldID = -1;
if (!is_dir($path)) {$temp = explode('/',$path); $fileStr = array_pop($temp); 
$path = ""; foreach ($temp as $x) $path .= "/" . $x; $path = substr($path,1);
	if(is_dir($path) && is_readable($path."/.sequentialAutoplay")) {
	$media = getMedia($path); //echo print_r($media);
	$mediaCount = count($media);
	for($i = 0; $i < $mediaCount; $i++) if($fileStr == $media[$i]) {$oldID = $i;}}
	if($oldID==-1) $oldID = file_get_contents($path."/.sequentialAutoplay");
	$sequentialAutoplayFile = fopen($path."/.sequentialAutoplay","w");
	$ID = -1;

	if(isset($oldID)) $ID = $oldID;

	if (++$ID > $mediaCount - 1) $ID = 0;
	$retValue = $media[$ID];
	fwrite($sequentialAutoplayFile,$ID);
	fclose($sequentialAutoplayFile);}
	return $retValue;}
	
function resetSequentialAutoplay($path){
	if (is_dir($path)){
	$media = getMedia($path);
	if (count($media) > 0) foreach ($media as $item) resetSequentialAutoplay($path."/".$item);
	if (file_exists($path."/.sequentialAutoplay")) {
	$sequentialAutoplayFile = fopen($path."/.sequentialAutoplay","w");
	fwrite($sequentialAutoplayFile,count($media) - 1);
	fclose($sequentialAutoplayFile);
	}}}
	
function recurseToThumb($path){
	$clipString = explode('/',$path); $clip = array_pop($clipString);
	$path = ""; foreach ($clipString as $x) $path .= "/" . $x;
	$path = str_replace("//","/",$path); 
	$path = substr($path,1);
	$media = getMedia($path);
	$count = 0; $id = null;
	//echo count($media);
	foreach ($media as $file) if ($file == $clip) $id = $count; else $count++;
	$thumbs = getThumbs($path);
	return $path . "/" . $thumbs[$id];}

//echo "lol";

//PREPARATION CODE
if (isset($_GET['path'])) $path = $_GET['path']; else {$path = "media"; resetSequentialAutoplay($path);}
$file = false; $autoplay = false;
if (!is_dir($path)) {$temp = explode('/',$path); $file = array_pop($temp); 
$path = ""; foreach ($temp as $x) $path .= "/" . $x;}
if (substr($path,0,1) == "/") $path = substr($path,1,strlen($path));
if(!$file&&(!$disableAutoplay||isset($_GET['sequentialOverride']))) $file = sequentialAutoplay($path."/".$_GET['sequentialOverride']);
if (isset($_GET['recurse'])) $file = recursiveAutoplayWithExclude($path."/".$_GET['recurse'],$_GET['exclude']);
if($file == ""&&!$disableAutoplay) $file = recursiveAutoplay($path);
if($file == "") $file = false; else {$array = explode($path,$file); $file = array_pop($array); $autoplay = true;}
if (is_readable($path."/.autoplay")&&!isset($_GET['recurse'])) $autoplayFile = file($path."/.autoplay", FILE_IGNORE_NEW_LINES);
if (isset($autoplayFile)) $autoplayFile = $autoplayFile[0];

//echo $path . "<br>";
//echo $file;
$media = getMedia($path);
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
	document.getElementById('clicker').addEventListener('ended',function(){window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."'+dest,'_self');},false);
	}
else window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."'+dest,'_self');

	}</script>";} 
	else echo "window.open('index.php?path=".$path.(($severeMode)?"&recurse=":"/")."/'+dest,'_self');}</script>";
//$patientID = 24601;
//echo "insert into observations (patient, path) values (" . $patientID . ", " . $path . "/" . $file . ")";
mysqli_query($db,"insert into observations (patient, path) values (" . $patientID . ", '" . addslashes($path . "/" . (($autoplay)?" [AUTOPLAY] ":""). $file) ."')") or die(mysqli_error($db));
//RENDERING CODE
if($severeMode && strlen($file) > 0) echo "<script type='text/javascript'>setTimeout(function(){window.onclick = function(){window.open('index.php','_self');}},500);</script>";
if($path == "media") echo "<script type='text/javascript'>var screensaver;
function resetScreenSaver(){clearTimeout(screensaver); //document.getElementById(\"clicker\").play();
screensaver = setTimeout(function(){window.open('preload.php','_self');
//document.getElementById(\"clicker\").pause(); //document.getElementById(\"clicker\").load();
}, 600000);}
resetScreenSaver();
//window.onmousemove = resetScreenSaver();
</script>";

$enablePopup = false;
$thumbs = getThumbs($path);
 if ($path == "media" && $file == ""){echo '<style type="text/css">body {font-size: 35pt; letter-spacing: 2px;}
.button {font-size: 35pt;}
</style>';
echo '</head> <body>';
echo '<div id="topBar"><span id="date_time"></span><script type="text/javascript">window.onload = date_time("date_time");</script>';
echo '</div>';} else echo '</head> <body>';
//echo $path."####".$file;
//if ($file != "") echo $path . "/" . recurseToThumb($path."/".$file);
echo '<audio id="clicker" hidden preload src="click.mp3"></audio><div id="content"';
if ($path != "media" || $file != "") echo ' style="height: 52%; margin-bottom: 0.5%;"'; echo ">";
//if ($path == "media") {preloadRecurse("media/Videos"); preloadRecurse("media/Talk");}
$extension = trimExt($file,true);
echo ((is_readable($path."/.autoplay.mp3")&&strlen($extension) < 1)?"<audio autoplay src='".$path."/.autoplay.mp3'></audio>":"");
if ($extension == strtolower("mp3") || $extension == strtolower("wav") || $extension == strtolower("ogg") || $extension == strtolower("flac") || $extension == strtolower("wma"))
{echo "<audio id='player' hidden autoplay>
<source  src='".$localPath.$path."/".$file."' type='audio/mp3'>
</audio>"
.(($severeMode)?"
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
<script type='text/javascript' src='".
(($severeMode)?"js/fullscreenSevere.js":"js/fullscreen.js")."'></script>";
//preloadRecurse("media/Videos");
//preloadRecurse("media/Talk");
}
elseif ($extension == strtolower("kanvar")) {
	$picFolder = file_get_contents($path."/".$file);
	$picPath = dirname($path."/".$file)."/".$picFolder;
	$slideshow = getMedia($picPath);
	echo "<img id='slideshow'><script type='text/javascript'>
	var picturesCount = 0;
	var pictures = new Array();";
	$count = 0;
	foreach ($slideshow as $picture) {
		echo "pictures[".$count++."] = '".$picPath."/".$picture."';
		";
		}
		echo "</script><script type='text/javascript' src='".
(($severeMode)?"js/fullscreenSevere.js":"js/fullscreen.js")."'></script>";
	}


$pathString = "index.php";
if($severeMode) $pathString .= (isset($_GET['recurse']))?"?recurse=".$_GET['recurse']:"";
else {
$autoReturnFile = file_get_contents($path."/.autoReturn");
if($autoReturnFile != false) $pathString .= "?path=".$autoReturnFile[0]; else $pathString .= "?path=".$path;
if($disableAutoplay&&file_exists($path."/.autoplayOnFinish")&&strlen($file)>0) $pathString .= "&sequentialOverride=".$file;} 


echo '<script type="text/javascript">document.getElementById("player").addEventListener(\'ended\',function(){window.open(\''.$pathString.'\',\'_self\');},false);</script>';

echo '</div>';



if (!isset($_GET['recurse'])) {echo '<div id="buttonDiv"'.(($path=="media")?" style='margin-top: 1%;'":"").'>';
$i = 0;
if (count($media) > 0)
foreach ($media as $source) {echo '<div class="button'.((!$severeMode&&strpos($path."/".$file,$media[$i])!==false&&$enablePopup)?" buttonActivated":"").'" style="background: '.$colours[$i].';" onclick="navigate(\''.$media[$i].'\');"><div class="buttonBack1"><div class="thumbnail" style="'.(($path != "media")?"background-repeat: repeat; ":"").'background-colour: '.$colours[$i].'; background-image: url(\''.$path."/".$thumbs[$i].'\');'.(($path == "media")?" border: none; width: 70%; background-size: contain; top: 1%;":"").'"></div>'.((strpos($file,'/') !== false)?"<span class='buttonMoreText'>(More)</span>":"").'<span class="buttonText"'.(($path == "media")?" style='top: 49%;'":"").'>'.trimExt($media[$i++],false).'</span></div><div class="buttonBack2"></div><div class="buttonBack3"></div></div>';
//if (count($media) == 1) echo '<div class="button"></div>';
//if ($i == 2 || $i == 1 && count($media) == 1) echo '<div class="button" onclick="window.open(\'index.php\',\'_self\');">Stop</div>';
} 
echo '</div>';}
//echo ($path != "media");
if ($file != "" || $path !="media") echo '<div id="stopButton" onclick="window.open(\'index.php\',\'_self\');"></div>';
?>
</body>
</html>
