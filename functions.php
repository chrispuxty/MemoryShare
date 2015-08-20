<?php

$colours = array(
0 => "#EC008B", //#2C24C9
1 => "#1B75BB", //#FF5500
2 => "#F16521", //#0D992D
3 => "#38B449"  //#ED2D45
);

/*$colours = array(
0 => "#1B75BB", //#2C24C9
1 => "#F16521", //#FF5500
2 => "#38B449", //#0D992D
3 => "#EC008B"  //#ED2D45
);*/

function getMedia($dir) {
	//echo "lol ".$dir;
	$media = Array();
	if(is_dir($dir)){
	$a = opendir($dir);
	$x = 0;
	//if (!$dir) echo "DIR FAULT:<br>".$dir; else
	while (false !== ($temp = readdir($a))) {
		if (substr($temp, 0, 1) != ".") $media[$x++] = $temp;
	}
	closedir($a);
	if (is_readable($dir."/.customOrder")) $media = file($dir."/.customOrder", FILE_IGNORE_NEW_LINES);
	else sort($media);}
	//echo print_r($media);
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
		if (!is_readable($dir."/.customOrder")) sort($retValue);
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
	if (strpos($retValue,$exclude)>0) $retValue = recursiveAutoplayWithExclude($path,$exclude);
	return $retValue;}
	
function sequentialAutoplay($path){$retValue = "";
	//echo $path;
	$file = false; if(!is_dir($path)) 
	{$temp = explodePath($path); $path = $temp[0]; $file = $temp[1];}
	//echo $path."!LOL!".$file;
if(is_readable($path."/.sequentialAutoplay")) {
	$media = getMedia($path);
	$mediaCount = count($media);
	$oldID = false;
	if($file!==false) {for ($i = 0; $i < $mediaCount; $i++) 
	if(strpos($media[$i],$file)!==false) $oldID = $i;}
	else $oldID = file_get_contents($path."/.sequentialAutoplay");
	$sequentialAutoplayFile = fopen($path."/.sequentialAutoplay","w");
	$ID = -1;
	if($oldID!==false) $ID = $oldID;
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
	
	function explodePath($path){$file = false;
if (!is_dir($path)) {$temp = explode('/',$path); $file = array_pop($temp); 
$path = ""; foreach ($temp as $x) $path .= "/" . $x;}
if (substr($path,0,1) == "/") $path = substr($path,1,strlen($path));
return Array($path, $file);}

function resolveAliasedDir($path){if(!is_dir($path)){
	
	}
	return $path;}
	
		function severeModeAutoplay($path,$exclude){$retValue = null;
		$mediaArray = array_flatten(recursiveMap($path)); $i = 0; $mediaArrayCount = count($mediaArray); //echo print_r($mediaArray);
		if(file_exists($path."/.sModeAutoplay")){
		$i = file_get_contents($path."/.sModeAutoplay");}
		$i += 1;
		//echo $i." <-BEFORE";
		//if($i>=2*$mediaArrayCount) $i = 0;
		if($i>=$mediaArrayCount) $i = 0; //-= $mediaArrayCount;
		file_put_contents($path."/.sModeAutoplay",$i);
		//echo $i;		
		//echo file_get_contents($path."/.sModeAutoplay");
		return $mediaArray[$i+0];} 
		
		function recursiveMap($path){$retValue = null;
			if(is_dir($path)){$retValue = array();
				$media = getMedia($path);
				$mediaCount = count($media);
				if ($mediaCount-- > 1) sort($media);
				foreach ($media as $clip) {
					$temp = null;
					if (is_dir($path."/".$clip)) $temp = recursiveMap($path."/".$clip);
					else $temp = $path."/".$clip;
					array_push($retValue,$temp);}
					}
				return $retValue;}
			
			function array_flatten($array) {

   $return = array();
   foreach ($array as $key => $value) {
       if (is_array($value)){ $return = array_merge($return, array_flatten($value));}
       else {$return[$key] = $value;}
   }
   return $return;
}
	
	?>
