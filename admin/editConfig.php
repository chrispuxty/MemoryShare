<?php include "../config.php"; $updated = false;
if(isset($_POST['submit'])&&isset($_POST['patientID'])){
		$patientID = null;
		if($_POST['patientID']=="New"&&isset($_POST['name'])&&strlen($_POST['name'])>0){
			$query = mysqli_query($db,"select max(id) from patients") or die(mysqli_error($db));
			$temp = mysqli_fetch_assoc($db,$query);
			$nextID = ((is_numeric($temp['max(id)']))?$temp['max(id)']+1:0);
			mysqli_query($db,"INSERT INTO patients(id, name) VALUES (".$nextID.",'".mysqli_real_escape_string($db,$_POST['name'])."')") or die(mysqli_error($db));
			$patientID = $nextID;
			}
			else {if ($_POST['patientID']!="New") $patientID = $_POST['patientID'];
			else $patientID = 0;}
		
		mysqli_query($db,"UPDATE options SET id=".$patientID) or die(mysqli_query($db));
	/*	$configFile = file("../config.php",FILE_IGNORE_NEW_LINES||FILE_SKIP_EMPTY_LINES);
		foreach($configFile as &$line)
	if(strpos($line,"patientID") === 1) $line = "$"."patientID = ".$patientID.";\n";
	file_put_contents("../config.php",$configFile);*/
	$updated = true;
	}
	
	$query = mysqli_query($db,"select * from patients");
$patients = Array();
	$temp = mysqli_fetch_assoc($db,$query);
	while ($temp != false) {array_push($patients,$temp); $temp = mysqli_fetch_assoc($db,$query);
	$currentPatientInArray = 0;
	for ($i = 0; $i < count($patients); $i++) if($patients[$i]['id'] == $patientID) $currentPatientInArray = $i;
	$voicePrompts = ($patients[$currentPatientInArray]['voicePrompts']==1);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><link rel="stylesheet" type="text/css" href="../css/main.css"/>
<style type="text/css">
input,select {font-size: 0.75em; margin: 25px;}
</style>
<style type="text/css">
body,input,select {font-size: 31px; line-height: 2em;}
select {border-radius: 50px; background: rgba(255,255,255,0.1); border: none; color: #fff; text-align: center; font-family: Planar; padding: 16px 32px;}
option {background: #191919;}
option:hover,select:hover, option:selected {background: #00CC00;}
input[type="submit"]{font-family: Planar; background-color: #FFFF00; border: none; color: #000; padding: 6px 27px; border-radius: 35px; font-size: 31px;<?php if(!$updated) echo " display: none;";?>}
input[type="text"]{font-family: Planar; font-size: 31px; width: 450px; text-align: center; border-radius: 50px; padding: 5px 12px; background: rgba(255,255,255,0.1); border: none; color: #FFF; margin-right: 200px;}
</style>
<script type="text/javascript" src="form.js"></script>
<script type="text/javascript" src="../js/jquery-1.9.1.min.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dementia Project - Edit Configuration</title>
</head>

<body>
<?php if($voicePrompts){if($updated) echo '<audio hidden autoplay src="./sec_int_audio/studies_indicate_kanvars_significant_other_enjoys_speaking_in_monotone_far_too_much.mp3"></audio>';
else echo '<audio hidden autoplay src="./sec_int_audio/what_if_I_want_to_select_a_resident_from_outside_the_dropbox_didnt_think_of_that_did_you_I_didnt_think_so_some_PHD_design_project_this_is.mp3"></audio>';}?>
<div id="content" style="height: 54.75%; padding-top: 9%; box-sizing: 
border-box; font-size: 1.2em; color: yellow;">
<form action="editConfig.php" method="post">
Select Resident:<br />
<select name="patientID" id="patientSelector" onchange="reveal(); 
//document.getElementById('submitButton').style.webkitAnimation = 'blinker 0.75s infinite';
document.getElementById('submitButton').style.display = 'inline';
$('#rudMsg').fadeOut();">
<?php foreach ($patients as $record)
echo '<option value="'.$record['id']."\" ".(($record['id']==$patientID)?" selected":"").">".$record['name']."</option>";?>
<option value="New">New...</option>
</select>
<input id="nameBox" type="text" name="name" placeholder="Please Type New Name" 
<?php if(count($patients)>0) echo 'style="display: none;"'?>><br />
<input type="submit" name="submit" value="<?php echo (($updated)?"Resident Updated":"Save Changes");?>" id="submitButton"/>
</form>
<?php if($updated) echo "<script type='text/javascript'>setTimeout(function(){window.open('user_settings.php','_self');},2000);</script>";?>
</div>
<div id="buttonDiv"></div>
<div id="stopButtonText" onclick="window.open('user_settings.php','_self');">BACK</div>
<div id="stopButton" onclick="window.open('user_settings.php','_self');"></div>
</body>
</html>
