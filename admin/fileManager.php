<?php include "../config.php";
	  include "../functions.php";
$pathStub = "../media";
$path = ((isset($_GET['path']))?$_GET['path']:$pathStub);
$temp = explodePath($path);
$path = $temp[0]; $file = $temp[1];
$media = getMedia($path);
$mediaCount = count($media);
//$roomForNewItems =($mediaCount < 4);
$thumbs = getThumbs($path);
$uploaded = false;

function delTree($dir) {if(is_dir($dir)){
	//echo $dir."</br>";
    $files = array_diff(scandir($dir), array('.','..')); 
   	foreach ($files as $file) {(is_dir($dir."/".$file)) ? delTree($dir."/".$file) : unlink($dir."/".$file); }}
    return ((!is_dir($dir))?unlink($dir):rmdir($dir)); 
} 

//Deletion Handler
if(isset($_GET['delete'])&&$_GET['delete']!=""&&$path!=$pathStub) {$x = -1;
for($i = 0; $i < count($thumbs); $i++) if(strpos($path."/".$_GET['delete'],$media[$i]) !== false) $x = $i;
//echo print_r($media);
//echo $path."/".$thumbs[$x];
		if ($x != -1) unlink($path."/".$thumbs[$x]); //Deletes thumbnail
		$delString = $path.$_GET['delete'];
		if(strtolower(trimExt($_GET['delete'],true)) == "kanvar") {
			$altDelString = $path."/".file_get_contents($path."/".$_GET['delete']);
			//echo $altDelString;
			delTree($altDelString);}
	delTree($delString);
	 $media = getMedia($path);
	 $mediaCount = count($media);
 $thumbs = getThumbs($path);
//$roomForNewItems =($mediaCount < 4);
}

//Autoplay Setting Handler
if(isset($_POST['submitAutoplay'])){
if(!file_exists($path."/.recursiveAutoplay")) {if(isset($_POST['recursiveAutoplay'])) touch($path."/.recursiveAutoplay");}
else if(!isset($_POST['recursiveAutoplay'])) unlink($path."/.recursiveAutoplay"); 

if(!file_exists($path."/.sequentialAutoplay")) {if(isset($_POST['sequentialAutoplay'])) touch($path."/.sequentialAutoplay");} 
else if(!isset($_POST['sequentialAutoplay'])) unlink($path."/.sequentialAutoplay");}

//Upload Handler
//echo print_r($_POST);

//if(isset($_POST['submit'])) echo "lol";

