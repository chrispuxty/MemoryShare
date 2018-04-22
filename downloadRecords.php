<?php include "../config.php";
      include "../functions.php";
if(isset($_GET['outputType'])){
	//Date range queries
	$query = mysqli_query($db,"select time from observations where patient=".$patientID." order by time asc limit 1");
	$oldestDate = null;
	$temp = mysqli_fetch_assoc($query);
	if($temp!=false) $oldestDate = $temp['time'];
	$query = mysqli_query($db,"select time from observations where patient=".$patientID." order by time desc limit 1");
	$latestDate = null;
	$temp = mysqli_fetch_assoc($query);
	if($temp!=false) $latestDate = $temp['time'];
	
	
	//Main log query
	$query = mysqli_query($db,"select patient,path,time from observations where patient=".$patientID." order by time asc");
	$observations = Array();
	$temp = mysqli_fetch_assoc($query);
	while ($temp != false) {array_push($observations,$temp); $temp = mysqli_fetch_assoc($query);}
	
	if($_GET['outputType'] === "csv")
	{echo "Patient ID,Media Path,Datestamp\n";
	foreach($observations as $record) echo $record['patient'].",".$record['path'].",".$record['time']."\n";}
	
	if($_GET['outputType'] === "xml"){
		echo "<xml>\n";
		echo "<patient id='".$patientID."'>\n";
		foreach ($observations as $record) if($record['patient'] == $patientID)
		echo "<record path='".$record['path']."' date='".$record['time']."'></record>\n";
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
height: 72%;
border: solid #cccc00;
width: 94%;
margin: 0 4% 0 auto;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Observations for '.$patientID.'</title>
</head>

<body><br/><br/><h1>Observations for '.$patientID.'</h1>
<div id="resultsTable"><table><tr><td><b>Time</b></td><td><b>Path</b></td></tr>';
foreach ($observations as $record) echo "<tr><td>".$record['time']."</td><td>".$record['path']."</td></tr>";
echo '</table></div>
<div style="position: fixed;
right: 96px;
bottom: -14px;
width: 207px;
height: 207px;
z-index: 2; color: #FF0000;">Back</div>
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
		if(strpos($current['path'],"Talk"!=false))
		$recordToModify['Talk'] += $difference;
		
		elseif ($extension == strtolower("mp3")
		|| $extension == strtolower("wav")
		|| $extension == strtolower("ogg")
		|| $extension == strtolower("flac")
		|| $extension == strtolower("wma"))
		$recordToModify['Music'] += $difference;
		
	    elseif ($extension == strtolower("avi")
		|| $extension == strtolower("mov")
		|| $extension == strtolower("wmv")
		|| $extension == strtolower("mpg")
		|| $extension == strtolower("mp4"))
		$recordToModify['Videos'] += $difference;
		
		elseif ($extension == strtolower("kanvar"))
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
}
#resultsTable{overflow-y: scroll;
height: 72%;
border: solid #cccc00;
width: 94%;
margin: 0 4% 0 auto;}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Observations for '.$patientID.'</title>
</head>

<body><br/><br/><h1>Observations for '.$patientID.'</h1>
<div id="resultsTable"><table><tr><td><b>Date</b></td><td><b>Music</b></td><td><b>Videos</b></td><td><b>Photos</b></td><td><b>Talk</b></td></tr>';

foreach ($days as $date => $record) echo "<tr><td>".$date."</td><td>".$record['Music']."</td><td>".$record['Videos']."</td><td>".$record['Photos']."</td><td>".$record['Talk']."</td></tr>";

/*foreach ($days as $date => $record) echo "<tr><td>".$date."</td><td>".date("H:i:s",$record['Music'])."</td><td>".date("H:i:s",$record['Videos'])."</td><td>".date("H:i:s",$record['Photos'])."</td><td>".date("H:i:s",$record['Talk'])."</td></tr>";*/

echo '</table></div>
<div style="position: fixed;
right: 96px;
bottom: -14px;
width: 207px;
height: 207px;
z-index: 2; color: #FF0000;">Back</div>
<div id="stopButton" onclick="window.open(\'records.php\',\'_self\');"</div>
</body></html>';

	 
	}
	
	
	}



?>
