<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dementia Project  - View Records</title>
<style>.thumbnail {background-repeat: no-repeat;}</style>
</head>

<body>
<div id="content" style="height: 54.75%; padding: 1% 5% 0% 5%; color: yellow; box-sizing: border-box; font-size: 1.2em;">
<!-- Google Chrome will almost certainly not display CSV and XML documents 
correctly.<br />
For these two formats, click on the appropriate button below, and when faced with a screen full of numbers (CSV) 
or nothing at all (XML) press CTRL-S and save the file with 
the chosen extension (.csv or .xml).  It is then ready for your perusal.<br />
<br />
Unless you are mining the data in an analytics package like SPSS, you will probably only need the HTML table output.
--> </div>
<div id="buttonDiv">

<div class="button" style="background: 
#1B75BB;"onclick="window.open('downloadRecords.php?outputType=html','_self');">
<div class="buttonBack1">
<div class="thumbnail" style="
background-colour: #1B75BB;
background-image: url('records.png'); border: none; 
width: 70%; background-size: contain; top: 1%;"></div>
<span class="buttonText">Raw Logs</span>
</div>
<div class="buttonBack2">
</div>
<div class="buttonBack3"></div>
</div>

<div class="button" style="background:
#F16521;"onclick="window.open('downloadRecords.php?outputType=html-report','_self');">
<div class="buttonBack1">
<div class="thumbnail" style="
background-colour: #F16521;
background-image: url('records.png'); border: none;
width: 70%; background-size: contain; top: 1%;"></div>
<span class="buttonText">Daily Report</span>
</div>
<div class="buttonBack2">
</div>
<div class="buttonBack3"></div>
</div>

<!-- <div class="button" style="background:
#F16521;"onclick="window.open('downloadRecords.php?outputType=xml','_self');">
<div class="buttonBack1">
<div class="thumbnail" style="
background-colour: #F16521;
background-image: url('records.png'); border: none;
width: 70%; background-size: contain; top: 1%;"></div>
<span class="buttonText">XML (R)</span>
</div>
<div class="buttonBack2">
</div>
<div class="buttonBack3"></div>
</div>

<div class="button" style="background:
#38B449;"onclick="window.open('downloadRecords.php?outputType=csv','_self');">
<div class="buttonBack1">
<div class="thumbnail" style="
background-colour: #38B449;
background-image: url('records.png'); border: none;
width: 70%; background-size: contain; top: 1%;"></div>
<span class="buttonText">CSV (SPSS)</span>
</div>
<div class="buttonBack2">
</div>
<div class="buttonBack3"></div>
</div> -->

</div>
<div id="stopButtonText" onclick="window.open('/closekiosk','_self');">EXIT</div>
<div id="stopButton" onclick="window.open('/closekiosk','_self');"></div>
<?php include "../config.php"; $query = mysql_query("SELECT * FROM patients WHERE id=".$patientID) or die(mysql_error()); $row = mysql_fetch_assoc($query);
if($row['voicePrompts']==1) echo "<audio hidden autoplay src='audio/homepage_2.mp3'></audio>";
?>

</body>
</html>