if(isset($_POST['submit'])//&&$roomForNewItems
 &&isset($_POST['name'])&&$_POST['name']!=""
 &&isset($_POST['action'])&&$_POST['action'] != "none")
 {//echo print_r($_FILES);
 $file_okay = true;
 $uploadName = $_POST['name'];

if(strlen($_POST['name2'])>0) $uploadName .=" ".$_POST['name2'];
if(strlen($_POST['name3'])>0) $uploadName .=" ".$_POST['name3'];
$uploadName = ucwords($uploadName);

//echo $uploadName;

	if ($_POST['action'] == "mkdir") {mkdir($path."/".$uploadName); mkdir($path."/".$uploadName."/.thumbs");
	//THIS IS A HACK, SCREW BEST PRACTICE >=(
	if(file_exists($path."/.ThisIsTheMusicFolder")) {touch($path."/".$uploadName."/.sequentialAutoplay"); touch($path."/".$uploadName."/.autoplayOnFinish");}

	
	//file_put_contents($path."/".$uploadName."/.thumbs/primer"," ");
	//unlink($path."/".$uploadName."/.thumbs/primer");
	}
	else if ($_POST['action']=="upload"){//echo "upload";
	
$allowedExts = array("gif", "jpeg", "jpg", "png","mp4","mp3","zip");
$file_okay = false;
$temp = explode(".", $_FILES["filename"]["name"]);
$extension = strtolower(end($temp));
if (in_array($extension, $allowedExts)) 
  {
 //if ($_FILES["filename"]["error"] > 0) echo "Return Code: " . $_FILES["filename"]["error"] . "<br>";  else
  {
//echo "Upload: " . $_FILES["filename"]["name"] . "<br>";
   		//echo "Type: " . $_FILES["filename"]["type"] . "<br>";
    	//echo "Size: " . ($_FILES["filename"]["size"] / 1024) . " kB<br>";
   		//echo "Temp file: " . $_FILES["filename"]["tmp_name"] . "<br>";
		//echo "Return Code: " . $_FILES["filename"]["error"] . "<br>"; 
    if (file_exists($path."/" . $uploadName)) echo $uploadName . " already exists. ";
    else{
		if($extension == "zip") {
			mkdir($path."/.".$uploadName);
			shell_exec("unzip ".$_FILES["filename"]["tmp_name"]." -d ".escapeshellarg($path."/.".$uploadName));
			/*$zip = new ZipArchive();
			//echo $_FILES["filename"]["tmp_name"];
			$zip->open($_FILES["filename"]["tmp_name"]);
			$zip->extractTo($path."/.".$uploadName);
			$zip->close();*/
			file_put_contents($path."/".$uploadName.".kanvar",".".$uploadName);
			}
		else {move_uploaded_file($_FILES["filename"]["tmp_name"],
      $path."/" .trimExt($uploadName,false).".".trimExt($_FILES["filename"]["name"],true));} $file_okay = true;
      //echo "Stored in: " . $path."/" . $uploadName ."/" . $_FILES["filename"]["name"];
	  } $uploaded = true;
    }
  }
else echo "Invalid file";
	}

//Thumbnail Upload  
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["thumbnail"]["name"]);
$extension = strtolower(end($temp));
if (in_array($extension, $allowedExts)&&$file_okay){
	
 // if ($_FILES["thumb"]["error"] > 0) echo "Return Code: " . $_FILES["thumb"]["error"] . "<br>";  else
  {	//echo "Upload: " . $_FILES["thumbnail"]["name"] . "<br>";
   		//echo "Type: " . $_FILES["thumbnail"]["type"] . "<br>";
    	//echo "Size: " . ($_FILES["thumbnail"]["size"] / 1024) . " kB<br>";
   		//echo "Temp file: " . $_FILES["thumbnail"]["tmp_name"] . "<br>";
    if (file_exists($path."/thumbs/" . $uploadName)) $uploadName . " already exists. ";
    else{ move_uploaded_file($_FILES["thumbnail"]["tmp_name"],
      $path."/.thumbs/".trimExt($uploadName,false).".".trimExt($_FILES["thumbnail"]["name"],true));
      //echo "Stored in: " . $path."/.thumbs/".trimExt($uploadName,false).".".trimExt($_FILES["thumbnail"]["name"],true);
	  }
    }
  }
else echo "Invalid file";
	
	$media = getMedia($path);
	$mediaCount = count($media);
//$roomForNewItems =($mediaCount < 4);
$thumbs = getThumbs($path);
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Secondary Interface - File Manager</title>
<link rel="stylesheet" type="text/css" href="../css/main.css"/>
<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
var mode = 0;
var mp3Array = ['<?php if($path==$pathStub) echo "./sec_int_audio/click_item_navigate_into.mp3"; else echo "./sec_int_audio/click_item_navigate_into_or_change_del_mode.mp3";?>',
'./sec_int_audio/click_to_delete.mp3',
'./sec_int_audio/Intro to Choosing File or Folder.mp3',
'./sec_int_audio/AFTER_CHOOSING_FILE_OPTION.mp3',
'./sec_int_audio/Label File.mp3',
'./sec_int_audio/Thumbnail for folder.mp3',
'./sec_int_audio/Label Folder.mp3',
'./sec_int_audio/Upload Successful.mp3'];
var textArray = ["Click on an item to navigate into it, or change to Delete Mode to delete an item</br></br></br></br>",
"Click on an item to delete it (you will be asked for confirmation)<br>or ................................. to stop deleting<p style='color: #FF0000;'>Be careful, as deletion cannot be undone</p>"];
setTimeout(function(){document.getElementById("audioPlayer").src = mp3Array[<?php if($uploaded) echo "7"; else echo "0";?>]; document.getElementById("audioPlayer").play();},500);

