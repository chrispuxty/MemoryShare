<?php include "../config.php"; include "../functions.php";
$pathStub = "../media";
$path = ((isset($_GET['path']))?$_GET['path']:$pathStub);
$temp = explodePath($path);
$path = $temp[0]; $file = $temp[1];
$media = getMedia($path);
$mediaCount = count($media);
//$roomForNewItems =($mediaCount < 4);
$thumbs = getThumbs($path);
$uploaded = false;
$allowedExts = array("gif", "jpeg", "jpg", "png","mp4","mp3");

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
		if(strtolower(trimExt($_GET['delete'],true)) == "kanvar"||strtolower(trimExt($_GET['delete'],true)) == "lee") {
			$altDelString = $path."/".file_get_contents($path."/".$_GET['delete']);
			//echo $altDelString;
			delTree($altDelString);}
	delTree($delString);
	 $media = getMedia($path);
	 $mediaCount = count($media);
 $thumbs = getThumbs($path);
//$roomForNewItems =($mediaCount < 4);
}

//Upload Sanitizer
$filesCount = count($_FILES['files']['name']);
for ($i = 0; $i < $filesCount; $i++) {$okay = false;
if($_FILES['files']['error'][$i]==UPLOAD_ERR_OK)
$extension = strtolower(trimExt($_FILES['files']['name'][$i],true));
foreach($allowedExts as $test) if($test == $extension) {$okay = true; break;}
if(!$okay){foreach //HACK CONTINUE PROGRAMMING FROM HERE

}

}
$filesCount = count($_FILES['files']['name']);

if(isset($_POST['submit'])&&isset($_POST['action'])&&$_POST['action']!="none"){
if($_POST['action']=="upload"||isset($_POST['name'])&&$filesCount>0){
//Determine whether to create .kanvar or .lee file, or to upload video

//Video file uploader - also handles any individual and valid files
if(trimExt($_FILES['files']['name'],true)=="mp4"||$filesCount==1) {}



}

}


?>

<html>
  <head>
    <title>Kanvar Nayer - Memory Box - File Management</title>
    <meta content="">
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <style>
   #content{height: 55%;}
   </style>
  </head>
  <body>
  <div id="content"></div>
  <div id="buttonDiv"></div>
  </body>
</html>