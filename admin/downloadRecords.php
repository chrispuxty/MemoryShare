<?php include "../config.php";
      include "../functions.php";
      date_default_timezone_set("UTC");
if(isset($_GET['outputType'])){
	$query = mysql_query("select name from patients where id=".$patientID." limit 1");
	$temp = mysql_fetch_assoc($query); $name = $temp['name'];
	//Date range queries
	$query = mysql_query("select time from observations where patient=".$patientID." order by time asc limit 1");
	$oldestDate = null;
	$temp = mysql_fetch_assoc($query);
	if($temp!=false) $oldestDate = $temp['time'];
	$query = mysql_query("select time from observations where patient=".$patientID." order by time desc limit 1");
	$latestDate = null;
	$temp = mysql_fetch_assoc($query);
	if($temp!=false) $latestDate = $temp['time'];
	
	
	//Main log query
	$query = mysql_query("select patient,path,time from observations where patient=".$patientID." order by time asc");
	$observations = Array();
	$temp = mysql_fetch_assoc($query);
	while ($temp != false) {array_push($observations,$temp); $temp = mysql_fetch_assoc($query);}
	
	if($_GET['outputType'] === "csv")
	{echo "Patient ID,Media Path,Datestamp\n";
	foreach($observations as $record) echo $record['patient'].",".$record['path'].",".$record['time']."\n";}
	
	if($_GET['outputType'] === "xml"){
		echo "<xml>\n";
		echo "<patient id='".$patientID."'>\n";
		foreach ($observations as $record) if($record['patient'] == $patientID)		
echo "<record path='".$record['path']."' 
date='".$record['time']."'></record>\n";
		echo "</patient>\n";
		echo "</xml>";}
		
		
	if ($_GET['outputType'] === "html") {
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<style>html,body {
/*overflow-x: visible; overflow-y: visible;*/
-webkit-touch-callout: all;
-webkit-user-select: all;
-khtml-user-select: all;
-moz-user-select: all;
-ms-user-select: all; 
user-select: all;
text-align: center;
}
#resultsTable{overflow-y: scroll;
height: 60%;
width: 90%;
margin: 0 7.5% 0 auto;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Observations for '.$name.'</title>
</head>

<body><br/><br/><h1>Observations for '.$name.'</h1>
<table style="position: relative; right: 61px;"><td width=272><b>Date &amp; Time</b></td><td width=748><b>Media</b></td></table>
<div 
id="resultsTable"><table>';
foreach ($observations as $record) {echo "<tr style='background: ";
		$colourString = null;
		$extension = trimExt($record['path'],true);
		if(strpos($record['path'],"Talk")!=false||strpos($record['path'],"Messages")!=false)
		$colourString = $colours[3];
		
		elseif ("mp3" == strtolower($extension)
		|| "wav" == strtolower($extension)
		|| "ogg" == strtolower($extension)
		|| "flac"== strtolower($extension)
		|| "wma" == strtolower($extension)
		||strpos($record['path'],"Music")!=false)
		$colourString = $colours[0];
		
	    elseif ("avi" == strtolower($extension)
		||  "mov" == strtolower($extension)
		||  "wmv" == strtolower($extension)
		||  "mpg" == strtolower($extension)
		||  "mp4" == strtolower($extension)
		||strpos($record['path'],"Videos")!=false)
		$colourString = $colours[1];
		
		elseif ("kanvar" == strtolower($extension)
		||strpos($record['path'],"Photos")!=false)
		$colourString = $colours[2];
		else echo "#000000";
		
$recordPath = str_replace("media","",$record['path']);
$recordPath = preg_replace('#/+#','/',$recordPath);
$recordPath = rtrim($recordPath,"/");
if (substr($recordPath,0,1) == "/") $recordPath = substr($recordPath,1);
$recordPath = str_replace("/", " > ", $recordPath);
$recordPath = trimExt($recordPath,false);


echo $colourString.";'><td width=272>".gmdate("d-m-Y\ H:i:s",strtotime($record['time']))."</td><td width=748>".((strlen($record['path']) 
== 6)?"Homepage":$recordPath)."</td></tr>";}
echo '</table></div>
<div id="stopButtonText" onclick="window.open(\'records.php\',\'_self\');">BACK</div>
<div id="stopButton" onclick="window.open(\'records.php\',\'_self\');"</div>
</body></html>';
		
		}
		
if ($_GET['outputType'] === "html-report") {
	$observationsCount = count($observations);
	
	$days = Array();
	foreach($observations as $record)
	$days[substr($record['time'],0,10)] = Array("Music" => 0,"Videos" => 0,"Photos" => 0,"Talk" => 0);
	
	
		
	for ($i = 0; $i < $observationsCount - 1; $i++){
		$current = $observations[$i];  $next = null;
		if($observationsCount > 2) $next = $observations[$i+1];
		else $next = Array("time" => date("Y-m-d h:i:s")."","path" => "/media");
		
	    $recordToModify = &$days[substr($current['time'],0,10)];
		$difference = strtotime($next['time']) - strtotime($current['time']);
		
		$extension = trimExt($current['path'],true);
		if(strpos($current['path'],"Talk")!=false||strpos($current['path'],"Messages")!=false)
		$recordToModify['Talk'] += $difference;
		
		elseif ("mp3" == strtolower($extension)
		|| "wav" == strtolower($extension)
		|| "ogg" == strtolower($extension)
		|| "flac"== strtolower($extension)
		|| "wma" == strtolower($extension))
		$recordToModify['Music'] += $difference;
		
	    elseif ("avi" == strtolower($extension)
		||  "mov" == strtolower($extension)
		||  "wmv" == strtolower($extension)
		||  "mpg" == strtolower($extension)
		||  "mp4" == strtolower($extension))
		$recordToModify['Videos'] += $difference;
		
		elseif ("kanvar" == strtolower($extension))
		$recordToModify['Photos'] += $difference;
			
		}
	
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<style>html,body {
/*overflow-x: visible; overflow-y: visible;*/
-webkit-touch-callout: all;
-webkit-user-select: all;
-khtml-user-select: all;
-moz-user-select: all;
-ms-user-select: all; 
user-select: all;
text-align: center;
}[
#resultsTable{overflow-y: scroll;
height: 60%;  
width: 85%;
margin: 0 auto 0 auto;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Observations for '.$name.'</title>
</head>

<body><br/><br/><h1>Observations for '.$name.'</h1>
<div style=" width: 85%; margin: 0 auto;">
<table style="margin-right: 330.5px;">
<colgroup><col style="width: 274px;"><col style="background: '.$colours[0].'; width: 196px;"><col style="background: '.$colours[1].'; width: 196px;"><col style="background: '.$colours[2].'; width: 196px;"><col style="background: '.$colours[3].'; width: 196px;"></colgroup> 
<tr><td><b>Date	</b></td><td><b>Music</b></td><td><b>Movies</b></td><td><b>Photos</b></td><td><b>Messages</b></td></tr></table></div>
<div id="resultsTable" style="overflow-y: scroll;
height: 60%;
width: 85%;
margin: 0 auto;">

<table style="width: 66%; margin-right: 19%;">
<colgroup><col style="width: 273px;"><col style="background: '.$colours[0].'; width: 195px;"><col style="background: '.$colours[1].'; width: 195px;"><col style="background: '.$colours[2].'; width: 195px;"><col style="background: '.$colours[3].'; width: 195px;"></colgroup>';

/*foreach ($days as $date => $record) echo "<tr><td>".$date."</td><td>".$record['Music']."</td><td>".$record['Videos']."</td><td>".$record['Photos']."</td><td>".$record['Talk']."</td></tr>";*/
date_default_timezone_set('UTC');
foreach ($days as $date => $record) echo "<tr><td>".gmdate("d-m-Y",strtotime($date))."</td><td>".date("G:i:s",$record['Music'])."</td><td>".date("G:i:s",$record['Videos'])."</td><td>".date("G:i:s",$record['Photos'])."</td><td>".date("G:i:s",$record['Talk'])."</td></tr>";

echo '</table></div>
<div id="stopButtonText" onclick="window.open(\'records.php\',\'_self\');">BACK</div>
<div id="stopButton" onclick="window.open(\'records.php\',\'_self\');"</div>
</body></html>';

	 
	}
	
	
	}



?>