function updateText(value){mode = value;
switch(mode){
case 0: $("#navButton").css("display","none"); $("#delButton").css("display","inline"); break;
case 1: $("#delButton").css("display","none"); 
$("#navButton").css("display","block"); break;}
document.getElementById("audioPlayer").pause();
document.getElementById("audioPlayer").src = mp3Array[value];
document.getElementById("audioPlayer").play();
$("#instructions").html(textArray[value]);}

function action(path,navFlag){
	switch(mode){
		case 0: if(navFlag) window.open("fileManager.php?path=<?php echo $path."/"?>"+path,'_self'); 
		else {splitText(name.substr(name.lastIndexOf("/"),name.length)); document.getElementById("editPath").value = path; $("#alterationBox").css('display','block');
		$('#autoplaySettingsBox').css('display','none'); $('#instructionBox').css('display','none');}
		//else alert("This is an individual clip of media, not a collection of different clips.  You therefore cannot navigate into it.");
		break; //Navigation
		case 1: if(window.confirm("Are you sure you wish to delete "+path+"?"))
		window.open("fileManager.php?path=<?php echo $path."/"?>&delete="+path,'_self'); break; //Deletion
		
		  }
	}
function advanceField(currentField,nextField){
if (document.activeElement == currentField && currentField.maxLength == currentField.value.length) 
document.getElementById(nextField).focus();}

function splitText(name){var break1 = name.substr(0,14).lastIndexOf(" "); var break2 = name.substr(break1,14).lastIndexOf(" ");
document.getElementById("editName1").value = name.substr(0,break1+1);
document.getElementById("editName2").value = name.substr(break1,break2+1);
document.getElementById("editName3").value = name.substr(break2,name.length);
}

</script>
<style>
#instructions {color: #cccc00; font-size: 1.2em;}
<?php if(!isset($_GET['path'])) echo ".buttonText {font-size: 30pt;}"; ?>
</style>
</head>

<body>
<audio id="audioPlayer" hidden></audio>
<div id="content" style="height: 54.75%;"><br/><br/>
<br /><br/>
<div id="instructionBox"><span id="instructions">Click on an item to navigate into it<?php
if($path!=$pathStub) echo ", or change to Delete Mode to delete an item</br></br></br></br>";?></span><br/><br/>
<input type="button" id="navButton" value="Return to Navigation Mode" onclick="updateText(0);" style="display: none;"/>
<input type="button" id="delButton" value="Change to Delete Mode" onclick="updateText(1);" style="background-color: #FF0000; color: #FFFFFF; <?php if(!isset($_GET["path"]))  echo "display: none;";?>"/><br/></br>
</div>
<form action="fileManager.php?path=<?php echo $path;?>" method="post">
<div id="autoplaySettingsBox"<?php if($path==$pathStub||true) echo ' style="display: none;'?>">
<input name="recursiveAutoplay" value="recursive" type="checkbox"
<?php if(file_exists($path."/.recursiveAutoplay")) echo "checked"; ?>/>Recursive Autoplay</br></br>
<input name="sequentialAutoplay" value="sequential" type="checkbox"
<?php if(file_exists($path."/.sequentialAutoplay")) echo "checked"; ?>/>Sequential Autoplay</br></br>
<input type="submit" name="submitAutoplay" value="Update Autoplay Settings For This Folder">
</form></div>
<div id="editBox" style="display: none; position: absolute; border: none; left: 20%; top: 58px; width: 60%;">
<form action="fileManager.php?path=<?php echo $path;?>" method="post" enctype="multipart/form-data">
<input type="button" id="cancelButton" style="background-color: #FF0000; color: #FFFFFF; font-size: 1em; position: absolute; top: 25px; right: 51px;"  value="X" onclick="$('#editBox').css('display','none'); $('#autoplaySettingsBox').css('display','none'); $('#instructionBox').css('display','block');">
<input type="radio" name="action" value="none" checked style="display: none;"/>
<table style="vertical-align: middle; line-height: 90px;">
<colgroup><col style="width: 100px;"><col style="width: 1000px;"></colgroup>
<tr><td>1</td><td>
<input type="radio" name="action" value="upload" id="fileUploadBtn" onchange="$('#fileUploadBox').css('display',(this.checked)?'inline':'none');
$('#fileUploadBox').css('-webkit-animation',(this.checked)?'blinker 1.5s infinite':'none');
" onclick="
document.getElementById('audioPlayer').pause();
document.getElementById('audioPlayer').src = mp3Array[3];
document.getElementById('audioPlayer').play();
"/>Upload New File<span id="fileUploadBox" style="display: none; -webkit-animation: -webkit-animation: blinker 1.5s infinite;" onclick="$('#fileUploadBox').css('-webkit-animation','none');">: <input type="file" name="filename" id="file" style="margin-left: 335px;"/></span></br>
<input type="radio" name="action" onchange="$('#fileUploadBox').css('display',(!this.checked)?'inline':'none');"value="mkdir"  id="mkdirBtn" onclick="
document.getElementById('audioPlayer').pause();
document.getElementById('audioPlayer').src = mp3Array[5];
document.getElementById('audioPlayer').play();
"/>Create New Folder
</td></tr><tr><td>2</td><td style="padding-left: 125px;">
Thumbnail: <input type="file" name="thumbnail" required id="thumb" onchange="
var value = ((document.getElementById('audioPlayer').src == mp3Array[3])?4:-1);
value = ((document.getElementById('audioPlayer').src == mp3Array[5] && value == -1)?6:value);
document.getElementById('audioPlayer').pause();
document.getElementById('audioPlayer').src = mp3Array[value];
document.getElementById('audioPlayer').play();
"/>
</td></tr><tr><td>3</td><td style="padding-bottom: 30px;">
<input id="name1" type="text" name="name" placeholder="Name - Line 1" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$" onkeyup="advanceField(this,'name2');" required/>
<input id="name2" type="text" name="name2" placeholder="Line 2" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$" onkeyup="advanceField(this,'name3');"/>
<input id="name3" type="text" name="name3" placeholder="Line 3" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$"/>
<input type="submit" name="submit" value="Submit" />
</td></tr></table>
</form>
</div>
<div id="alterationBox" style="display: none; position: absolute; border: none; left: 20%; top: 58px; width: 60%;">
<form action="fileManager.php?path=<?php echo $path;?>" method="post" enctype="multipart/form-data">
<input type="button" id="cancelButton" value="X" onclick="$('#alterationBox').css('display','none'); $('#autoplaySettingsBox').css('display','none'); $('#instructionBox').css('display','block');">
<input type="radio" name="action" value="none" checked style="display: none;"/>
<table style="vertical-align: middle; line-height: 90px;">
<colgroup><col style="width: 100px;"><col style="width: 1000px;"></colgroup>
<tr><td>Thumbnail</td><td style="padding-left: 125px;">
New Thumbnail: <input type="file" name="thumbnail" id="thumb" onchange="
var value = ((document.getElementById('audioPlayer').src == mp3Array[3])?4:-1);
value = ((document.getElementById('audioPlayer').src == mp3Array[5] && value == -1)?6:value);
document.getElementById('audioPlayer').pause();
document.getElementById('audioPlayer').src = mp3Array[value];
document.getElementById('audioPlayer').play();
"/>
</td></tr><tr><td>2</td><td style="padding-bottom: 30px;">
<input id="editPath" type="hidden" name="path">
<input id="editName1" type="text" name="name" placeholder="Name - Line 1" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$" onkeyup="advanceField(this,'editName2');" required/>
<input id="editName2" type="text" name="name2" placeholder="Line 2" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$" onkeyup="advanceField(this,'editName3');"/>
<input id="editName3" type="text" name="name3" placeholder="Line 3" size="14" maxlength="13" pattern="^[a-zA-Z0-9_\x20]*$"/>
<input type="submit" name="submit" value="Submit" />
</td></tr></table>
</form>
</div>
</div>
<div id="buttonDiv">
<?php
//Offsetter
$mediaOffset = 0; $colourCount = 0;
if(isset($_GET['mediaOffset'])) {$mediaOffset = $_GET['mediaOffset'];
if($mediaOffset >= $mediaCount + 1) $mediaOffset = 0;}

for ($i = $mediaOffset; (($i < $mediaCount) && (($path==$pathStub)?(true):($i < $mediaOffset + 3))); $i++) {echo '<div 
class="button'.((strpos($path."/".$file,$media[$i])!==false)?" 
buttonActivated":"").'" style="background: '.$colours[$colourCount].';'.((is_dir($path."/".$media[$i]))?'':' opacity: 0.5;').'" onclick="action(\''.$media[$i].'\''.((is_dir($path."/".$media[$i]))?',true':',false').');">
<div class="buttonBack1">
<div class="thumbnail" style="'.(($path != "media")?"background-repeat: repeat-x; ":"").'background-colour: '.$colours[$colourCount++].';
background-image: url(\''.$path."/".$thumbs[$i].'\');
'.(($path == $pathStub)?" border: none; width: 70%; background-size: contain; top: 1%;":"").'
"></div>'.((strpos($file,'/') !== false)?"
<span class='buttonMoreText'>(More)</span>":"").'
<span class="buttonText"'.(($path == $pathStub)?" style='top: 49%;'":"").'>'.trimExt($media[$i],false).'</span>
</div>
<div class="buttonBack2"></div>
<div class="buttonBack3"></div>
</div>';}

if($mediaCount - $mediaOffset < (($mediaCount > 3)?3:4) 
//$roomForNewItems
) echo '<div class="button" style="background: '.$colours[$colourCount++].';" 
onclick="document.getElementById(\'audioPlayer\').pause();
document.getElementById(\'audioPlayer\').src = mp3Array[2];
document.getElementById(\'audioPlayer\').play();
$(\'#editBox\').css(\'display\',\'block\'); $(\'#autoplaySettingsBox\').css(\'display\',\'none\'); $(\'#instructionBox\').css(\'display\',\'none\');
$(\'#fileUploadBtn\').delay(2569).animate({opacity: 0},250,function(){$(\'#fileUploadBtn\').animate({opacity: 1},250);});
$(\'#mkdirBtn\').delay(3737).animate({opacity: 0},250,function(){$(\'#mkdirBtn\').animate({opacity: 1},250);});


">
<div class="buttonBack1"><div class="thumbnail"></div>
<span class="buttonText">New...</span></div>
<div class="buttonBack2"></div>
<div class="buttonBack3"></div>
</div>';

if($mediaCount > 3 && $path!=$pathStub) echo '<div class="button" style="background: '.$colours[$colourCount].';" onclick="window.open(\'fileManager.php?path='.$path.'&mediaOffset='.($mediaOffset+3).'\',\'_self\');"><div class="buttonBack1"><div class="thumbnail" style="background-colour: '.$colours[$colourCount++].'; background-image: url(\'./More.png\');"></div><span class="buttonText">More</br>'.array_pop(explode("/",$path)).'</span></div><div class="buttonBack2"></div><div class="buttonBack3"></div></div>';


?>
</div>
<div id="stopButtonText" onclick="window.open('<?php if(isset($_GET['path'])) echo "fileManager.php";
else echo "index.html";
?>','_self');">Back</div>
<div id="stopButton" onclick="window.open('<?php if(isset($_GET['path'])) echo "fileManager.php";
else echo "index.html";
?>','_self');"></div>
</body>
</html>
